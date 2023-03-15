<?php
require_once("../class/class.helper.php");
$page = isset($_GET['page']) ? $_GET['page'] : 1;


$url = "https://lmpclass.sikderithub.com/api/getVideo.php";

$res =helper::CallAPI("GET", $url, array(
    "page"=>$page
));

echo json_encode($res);