<?php
require "connection.php";

$conn;
$uploadPath = "uploads/";
$uploadUrl ="http://".$_SERVER['SERVER_NAME']."/" . $uploadPath;
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
    $videoTitle = $_REQUEST["videoTitle"];
    $videoId = $_REQUEST["videoId"];
  




    

    $stmt =  $conn->prepare("SELECT id FROM facebook_video WHERE id=$videoId LIMIT 1");
    $stmt->execute();

    if($stmt->rowCount()>0){
        
    }else{
        $response["status"] = "failed";
        $response["message"] = "Sorry, Failed to upload post for ". $videoId;
        echo json_encode($response);
        $conn = null;
        die();
    }


    if ( !isset( $_FILES["image"] ) && empty( $_FILES["image"]["name"] ) ) {
        //file not provided not update
        $stmt = $conn->prepare("UPDATE facebook_video SET video_title='$videoTitle', video_link='$videoLink' WHERE id=$videoId LIMIT 1 ");
       
        if( $stmt->execute()){
            $response["status"] = "success";
            $response["message"] = "Updated successfully ". $videoTitle;
            echo json_encode($response);
            $conn = null;
            die();
        }

    }else{
        //new thumb provided updload and update

        // INSERT INTO facebook_video (video_title, ,video_link, random_views)
        //                     VALUES ('$videoTitle', '$imageUrl','$videoLink', '$radmom')
        $random = substr(md5(mt_rand()), 0, 7);
        $imageInfo = pathinfo($_FILES["image"]["name"]);
        $imageExtensionInfo = $imageInfo["extension"];
        $imageUrl = $uploadUrl . "images/video_tutorial_image_" . $random . getFileName() . "." . $imageExtensionInfo;
        $imagePath = $uploadPath . "images/video_tutorial_image_" . $random . getFileName() . "." . $imageExtensionInfo;


        try {
            move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);

            $radmom = rand(3442, 10000);

            $insertStm = "UPDATE facebook_video SET  thumbail='$imageUrl', video_title='$videoTitle', video_link='$videoLink' WHERE id=$videoId LIMIT 1 ";
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