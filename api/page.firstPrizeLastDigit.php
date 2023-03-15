<?php
require_once("../class/class.page.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $userId =  isset($_POST['userId']) ? $_POST['userId'] : '';
    
    $page = new page();

    echo json_encode(
        $page->firstPrizeLastDigit($userId)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}