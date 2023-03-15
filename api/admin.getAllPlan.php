<?php
require_once("../class/class.helper.php");
require_once("../class/class.lotttery.php");

if(!empty($_POST)){
    $userId = $_POST['userId'] ?? '';

    $lottery = new lottery();

    echo json_encode(
        $lottery->getPlanListByUserId($userId)
    );

}else{
    echo json_encode(
        helper::errorResponse("Request not permitted")
    );

}