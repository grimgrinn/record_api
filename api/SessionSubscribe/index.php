<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Session.php';
include_once '../../service/message.php';

$database = new Database();
$db = $database->connect();
$session = new Session($db);

$sessionId = $_POST['sessionId'] ? $_POST['sessionId'] : die('Неверный параметр (sessionId)');
$userEmail = $_POST['userEmail'] ? $_POST['userEmail'] : die('Неверный параметр (userEmail)');

$session->id = (int) $sessionId;
if($session->book($userEmail)) {
    echo jsonAnswer([],'ok','Спасибо, вы успешно записаны');
} else {
    echo jsonAnswer([],'error','Извините, все места заняты');
}



