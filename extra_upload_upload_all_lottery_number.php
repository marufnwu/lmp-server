<?php
require "connection.php";

$conn;
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $response["status"]="failed";
            $response["message"]="Unknown error occured. Please contact with admin.";
            echo json_encode($this->response);
            exit;
    }else{
            $valid_passwords = array ("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
            if (!$validated) {
            $response["status"]="failed";
            $response["message"]="Unknown error occured with authentication. Please contact with admin.";
            echo json_encode($response);
            exit;
    }
}


    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();

    
    $rawJsonData=file_get_contents('php://input');
    $convertedData = json_decode($rawJsonData);

    
    $serial = $convertedData[0]->LotterySerialNumber;

    
    $stmt = $conn->prepare("SELECT count(id) FROM lottery_number_table WHERE LotterySerialNumber='$serial' ");

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $dlStmt = $conn->prepare("DELETE FROM lottery_number_table WHERE LotterySerialNumber='$serial' ");
            
            $dlStmt->execute();
        }

       
    }
    
    
    foreach ($convertedData as $single) {
        $lotteryNumber=$single->LotteryNumber;
        $lotterySerialNumber=$single->LotterySerialNumber;
        $winType=$single->WinType;
        $winDate=$single->WinDate;
        $winTime=$single->WinTime;
        $winDayName=$single->WinDayName;
        
        
        try {
        $insertStm = "INSERT INTO lottery_number_table (LotteryNumber, LotterySerialNumber,WinType,WinDate,WinTime,WinDayName)
        VALUES ('$lotteryNumber', '$lotterySerialNumber','$winType','$winDate','$winTime','$winDayName')";
        $insertResult = $conn->exec($insertStm);
        $response["status"] = "success";
        $response["message"] = "uploaded successfully";
        } catch (Exception $e) {
        $response["status"] = "failed";
        $response["message"] = "Sorry, Failed to upload post for " . $e->getMessage();
        }
    }
    echo json_encode($response);
    $conn = null;
    
    
}