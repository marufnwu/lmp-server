<?php
require_once("../class/class.helper.php");
require_once("../class/class.resultPdf.php");
if(!empty($_GET)){
    $slotId = isset($_REQUEST['slotId']) ? $_REQUEST['slotId'] : 1;
    $key = isset($_REQUEST['key']) ? $_REQUEST['key'] : "";

    if($key!="maruf.is.rex"){
        echo json_encode(
            helper::errorResponse("Invalid request")
        );

        exit();
    }
    
    $pdf =new resultpdf();

    echo json_encode(
        $pdf->downLoad($slotId)
    );

    exit();
    
}else{
    echo json_encode(
        helper::errorResponse("Request Not Permitted")
    );
    
}