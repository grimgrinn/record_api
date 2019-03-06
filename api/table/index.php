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

$table = Tables::isValidName($_POST['table']) ? $_POST['table'] : die('Неверный параметр (table)');

$item = new $table($db);

if (isset($_POST['id'])) {
    $item->id = (int)$_POST['id'];
    $item->readSingle();
    echo jsonAnswer($item);
    return;
}

$result = $item->read();
$num = $result->rowCount();

if($num > 0) {
    while($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $news_arr[] = $row;
    }
    echo jsonAnswer($news_arr);
} else {
    echo jsonAnswer([],'ok','there is no data here');
}

