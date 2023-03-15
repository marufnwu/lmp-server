<?php
require_once("../class/thumb.php");
require_once("../class/class.helper.php");
require_once("../class/class.audio.php");
if (empty($_POST)) {
    echo json_encode(helper::errorResponse("Request is not permitted"));
    die();
}

$title = isset($_POST['title']) ? $_POST['title'] : "";

$fileName  =  $_FILES['audioFile']['name'];
$tempPath  =  $_FILES['audioFile']['tmp_name'];
$fileSize  =  $_FILES['audioFile']['size'];


$milliseconds = round(microtime(true) * 1000);
$videoId = null;


$upload_path = '../uploads/audio_list/'; // set upload folder path 

$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// valid image extensions
$valid_extensions = array('mp3', 'wav', 'ogg');

$thumb = new thumb();
$thumUpload = $thumb->upload($_FILES['thumb'], $milliseconds);

if ($thumUpload['error']) {
    echo json_encode(helper::errorResponse($thumUpload['msg']));
    die();
}

// allow valid file formats
if (in_array($fileExt, $valid_extensions)) {

    try {

        $videoId = $milliseconds;

        $fileName = $videoId . "." . $fileExt;

        // array(
        //     "image"=>helper::rootUrl()."/".$thumUpload["msg"],
        //     "audio"=>helper::rootUrl()."/uploads/audio_list/".$fileName
        // )

        if (move_uploaded_file($tempPath, $upload_path . $fileName)) {
            $audio = new audio();
            echo json_encode($audio->addAudioTutorial($title,"uploads/audio_list/".$fileName ,$thumUpload["msg"] ));

        } else {
            echo "File upload error=> " . $_FILES['videoFile']['error'];
        }
    } catch (Exception $e) {
        echo $e;
    }
} else {
    echo "$fileName File not valid";
}
