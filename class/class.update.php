<?php
include_once "class.helper.php";
require_once(__DIR__ . "/../connection.php");
class update{
    private $db;
    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }


    public function check($userId, $userVersion)
    {
        $userVersion = (int) $userVersion;

        $s = "SELECT * FROM force_update WHERE  forceUpdate=1 AND checkVersion  > $userVersion LIMIT 1";
       
        $stmt = $this->db->prepare($s);
        $stmt->execute();

        if($stmt->rowCount()>0){
            return helper::successResponseWithData("Update Available", "data", $stmt->fetch(PDO::FETCH_ASSOC));
        }else{
            return helper::errorResponse("Update not available");
        }


    }

    public function changeStatus($status){
        $s = "UPDATE force_update SET forceUpdate=$status LIMIT 1";

        $stmt = $this->db->prepare($s);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return helper::successResponse();
        } else {
            return helper::errorResponse();
        }
    }


    public function info()
    {
        $s = "SELECT * FROM force_update  LIMIT 1";
       
        $stmt = $this->db->prepare($s);
        $stmt->execute();

        if($stmt->rowCount()>0){
            return helper::successResponseWithData("Update Available", "data", $stmt->fetch(PDO::FETCH_ASSOC));
        }else{
            return helper::errorResponse("Not Found");
        }


    }

    public function save($checkVersion, $message, $thumUrl, $thumbAction, $updateAction, $openPlayStore, $manadatory, ){
        $stmt = $this->db->prepare("UPDATE force_update SET checkVersion= $checkVersion,  message='$message', image='$thumUrl', actionUrl='$thumbAction', updateUrl='$updateAction', openPlayStore=$openPlayStore, mandatory=$manadatory  ");

        if($stmt->execute()){
            return $this->info();
        }else{
            return helper::errorResponse();
        }
    }
}

