<?php
include_once("../class/class.audio.php");
$name = isset($_GET['name']) ? $_GET['name'] : '';

$audio = new audio();
$result = $audio->getAudio($name);

echo json_encode($result);
exit;