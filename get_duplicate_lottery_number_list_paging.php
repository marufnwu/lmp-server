<?php
require "connection.php";
require "network.php";
require "aws.php";

require_once __DIR__."/class/mongodbRepo.php";

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


    private $perPage = 100;
    




    public function getLotteryNumberList()
    {
        $this->response = array();

//        $this->response["status"]="failed";
//            $this->response["message"]="This function under construction";
//            echo json_encode($this->response);
//
//            die();



        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->response["status"]="failed";
            $this->response["message"]="Unknown error occurred. Please contact with admin.";
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
        $this->allPosts = array();

//        $mongo = new mongodbRepo();
//        $res = $mongo->getDuplicateLotteryNumber($this->pageNumber, 30, $this->itemCount);
//
//        echo json_encode($res);
//
//        die();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

//        $aws = new aws();
//        $awsConn = $aws->getInstance();

        try {


            $startAt = $this->perPage * ($this->pageNumber - 1);

            if(empty($this->itemCount) || $this->itemCount<1){
                //echo $date = date('Y-m-d H:i:s')." 1\n";
                $count = $this->conn->prepare("SELECT  COUNT(*) AS total FROM (SELECT COUNT(id) FROM lottery_number_table GROUP BY LotteryNumber) AS z");
                //$count = $awsConn->prepare("SELECT  COUNT(*) AS total FROM (SELECT COUNT(id) FROM lottery_number_table GROUP BY LotteryNumber) AS z");
                $count->execute();
                //echo $date = date('Y-m-d H:i:s')." 2\n";

        
                $totalItem =  $count->fetch()['total'];
                $this->itemCount = ceil($totalItem / $this->perPage);
                //echo $date = date('Y-m-d H:i:s')." rr\n";

            }







            //echo $date = date('Y-m-d H:i:s')." 3\n";

            $searchStm = $this->conn->prepare("SELECT LotteryNumber, LotterySerialNumber , SUM(1) AS count FROM lottery_number_table GROUP BY LotteryNumber ORDER BY count  DESC LIMIT $startAt, $this->perPage");
            //$searchStm = $awsConn->prepare("SELECT LotteryNumber, LotterySerialNumber , SUM(1) AS count FROM lottery_number_table GROUP BY LotteryNumber ORDER BY count  DESC LIMIT $startAt, $this->perPage");
            //$searchStm = $this->conn->prepare("SELECT LotteryNumber, LotterySerialNumber , SUM(1) AS count FROM lottery_number_table GROUP BY LotteryNumber ORDER BY count  DESC LIMIT 200");
            $searchStm->execute();
            //echo $date = date('Y-m-d H:i:s')." 4\n";


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

    public function getFromSecondServer()
    {
        $url = "https://secondary.lmpindia.com/get_duplicate_lottery_number_list_paging.php";
        $res = network::callAPI("GET", $url, $_REQUEST);

        echo $res;
    }
}


$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();
//$LotteryNumbersClassObj->getFromSecondServer();
