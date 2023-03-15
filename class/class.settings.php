<?php
require_once(__DIR__."/../connection.php");
require_once("class.helper.php");
class settings{
    const perPage = 10;
    private PDO  $db;
    public function __construct(){
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }


    public function updateHomeWp($status){
        $stmt  =$this->db->prepare("UPDATE settings SET homeWhatsapp=$status LIMIT 1 ");
        if ($stmt->execute()) {
            return helper::successResponse();
        } else {
            return helper::errorResponse();
        }
    }


    public function get(){
        $stmt = $this->db->prepare("SELECT * FROM settings LIMIT 1");
        if($stmt->execute()){
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);

            return helper::successResponseWithData("Success", "data", $settings);

        }else{
            return helper::errorResponse();
        }
    }

}