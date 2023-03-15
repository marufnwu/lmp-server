<?php
require_once("../class/class.dialog.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $activity = isset($_POST['activity']) ? $_POST['activity'] : "";
    
    $dialog = new dialog();

    if(!helper::isAuth()){
        echo json_encode(
            helper::errorResponse("Request is not authenticated")
        );
    }else{
        echo json_encode(
            $dialog->getDialogInfo($activity)
        );
    }
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}