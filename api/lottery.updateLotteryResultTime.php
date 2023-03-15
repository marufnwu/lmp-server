<?php
require_once("../class/class.lotttery.php");
require_once("../class/class.helper.php");
if(!empty($_POST)){
    $active = filter_var(isset($_POST['active']) ? $_POST['active'] : false, FILTER_VALIDATE_BOOLEAN);
    $name = isset($_POST['name']) ? $_POST['name'] : " ";
    $time = isset($_POST['time']) ? $_POST['time'] : " ";
    $id = isset($_POST['id']) ? $_POST['id'] : 0;

    $lottery = new lottery();

    echo json_encode(
        $lottery->updateResultTimeSlot($id, $name, $time, $active)
    );

}else{
    echo json_encode(
        helper::errorResponse("Get Request Not Permitted")
    );
    
}