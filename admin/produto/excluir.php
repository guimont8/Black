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
    $stmt = $db->prepare("DELETE FROM produto WHERE codigo = :codigo");
    $stmt->execute(['codigo' => $codigo]);
    
    setSuccess('Produto excluído com sucesso!');
} catch (PDOException $e) {
    setError('Erro ao excluir produto: ' . $e->getMessage());
}

header('Location: index.php');
exit;
?>
