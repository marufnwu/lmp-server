<?php
require_once('../connection.php');
require_once('class.api.inc.php');
require_once('class.plan.php');
class confirmation
{
    public function payStatus($txnId, $deductAmount, $hookStatus)
    {




        $conn = new Connection();
        $db = $conn->getInstance();


        $stmtTxn = $db->prepare("SELECT * FROM plan_transaction WHERE transactionRef=(:txnId)");
        $stmtTxn->bindParam(":txnId", $txnId);

        if ($stmtTxn->execute()) {
            if ($stmtTxn->rowCount() > 0) {
                $row = $stmtTxn->fetch();

                $userId = $row['userid'];
                $validity = (int)$row['validity'];
                $price = (int) $row['price'];
                $paid = (int)$row['paid'];
                $due = (int)$row['due'];
                $status = (int)$row['status'];
                $planId = (int)$row['planId'];
                $email = $row['email'];
                $productId = $row['productId'];



                if ($status == 0) {
                    //status didn't update before
                    $paid = (int)$paid + $deductAmount;
                    $due = (int)$due - $deductAmount;

                    if ($hookStatus == 1) {
                        //success

                        $stmtUpdate = $db->prepare("UPDATE plan_transaction SET status=(:status), paid=(:paid), due=(:due) WHERE transactionRef=(:ref)");

                        $stmtUpdate->bindParam(':status', $hookStatus, PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':paid', $paid, PDO::PARAM_INT);
                        $stmtUpdate->bindParam(':due', $due, PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':ref', $txnId, PDO::PARAM_STR);


                        if ($stmtUpdate->execute()) {
                            
                            $license = new plan();
                            $res = $license->setLisence($planId, $userId, $validity);


                            
                            $param = array(
                                "product" => $productId,
                                "email" => $email
                            );

                            $result = api::callAPI("POST", "https://lmpindia.com/generateEmailFile.php", $param);


                            return array(
                                "error" => false,
                                "msg" => "Payment updated as success ",
                                "lisence" => $res
                            );
                        } else {
                            return array(
                                "error" => true,
                                "msg" => $stmtUpdate->errorInfo()
                            );
                        }
                    } else {
                        //failed or user canceled
                        $stmtUpdate = $db->prepare("UPDATE plan_transaction SET status=(:status)WHERE transactionRef=(:ref)");

                        $stmtUpdate->bindParam(':status', $hookStatus, PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':ref', $txnId, PDO::PARAM_STR);
                        if ($stmtUpdate->execute()) {
                            return array(
                                "error" => false,
                                "msg" => "Payment updated as failed"
                            );
                        } else {
                            return array(
                                "error" => true,
                                "msg" => $stmtUpdate->errorInfo()
                            );
                        }
                    }
                } else {
                    //already updated
                    return array(
                        "error" => true,
                        "msg" => "Status already updated"
                    );
                }
            } else {
                return array(
                    "error" => true,
                    "msg" => "Txn Id Not found"
                );
            }
        } else {
            return array(
                "error" => true,
                "msg" => $stmtTxn->errorInfo()
            );
        }
    }
}
