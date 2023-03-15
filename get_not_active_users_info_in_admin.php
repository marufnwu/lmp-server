<?php
require "connection.php";

class LotteryNumbersClass
{
    public $pageNumber;
    public $itemCount;
    public $listPosition;
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

        $this->pageNumber = $_REQUEST["PageNumber"];
        $this->itemCount = $_REQUEST["ItemCount"];
        $this->listPosition = $_REQUEST['listPosition'];
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

        $currentDate = round(microtime(true) * 1000);

        $stmt = $this->conn->prepare("SELECT last_activated, 
                                        FROM_UNIXTIME((CURRENT_TIMESTAMP() + 1)/1000), 
                                        FROM_UNIXTIME(last_activated/1000) AS d 
                                        FROM `user_info_table`  ORDER BY d DESC");

       
    }
}


$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();
