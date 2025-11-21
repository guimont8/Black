<?php
require_once 'config/database.php';
require_once 'config/session.php';

// Se já estiver logado, redirecionar
if (isLoggedIn()) {
    if (isAdmin()) {
        header('Location: /admin/index.php');
    } else {
        header('Location: /user/index.php');
    }
    exit;
}

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    
    if (empty($email) || empty($senha)) {
        $erro = 'Preencha todos os campos';
    } else {
        try {
            $db = getDB();
            $stmt = $db->prepare("SELECT * FROM conta WHERE email = :email");
            $stmt->execute(['email' => $email]);
            $usuario = $stmt->fetch();
            
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Login bem-sucedido
                $_SESSION['usuario_codigo'] = $usuario['codigo'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_tipo'] = $usuario['tipo'];
                
                if ($usuario['tipo'] === 'admin') {
                    header('Location: /admin/index.php');
                } else {
                    header('Location: /user/index.php');
                }
                exit;
            } else {
                $erro = 'Email ou senha incorretos';
            }
        } catch (PDOException $e) {
            $erro = 'Erro ao fazer login: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema E-commerce</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container { 
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 30px; 
            color: #333;
        }
        .form-group { margin-bottom: 20px; }
        label { 
            display: block; 
            margin-bottom: 5px; 
            font-weight: bold; 
            color: #555;
        }
        input[type="email"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            font-size: 16px;
        }
        input[type="email"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        .btn { 
            width: 100%;
            padding: 12px; 
            background: #667eea; 
            color: white; 
            border: none; 
            cursor: pointer; 
            border-radius: 4px; 
            font-size: 16px;
            font-weight: bold;
        }
        .btn:hover { background: #5568d3; }
        .alert-error { 
            padding: 12px; 
            margin-bottom: 20px; 
            background: #f8d7da; 
            color: #721c24; 
            border: 1px solid #f5c6cb; 
            border-radius: 4px;
        }
        .info-box {
            margin-top: 30px;
            padding: 20px;
            background: #e7f3ff;
            border-radius: 4px;
            font-size: 14px;
        }
        .info-box h3 {
            margin-bottom: 10px;
            color: #004085;
        }
        .info-box p {
            margin-bottom: 5px;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Sistema E-commerce</h1>
        
        <?php if ($erro): ?>
            <div class="alert-error"><?php echo htmlspecialchars($erro); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required 
                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
            </div>
            
            <div class="form-group">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            
            <button type="submit" class="btn">Entrar</button>
        </form>
        
        <div class="info-box">
            <h3>Contas de Teste:</h3>
            <p><strong>Admin:</strong> admin@sistema.com / admin123</p>
            <p><strong>Usuário:</strong> joao@email.com / admin123</p>
            <p><strong>Usuário:</strong> maria@email.com / admin123</p>
        </div>
    </div>
</body>
</html>
