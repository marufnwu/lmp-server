<?php
require_once __DIR__."/../class/class.resultImage.php";

$imageUrl = $_GET['imageUrl'] ?? '';
$winDate = $_GET['winDate'] ?? '';
$winTime = $_GET['winTime'] ?? '';
$slotId = $_GET['slotId'] ?? 0;

$image = new ResultImage();

echo json_encode($image->add($imageUrl, $winDate, $winTime, $slotId));

?>