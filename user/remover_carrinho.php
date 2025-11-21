<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$codigo = $_GET['codigo'] ?? null;
$usuario_codigo = getUsuarioLogado();

if (!$codigo) {
    header('Location: carrinho.php');
    exit;
}

try {
    // Remover item do carrinho (apenas se pertencer ao usuário logado)
    $stmt = $db->prepare("
        DELETE FROM carrinho 
        WHERE codigo = :codigo AND codigo_conta = :codigo_conta
    ");
    $stmt->execute([
        'codigo' => $codigo,
        'codigo_conta' => $usuario_codigo
    ]);
    
    setSuccess('Item removido do carrinho!');
} catch (PDOException $e) {
    setError('Erro ao remover item: ' . $e->getMessage());
}

header('Location: carrinho.php');
exit;
?>
