<?php
include_once("../class/class.audio.php");

$audio = new audio();
$result = $audio->getRandomVoiceMessage();

echo json_encode($result);
exit;