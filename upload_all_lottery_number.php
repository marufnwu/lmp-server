<?php
require "connection.php";
require "connection2.php";
require "aws.php";
require "network.php";
require_once __DIR__."/vendor/autoload.php";

$conn;
$response = array();

$client = new MongoDB\Client('mongodb://localhost:27017');

$db = $client->lmp;


//select table/collection
$lotteryTable  = $db->lottery_numbers;
$viewsTable  = $db->result_view;



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        $response["status"] = "failed";
        $response["message"] = "Unknown error occured. Please contact with admin.";
        echo json_encode($response);
        exit;
    } else {
        $valid_passwords = array("abdullah" => "563014");
        $valid_users = array_keys($valid_passwords);
        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];
        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);
        if (!$validated) {
            $response["status"] = "failed";
            $response["message"] = "Unknown error occured with authentication. Please contact with admin.";
            echo json_encode($response);
            exit;
        }
    }


    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();


    $ConnectionObj2 = new Connection2();
    $conn2 = $ConnectionObj2->getInstance();

//    $aws = new aws();
//    $awsConn = $aws->getInstance();


    $rawJsonData = file_get_contents('php://input');
    $convertedData = json_decode($rawJsonData);


    $serial = $convertedData[0]->LotterySerialNumber;
    $winDate = $convertedData[0]->WinDate;
    $winTime = $convertedData[0]->WinTime;


    $stmt = $conn->prepare("SELECT count(id) FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");
    $stmt2 = $conn2->prepare("SELECT count(id) FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");

    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $dlStmt = $conn->prepare("DELETE FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");
            $dlStmt2 = $conn2->prepare("DELETE FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");
            
            $dlStmt->execute();
            $dlStmt2->execute();
        }

       
    }

//    $stmt = $awsConn->prepare("SELECT count(id) FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");
//    if ($stmt->execute()) {
//        if ($stmt->rowCount() > 0) {
//            $dlStmt = $awsConn->prepare("DELETE FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");
//
//            $dlStmt->execute();
//        }
//    }

    //$res = $lotteryTable->insertMany($convertedData);
//    $response["status"] = "success";
//    $response["message"] = "Total Inserted ".$res->getInsertedCount();

    $_id = $winDate."_".$winTime;
    //$res = $viewsTable->insertOne(['_id'=>$_id, 'winDate'=>$winDate, 'winTime'=>$winTime, 'views'=>intval(0)]);
    $conn->beginTransaction();
    $conn2->beginTransaction();

    foreach ($convertedData as $single) {
        $lotteryNumber = $single->LotteryNumber;
        $lotterySerialNumber = $single->LotterySerialNumber;
        $winType = $single->WinType;
        $winDate = $single->WinDate;
        $winTime = $single->WinTime;
        $winDayName = $single->WinDayName;
        $slotId = $single->SlotId;
        $name = $single->Name;

        try {
            $insertStm = "INSERT INTO lottery_number_table (LotteryNumber, LotterySerialNumber,WinType,WinDate,WinTime,WinDayName, SlotId, Name)
            VALUES ('$lotteryNumber', '$lotterySerialNumber','$winType','$winDate','$winTime','$winDayName', '$slotId', '$name')";

            $insertResult = $conn->exec($insertStm);
            $insertResult2 = $conn2->exec($insertStm);
            //$insertResult1 = $awsConn->exec($insertStm);

            $response["status"] = "success";
            $response["message"] = "Lottery uploaded successfully";
        } catch (Exception $e) {
            $response["status"] = "failed";
            $response["message"] = "Sorry, Failed to upload post for " . $e->getMessage();
        }
    }



    $url = "https://secondary.lmpindia.com/upload_all_lottery_number.php";
    //$res = network::callAPI("POST", $url, $rawJsonData);


    $conn->commit();
    $conn2->commit();

    echo json_encode($response);
    $conn = null;
}
?>