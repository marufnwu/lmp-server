<?php
require "connection.php";
require "network.php";

$conn;
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SERVER['PHP_AUTH_USER'])) {
        $response["status"] = "failed";
        $response["message"] = "Unknown error occured. Please contact with admin.";
        echo json_encode($this->response);
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

    $district = isset($_REQUEST['district']) ? $_REQUEST['district'] : '';
    $city = isset($_REQUEST['city']) ? $_REQUEST['city'] : '';
    $deviceId = isset($_REQUEST['deviceId']) ? $_REQUEST['deviceId'] : '';
    $userId = isset($_REQUEST['userId']) ? $_REQUEST['userId'] : '';


    $stmt = $conn->prepare("INSERT INTO location (district, city, userId, deviceId) VALUES('$district' , '$city','$userId', '$deviceId' ) ");

    if ($stmt->execute()) {
        echo json_encode(
            array(
                "error" => false,
                "msg" => "Success"
            )
        );
    } else {
        echo json_encode(
            array(
                "error" => true,
                "msg" => $stmt->errorInfo()
            )
        );
    }




    $conn = null;
}
