<?php
include_once __DIR__."/../class/class.update.php";

$userVersion = isset($_GET['version']) ? $_GET['version'] : 0;
$userId = isset($_GET['userId']) ? $_GET['userId'] : 0;

$update  = new update();

echo json_encode($update->check($userId, $userVersion), JSON_NUMERIC_CHECK);