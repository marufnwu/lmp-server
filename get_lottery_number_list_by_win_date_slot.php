<?php
//require "connection.php";
require "connection2.php";
require "network.php";
//require "aws.php";
//require_once __DIR__."/class/mongodbRepo.php";

class LotteryNumbersClass
{
    public $winDate;
    public $winTime;
    public $userId;
    public $from;
    public $to;

    public $response;
    public $allPosts;
    public $ConnectionObj;
    public $conn;
    public $slotId;

    public function getLotteryNumberList()
    {
        //echo date('Y-m-d H:i:s')." 111\n";


        $this->response = array();
        // if (!isset($_SERVER['PHP_AUTH_USER'])) {
        //     $this->response["status"]="failed";
        //     $this->response["message"]="Unknown error occured. Please contact with admin.";
        //     //echo json_encode($this->response);
        //     exit;
        // }else{
        //     $valid_passwords = array ("abdullah" => "563014");
        //     $valid_users = array_keys($valid_passwords);
        //     $user = $_SERVER['PHP_AUTH_USER'];
        //     $pass = $_SERVER['PHP_AUTH_PW'];
        //     $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
        //     if (!$validated) {
        //     $this->response["status"]="failed";
        //     $this->response["message"]="Unknown error occured with authentication. Please contact with admin.";
        //     //echo json_encode($this->response);
        //     exit;
        //     }
        // }

        $this->slotId = $_REQUEST["SlotId"];
        $this->winDate = $_REQUEST["WinDate"];
        $this->userId = $_REQUEST["userId"];
        $this->allPosts = array();

        //echo date('Y-m-d H:i:s')." 1\n";

        $this->ConnectionObj = new Connection2();
        $this->conn = $this->ConnectionObj->getInstance();


        


        $this->response["status"] = "success";
        $this->response["message"] = "Lottery number information found successfully. using mongodb";

        //$mongo = new mongodbRepo();
        //echo date('Y-m-d H:i:s')." 2\n";



        //$this->response["data"] = $mongo->getResultByDateAndSlot($this->winDate, $this->slotId);
        //echo date('Y-m-d H:i:s')." 3\n";

//        if(!empty($this->response["data"])){
//            echo json_encode($this->response);
//            die();
//        }



        try {
            $searchStm = $this->conn->prepare("SELECT * FROM lottery_number_table WHERE SlotId='$this->slotId' AND WinDate='$this->winDate' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime");
            //$searchStm = $awsConn->prepare("SELECT * FROM lottery_number_table WHERE SlotId='$this->slotId' AND WinDate='$this->winDate' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime");
            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if (count($searchResult) === 0) {
                $this->response["status"] = "not exist";
                $this->response["message"] = "No information found about this lottery number.";
                echo json_encode($this->response);
            } 
            else if (count($searchResult) == 131) {

                $this->response["status"] = "success";
                $this->response["message"] = "Lottery number information found successfully. using mysql";
                $this->response["data"] = $searchResult;

//                $pdfStmt = $this->conn->prepare("SELECT * FROM pdf WHERE slot=$this->slotId AND date='$this->winDate' LIMIT 1");
//                $pdfStmt->execute();
//
//                if($pdfStmt->rowCount()>0){
//                    $pdf = $pdfStmt->fetch(PDO::FETCH_ASSOC);
//                    $pdf['pdf'] = "https://lotterymasterpro.com/".$pdf['pdf'];
//                    $this->response['pdf'] = $pdf;
//                }

                echo json_encode($this->response);
            }else{
                $this->response["status"] = "not exist";
                $this->response["message"] = "Result upload error";
                echo json_encode($this->response);
            }
            $this->conn = null;
        } catch (PDOException $e) {
            $this->response["status"] = "failed";
            $this->response["message"] = "Sorry, Result checking failed for " . $e->getMessage();
            echo json_encode($this->response);
            $this->conn = null;
        }
    }

    public function getFromSecondServer()
    {
        $url = "https://secondary.lmpindia.com/get_lottery_number_list_by_win_date_slot.php";
        $res = network::callAPI("GET", $url, $_REQUEST);

        echo $res;
    }
}

//echo date('Y-m-d H:i:s')." 1444\n";
$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();
//$LotteryNumbersClassObj->getFromSecondServer();

