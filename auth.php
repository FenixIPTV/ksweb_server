<?php
ini_set('session.cookie_lifetime', 0);
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);

session_start();

function authenticate() {
    if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
        header('HTTP/1.1 401 Unauthorized');
        header('Location: login.php');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['token']) {
            header('HTTP/1.1 403 Forbidden');
            die('Token CSRF inválido');
        }
    }
    
    return [
        'username' => $_SESSION['username'],
        'token' => $_SESSION['token']
    ];
}
?>