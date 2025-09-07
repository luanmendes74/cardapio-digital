<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se um utilizador de estabelecimento está autenticado
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

// Verifica se um Super Admin está autenticado
function isSuperAdminLoggedIn()
{
    return isset($_SESSION['super_admin_id']);
}

// --- NOVO: Helper de mensagens Flash ---
// Cria uma mensagem que só é exibida uma vez e depois é destruída.
// Útil para mensagens de sucesso ou erro após uma ação.
function flash($name = '', $message = '', $class = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash" role="alert"><span class="block sm:inline">' . $_SESSION[$name] . '</span></div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

