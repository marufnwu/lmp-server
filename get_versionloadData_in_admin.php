<?php 
require_once 'connect.php';


        $query_premium = "SELECT * FROM tbl_app_update WHERE id='1' ";
        $result_premium = mysqli_query($connn, $query_premium);
        $row_premium = mysqli_fetch_assoc($result_premium); 
        
        $response = array();
            array_push($response, 
            array( 
                'version_code'=>$row_premium['version_code'], 
                'video_thumbail'=>$row_premium['video_thumbail'],
                'video_link'=>$row_premium['video_link'],
                'description'=>$row_premium['description'],
                'update_position'=>$row_premium['update_position'], 
                'status'=>$row_premium['status']) 
            );
        
        echo json_encode($response);   
   

?>