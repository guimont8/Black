<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$codigo_produto = $_POST['codigo_produto'] ?? null;
$usuario_codigo = getUsuarioLogado();

if (!$codigo_produto) {
    setError('Produto inválido');
    header('Location: index.php');
    exit;
}

try {
    // Verificar se o produto existe e tem estoque
    $stmt = $db->prepare("SELECT * FROM produto WHERE codigo = :codigo");
    $stmt->execute(['codigo' => $codigo_produto]);
    $produto = $stmt->fetch();
    
    if (!$produto) {
        setError('Produto não encontrado');
    } elseif ($produto['estoque'] <= 0) {
        setError('Produto sem estoque');
    } else {
        // Verificar se o produto já está no carrinho
        $stmt = $db->prepare("
            SELECT * FROM carrinho 
            WHERE codigo_conta = :codigo_conta AND codigo_produto = :codigo_produto
        ");
        $stmt->execute([
            'codigo_conta' => $usuario_codigo,
            'codigo_produto' => $codigo_produto
        ]);
        $item_carrinho = $stmt->fetch();
        
        if ($item_carrinho) {
            // Atualizar quantidade
            $stmt = $db->prepare("
                UPDATE carrinho 
                SET quantidade = quantidade + 1 
                WHERE codigo_conta = :codigo_conta AND codigo_produto = :codigo_produto
            ");
            $stmt->execute([
                'codigo_conta' => $usuario_codigo,
                'codigo_produto' => $codigo_produto
            ]);
        } else {
            // Adicionar novo item
            $stmt = $db->prepare("
                INSERT INTO carrinho (codigo_conta, codigo_produto, quantidade) 
                VALUES (:codigo_conta, :codigo_produto, 1)
            ");
            $stmt->execute([
                'codigo_conta' => $usuario_codigo,
                'codigo_produto' => $codigo_produto
            ]);
        }
        
        setSuccess('Produto adicionado ao carrinho!');
    }
} catch (PDOException $e) {
    setError('Erro ao adicionar produto ao carrinho: ' . $e->getMessage());
}

header('Location: index.php');
exit;
?>
