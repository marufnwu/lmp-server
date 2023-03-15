<?php
require_once("../class/class.helper.php");
require_once("../class/class.settings.php");

$status = $_GET['status'] ?? 0;


$settings = new settings();

echo json_encode(
    $settings->updateHomeWp($status)
);