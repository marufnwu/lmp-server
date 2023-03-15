<?php 
require_once '../connect.php';

//$type = $_REQUEST["UserId"];
$type = "0";

        $query = "SELECT * FROM paid_license WHERE id='$type'";
        $result = mysqli_query($conn, $query);
        $response = array();
        while( $row = mysqli_fetch_assoc($result) ){
           
            array_push($response, 
            array( 
                'id'=>$row['id'], 
                'license_number	'=>$row['license_number	'],
                'started_date'=>$row['started_date'],
                'expire_date'=>$row['expire_date']) 
            );
        }
        echo json_encode($response);   
   
mysqli_close($conn);

?>