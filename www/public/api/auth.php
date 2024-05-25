<?php
/**
 * Autentiserar användare.
 * Om inget postat så kontrolleras om sessionsvariabeln lever (=)
 * 
 * @param $_POST['username']  användarnamn vid inloggning
 * @param $_POST['pwd']       lösenord 
 * @return {"auth": true/false, "userdata": {uid, firstname, surname}/null} 
 */
session_start();

include('../../model/DbEgyTalk.php');
$db = new DbEgyTalk();

$result['auth'] = false;
$result['userdata'] = null;
$statusCode = 401;

// Om redan inloggad skicka data
if (isset($_SESSION['uid'])) {
   $user = $db->getUserFromUid($_SESSION['uid']);
} else if (isset($_POST['username'], $_POST['pwd'])) {
   $user = $db->auth($_POST['username'], $_POST['pwd']);
}

if (isset($user) && !empty($user)) {
   $result['auth'] = true;
   $result['userdata'] = $user;
   session_regenerate_id();
   $_SESSION['uid'] = $user['uid'];
   $statusCode = 200;
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
