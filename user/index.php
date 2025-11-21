<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();

// Filtro por categoria
$categoria_filtro = $_GET['categoria'] ?? '';

// Buscar categorias para o filtro
$stmt = $db->query("SELECT * FROM categoria ORDER BY nome ASC");
$categorias = $stmt->fetchAll();

// Buscar produtos
if ($categoria_filtro) {
    $stmt = $db->prepare("
        SELECT p.*, c.nome as categoria_nome, co.nome as dono_nome 
        FROM produto p
        LEFT JOIN categoria c ON p.codigo_categoria = c.codigo
        LEFT JOIN conta co ON p.codigo_dono = co.codigo
        WHERE p.codigo_categoria = :categoria
        ORDER BY p.data_criacao DESC
    ");
    $stmt->execute(['categoria' => $categoria_filtro]);
} else {
    $stmt = $db->query("
        SELECT p.*, c.nome as categoria_nome, co.nome as dono_nome 
        FROM produto p
        LEFT JOIN categoria c ON p.codigo_categoria = c.codigo
        LEFT JOIN conta co ON p.codigo_dono = co.codigo
        ORDER BY p.data_criacao DESC
    ");
}
$produtos = $stmt->fetchAll();

// Contar itens no carrinho
$stmt = $db->prepare("SELECT COUNT(*) as total FROM carrinho WHERE codigo_conta = :codigo_conta");
$stmt->execute(['codigo_conta' => getUsuarioLogado()]);
$carrinho_count = $stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Produtos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin-bottom: 10px; }
        .nav { background: #444; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-left a, .nav-right a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav-left a:hover, .nav-right a:hover { background: #555; }
        .filter-section { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .filter-section h3 { margin-bottom: 15px; }
        .filter-buttons { display: flex; flex-wrap: wrap; gap: 10px; }
        .filter-btn { padding: 8px 16px; background: #e9ecef; border: 1px solid #ddd; border-radius: 4px; text-decoration: none; color: #333; }
        .filter-btn:hover { background: #dee2e6; }
        .filter-btn.active { background: #007bff; color: white; border-color: #007bff; }
        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; }
        .product-card { background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 4px 8px rgba(0,0,0,0.2); }
        .product-image { width: 100%; height: 200px; background: #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; }
        .product-info { padding: 15px; }
        .product-name { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .product-category { font-size: 12px; color: #6c757d; margin-bottom: 10px; }
        .product-price { font-size: 24px; color: #28a745; font-weight: bold; margin-bottom: 10px; }
        .product-owner { font-size: 12px; color: #6c757d; margin-bottom: 10px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; display: inline-block; width: 100%; text-align: center; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .cart-badge { background: #dc3545; color: white; padding: 2px 8px; border-radius: 12px; font-size: 12px; margin-left: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Loja Virtual</h1>
            <p>Bem-vindo, <?php echo htmlspecialchars(getNomeUsuario()); ?></p>
        </div>
    </div>
    
    <div class="nav">
        <div class="nav-left">
            <a href="index.php">Produtos</a>
            <a href="meus_produtos.php">Meus Produtos</a>
            <a href="carrinho.php">Carrinho <?php if ($carrinho_count > 0): ?><span class="cart-badge"><?php echo $carrinho_count; ?></span><?php endif; ?></a>
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
        
        <div class="filter-section">
            <h3>Filtrar por Categoria</h3>
            <div class="filter-buttons">
                <a href="index.php" class="filter-btn <?php echo empty($categoria_filtro) ? 'active' : ''; ?>">Todas</a>
                <?php foreach ($categorias as $cat): ?>
                    <a href="index.php?categoria=<?php echo $cat['codigo']; ?>" 
                       class="filter-btn <?php echo $categoria_filtro == $cat['codigo'] ? 'active' : ''; ?>">
                        <?php echo htmlspecialchars($cat['nome']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="products-grid">
            <?php if (empty($produtos)): ?>
                <p>Nenhum produto encontrado.</p>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <?php if ($produto['imagem']): ?>
                                <img src="<?php echo htmlspecialchars($produto['imagem']); ?>" 
                                     alt="<?php echo htmlspecialchars($produto['nome']); ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                Sem imagem
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <div class="product-name"><?php echo htmlspecialchars($produto['nome']); ?></div>
                            <div class="product-category"><?php echo htmlspecialchars($produto['categoria_nome']); ?></div>
                            <div class="product-price">R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></div>
                            <div class="product-owner">Vendedor: <?php echo htmlspecialchars($produto['dono_nome']); ?></div>
                            <?php if ($produto['estoque'] > 0): ?>
                                <form action="adicionar_carrinho.php" method="POST">
                                    <input type="hidden" name="codigo_produto" value="<?php echo $produto['codigo']; ?>">
                                    <button type="submit" class="btn btn-success">Adicionar ao Carrinho</button>
                                </form>
                            <?php else: ?>
                                <button class="btn" disabled style="background: #6c757d;">Sem Estoque</button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
