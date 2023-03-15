<?php
require_once("../class/class.helper.php");
$page = isset($_GET['page']) ? $_GET['page'] : 1;


$url = "https://lmpclass.sikderithub.com/api/getSpecialVideo.php?page=$page";

$res =helper::CallAPI("GET", $url);

echo json_encode(array());