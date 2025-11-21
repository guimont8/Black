<?php
/**
 * Configuração e funções de sessão
 */

// Iniciar sessão se ainda não foi iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Verifica se o usuário está logado
 */
function isLoggedIn() {
    return isset($_SESSION['usuario_codigo']) && !empty($_SESSION['usuario_codigo']);
}

/**
 * Verifica se o usuário é admin
 */
function isAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] === 'admin';
}

/**
 * Obtém o código do usuário logado
 */
function getUsuarioLogado() {
    return $_SESSION['usuario_codigo'] ?? null;
}

/**
 * Obtém o nome do usuário logado
 */
function getNomeUsuario() {
    return $_SESSION['usuario_nome'] ?? 'Usuário';
}

/**
 * Redireciona para login se não estiver logado
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /login.php');
        exit;
    }
}

/**
 * Redireciona para login se não for admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: /user/index.php');
        exit;
    }
}

/**
 * Define mensagem de sucesso
 */
function setSuccess($mensagem) {
    $_SESSION['mensagem_sucesso'] = $mensagem;
}

/**
 * Define mensagem de erro
 */
function setError($mensagem) {
    $_SESSION['mensagem_erro'] = $mensagem;
}

/**
 * Obtém e limpa mensagem de sucesso
 */
function getSuccess() {
    if (isset($_SESSION['mensagem_sucesso'])) {
        $msg = $_SESSION['mensagem_sucesso'];
        unset($_SESSION['mensagem_sucesso']);
        return $msg;
    }
    return null;
}

/**
 * Obtém e limpa mensagem de erro
 */
function getError() {
    if (isset($_SESSION['mensagem_erro'])) {
        $msg = $_SESSION['mensagem_erro'];
        unset($_SESSION['mensagem_erro']);
        return $msg;
    }
    return null;
}

/**
 * Faz logout do usuário
 */
function logout() {
    session_destroy();
    header('Location: /login.php');
    exit;
}
?>
