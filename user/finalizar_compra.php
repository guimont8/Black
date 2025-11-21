<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$usuario_codigo = getUsuarioLogado();

try {
    // Iniciar transação
    $db->beginTransaction();
    
    // Buscar itens do carrinho
    $stmt = $db->prepare("
        SELECT c.*, p.nome, p.preco, p.estoque
        FROM carrinho c
        INNER JOIN produto p ON c.codigo_produto = p.codigo
        WHERE c.codigo_conta = :codigo_conta
    ");
    $stmt->execute(['codigo_conta' => $usuario_codigo]);
    $itens = $stmt->fetchAll();
    
    if (empty($itens)) {
        throw new Exception('Carrinho vazio');
    }
    
    // Verificar estoque e calcular total
    $total = 0;
    foreach ($itens as $item) {
        if ($item['estoque'] < $item['quantidade']) {
            throw new Exception('Produto "' . $item['nome'] . '" não tem estoque suficiente');
        }
        $total += ($item['preco'] * $item['quantidade']);
    }
    
    // Criar pedido
    $stmt = $db->prepare("
        INSERT INTO pedido (codigo_conta, valor_total, status) 
        VALUES (:codigo_conta, :valor_total, 'finalizado')
    ");
    $stmt->execute([
        'codigo_conta' => $usuario_codigo,
        'valor_total' => $total
    ]);
    $codigo_pedido = $db->lastInsertId();
    
    // Criar itens do pedido e atualizar estoque
    foreach ($itens as $item) {
        // Inserir item do pedido
        $stmt = $db->prepare("
            INSERT INTO item_pedido (codigo_pedido, codigo_produto, quantidade, preco_unitario, subtotal) 
            VALUES (:codigo_pedido, :codigo_produto, :quantidade, :preco_unitario, :subtotal)
        ");
        $subtotal = $item['preco'] * $item['quantidade'];
        $stmt->execute([
            'codigo_pedido' => $codigo_pedido,
            'codigo_produto' => $item['codigo_produto'],
            'quantidade' => $item['quantidade'],
            'preco_unitario' => $item['preco'],
            'subtotal' => $subtotal
        ]);
        
        // Atualizar estoque
        $stmt = $db->prepare("
            UPDATE produto 
            SET estoque = estoque - :quantidade 
            WHERE codigo = :codigo_produto
        ");
        $stmt->execute([
            'quantidade' => $item['quantidade'],
            'codigo_produto' => $item['codigo_produto']
        ]);
    }
    
    // Limpar carrinho
    $stmt = $db->prepare("DELETE FROM carrinho WHERE codigo_conta = :codigo_conta");
    $stmt->execute(['codigo_conta' => $usuario_codigo]);
    
    // Confirmar transação
    $db->commit();
    
    setSuccess('Compra finalizada com sucesso! Pedido #' . $codigo_pedido . ' - Total: R$ ' . number_format($total, 2, ',', '.'));
    header('Location: carrinho.php');
    exit;
    
} catch (Exception $e) {
    // Reverter transação em caso de erro
    $db->rollBack();
    setError('Erro ao finalizar compra: ' . $e->getMessage());
    header('Location: carrinho.php');
    exit;
}
?>
