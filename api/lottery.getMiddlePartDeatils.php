<?php
require_once("../class/class.lotttery.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){

    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    $middle =  isset($_POST['middle']) ? $_POST['middle'] : '';
    $range =  isset($_POST['range']) ? $_POST['range'] : '';
    
    $lottery = new lottery();

    echo json_encode(
        $lottery->getMiddleWithPartLotteryNumber($middle, $range)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}