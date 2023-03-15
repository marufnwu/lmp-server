<?php
require "connection.php";

$conn;
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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


    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();

    $numOne = $_REQUEST["numOne"];
    $numTwo = $_REQUEST["numTwo"];
    $numThree = $_REQUEST["numThree"];
    $numFour = $_REQUEST["numFour"];
    $numFive = $_REQUEST["numFive"];

    $numSix = $_REQUEST["numSix"];
    $numSeven = $_REQUEST["numSeven"];
    $numEight = $_REQUEST["numEight"];
    $numNine = $_REQUEST["numNine"];
    $numTen = $_REQUEST["numTen"];

    try {
        
        require_once 'connect.php';
        
        $updateDate = round(microtime(true) * 1000);
        $queryLice = "UPDATE tbl_special_number SET number_one='$numOne', number_two='$numTwo', number_three='$numThree', number_four='$numFour', number_five='$numFive',
        number_six='$numSix', number_seven='$numSeven', number_eight='$numEight', number_nine='$numNine', number_ten='$numTen', 
        
        upload_date='$updateDate' WHERE id='1'";
        if(mysqli_query($connn, $queryLice)){
        $response["status"] = "success";
        $response["message"] = "uploaded successfully";
        echo json_encode($response); 
           
        }
        $conn = null;

    } catch (Exception $e) {
        $response["status"] = "failed";
        $response["message"] = "Sorry, Failed to upload post for " . $e->getMessage();
        echo json_encode($response);
        $conn = null;
    }
}

