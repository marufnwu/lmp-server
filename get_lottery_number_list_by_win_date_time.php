<?php
require "connection.php";

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

    public function getLotteryNumberList()
    {
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

	    $this->winTime = $_REQUEST["WinTime"];
        $this->winDate = $_REQUEST["WinDate"];
        $this->userId = $_REQUEST["userId"];
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

        try {
            $searchStm = $this->conn->prepare("SELECT * FROM lottery_number_table WHERE WinTime='$this->winTime' AND WinDate='$this->winDate' ORDER BY STR_TO_DATE(WinDate,'%d-%m-%Y') DESC, WinTime");
            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if (count($searchResult) === 0) {
                $this->response["status"]="not exist";
                $this->response["message"]="No information found about this lottery number.";
                echo json_encode($this->response);
            } else if (count($searchResult) >= 1) {
                
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


$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();