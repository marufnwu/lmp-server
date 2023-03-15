<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
require "../connection.php";
require "../connect.php";
if (!empty($_POST)) {
    $phone = isset($_POST['phone']) ?  $_POST['phone'] : "";
    $package = isset($_POST['package']) ?  $_POST['package'] : 0;
    $product = isset($_POST['product']) ?  $_POST['product'] : " ";
    $email = isset($_POST['email']) ?  $_POST['email'] : " ";

    if (empty($phone) || $package < 1) {
        echo json_encode(
            array(
                "error" => true,
                "msg" => json_encode($_POST)
            )
        );

        exit();
    }

    $conn = new Connection();
    $db = $conn->getInstance();

    $chekUser = $db->prepare("SELECT * FROM user_info_table WHERE phone=(:phone) LIMIT 1");
    $chekUser->bindParam(":phone", $phone, PDO::PARAM_STR);

    if ($chekUser->execute()) {

        if ($chekUser->rowCount() > 0) {

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
                if ($stmtRefCode->execute()) {

                    if ($stmtRefCode->rowCount() < 1) {
                        $isMatch = false;
                    }
                }
            }



            $price = 0;

            //get package data from server

            $stmtPkg = $db->prepare("SELECT * FROM service_package WHERE id=(:id) AND active=1 LIMIT 1");
            $stmtPkg->bindParam(":id", $package, PDO::PARAM_INT);

            if ($stmtPkg->execute()) {
                
                if ($stmtPkg->rowCount() > 0) {
                    $row = $stmtPkg->fetch();
                    $planId = $row['planId'];
                    $validity = $row['validity'];
                    $price = $row['price'];
                    $pkgId = $row['id'];


                    $issueAt = date('Y-m-d H:i:s');
                    $paid = 0;
                    $due = $price;


                    $transactionStmt = $db->prepare("INSERT INTO
            `plan_transaction` (`id`, `transactionRef`, `agentId`, `userid`, `planId`, `packageId`, `validity`,  `issueAt`, `price`, `paid`, `due`, `status`, productId, email)
            VALUES (NULL, :ref, :userId, :userId, :plan, :packageId, :validity,  :issueAt, :price, 0, :price, 0, :productId, :email)");

                    $transactionStmt->bindParam(":ref", $refCode, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":userId", $userId, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":plan", $planId, PDO::PARAM_INT);
                    $transactionStmt->bindParam(":validity", $validity, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":issueAt", $issueAt, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":price", $price, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":productId", $product, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":email", $email, PDO::PARAM_STR);
                    $transactionStmt->bindParam(":packageId", $pkgId, PDO::PARAM_INT);

                    if ($transactionStmt->execute()) {
                        echo json_encode(
                            array(
                                "error" => false,
                                "msg" => "Success",
                                "txnId" => $refCode,
                                "price" => (float)$price,
                                "phone" => $phone,
                                "package" => (int)$package,
                                "userId" => $userId
                            )
                        );
                    } else {

                        echo json_encode(
                            array(
                                "error" => true,
                                "msg" => $transactionStmt->errorInfo()
                            )
                        );
                        exit();
                    }
                } else {
                    return array(
                        "error" => true,
                        "msg" => "Invalid Package"
                    );
                }
            } else {
                return array(
                    "error" => true,
                    "msg" => $stmtPkg->errorInfo()
                );
            }
        } else {
            echo json_encode(
                array(
                    "error" => true,
                    "msg" => "User not found "
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
