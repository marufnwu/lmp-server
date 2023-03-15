<?php
require_once("../connection.php");
require_once("class.helper.php");
class video{
    private $db;
    public function __construct(){
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public function getRandomVideo(){
        $stmt = $this->db->prepare("SELECT * FROM facebook_video ORDER BY RAND() LIMIT 7");
        try{
            $stmt->execute();
            return helper::successResponseWithData("Success", "data", $stmt->fetchAll(PDO::FETCH_ASSOC));
        }catch(Exception $e){
            return helper::errorResponse($e->getMessage());
        }
    }
}