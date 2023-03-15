<?php
require_once __DIR__."/../class/class.resultImage.php";

$id = $_GET['id'] ?? 0;
$imageUrl = $_GET['imageUrl'] ?? "";

$image = new ResultImage();
echo json_encode($image->update($id, $imageUrl));

?>