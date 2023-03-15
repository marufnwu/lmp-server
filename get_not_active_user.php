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
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

        try {


            $startAt = $this->perPage * ($this->pageNumber - 1);

           
                $count = $this->conn->prepare("SELECT COUNT(id) AS total FROM `user_info_table` WHERE 
                (pro_license=1 OR
                paid_license=1 OR
                4digit_license=1 OR
                last_digit_license=1) =1
                AND DATEDIFF(CURDATE(), FROM_UNIXTIME(last_activated/1000,'%Y-%m-%d'))>30 AND isCall=0");
                $count->execute();
        
                $totalItem =  $count->fetch()['total'];
                $this->itemCount = ceil($totalItem / $this->perPage);
            

            $searchStm = $this->conn->prepare("SELECT * FROM `user_info_table` WHERE 
                                                (pro_license=1 OR
                                                paid_license=1 OR
                                                4digit_license=1 OR
                                                last_digit_license=1) =1
                                                AND DATEDIFF(CURDATE(), FROM_UNIXTIME(last_activated/1000,'%Y-%m-%d'))>30 AND isCall=0 LIMIT $startAt, $this->perPage");
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
                $this->response["currentPage"]=(int)$this->pageNumber;
                $this->response["totalPages"]=(int)$this->itemCount;
                $this->response["totalItem"]=(int)$totalItem;
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
