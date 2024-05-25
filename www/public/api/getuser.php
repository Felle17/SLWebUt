<?php
session_start();
$result= [];
$statusCode = 401;
include('../../model/DbEgyTalk.php');

if (isset($_SESSION['uid'])){
   
   $db = new DbEgyTalk();
   $uid = $_SESSION['uid'];
   
   $result = $db->getUserFromUid($uid);
   $statusCode = 200;   
}else{
   $statusCode = 400; 
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);