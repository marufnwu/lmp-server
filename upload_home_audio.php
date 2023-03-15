<?php
require "connection.php";
$AUDIO_NAME = "HomeAudio";
$response = array();
$uploadPath = "uploads/";
$uploadUrl = "http://".$_SERVER['HTTP_HOST'] ."/". $uploadPath;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$ConnectionObj = new Connection();
$conn = $ConnectionObj->getInstance();

$targetUrl = $_REQUEST["TargetUrl"];
$activeStatus = $_REQUEST["ActiveStatus"];

$millSecond = round(microtime(true) * 1000);

$audioInfo = pathinfo($_FILES["audio"]["name"]);
$audioExtensionInfo = $audioInfo["extension"];
$audioUrl = $uploadUrl . "audios/homeAudio/home_audio_" . $millSecond . "." . $audioExtensionInfo;
$audioPath = $uploadPath . "audios/homeAudio/home_audio_" . $millSecond . "." . $audioExtensionInfo;

$allowedExts = array( "mp3", "wma", "wav", "ogg");
$extension = pathinfo($_FILES['audio']['name'], PATHINFO_EXTENSION);



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



if(in_array($extension, $allowedExts)){
    try {
        move_uploaded_file($_FILES["audio"]["tmp_name"], $audioPath);

        $stmtCheck = $conn->prepare("SELECT * FROM audio_file WHERE name='$AUDIO_NAME' ");
        if($stmtCheck->execute()){
            if($stmtCheck->rowCount()>0){
                $stmtUpdate = $conn->prepare("UPDATE audio_file SET audioUrl='$audioUrl' WHERE name='$AUDIO_NAME' ");
                if($stmtUpdate->execute()){
                    echo json_encode(
                        array(
                            "error"=>false,
                            "msg"=>"Audio Updated"
                        )
                    );
                }
            }else{
                //insert
                $stmtInsert = $conn->prepare("INSERT INTO audio_file (audioUrl, name, active) VALUES('$audioUrl', '$AUDIO_NAME', 0)");
                if($stmtInsert->execute()){
                    echo json_encode(
                        array(
                            "error"=>false,
                            "msg"=>"Audio Inserted"
                        )
                    );
                }
            }
        }

        $conn=null;

    } catch (Exception $e) {
        $conn = null;

        echo json_encode(
            array(
                "error"=>true,
                "msg"=>"Sorry, Failed to upload post for " . $e->getMessage()
            )
        );
    }
}else{
    echo json_encode(
        array(
            "error"=>true,
            "msg"=>$extension." File not supported"
        )
    );
}
}

?>