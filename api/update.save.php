<?php
include_once __DIR__."/../class/class.update.php";

$checkVersion = isset($_GET['checkVersion']) ? $_GET['checkVersion'] : 0;
$message = isset($_GET['message']) ? $_GET['message'] : 0;
$thumbUrl = isset($_GET['thumbUrl']) ? $_GET['thumbUrl'] : 0;
$thumbAction = isset($_GET['thumbAction']) ? $_GET['thumbAction'] : 0;
$updateAction = isset($_GET['updateAction']) ? $_GET['updateAction'] : 0;
$openGooglePlay = isset($_GET['openGooglePlay']) ? $_GET['openGooglePlay'] : 0;
$mandatory = isset($_GET['mandatory']) ? $_GET['mandatory'] : 0;

$update  = new update();

echo json_encode($update->save($checkVersion, $message, $thumbUrl, $thumbAction, $updateAction, $openGooglePlay, $mandatory));