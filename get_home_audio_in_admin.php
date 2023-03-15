<?php
require "connection.php";

$ConnectionObj = new Connection();
$conn = $ConnectionObj->getInstance();
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        echo json_encode(
            array(
                "error"=>true,
                "msg"=>"Unknown error occured. Please contact with admin. aa"
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
                "msg"=>"Unknown error occured. Please contact with admin. bb"
            )
        );
    
        exit;
        }
    }


    echo json_encode(
        array(
            "error" => true,
            "msg" => "Unknown error occured. Please contact with admin. aa"
        )
    );

    $status = isset($_POST['status']) ? $_POST['status'] : 0;
    try{
        $stmt = $conn->prepare("SELECT * FROM audio_file  WHERE name='HomeAudio' ");
        if($stmt->execute()){
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            

            if($data){
                $data['active']=(bool)$data['active'];
                echo json_encode(
                    array(
                        "error"=>false,
                        "msg"=>"Success",
                        "audio"=>$data
                    )
                );
                
            }else{
                echo json_encode(
                    array(
                        "error"=>true,
                        "msg"=>"Audio Not Found"
                    )
                );
            }
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