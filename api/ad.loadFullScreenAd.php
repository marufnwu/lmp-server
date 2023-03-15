<?php
require_once("../class/class.helper.php");
require_once("../class/class.ad.php");

if(!empty($_POST)){
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    $activity =  isset($_POST['activity']) ? $_POST['activity'] : '';

    $ad = new ad();

    echo json_encode(
        $ad->loadFullScreenAd($userId, $activity)
    );

}else{
    echo json_encode(
        helper::errorResponse("Ad Loading failed")
    );
    
}