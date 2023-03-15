<?php 
require_once 'connect.php';

$UserId = $_REQUEST["userId"];
$PageId = $_REQUEST["pageId"];

$query_paid_info = "SELECT * FROM tbl_paid_for_contact WHERE target_page='$PageId' AND status='1' ";
$result_paid_info = mysqli_query($connn, $query_paid_info);
$row_paid_info = mysqli_fetch_assoc($result_paid_info);   


$query_banner = "SELECT * FROM tbl_banner_ad WHERE status='1' ";
$result_banner = mysqli_query($connn, $query_banner);
$row_banner = mysqli_fetch_assoc($result_banner);  

      
        
        $response = array();
            array_push($response, 
            array( 
                'phone_one'=>$row_paid_info['phone_one'], 
                'phone_two'=>$row_paid_info['phone_two'],
                'phone_three'=>$row_paid_info['phone_three'],
                'whats_app'=>$row_paid_info['whats_app'],
                'video_link'=>$row_paid_info['video_link'], 
                'video_thumbail'=>$row_paid_info['video_thumbail'],
                'banner_image'=>$row_banner['banner_image'],
                'target_link'=>$row_banner['target_link']) 
            );
        
        echo json_encode($response);   
   

?>