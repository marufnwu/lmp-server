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
            
           
           $searchStm = $this->conn->prepare("SELECT LotteryNumber, SUBSTR(LotteryNumber,1,2) AS Middlle FROM lottery_number_table GROUP BY SUBSTR(LotteryNumber,-4,2) ORDER BY COUNT(Id) DESC, SUBSTR(LotteryNumber,1,2) DESC, SUBSTR(LotteryNumber,2,2) DESC");
                
            
            
            $searchStm->execute();
            

            if ($searchStm->rowCount()==0) {
                $this->response["status"]="not exist";
                $this->response["message"]="No information found about this lottery number.";
                echo json_encode($this->response);
            } else if ($searchStm->rowCount()>= 1) {

                $searchResult = array();
                while($row = $searchStm->fetch()){

                    $item = array(
                        "Id"=>"",
                        "LotteryNumber"=>$row['LotteryNumber'],
                        "LotterySerialNumber"=>"",
                        "WinType"=>"",
                        "WinDate"=>"",
                        "WinTime"=>"",
                        "WinDayName"=>"",
                    );

                    array_push($searchResult, $item);
                }


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
