<?php 
require_once 'connect.php';

$type = $_REQUEST["searchKey"];
        $query = "SELECT * FROM user_info_table WHERE Phone LIKE '$type%' LIMIT 50";
        $result = mysqli_query($connn, $query);
        $response = array();
        while( $row = mysqli_fetch_assoc($result) ){
            $response[] = array(
                'Id' => $row['Id'],
                'Token' => $row['Token'],
                'Phone' => $row['Phone'],
                'paid_license' => $row['paid_license'],
                'ac_position' => $row['ac_position'],
                'RegistrationDate' => $row['RegistrationDate'],
                'ActiveStatus' => $row['ActiveStatus'],
                'pro_license' => $row['pro_license'],
                '4digit_license' => $row['4digit_license'],
                'last_digit_license' => $row['last_digit_license'],
                'middle_part_license' => $row['middle_part_license'],
                'middle_plays_more_license' => $row['middle_plays_more_license'],
                'used_version' => $row['used_version'],
                'last_activated' => $row['last_activated'],
                'comment' => $row['comment'],
            );
        }
        echo json_encode($response);   
   


?>