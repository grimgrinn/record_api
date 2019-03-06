<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/News.php';
include_once '../../models/Session.php';
include_once '../../service/BasicEnum.php';
include_once '../../enums/Tables.php';
include_once '../../service/message.php';

$database = new Database();
$db = $database->connect();
$news = new News($db);

$email = $_POST['userEmail'] ? $_POST['userEmail'] : die('Неверный параметр (email)');
$title = $_POST['newsTitle'] ? $_POST['newsTitle'] : die('Неверный параметр (newsTitle)');
$message = $_POST['newsMessage'] ? $_POST['newsMessage'] : die('Неверный параметр (newsMessage)');

$news->newsTitle = $title;
$news->newsMessage = $message;
if($news->create()) {
    echo jsonAnswer([],'ok','Спасибо, ваша новость сохранена');
} else {
    echo jsonAnswer([],'error','ошибка базы днных');
}



