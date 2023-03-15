<?php
require "connection.php";

$ConnectionObj = new Connection();
$conn = $ConnectionObj->getInstance();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        echo json_encode(
            array(
                "error"=>true,
                "msg"=>"Unknown error occured. Please contact with admin."
            )
        );
    
    
        exit;
    }else{
        $valid_passwords = array ("abdullah" => "563014");
        $valid_users = array_keys($valid_passwords);
        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];
        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
        if (!$validated) {
        echo json_encode(
            array(
                "error"=>true,
                "msg"=>"Unknown error occured. Please contact with admin."
            )
        );
    
        exit;
        }
    }

    $status = isset($_POST['status']) ? $_POST['status'] : 0;
    $status =  filter_var($status, FILTER_VALIDATE_BOOLEAN, 0);
    
    $url = isset($_POST['url']) ? $_POST['url'] : "";

    if(empty($url)){
        echo json_encode(
            array(
                "error"=>true,
                "msg"=>"Url not valid"
            )
        );
    }

    try{
        $stmt = $conn->prepare("UPDATE audio_file SET audioUrl='$url', active='$status' WHERE name='HomeAudio' ");
        if($stmt->execute()){
            echo json_encode(
                array(
                    "error"=>false,
                    "msg"=>"Updated"
                )
            );
        }
    }catch(Exception $e){
        echo json_encode(
            array(
                "error"=>true,
                "msg"=>$e->getMessage()
            )
        );
    }
    
}