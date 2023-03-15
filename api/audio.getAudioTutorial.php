<?php
require_once("../class/class.audio.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $page = isset($_POST['page']) ? $_POST['page'] : 1;
    
    $audio = new audio();

    if(!helper::isAuth()){
        echo json_encode(
            helper::errorResponse("Request is not authenticated")
        );
    }else{
        echo json_encode(
            $audio->getAudios($page)
        );
    }
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}