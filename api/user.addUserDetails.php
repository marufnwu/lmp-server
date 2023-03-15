<?php
require_once("../class/class.user.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $name = isset($_POST['name']) ? $_POST['name'] : " ";
    $userId = isset($_POST['userId']) ? $_POST['userId'] : " ";
    $zila = isset($_POST['zila']) ? $_POST['zila'] : " ";
    $thana = isset($_POST['thana']) ? $_POST['thana'] : " ";
    $village = isset($_POST['village']) ? $_POST['village'] : " ";
    $postOffice = isset($_POST['postOffice']) ? $_POST['postOffice'] : " ";
    $pinCode = isset($_POST['pinCode']) ? $_POST['pinCode'] : " ";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : " ";
    $whatsApp = isset($_POST['whatsApp']) ? $_POST['whatsApp'] : " ";

    $user = new user();

    echo json_encode(
        $user->addUserDetails($userId, $name, $zila, $thana, $village, $postOffice, $pinCode, $phone, $whatsApp)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}