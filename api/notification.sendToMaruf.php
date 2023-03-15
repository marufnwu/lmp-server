<?php
include_once("../class/class.notification.inc.php");


//$to = "/topics/FreeUser";
$to = "cUtVHfqIQPO8BNbtk4WK2n:APA91bE8vyn4eg6QFzdTefLUItDf-Id44aqaXfq7LNNX1rmqEsaDyexhUO7SqEliYYl7jxUM5Qwb7EgyeoG0fFOPnFaHnOw9jBI8DLcEbkdySxQRyOP-9UZtwOCDKV6zLv9s6W0jPeJR";

$data = array(
    "notiType" => 2,
    "tittle" => "Greetings!!",
    "description" => "Thankyou all for being with us.",
    "action" => 2,
    "actionActivity" => ".ui.main.MainActivity",
    "notiClearAble" => 1,
    "actionUrl" => "https://lmpclass.com",
    "imgUrl" => "",
    "transactionRef" => "hhhh",
    "userEmail" => "jhjhjhjh@YGG",

);


$noti = new notification($to, $data);
echo json_decode($noti->sendNotification());


// $invoice = new invoice();
// $invoice->createInvoice("Uttam Sikdar", "maruf.paikgacha@gmail.com", 500, 500, 0, 5, "252s52s2s3", "Pay_adhasjcbsbdsb","2512252", "01778473031", 500, "07-13-2021");
// $invoice->send("maruf.paikgacha@gmail.com");
#T6Qg5GZAJHagSDf ->password

