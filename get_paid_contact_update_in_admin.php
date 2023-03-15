<?php
require "connection.php";

$conn;
$uploadPath = "uploads/";
$uploadUrl = "https://lotterymasterpro.com/" . $uploadPath;
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->response["status"]="failed";
            $this->response["message"]="Unknown error occured. Please contact with admin.";
            echo json_encode($this->response);
            exit;
        }else{
            $valid_passwords = array ("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
            if (!$validated) {
            $this->response["status"]="failed";
            $this->response["message"]="Unknown error occured with authentication. Please contact with admin.";
            echo json_encode($this->response);
            exit;
            }
        }


    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();

    $videoLink = $_REQUEST["videoLink"];
    $phoneOne = $_REQUEST["phoneOne"];
    $phoneTwo = $_REQUEST["phoneTwo"];
    $phoneThree = $_REQUEST["phoneThree"];
    $whatsAppNum = $_REQUEST["whatsAppNum"];
    $targetActivity = $_REQUEST["targetActivity"];

    $imageInfo = pathinfo($_FILES["image"]["name"]);
    $imageExtensionInfo = $imageInfo["extension"];
    $random = substr(md5(mt_rand()), 0, 7);
    $imageUrl = $uploadUrl . "images/target_thumbail_image_".$targetActivity.$random. getFileName() . "." . $imageExtensionInfo;
    $imagePath = $uploadPath . "images/target_thumbail_image_".$targetActivity.$random. getFileName() . "." . $imageExtensionInfo;

    try {
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        $insertStm = "UPDATE tbl_paid_for_contact SET phone_one='$phoneOne', phone_two='$phoneTwo', phone_three='$phoneThree', whats_app='$whatsAppNum', video_link='$videoLink', video_thumbail='$imageUrl' WHERE target_page='$targetActivity'";
        $insertResult = $conn->exec($insertStm);
        $response["status"] = "success";
        $response["message"] = "uploaded successfully";
        echo json_encode($response);
        $conn = null;

    } catch (Exception $e) {
        $response["status"] = "failed";
        $response["message"] = "Sorry, Failed to upload post for " . $e->getMessage();
        echo json_encode($response);
        $conn = null;
    }
}

function getFileName()
{
    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();
    $originalStm = $conn->prepare("SELECT * FROM ads_info_table ORDER BY Id DESC");
    $originalStm->execute();
    $lastItem = $originalStm->fetch(PDO::FETCH_ASSOC);
    $lastItemId = $lastItem["Id"];
    return $lastItemId;
    $conn = null;

}