<?php
require_once __DIR__."/../class/class.resultImage.php";

$winDate = $_GET['winDate'] ?? '';
$slotId = $_GET['slotId'] ?? 0;

$image = new ResultImage();

echo json_encode($image->get($winDate, $slotId));

?>