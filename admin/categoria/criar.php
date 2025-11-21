<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

requireAdmin();

$db = getDB();
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    
    if (empty($nome)) {
        $erro = 'O nome da categoria é obrigatório';
    } else {
        try {
            $stmt = $db->prepare("INSERT INTO categoria (nome) VALUES (:nome)");
            $stmt->execute(['nome' => $nome]);
            
            setSuccess('Categoria criada com sucesso!');
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $erro = 'Já existe uma categoria com este nome';
            } else {
                $erro = 'Erro ao criar categoria: ' . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Categoria - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border: none; cursor: pointer; border-radius: 4px; font-size: 16px; }
        .btn:hover { background: #0056b3; }
        .btn-secondary { background: #6c757d; margin-left: 10px; }
        .btn-secondary:hover { background: #5a6268; }
        .alert-error { padding: 15px; margin-bottom: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>Nova Categoria</h1>
        </div>
    </div>
    
    <div class="container">
        <div class="card">
            <?php if ($erro): ?>
                <div class="alert-error"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome da Categoria *</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>">
                </div>
                
                <div>
                    <button type="submit" class="btn">Salvar</button>
                    <a href="index.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
