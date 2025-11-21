<?php
require_once '../../config/database.php';
require_once '../../config/session.php';

requireAdmin();

$db = getDB();
$codigo = $_GET['codigo'] ?? null;

if (!$codigo) {
    header('Location: index.php');
    exit;
}

try {
    // Verificar se existem produtos vinculados a esta categoria
    $stmt = $db->prepare("SELECT COUNT(*) as total FROM produto WHERE codigo_categoria = :codigo");
    $stmt->execute(['codigo' => $codigo]);
    $result = $stmt->fetch();
    
    if ($result['total'] > 0) {
        setError('Não é possível excluir esta categoria pois existem produtos vinculados a ela');
    } else {
        $stmt = $db->prepare("DELETE FROM categoria WHERE codigo = :codigo");
        $stmt->execute(['codigo' => $codigo]);
        
        setSuccess('Categoria excluída com sucesso!');
    }
} catch (PDOException $e) {
    setError('Erro ao excluir categoria: ' . $e->getMessage());
}

header('Location: index.php');
exit;
?>
