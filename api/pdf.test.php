<?php
require_once("../class/class.helper.php");
require_once("../class/class.resultPdf.php");


$pdf =new resultpdf();

echo json_encode(
    $pdf->testDownLoad()
);