<?php
require "connection.php";
require_once 'connect.php';
$conn;
$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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


    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();

    $token = $_REQUEST["Token"];
    $phone = $_REQUEST["Phone"];
    $registrationDate = $_REQUEST["RegistrationDate"];
    $activeStatus = $_REQUEST["ActiveStatus"];
    $countryCode = $_REQUEST["countryCode"];

    try {
        $CheckSQL = "SELECT * FROM user_info_table WHERE Phone='$phone'  ";
        $check = mysqli_fetch_array(mysqli_query($connn, $CheckSQL));

        if (isset($check)) {

            //user already created

            $ActiveStatus = (int)$check["ActiveStatus"];
            $pro_license = (int)$check["pro_license"];
            $paid_license = (int)$check["paid_license"];

            if ($ActiveStatus == 0) {
                $UpdateStatus = "UPDATE user_info_table SET Token='$token', ActiveStatus='1', country_code='$countryCode' WHERE Phone='$phone' ";
                mysqli_query($connn, $UpdateStatus);

                $response["status"] = "clear";
                $response["message"] = $check['Id'];
                echo json_encode($response);
            } else {

                if ($pro_license == 1 || $paid_license == 1) {

                    $UpdateStatus = "UPDATE user_info_table SET Token='$token', ActiveStatus='1', country_code='$countryCode' WHERE Phone='$phone' ";
                    mysqli_query($connn, $UpdateStatus);

                    if ($phone == '1778473031') {
                        $response["status"] = "clear";
                    } else {
                        $response["status"] = "logged";
                    }

                    //$response["status"] = "clear";
                    $response["message"] = $check['Id'];
                    echo json_encode($response);
                } else {

                    $UpdateStatus = "UPDATE user_info_table SET Token='$token', ActiveStatus='1', country_code='$countryCode' WHERE Phone='$phone' ";
                    mysqli_query($connn, $UpdateStatus);

                    $response["status"] = "clear";
                    $response["message"] = $check['Id'];
                    echo json_encode($response);
                }
            }
        } else {
            $milliseconds = round(microtime(true) * 1000);
            //new user, need to create user
            $insertUser = "INSERT INTO user_info_table (Token, Phone,RegistrationDate,country_code, last_activated)
            VALUES ('$token', '$phone','$registrationDate','$countryCode', '$milliseconds')";

            if (mysqli_query($connn, $insertUser)) {
                $CheckSQL = "SELECT * FROM user_info_table WHERE Phone='$phone'  ";
                $check = mysqli_fetch_array(mysqli_query($connn, $CheckSQL));

                $response["status"] = "clear";
                $response["message"] = $check['Id'];
                echo json_encode($response);
            } else {
                echo mysqli_error($connn);
            }
        }
    } catch (Exception $e) {
        $response["status"] = "failed";
        $response["message"] = "Sorry, Failed to upload post for " . $e->getMessage();
        echo json_encode($response);
        $conn = null;
    }
}
