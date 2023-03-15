<?php
require "connection.php";

class LotteryNumbersClass
{
    public $userId;
    public $pageId;
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

        $this->pageId = $_REQUEST["pageId"];
        $this->userId = $_REQUEST["userId"];
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

        try {
            require_once 'connect.php';
$query_paid_info = "SELECT * FROM tbl_paid_for_contact WHERE target_page='$this->pageId' AND status='1' ";
$result_paid_info = mysqli_query($connn, $query_paid_info);
$row_paid_info = mysqli_fetch_assoc($result_paid_info);   


$query_banner = "SELECT * FROM tbl_banner_ad WHERE status='1' ";
$result_banner = mysqli_query($connn, $query_banner);
$row_banner = mysqli_fetch_assoc($result_banner);  

            $this->response["phone_one"] = $row_paid_info['phone_one'];
            $this->response["phone_two"] = $row_paid_info['phone_two'];
            $this->response["phone_three"] = $row_paid_info['phone_three'];
            $this->response["whats_app"] = $row_paid_info['whats_app'];
            $this->response["video_link"] = $row_paid_info['video_link'];
            $this->response["video_thumbail"] = $row_paid_info['video_thumbail'];
            $this->response["banner_image"] = $row_banner['banner_image'];
            $this->response["target_link"] = $row_banner['target_link'];
            echo json_encode($this->response);
           $this->conn = null;
        } catch (PDOException $e) {
            $this->response["status"]='0';
            $this->response["message"]="Sorry, Result checking failed for " . $e->getMessage();
            echo json_encode($this->response);
            $this->conn = null;
        }
    }
}


$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();