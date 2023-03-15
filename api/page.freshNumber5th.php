<?php
require_once("../class/class.page.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $userId = $_POST['userId'] ?? '';
    
    $page = new page();

    echo json_encode(
        $page->freshNumber5th($userId)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}