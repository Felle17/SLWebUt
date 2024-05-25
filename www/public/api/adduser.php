<?php

/**
 * Lägger till en användare
 * 
 * @param $_POST['firstname']   förnamn
 * @param $_POST['surname']     eftrenamn
 * @param $_POST['user']        användarnamn
 * @param $_POST['pwd']         lösen
 * @return {"success": true/false} beroende på om det gick att lägga till en post
 */
session_start();

include('../../model/DbEgyTalk.php');
$db = new DbEgyTalk();
$statusCode = 400;
$success = false;

if (isset($_POST['firstname'], $_POST['surname'], $_POST['user'], $_POST['pwd']) && !isset($_SESSION['uid'])) {
   if ($db->addUser($_POST['firstname'], $_POST['surname'], $_POST['user'], $_POST['pwd'])) {
      $success = true;
      $statusCode = 201;
   }
}

$result['success'] =  $success;

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
