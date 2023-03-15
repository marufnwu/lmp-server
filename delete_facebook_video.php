<?php
require_once "connection.php";
require_once "class/class.helper.php";

$videoId = $_GET['videoId'] ?? 0;

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    echo json_encode(helper::errorResponse("Unknown error occured. Please contact with admin"));
    exit;
} else {
    $valid_passwords = array("abdullah" => "563014");
    $valid_users = array_keys($valid_passwords);
    $user = $_SERVER['PHP_AUTH_USER'];
    $pass = $_SERVER['PHP_AUTH_PW'];
    $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
    if (!$validated) {
        
        echo json_encode(helper::errorResponse("Unknown error occured. Please contact with admin."));
        exit;
    }
}

$db = new Connection();
$conn = $db->getInstance();

$stmt  =$conn->prepare("DELETE FROM facebook_video WHERE id=$videoId ");
if($stmt->execute()){
    if($stmt->rowCount()>0){
        echo json_encode(helper::successResponse());
    }else{
        echo json_encode(helper::errorResponse());
    }
}else{
   echo json_encode(helper::errorResponse());
}

?>