<?php
session_start();
$result['success'] = false;
$settings = [];
$statusCode = 401;

if (isset($_SESSION['uid'])) {
   if (isset($_POST['phone']))
      $settings['phone'] = $_POST['phone'];

   if (isset($_POST['mail']))
      $settings['mail'] = $_POST['mail'];

   if (!empty($settings)) {
      include('../../model/DbEgyTalk.php');
      $db = new DbEgyTalk();
      $result['success'] = $db->setSettings($_SESSION['uid'], $settings);
   }

   if ($result['success'])
      $statusCode = 201;
   else
      $statusCode = 400;
}

http_response_code($statusCode);
header('Content-Type: application/json');
echo json_encode($result, JSON_UNESCAPED_UNICODE);
