<?php
require "connection.php";

class ResultInfoClass{
    public $lotteryNumber;

    public $response;
    public $status;
    public $message;
    public $ConnectionObj;
    public $conn;

    public function getResultInfos(){
        $this->response = array();
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->response["status"]="failed";
            $this->response["message"]="Unknown error occured. Please contact with admin.";
            echo json_encode($this->response);
            exit;
        }else{
            $valid_passwords = array ("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
            if (!$validated) {
            $this->response["status"]="failed";
            $this->response["message"]="Unknown error occured with authentication. Please contact with admin.";
            echo json_encode($this->response);
            exit;
            }
        }

        $this->lotteryNumber = $_REQUEST["LotteryNumber"];

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();
        try {
            $searchStm = $this->conn->prepare("SELECT lottery_number_table.*, lottery_result_time.time FROM lottery_number_table INNER JOIN lottery_result_time ON lottery_number_table.SlotId =  lottery_result_time.id WHERE SUBSTR(LotteryNumber,2,2)='$this->lotteryNumber' AND (LENGTH(LotteryNumber) > 4) AND WinType='2nd' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime");
            $searchStm->execute();
            //$searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if ($searchStm->rowCount()==0) {
                $this->response["status"]="not exist";
                $this->response["message"]="No information found about this lottery number.";
                echo json_encode($this->response);
            } else if ($searchStm->rowCount()>= 1) {

                $searchResult = array();
                while($row = $searchStm->fetch()){

                    $item = array(
                        "Id"=>$row['Id'],
                        "LotteryNumber"=>$row['LotteryNumber'],
                        "LotterySerialNumber"=>$row['LotterySerialNumber'],
                        "WinType"=>$row['WinType'],
                        "WinDate"=>$row['WinDate'],
                        "WinTime"=>$row['time'],
                        "WinDayName"=>$row['WinDayName'],
                        "SlotId"=>$row['SlotId'],
                    );

                    array_push($searchResult, $item);
                }


                $this->response["status"]="success";
                $this->response["message"]="Lottery number information found successfully.";
                $this->response["data"]=$searchResult;
                echo json_encode($this->response);
            }
            $this->conn = null;
        } catch (PDOException $e) {
            $this->response["status"]="failed";
            $this->response["message"]="Sorry, Result checking failed for " . $e->getMessage();
            echo json_encode($this->response);
            $this->conn = null;
        }
    }
}


$ResultInfoClassObj = new ResultInfoClass();
$ResultInfoClassObj->getResultInfos();
