<?php 
require_once 'connect.php';

$targetActivity = $_REQUEST["target_page"];

        $query_premium = "SELECT * FROM tbl_paid_for_contact WHERE target_page='$targetActivity' ";
        $result_premium = mysqli_query($connn, $query_premium);
        $row_premium = mysqli_fetch_assoc($result_premium); 
        
        $response = array();
            array_push($response, 
            array( 
                'phone_one'=>$row_premium['phone_one'], 
                'phone_two'=>$row_premium['phone_two'],
                'phone_three'=>$row_premium['phone_three'],
                'whats_app'=>$row_premium['whats_app'],
                'video_link'=>$row_premium['video_link'], 
                'video_thumbail'=>$row_premium['video_thumbail']) 
            );
        
        echo json_encode($response);   
   

?>