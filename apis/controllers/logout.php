<?php
if (!isset($_SESSION["login"])) {
    Validate::resultMessage(0, 403, 'You are already logged out.');
    exit();
}

$_SESSION = [];

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 42000, '/');
}

session_destroy();
