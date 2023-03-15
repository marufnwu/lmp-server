<?php
require_once __DIR__."/../class/class.resultImage.php";


$image = new ResultImage();

echo json_encode($image->getAll());

?>