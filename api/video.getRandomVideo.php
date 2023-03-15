<?php
include_once("../class/class.video.php");

$video = new video();
$result = $video->getRandomVideo();

echo json_encode($result);
exit;