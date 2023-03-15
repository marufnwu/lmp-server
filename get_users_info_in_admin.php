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

        try {
            $currentDate = round(microtime(true) * 1000);
            if($this->listPosition == "1"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '1' ");
            }else if($this->listPosition == "2"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ac_position='1' ORDER BY Id DESC");
            }else if($this->listPosition == "3"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE (paid_license.plan_type='1' OR paid_license.plan_type='2') AND paid_license.expire_date < '$currentDate' AND paid_license.status='1' ");
            }else if($this->listPosition == "4"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ac_position='0' ORDER BY Id DESC");
            }else if($this->listPosition == "5"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ActiveStatus='0' AND ac_position='1' ORDER BY Id DESC");
            }else if($this->listPosition == "6"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ActiveStatus='1' AND ac_position='1' ORDER BY Id DESC");
            }else if($this->listPosition == "7"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '2' ");
            }else if($this->listPosition == "8"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ac_position='1' ORDER BY last_activated DESC");
            }else if($this->listPosition == "9"){
                $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE ac_position='1' ORDER BY used_version DESC");
            }else if($this->listPosition == "10"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '3' ");
            }else if($this->listPosition == "11"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '4' ");
            }else if($this->listPosition == "12"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '5' ");
            }else if($this->listPosition == "13"){
                $searchStm = $this->conn->prepare("SELECT * FROM paid_license JOIN user_info_table ON user_id = user_info_table.Id WHERE paid_license.status = '1' AND paid_license.expire_date > '$currentDate' AND paid_license.plan_type = '6' ");
            }
            
            
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
