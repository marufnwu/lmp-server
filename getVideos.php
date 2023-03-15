<?php
require_once("api.class.php");
if(!empty($_POST)){
    $page = isset($_POST['page']) ? $_POST['page'] : "";
    

    $url = "https://lmpvideo.sikderithub.com/getVideos.php";

    $param = array(
        "page"=>$page
    );
    $res = api::CallAPI("POST", $url, $param);

    echo $res;
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}