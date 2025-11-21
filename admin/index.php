<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireAdmin();

$db = getDB();

// Estatísticas
$stmt = $db->query("SELECT COUNT(*) as total FROM produto");
$total_produtos = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM categoria");
$total_categorias = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM conta");
$total_contas = $stmt->fetch()['total'];

$stmt = $db->query("SELECT COUNT(*) as total FROM pedido");
$total_pedidos = $stmt->fetch()['total'];

$stmt = $db->query("SELECT SUM(valor_total) as total FROM pedido WHERE status = 'finalizado'");
$total_vendas = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin-bottom: 10px; }
        .nav { background: #444; padding: 10px; margin-bottom: 20px; }
        .nav a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav a:hover { background: #555; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center; }
        .stat-value { font-size: 48px; font-weight: bold; color: #007bff; margin-bottom: 10px; }
        .stat-label { font-size: 18px; color: #6c757d; }
        .welcome-box { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .welcome-box h2 { margin-bottom: 15px; color: #333; }
        .welcome-box p { color: #6c757d; line-height: 1.6; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Painel Administrativo</h1>
            <p>Bem-vindo, <?php echo htmlspecialchars(getNomeUsuario()); ?></p>
        </div>
    </div>
    
    <div class="nav">
        <div class="container">
            <a href="/admin/index.php">Dashboard</a>
            <a href="/admin/categoria/index.php">Categorias</a>
            <a href="/admin/produto/index.php">Produtos</a>
            <a href="/admin/conta/index.php">Contas</a>
            <a href="/user/index.php">Área do Usuário</a>
            <a href="/logout.php">Sair</a>
        </div>
    </div>
    
    <div class="container">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_produtos; ?></div>
                <div class="stat-label">Produtos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_categorias; ?></div>
                <div class="stat-label">Categorias</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_contas; ?></div>
                <div class="stat-label">Contas</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $total_pedidos; ?></div>
                <div class="stat-label">Pedidos</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">R$ <?php echo number_format($total_vendas, 2, ',', '.'); ?></div>
                <div class="stat-label">Total em Vendas</div>
            </div>
        </div>
        
        <div class="welcome-box">
            <h2>Bem-vindo ao Sistema de E-commerce</h2>
            <p>Este é o painel administrativo onde você pode gerenciar todos os aspectos do sistema:</p>
            <ul style="margin-top: 15px; margin-left: 20px; color: #6c757d;">
                <li style="margin-bottom: 10px;"><strong>Categorias:</strong> Crie e gerencie categorias de produtos</li>
                <li style="margin-bottom: 10px;"><strong>Produtos:</strong> Adicione, edite e remova produtos do sistema</li>
                <li style="margin-bottom: 10px;"><strong>Contas:</strong> Gerencie usuários do sistema</li>
                <li style="margin-bottom: 10px;"><strong>Área do Usuário:</strong> Acesse a interface de compras</li>
            </ul>
        </div>
    </div>
</body>
</html>
