<?php
require_once("../connection.php");
require_once("class.helper.php");
require_once("class.auth.php");
require_once("class.license.php");


class user
{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public static function isPremium(){

        $userId = auth::authorize();
        if(empty($userId)){
            return false;
        }

        $user = new license($userId);
        $license = $user->getAllLicense($userId);

        if(!$license['error']){
            return true;
        }

        return false;
       
    }

    public function addUserDetails($userId, $name, $zila, $thana, $village, $postOffice, $pinCode, $phone, $whatsapp ){

        $stmt = $this->db->prepare("INSERT INTO user_details (user_id, name, zila, thana, post_office, village, pin_code, phone, whatsapp) VALUES($userId, '$name', '$zila', '$thana', '$village', '$postOffice', '$pinCode', '$phone', '$whatsapp' ) ");

        if($stmt->execute()){
            return helper::successResponse("Added");
        }else{
            return helper::errorResponse($stmt->errorInfo());
        }
    }

}