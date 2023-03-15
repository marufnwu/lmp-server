<?php
require_once("../class/class.contact.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $page = isset($_POST['page']) ? $_POST['page'] : "";
    
    $contact = new contact();

    if(!helper::isAuth()){
        echo json_encode(
            helper::errorResponse("Request is not authenticated")
        );
    }else{
        echo json_encode(
            $contact->getCustContactList()
        );
    }
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}