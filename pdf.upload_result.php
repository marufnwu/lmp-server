<?php
require_once("../class/class.helper.php");
require_once("../class/class.resultPdf.php");
if(!empty($_POST)){
    $slotId = isset($_POST['slotId']) ? $_POST['slotId'] : 1;
    
    $pdf =new resultpdf();

    echo json_encode(
        $pdf->downLoad($slotId)
    );
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}