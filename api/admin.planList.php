<?php
require_once("../class/class.helper.php");
require_once("../class/class.lotttery.php");

$lottery = new lottery();

echo json_encode(
    $lottery->getPlanList()
);