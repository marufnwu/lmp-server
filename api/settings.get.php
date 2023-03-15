<?php
require_once("../class/class.helper.php");
require_once("../class/class.settings.php");


$settings = new settings();

echo json_encode(
    $settings->get()
);