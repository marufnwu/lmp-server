<?php
require "connection.php";

class LotteryNumbersClass
{
    public $pageNumber;
    public $itemCount;
    public $from;
    public $to;

    public $response;
    public $allPosts;
    public $ConnectionObj;
    public $conn;


    private $perPage = 50;
    




    public function getLotteryNumberList()
    {
        $this->response = array();

        // $this->response["status"]="failed";
        // $this->response["message"]="This function currently off. Please try again later";
        // echo json_encode($this->response);

        // die();


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
        $middle = $_REQUEST["Middle"];
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();
        try {


            $startAt = $this->perPage * ($this->pageNumber - 1);

            if(empty($this->itemCount) || $this->itemCount<1){

                $count = $this->conn->prepare("SELECT SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<30 THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING SUBSTR(LotteryNumber, -4, 2)='$middle' AND LENGTH(LotteryNumber)<5 AND App=0");
                $count->execute();


                $totalItem =  $count->rowCount();
                $this->itemCount = ceil($totalItem / $this->perPage);
            }

            $searchStm = $this->conn->prepare("SELECT WinDate, WinTime, LotteryNumber, LotterySerialNumber ,SUM(CASE WHEN  DATEDIFF(CURDATE(), STR_TO_DATE(WinDate, '%d-%m-%Y'))<30 THEN 1 ELSE 0 END ) AS App FROM `lottery_number_table`  GROUP BY LotteryNumber HAVING SUBSTR(LotteryNumber, -4, 2)='$middle' AND LENGTH(LotteryNumber)<5 AND App=0 ORDER BY LotteryNumber ASC LIMIT $startAt, $this->perPage");
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
                $this->response["currentPage"]=$this->pageNumber;
                $this->response["totalPages"]=$this->itemCount;
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
