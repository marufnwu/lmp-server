<?php
require "connection.php";
require "network.php";
require_once "aws.php";


require __DIR__."/class/mongodbRepo.php";

class ResultInfoClass{
    public $lotteryNumber;

    public $pageNumber;
    public $itemCount;
    public $allPosts;

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

        $this->pageNumber = $_REQUEST["PageNumber"];
        $this->itemCount = $_REQUEST["ItemCount"];
        $this->lotteryNumber = $_REQUEST["LotteryNumber"];
        $this->allPosts = array();


        srand(floor(microtime(true) * 1000));
        $isMongo = rand(0,1)==1;

//        if($isMongo){
//            $mongo = new mongodbRepo();
//            echo json_encode( $mongo->searchLotteryNumber($this->lotteryNumber, $this->pageNumber, 100, 0));
//            die();
//        }




        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();


//        $aws = new aws();
//        $awsConn =$aws->getInstance();



        try {

            $searchStm = $this->conn->prepare("SELECT LotteryNumber, LotterySerialNumber, WinDate, WinTime, WinType, SlotId FROM lottery_number_table WHERE LotteryNumber LIKE '$this->lotteryNumber%' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime LIMIT 100");
            //$searchStm = $awsConn->prepare("SELECT LotteryNumber, LotterySerialNumber, WinDate, WinTime, WinType, SlotId FROM lottery_number_table WHERE LotteryNumber LIKE '$this->lotteryNumber%' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime LIMIT 100");



            $searchStm->execute();
            //$searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if ($searchStm->rowCount()==0) {
                $this->response["status"]="not exist";
                $this->response["message"]="No information found about this lottery number.";
                echo json_encode($this->response);
            } else if ($searchStm->rowCount()>= 1) {




                $this->response["status"]="success";
                $this->response["message"]="Lottery number information found successfully.";
                $this->response["data"]=$searchStm->fetchAll(PDO::FETCH_ASSOC);
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

