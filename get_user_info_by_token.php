<?php
require "connection.php";

class ResultInfoClass{
    
    
    public $token;
    public $userId;
    
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
        
        $this->token = $_REQUEST["Token"];
        $this->userId = $_REQUEST["userId"];

        $this->ConnectionObj = new Connection();
        $this->conn = $this->ConnectionObj->getInstance();
        try {
            $searchStm = $this->conn->prepare("SELECT * FROM user_info_table WHERE Id='$this->userId' AND Token='$this->token' AND ActiveStatus='1' ");
            $searchStm->execute();
            $searchResult = $searchStm->fetchAll(PDO::FETCH_ASSOC);
            if (count($searchResult) === 0) {
                $this->response["status"]="not_exist";
                $this->response["message"]="No information found about this token.";
                echo json_encode($this->response);
            } else if (count($searchResult) >= 1) {
                $this->response["status"]="success";
                $this->response["message"]="Token information found successfully.";
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


$ResultInfoClassObj = new ResultInfoClass();
$ResultInfoClassObj->getResultInfos();