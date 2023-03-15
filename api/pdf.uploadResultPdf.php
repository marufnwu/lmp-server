<?php
require_once("../connection.php");
require_once("../class/class.helper.php");
if (empty($_POST)) {
    echo json_encode(helper::errorResponse("Request is not permitted"));
    die();
}

$date = $_POST['date'] ?? "";
$slotId = $_POST['slotId'] ?? 0;

$fileName  =  $_FILES['pdfFile']['name'];
$tempPath  =  $_FILES['pdfFile']['tmp_name'];
$fileSize  =  $_FILES['pdfFile']['size'];


$milliseconds = round(microtime(true) * 1000);
$videoId = null;


$upload_path = '../uploads/resultPdf/'; // set upload folder path

$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// valid image extensions
$valid_extensions = array('jpg', 'jpeg', 'png');

if (in_array($fileExt, $valid_extensions)) {

    try {

        $videoId = $milliseconds;

        $fileName = $videoId . "." . $fileExt;

        // array(
        //     "image"=>helper::rootUrl()."/".$thumUpload["msg"],
        //     "audio"=>helper::rootUrl()."/uploads/audio_list/".$fileName
        // )

        if (move_uploaded_file($tempPath, $upload_path . $fileName)) {
            $url = "uploads/resultPdf/".$fileName;
            $conn = new Connection();
            $db = $conn->getInstance();

            $stmt = $db->prepare("INSERT INTO pdf (date, slot, pdf) VALUES('$date', $slotId, '$url') ");
            if($stmt->execute()){
                echo json_encode(helper::successResponse());
            }else{
                echo json_encode(helper::errorResponse());
            }

        } else {
            echo json_encode(helper::errorResponse("File upload error=> " . $_FILES['pdfFile']['error']));
        }
    } catch (Exception $e) {
        echo json_encode(helper::errorResponse($e->getMessage()));
    }
} else {
    echo json_encode(helper::errorResponse($fileName));
}
