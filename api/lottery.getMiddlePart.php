<?php
require_once("../class/class.helper.php");
require_once("../class/class.lotttery.php");

if(!empty($_POST)){
    $middle =  isset($_POST['middle']) ? $_POST['middle'] : '';
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';


    if(!helper::isAuth()){
        echo json_encode(helper::errorResponse("Request is not authenticate"));
        exit();
    }
    
    $lottery = new lottery();

    echo json_encode(
        $lottery->getPartByMiddle($userId, $middle)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}