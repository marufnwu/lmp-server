<?php
require_once __DIR__."/../class/class.resultImage.php";

$id = $_GET['id'] ?? 0;

$image = new ResultImage();

echo json_encode($image->delete($id));

?>