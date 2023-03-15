<?php
require_once("../connection.php");
require_once("class.helper.php");
class dialog{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    function getDialogInfo($activity){
        $stmt = $this->db->prepare("SELECT * FROM activity_dialog WHERE activity='$activity' AND active=1 LIMIT 1");
        if($stmt->execute()){

            if($stmt->rowCount()>0){
                $row = $stmt->fetch();
                return array(
                    "error"=>false,
                    "msg"=>"Success",
                    "activityDialog"=>array(
                        "id"=>(int)$row['id'],
                        "activity"=>$row['activity'],
                        "action"=>(bool)$row['action'],
                        "actionUrl"=>$row['actionUrl'],
                        "imageUrl"=>$row['imageUrl'],
                        "showImage"=>(bool)$row['showImage'],
                        "description"=>$row['description'],
                        "active"=>(bool)$row['active'],
                    )
                );
            }else{
                return helper::errorResponse("No Dialog Found");
            }

            
        }else{
            return helper::errorResponse($stmt->errorInfo());
        }
    }
}