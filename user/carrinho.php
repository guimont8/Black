<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$usuario_codigo = getUsuarioLogado();

// Buscar itens do carrinho
$stmt = $db->prepare("
    SELECT c.*, p.nome, p.preco, p.estoque, p.imagem, 
           (c.quantidade * p.preco) as subtotal
    FROM carrinho c
    INNER JOIN produto p ON c.codigo_produto = p.codigo
    WHERE c.codigo_conta = :codigo_conta
    ORDER BY c.data_adicao DESC
");
$stmt->execute(['codigo_conta' => $usuario_codigo]);
$itens = $stmt->fetchAll();

// Calcular total
$total = 0;
foreach ($itens as $item) {
    $total += $item['subtotal'];
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin-bottom: 10px; }
        .nav { background: #444; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-left a, .nav-right a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav-left a:hover, .nav-right a:hover { background: #555; }
        .cart-container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .cart-item { display: flex; align-items: center; padding: 20px; border-bottom: 1px solid #ddd; }
        .cart-item:last-child { border-bottom: none; }
        .item-image { width: 100px; height: 100px; background: #e9ecef; margin-right: 20px; display: flex; align-items: center; justify-content: center; border-radius: 4px; }
        .item-image img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
        .item-info { flex: 1; }
        .item-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .item-price { font-size: 16px; color: #28a745; margin-bottom: 5px; }
        .item-quantity { font-size: 14px; color: #6c757d; }
        .item-subtotal { font-size: 18px; font-weight: bold; margin-right: 20px; }
        .item-actions { }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .cart-summary { margin-top: 30px; padding-top: 20px; border-top: 2px solid #ddd; text-align: right; }
        .total-label { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .total-value { font-size: 32px; color: #28a745; font-weight: bold; margin-bottom: 20px; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .empty-cart { text-align: center; padding: 60px 20px; color: #6c757d; }
        .empty-cart h2 { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Carrinho de Compras</h1>
            <p>Bem-vindo, <?php echo htmlspecialchars(getNomeUsuario()); ?></p>
        </div>
    </div>
    
    <div class="nav">
        <div class="nav-left">
            <a href="index.php">Produtos</a>
            <a href="meus_produtos.php">Meus Produtos</a>
            <a href="carrinho.php">Carrinho</a>
        </div>
        <div class="nav-right">
            <?php if (isAdmin()): ?>
                <a href="/admin/index.php">Painel Admin</a>
            <?php endif; ?>
            <a href="/logout.php">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <?php if ($success = getSuccess()): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error = getError()): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <div class="cart-container">
            <?php if (empty($itens)): ?>
                <div class="empty-cart">
                    <h2>Seu carrinho está vazio</h2>
                    <p>Adicione produtos ao carrinho para continuar comprando</p>
                    <br>
                    <a href="index.php" class="btn">Ver Produtos</a>
                </div>
            <?php else: ?>
                <?php foreach ($itens as $item): ?>
                    <div class="cart-item">
                        <div class="item-image">
                            <?php if ($item['imagem']): ?>
                                <img src="<?php echo htmlspecialchars($item['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($item['nome']); ?>">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </div>
                        <div class="item-info">
                            <div class="item-name"><?php echo htmlspecialchars($item['nome']); ?></div>
                            <div class="item-price">R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?> cada</div>
                            <div class="item-quantity">Quantidade: <?php echo $item['quantidade']; ?></div>
                        </div>
                        <div class="item-subtotal">
                            R$ <?php echo number_format($item['subtotal'], 2, ',', '.'); ?>
                        </div>
                        <div class="item-actions">
                            <a href="remover_carrinho.php?codigo=<?php echo $item['codigo']; ?>" 
                               class="btn btn-danger"
                               onclick="return confirm('Deseja remover este item do carrinho?')">Remover</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="cart-summary">
                    <div class="total-label">Total:</div>
                    <div class="total-value">R$ <?php echo number_format($total, 2, ',', '.'); ?></div>
                    <form action="finalizar_compra.php" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-success" style="font-size: 18px; padding: 15px 40px;">
                            Finalizar Compra
                        </button>
                    </form>
                    <a href="index.php" class="btn" style="font-size: 18px; padding: 15px 40px; margin-left: 10px;">
                        Continuar Comprando
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
