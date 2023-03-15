<?php
require_once __DIR__."/../connection.php";
require_once "class.helper.php";
class ResultImage{
    private $db;
    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }


    private function deleteIfExits($winDate, $slotId){
        $stmt = $this->db->prepare("DELETE FROM result_image WHERE winDate='$winDate' AND slotId=$slotId ");
        if($stmt->execute()){
            return true;
        }

        return false;
    }

    public function get($windate, $slotId)
    {
       // helper::AuthMiddleware();
        $stmt  = $this->db->prepare("SELECT * FROM result_image WHERE winDate='$windate' AND slotId=$slotId");
        if ($stmt->execute()) {
           
            if($stmt->rowCount()>0){
                $data = $stmt->fetch(PDO::FETCH_ASSOC);
                return helper::successResponseWithData("Success", "data", $data);
            }else{
                return helper::errorResponse("Result Image Not Found");
            }

            
        } else {
            return helper::errorResponse();
        }

    }

    public function add($imageUrl, $winDate, $wintime,  $slotId){

        helper::AuthMiddleware();

        $this->deleteIfExits($winDate, $slotId);
        $stmt = $this->db->prepare("INSERT INTO result_image (winDate, winTime,  slotId, imageUrl) VALUES('$winDate', '$wintime',  $slotId, '$imageUrl' )");

        if($stmt->execute()){
            return helper::successResponse();
        }else{
            return helper::errorResponse();
        }
    }


    public function update($id, $imageUrl)
    {

        helper::AuthMiddleware();
       
        $stmt = $this->db->prepare("UPDATE result_image SET imageUrl='$imageUrl' WHERE id=$id");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return helper::successResponse();
            } else {
                return helper::errorResponse();
            }
        } else {
            return helper::errorResponse();
        }
    }



    public function getAll()
    {
      // helper::AuthMiddleware();
       $stmt  = $this->db->prepare("SELECT * FROM result_image ORDER BY DATE(winDate) DESC, slotId ASC");
       if($stmt->execute()){
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return helper::successResponseWithData("Success", "data", $data);
       }else{
            return helper::errorResponse();
       }


    }



    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM result_image WHERE id=$id LIMIT 1");
        if($stmt->execute()){
            if($stmt->rowCount()>0){
                return helper::successResponse();

            }else{
                return helper::errorResponse();
            }
        }else{
            return helper::errorResponse();
        }
    }
}