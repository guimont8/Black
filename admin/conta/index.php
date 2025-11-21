<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

requireAdmin();

$db = getDB();

// Buscar todas as contas
$stmt = $db->query("SELECT codigo, nome, email, tipo, data_criacao FROM conta ORDER BY data_criacao DESC");
$contas = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Contas - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin-bottom: 10px; }
        .nav { background: #444; padding: 10px; margin-bottom: 20px; }
        .nav a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav a:hover { background: #555; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f5f5f5; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold; }
        .badge-admin { background: #dc3545; color: white; }
        .badge-user { background: #28a745; color: white; }
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
        <?php if ($success = getSuccess()): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        
        <?php if ($error = getError()): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($contas)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Nenhuma conta cadastrada</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($contas as $conta): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($conta['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($conta['nome']); ?></td>
                            <td><?php echo htmlspecialchars($conta['email']); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $conta['tipo']; ?>">
                                    <?php echo strtoupper($conta['tipo']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($conta['data_criacao'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
