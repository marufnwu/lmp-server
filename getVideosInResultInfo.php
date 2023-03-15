<?php
require_once("api.class.php");
$limit = 3;

$url = "https://lmpvideo.sikderithub.com/getVideosInResultInfo.php";

$param = array(
    "limit" => $limit
);
$res = api::CallAPI("POST", $url, $param);

echo $res;
