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
            $this->response["status"] = "failed";
            $this->response["message"] = "Unknown error occured. Please contact with admin.";
            echo json_encode($this->response);
            exit;
        } else {
            $valid_passwords = array("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
            if (!$validated) {
                $this->response["status"] = "failed";
                $this->response["message"] = "Unknown error occured with authentication. Please contact with admin.";
                echo json_encode($this->response);
                exit;
            }
        }


        $this->userId = $_REQUEST["userId"];
        $this->allPosts = array();

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();

        try {
            require_once 'connect.php';
            $currentDate = round(microtime(true) * 1000);

            $query_pro = "SELECT * FROM paid_license WHERE user_id='$this->userId' AND plan_type='2' AND status ='1' ";

            $pro_check = mysqli_fetch_array(mysqli_query($connn, $query_pro));
            if (isset($pro_check)) {
                if ($pro_check['expire_date'] > $currentDate) {
                    $PRO_LICENSE = "1";
                } else {
                    $PRO_LICENSE = "0";
                }
            } else {
                $PRO_LICENSE = "0";
            }

            if ($PRO_LICENSE == "0") {
                $this->response["status"] = '0';
                $this->response["message"] = "You have no right to access this ".$this->userId;

                echo json_encode($this->response);
                die();
            }


            $query_paid_info = "SELECT * FROM tbl_special_number WHERE id='1' ";
            $result_paid_info = mysqli_query($connn, $query_paid_info);
            $row_paid_info = mysqli_fetch_assoc($result_paid_info);


            $this->response["number_one"] = $row_paid_info['number_one'];
            $this->response["number_two"] = $row_paid_info['number_two'];
            $this->response["number_three"] = $row_paid_info['number_three'];
            $this->response["number_four"] = $row_paid_info['number_four'];
            $this->response["number_five"] = $row_paid_info['number_five'];

            $this->response["number_six"] = $row_paid_info['number_six'];
            $this->response["number_seven"] = $row_paid_info['number_seven'];
            $this->response["number_eight"] = $row_paid_info['number_eight'];
            $this->response["number_nine"] = $row_paid_info['number_nine'];
            $this->response["number_ten"] = $row_paid_info['number_ten'];

            $this->response["upload_date"] = $row_paid_info['upload_date'];


            echo json_encode($this->response);
            $this->conn = null;
        } catch (PDOException $e) {
            $this->response["status"] = '0';
            $this->response["message"] = "Sorry, Result checking failed for " . $e->getMessage();
            echo json_encode($this->response);
            $this->conn = null;
        }
    }
}


$LotteryNumbersClassObj = new LotteryNumbersClass();
$LotteryNumbersClassObj->getLotteryNumberList();
