<?php
require_once("../class/class.helper.php");
require_once("../class/class.lotttery.php");
require_once("../class/class.license.php");

if(!empty($_POST)){
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    $digit =  isset($_POST['digit']) ? $_POST['digit'] : '';
    $totalPages =  isset($_POST['totalPages']) ? $_POST['totalPages'] : 0;
    $pageNumber =  isset($_POST['pageNumber']) ? $_POST['pageNumber'] : 1;


    if(!helper::isAuth()){
        echo json_encode(helper::errorResponse("Request is not authenticate"));
        exit();
    }

    $license = new license($userId);

    if($license->checkLicense(4)['error']){
        echo json_encode(helper::errorResponse("You have no permission for access this page"));
        exit();
    }
    
    $lottery = new lottery();

    echo json_encode(
        $lottery->getFirstPrizelastDigitDetailsByDigit($digit, $pageNumber, $totalPages)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}