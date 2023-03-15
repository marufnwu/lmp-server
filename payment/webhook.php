<?php
require_once('confirmation.php');
//production
$MERCHANT_KEY = "U1VNW1790Q";
$SALT = "ZBAXB2I7UF";

define("PAYMENT_SUCCESS", "success");
define("PAYMENT_FAILED", "failure");
define("PAYMENT_USER_CANCEL", "userCancelled");

if (isset($_POST)) {
    //"salt|status|udf10|udf9|udf8|udf7|udf6|udf5|udf4|udf3|udf2|udf1|email|firstname|productinfo|amount|txnid|key"
    file_put_contents('mylog.log', PHP_EOL . json_encode($_POST), FILE_APPEND);

    $status = isset($_POST['status']) ? $_POST['status'] : " ";
    $key = isset($_POST['key']) ? $_POST['key'] : " ";
    $txnid = isset($_POST['txnid']) ? $_POST['txnid'] : " ";
    $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;
    $productinfo = isset($_POST['productinfo']) ? $_POST['productinfo'] : " ";
    $firstname = isset($_POST['firstname']) ? $_POST['firstname'] : " ";
    $email = isset($_POST['email']) ? $_POST['email'] : " ";
    $udf1 = isset($_POST['udf1']) ? $_POST['udf1'] : " ";
    $hash = isset($_POST['hash']) ? $_POST['hash'] : " ";


    $msg = isset($_POST['error']) ? $_POST['error'] : " ";

    $net_amount_debit = isset($_POST['net_amount_debit']) ? $_POST['net_amount_debit'] : 0;
    
    $easepayid = isset($_POST['easepayid']) ? $_POST['easepayid'] : "";


    $field = $SALT . "|" . $status . "||||||||||" . $udf1 . "|" . $email . "|" . $firstname . "|" . $productinfo . "|" . $amount . "|" . $txnid . "|" . $key;
    

    $calculatedHash = hash('sha512', $field);

    if($calculatedHash == $hash){
        file_put_contents('mylog.log', PHP_EOL . "Valid hash", FILE_APPEND);
        if($status == PAYMENT_SUCCESS){
            //payment success
            $payStaus = new confirmation();
            $result = $payStaus->payStatus($txnid, $net_amount_debit, 1);

            file_put_contents('mylog.log', PHP_EOL . json_encode($result), FILE_APPEND);
        }else if($status == PAYMENT_FAILED){
            //failed
            $payStaus = new confirmation();
            $result = $payStaus->payStatus($txnid, $net_amount_debit, 2);

            file_put_contents('mylog.log', PHP_EOL . json_encode($result), FILE_APPEND);

        }else if($status == PAYMENT_USER_CANCEL){
            //cancel by user
            $payStaus = new confirmation();
            $result = $payStaus->payStatus($txnid, $net_amount_debit, 2);

            file_put_contents('mylog.log',  PHP_EOL .json_encode($result), FILE_APPEND);
        }
    }else{
        file_put_contents('mylog.log', PHP_EOL ."Invalid hash", FILE_APPEND);
        $result = "Invalid hash";
    }

    
    echo json_encode($result);
}
