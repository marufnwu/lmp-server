<?php
require_once("../class/class.lotttery.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $isCheckActive = filter_var(isset($_POST['checkActive']) ? $_POST['checkActive'] : false, FILTER_VALIDATE_BOOLEAN);
    
    $lottery = new lottery();

    echo json_encode(
        $lottery->getLotteryResultTime($isCheckActive)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}