<?php

/**
 * Lägger till en post
 * 
 * @param $_POST['post_txt']  Postade texten
 * @return {"success": true/false} beroende på om det gick att lägga till en post
 */
session_start();
$success = false;
$statusCode = 401;

if (isset($_SESSION['uid'], $_POST['post_txt'])) {
   include('../../model/DbEgyTalk.php');

   $db = new DbEgyTalk();

   if ($db->addPost($_SESSION['uid'], $_POST['post_txt'])) {
      $success = true;
      $statusCode = 201;
   }else{
      $statusCode = 400;
   }
}

$result['success'] =  $success;

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
