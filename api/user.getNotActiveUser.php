<?php
require_once("../class/class.user.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    $days = isset($_POST['days']) ? $_POST['days'] : 30;
    
    $user = new user();

    if(!helper::isAuth()){
        echo json_encode(
            helper::errorResponse("Request is not authenticated")
        );
    }else{
        echo json_encode(
            $user->getNotActiveUser($page, $days)
        );
    }
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}