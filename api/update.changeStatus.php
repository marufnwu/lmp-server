<?php
include_once __DIR__."/../class/class.update.php";

$status = isset($_GET['status']) ? $_GET['status'] : 0;

$update  = new update();

echo json_encode($update->changeStatus($status));