<?php
require_once("../class/class.license.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    
    $license = new license($userId);

    echo json_encode(
        $license->serialCheckLicense()
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}