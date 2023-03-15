<?php
require_once 'connection.php';
require_once 'class/class.helper.php';

$UserId = $_REQUEST["userId"];
$comment = $_REQUEST["comment"];


$ConnectionObj = new Connection();
$conn = $ConnectionObj->getInstance();

$stmt = $conn->prepare("UPDATE user_info_table SET comment='$comment' WHERE Id='$UserId' LIMIT 1");
if($stmt->execute()){
    echo json_encode(helper::successResponse("Comment updated"));
}else{
    echo json_encode(helper::errorResponse($stmt->errorInf()));
}