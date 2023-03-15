<?php
require_once("../connection.php");
require_once("class.helper.php");
define("SERIAL_CHECK_LISCENSE", 3);

class license
{
    private $db;
    private $userId;

    function __construct($userId)
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
        $this->userId = $userId;
    }

    function getAllLicense($userId){
        try{
            if (!helper::isAuth()) {
                return helper::errorResponse("User is not authenticated");
            }
    
            $stmt = $this->db->prepare("SELECT Phone FROM user_info_table WHERE Id='$userId' ");
            $stmt->execute();

            if($stmt->rowCount()>0){
                $phone = $stmt->fetch()['Phone'];
                $stmt = $this->db->prepare("SELECT paid_license.*, plan.name FROM paid_license LEFT JOIN plan ON paid_license.plan_type = plan.planId WHERE user_id='$userId' ");
                $stmt->execute();

                $list = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $newList = array();

                foreach ($list as $key => $value){
                    $value['started_date'] = date("m-d-Y", $value['started_date']/1000);
                    $value['expire_date'] = date("m-d-Y", $value['expire_date']/1000);

                    array_push($newList, $value);
                }

                $data = array(
                    "error"=>false,
                    "msg"=>"Success",
                    "phone"=>$phone,
                    "data"=> $newList
                );

                return $data;

            }else{
                return helper::errorResponse("User not valid");
            }

        }catch(Exception $e){
            return helper::errorResponse($e->getMessage());
        }



    }

    function getLicense()
    {
    }

    function serialCheckLicense()
    {

        if (!helper::isAuth()) {
            return helper::errorResponse("User is not authenticated");
        }


        $stmt = $this->db->prepare("SELECT * FROM paid_license WHERE user_id='$this->userId' AND plan_type=3" . " AND status =1 LIMIT 1");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data['expire_date'] > helper::currentTimestamp()) {
                    //valid license
                    return array(
                        "error" => false,
                        "msg" => "License is valid",
                        "license" => $data
                    );
                } else {
                    //license is expired
                    return helper::errorResponse("License is expired");
                }
            } else {
                return helper::errorResponse("License not found or deactivated");
            }
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }


    function checkLicense($planType)
    {

        if (!helper::isAuth()) {
            return helper::errorResponse("User is not authenticated");
        }



        $stmt = $this->db->prepare("SELECT * FROM paid_license WHERE user_id='$this->userId' AND plan_type='$planType' " . " AND status =1 LIMIT 1");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $data = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($data['expire_date'] > helper::currentTimestamp()) {
                    //valid license
                    return array(
                        "error" => false,
                        "msg" => "License is valid",
                        "license" => $data
                    );
                } else {
                    //license is expired
                    return helper::errorResponse("License is expired");
                }
            } else {
                return helper::errorResponse("License not found or deactivated");
            }
        } else {
            return helper::errorResponse($stmt->errorInfo());
        }
    }

    
}
