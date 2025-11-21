<?php
require_once '../config/database.php';
require_once '../config/session.php';

requireLogin();

$db = getDB();
$codigo = $_GET['codigo'] ?? null;
$usuario_codigo = getUsuarioLogado();

if (!$codigo) {
    header('Location: meus_produtos.php');
    exit;
}

try {
    // Verificar se o usuário é o dono do produto antes de excluir
    $stmt = $db->prepare("SELECT codigo_dono FROM produto WHERE codigo = :codigo");
    $stmt->execute(['codigo' => $codigo]);
    $produto = $stmt->fetch();
    
    if (!$produto) {
        setError('Produto não encontrado');
    } elseif ($produto['codigo_dono'] != $usuario_codigo) {
        setError('Você não tem permissão para excluir este produto');
    } else {
        $stmt = $db->prepare("DELETE FROM produto WHERE codigo = :codigo AND codigo_dono = :codigo_dono");
        $stmt->execute([
            'codigo' => $codigo,
            'codigo_dono' => $usuario_codigo
        ]);
        
        setSuccess('Produto excluído com sucesso!');
    }
} catch (PDOException $e) {
    setError('Erro ao excluir produto: ' . $e->getMessage());
}

header('Location: meus_produtos.php');
exit;
?>
