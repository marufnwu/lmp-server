<?php
require_once("../class/class.license.php");
require_once("../class/class.audio.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    
    $license = new license($userId);
    $helper = new helper();
    $audio = new audio();

    $serialCheckLicense = $license->serialCheckLicense();

    $audioFile = $audio->getAudio("lotterySerialCheck");


    $isLicensed = false;

    if(!$serialCheckLicense['error']){
        $isLicensed = true;
    }

    $audioUrl = null;

    if(!$audioFile['error']){
        $audioUrl = $audioFile['audio']['audioUrl'];
    }

    $banner = $helper->getActivityBanner("lotterySerialCheckActivity");

    echo json_encode(
        array(
            "error"=>false,
            "msg"=>"Success",
            "banner"=>$banner,
            "audioUrl"=>$audioUrl,
            "isLicensed"=>$isLicensed
        )
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}