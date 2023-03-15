<?php
require_once 'connect.php';
require_once 'connection.php';

$videoId = $_REQUEST["videoId"];
$userId = $_REQUEST["userId"];

// $ConnectionObj = new Connection();
// $conn = $ConnectionObj->getInstance();

try{
    // $stmt = $conn->prepare("INSERT INTO fb_video_views (videoId, userId) SELECT '$videoId', '$userId' WHERE NOT EXISTS (SELECT id FROM fb_video_views 
    //     WHERE `videoId`='$videoId' AND `userId`='$userId' LIMIT 1)");
    // $stmt->execute();

    echo json_encode(
        array(
            "error"=>false,
            "msg"=>"Views Addded"
        )
    );


}catch(Exception $e){
    echo json_encode(
        array(
            "error"=>true,
            "msg"=>$e->getMessage()
        )
    );
}

