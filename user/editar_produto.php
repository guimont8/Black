<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$erro = '';
$codigo = $_GET['codigo'] ?? null;
$usuario_codigo = getUsuarioLogado();

if (!$codigo) {
    header('Location: meus_produtos.php');
    exit;
}

// Buscar produto e verificar se o usuário é o dono
$stmt = $db->prepare("SELECT * FROM produto WHERE codigo = :codigo");
$stmt->execute(['codigo' => $codigo]);
$produto = $stmt->fetch();

if (!$produto) {
    setError('Produto não encontrado');
    header('Location: meus_produtos.php');
    exit;
}

// Verificar se o usuário logado é o dono do produto
if ($produto['codigo_dono'] != $usuario_codigo) {
    setError('Você não tem permissão para editar este produto');
    header('Location: meus_produtos.php');
    exit;
}

// Buscar categorias para o select
$stmt = $db->query("SELECT codigo, nome FROM categoria ORDER BY nome ASC");
$categorias = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco = $_POST['preco'] ?? '';
    $estoque = $_POST['estoque'] ?? 0;
    $codigo_categoria = $_POST['codigo_categoria'] ?? '';
    $imagem = trim($_POST['imagem'] ?? '');
    
    if (empty($nome)) {
        $erro = 'O nome do produto é obrigatório';
    } elseif (empty($preco) || $preco <= 0) {
        $erro = 'O preço deve ser maior que zero';
    } elseif (empty($codigo_categoria)) {
        $erro = 'Selecione uma categoria';
    } else {
        try {
            $stmt = $db->prepare("
                UPDATE produto 
                SET nome = :nome, descricao = :descricao, preco = :preco, 
                    estoque = :estoque, codigo_categoria = :codigo_categoria, imagem = :imagem
                WHERE codigo = :codigo AND codigo_dono = :codigo_dono
            ");
            $stmt->execute([
                'nome' => $nome,
                'descricao' => $descricao,
                'preco' => $preco,
                'estoque' => $estoque,
                'codigo_categoria' => $codigo_categoria,
                'imagem' => $imagem,
                'codigo' => $codigo,
                'codigo_dono' => $usuario_codigo
            ]);
            
            setSuccess('Produto atualizado com sucesso!');
            header('Location: meus_produtos.php');
            exit;
        } catch (PDOException $e) {
            $erro = 'Erro ao atualizar produto: ' . $e->getMessage();
        }
    }
} else {
    $_POST = $produto;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { background: #333; color: white; padding: 20px; margin-bottom: 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"], textarea, select { 
            width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 16px; 
        }
        textarea { min-height: 100px; resize: vertical; font-family: Arial, sans-serif; }
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
            <h1>Editar Produto</h1>
        </div>
    </div>
    
    <div class="container">
        <div class="card">
            <?php if ($erro): ?>
                <div class="alert-error"><?php echo htmlspecialchars($erro); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nome">Nome do Produto *</label>
                    <input type="text" id="nome" name="nome" required 
                           value="<?php echo htmlspecialchars($_POST['nome']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="descricao">Descrição</label>
                    <textarea id="descricao" name="descricao"><?php echo htmlspecialchars($_POST['descricao']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="codigo_categoria">Categoria *</label>
                    <select id="codigo_categoria" name="codigo_categoria" required>
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ($categorias as $categoria): ?>
                            <option value="<?php echo $categoria['codigo']; ?>"
                                <?php echo ($_POST['codigo_categoria'] == $categoria['codigo']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($categoria['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="preco">Preço (R$) *</label>
                    <input type="number" id="preco" name="preco" step="0.01" min="0" required 
                           value="<?php echo htmlspecialchars($_POST['preco']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="estoque">Estoque</label>
                    <input type="number" id="estoque" name="estoque" min="0" 
                           value="<?php echo htmlspecialchars($_POST['estoque']); ?>">
                </div>
                
                <div class="form-group">
                    <label for="imagem">URL da Imagem</label>
                    <input type="text" id="imagem" name="imagem" 
                           value="<?php echo htmlspecialchars($_POST['imagem']); ?>">
                </div>
                
                <div>
                    <button type="submit" class="btn">Atualizar</button>
                    <a href="meus_produtos.php" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
