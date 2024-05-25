<?php
session_start();
$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

session_destroy();

$result['auth'] = false;
$result['userdata'] = [];

header('Content-Type: application/json');

echo json_encode($result, JSON_UNESCAPED_UNICODE);