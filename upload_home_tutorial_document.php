<?php
require "connection.php";

$conn;
$uploadPath = "uploads/";
$uploadUrl = "https://lotteryresultpro.lotterysambadpro.xyz/" . $uploadPath;
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

    $targetUrl = $_REQUEST["TargetUrl"];

    $imageInfo = pathinfo($_FILES["image"]["name"]);
    $imageExtensionInfo = $imageInfo["extension"];
    $imageUrl = $uploadUrl . "images/tutorial_image_" . getFileName() . "." . $imageExtensionInfo;
    $imagePath = $uploadPath . "images/tutorial_image_" . getFileName() . "." . $imageExtensionInfo;

    try {
        move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        $insertStm = "UPDATE tutorial_table SET TargetUrl='$targetUrl', ImageUrl='$imageUrl' WHERE Id=1";
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
    $originalStm = $conn->prepare("SELECT * FROM tutorial_table ORDER BY Id DESC");
    $originalStm->execute();
    $lastItem = $originalStm->fetch(PDO::FETCH_ASSOC);
    $lastItemId = $lastItem["Id"];
    return $lastItemId;
    $conn = null;

}