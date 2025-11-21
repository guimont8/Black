<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$usuario_codigo = getUsuarioLogado();

// Buscar produtos do usuário logado
$stmt = $db->prepare("
    SELECT p.*, c.nome as categoria_nome 
    FROM produto p
    LEFT JOIN categoria c ON p.codigo_categoria = c.codigo
    WHERE p.codigo_dono = :codigo_dono
    ORDER BY p.data_criacao DESC
");
$stmt->execute(['codigo_dono' => $usuario_codigo]);
$produtos = $stmt->fetchAll();

// Buscar categorias para o formulário
$stmt = $db->query("SELECT codigo, nome FROM categoria ORDER BY nome ASC");
$categorias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Produtos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 1400px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .header h1 { margin-bottom: 10px; }
        .nav { background: #444; padding: 10px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-left a, .nav-right a { color: white; text-decoration: none; padding: 10px 15px; display: inline-block; }
        .nav-left a:hover, .nav-right a:hover { background: #555; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; display: inline-block; font-size: 14px; }
        .btn:hover { background: #0056b3; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .alert { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        table { width: 100%; background: white; border-collapse: collapse; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; font-size: 14px; }
        th { background: #007bff; color: white; }
        tr:hover { background: #f5f5f5; }
        .actions a { margin-right: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Meus Produtos</h1>
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
        
        <div style="margin-bottom: 20px;">
            <a href="criar_produto.php" class="btn btn-success">+ Novo Produto</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Você ainda não cadastrou nenhum produto</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria_nome']); ?></td>
                            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($produto['estoque']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($produto['data_criacao'])); ?></td>
                            <td class="actions">
                                <a href="editar_produto.php?codigo=<?php echo $produto['codigo']; ?>" class="btn">Editar</a>
                                <a href="excluir_produto.php?codigo=<?php echo $produto['codigo']; ?>" 
                                   class="btn btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
