<?php
include_once __DIR__."/../class/class.update.php";

$update  = new update();

echo json_encode($update->info(), JSON_NUMERIC_CHECK);