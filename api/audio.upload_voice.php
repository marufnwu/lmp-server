<?php
require_once("../class/class.helper.php");
require_once("../class/class.audio.php");
require_once("../connection.php");


$fileName  =  $_FILES['audioFile']['name'];
$tempPath  =  $_FILES['audioFile']['tmp_name'];
$fileSize  =  $_FILES['audioFile']['size'];

$to =  isset($_POST['to']) ? $_POST['to'] : '';
$from =  isset($_POST['from']) ? $_POST['from'] : '';
$type =  isset($_POST['type']) ? $_POST['type'] : '';
$sentAt  =  isset($_POST['sentAt']) ? $_POST['sentAt'] : '';
$duration =  isset($_POST['duration']) ? $_POST['duration'] : '';
$keypart =  isset($_POST['keypart']) ? $_POST['keypart'] : '';




$milliseconds = round(microtime(true) * 1000);

$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

$newName = $milliseconds.".".$fileExt;

$upload_path = '../uploads/audio_list/'; // set upload folder path

$newFileName = $upload_path.$newName;


// valid image extensions
$valid_extensions = array('mp3', 'wav', 'ogg');


// allow valid file formats
if (in_array($fileExt, $valid_extensions)) {

    try {

        if (move_uploaded_file($tempPath, $upload_path . $newName)) {

            $connectionObj = new Connection();
            $conn = $connectionObj->getInstance();

            $stmt = $conn->prepare("INSERT INTO message (mKey, type, sentAt, sender, receiver, audioUri, duration) VALUES('$keypart', '$type', '$sentAt', '$from', '$to', '$newFileName', $duration ) ");
            $stmt->execute();

            echo json_encode(helper::successResponse( $_POST['sentAt']));

        } else {
            echo helper::errorResponse("File upload error=> " . $_FILES['audioFile']['error']);
        }
    } catch (Exception $e) {
        echo json_encode(helper::errorResponse($e->getMessage()));
    }
} else {
    echo helper::errorResponse("$fileName File not valid");
}
