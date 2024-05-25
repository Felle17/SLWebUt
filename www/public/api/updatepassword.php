<?php
session_start();
$result['success'] = false;
$statusCode = 401;

if (isset($_SESSION['uid']) && isset($_POST['pwd'], $_POST['oldpwd'])) {
   include('../../model/DbEgyTalk.php');
   $db = new DbEgyTalk();
   $result['success'] = $db->setPassword($_SESSION['uid'], $_POST['oldpwd'], $_POST['pwd']);
   
   if($result['success'])
      $statusCode = 201;
   else
      $statusCode = 400;
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE); 