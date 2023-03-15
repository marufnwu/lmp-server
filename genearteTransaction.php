<?php
error_reporting(E_ALL);
require "../connection.php";
require "../connect.php";
if (!empty($_POST)) {
    $phone = isset($_POST['phone']) ?  $_POST['phone'] : "";
    $plan = isset($_POST['plan']) ?  $_POST['plan'] : 0;

    if (empty($phone) || $plan < 1 ) {
        echo json_encode(
            array(
                "error" => true,
                "msg" => "Some filed is missing"
            )
        );

        exit();
    }

    $conn = new Connection();
    $db = $conn->getInstance();

    $chekUser = $db->prepare("SELECT * FROM user_info_table WHERE phone=(:phone) LIMIT 1");
    $chekUser->bindParam(":phone", $phone, PDO::PARAM_STR);
    echo "1\n";
    if ($chekUser->execute()) {
        echo "2\n";
        if ($chekUser->rowCount() > 0) {
            echo "3\n";
            $row = $chekUser->fetch();
            $userId = $row['Id'];



            $isMatch  = true;
            while ($isMatch) {
                $characters = '123456789';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < 10; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
                $refCode = $randomString;


                $stmtRefCode = $db->prepare("SELECT transactionRef FROM plan_transaction WHERE transactionRef=(:ref)");
                $stmtRefCode->bindParam(":ref", $refCode, PDO::PARAM_STR);
                if($stmtRefCode->execute()){
                
                    if ($stmtRefCode->rowCount() < 1) {
                        $isMatch = false;
                    }
                }
               
            }

           

            $price = 0;

            if ($plan == 1) {
                $price = 1;
            } else if ($plan == 2) {
                $price = 2;
            }

            $issueAt = date('Y-m-d H:i:s');
            $paid = 0;
            $due = $price;

            $validity = 30 * 12;

            $transactionStmt = $db->prepare("INSERT INTO
            `plan_transaction` (`id`, `transactionRef`, `agentId`, `userid`, `planId`, `packageId`, `validity`,  `issueAt`, `price`, `paid`, `due`, `status`)
            VALUES (NULL, :ref, :userId, :userId, :plan, 0, :validity,  :issueAt, :price, 0, :price, 0)");

            $transactionStmt->bindParam(":ref", $refCode, PDO::PARAM_STR);
            $transactionStmt->bindParam(":userId", $userId, PDO::PARAM_STR);
            $transactionStmt->bindParam(":plan", $plan, PDO::PARAM_INT);
            $transactionStmt->bindParam(":validity", $validity, PDO::PARAM_STR);
            $transactionStmt->bindParam(":issueAt", $issueAt, PDO::PARAM_STR);
            $transactionStmt->bindParam(":price", $price, PDO::PARAM_STR);

            echo "5d\n";
            if ($transactionStmt->execute2()) {
                echo "5\n";
                echo json_encode(
                    array(
                        "error"=>false,
                        "msg"=>"Success",
                        "txnId"=>$refCode,
                        "price"=>(float)$price,
                        "phone"=>$phone,
                        "plan"=>(int)$plan,
                        "userId"=>$userId
                    )
                );
            } else {
                echo "6\n";

                echo json_encode(
                    array(
                        "error" => true,
                        "msg" => $transactionStmt->errorInfo()
                    )
                );
                exit();
            }
        } else {
            echo json_encode(
                array(
                    "error" => true,
                    "msg" => "User not found " . $chekUser->rowCount()
                )
            );
            exit();
        }
    } else {
        echo json_encode(
            array(
                "error" => true,
                "msg" => $chekUser->errorInfo()
            )
        );
        exit();
    }
} else {
    echo json_encode(
        array(
            "error" => true,
            "msg" => "Invalid Request"
        )
    );
}
