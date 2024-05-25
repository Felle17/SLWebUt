<?php
session_start();
$result = null;
$statusCode = 401;

if (isset($_SESSION['uid'])) {
   include('../../model/DbEgyTalk.php');
   $db = new DbEgyTalk();
   $result = $db->getSettings($_SESSION['uid']);
   $statusCode = 200;
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
