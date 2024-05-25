<?php
/**
 * Lägger till en kommentar
 * 
 * @param $_POST['pid']  pid för post som skall kommenteras
 * @param $_POST['comment_txt']  pid för post som skall kommenteras
 * @return {"success": true/false} beroende på om det gick att lägga till en post
 */
session_start();
include('../../model/DbEgyTalk.php');
$db = new DbEgyTalk();
$statusCode = 401;

$success = false;

if (isset($_SESSION['uid'], $_POST['pid'], $_POST['comments_txt'])) {
    if($db->addComment($_SESSION['uid'], $_POST['pid'], $_POST['comments_txt'])){
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