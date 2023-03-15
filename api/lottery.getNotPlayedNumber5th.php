<?php
require_once("../class/class.lotttery.php");

if(!empty($_POST)){
    $currPage = $_POST['currPage'] ?? 1;
    $itemCount = $_POST['itemCount'] ?? 0;


//    if(!helper::isAuth()){
//        echo json_encode(helper::errorResponse("Request is not authenticate"));
//        exit();
//    }

    $lottery = new lottery();

    echo json_encode(
        $lottery->getNotPlayedNumber3rd4th($currPage, $itemCount)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );

}