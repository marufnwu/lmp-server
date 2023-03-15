<?php
    require_once 'connect.php';
    require "connection.php";
    include("getid3/getid3.php");

    session_start();

    $ConnectionObj = new Connection();
    $conn = $ConnectionObj->getInstance();


    $UserId = $_REQUEST["userid"];

    $_SESSION["userId"] = $UserId;


    $Token = $_REQUEST["token"];
    $current_version = $_REQUEST["current_version"];

    $ActivitedDate = round(microtime(true) * 1000);
    $queryRealtime = "UPDATE user_info_table SET last_activated='$ActivitedDate', used_version='$current_version' WHERE Id='$UserId'";
    mysqli_query($connn, $queryRealtime);




    $query_users = "SELECT * FROM user_info_table WHERE Id='$UserId'";
    $result_users = mysqli_query($connn, $query_users);
    $row_users = mysqli_fetch_assoc($result_users);

    $query_ads_info = "SELECT * FROM tbl_home_thumails WHERE ActiveStatus='true' ";
    $result_ads_info = mysqli_query($connn, $query_ads_info);
    $row_ads_info = mysqli_fetch_assoc($result_ads_info);




    $query_update = "SELECT * FROM tbl_app_update WHERE status='1' ";
    $result_update = mysqli_query($connn, $query_update);
    $row_update = mysqli_fetch_assoc($result_update);

    $query_banner = "SELECT * FROM tbl_banner_ad WHERE status='1' ";
    $result_banner = mysqli_query($connn, $query_banner);
    $row_banner = mysqli_fetch_assoc($result_banner);

    $query_marquee = "SELECT * FROM tbl_marquee_text";
    $result_marquee = mysqli_query($connn, $query_marquee);
    $row_marquee = mysqli_fetch_assoc($result_marquee);

    $query_vip = "SELECT * FROM paid_license WHERE user_id='$UserId' AND plan_type='1' AND status ='1' LIMIT 1 ";
    $result_vip = mysqli_query($connn, $query_vip);
    $row_vip = mysqli_fetch_assoc($result_vip);

    $query_pro = "SELECT * FROM paid_license WHERE user_id='$UserId' AND plan_type='2' AND status ='1' ";
    $result_pro = mysqli_query($connn, $query_pro);
    $row_pro = mysqli_fetch_assoc($result_pro);


    $currentDate = round(microtime(true) * 1000);

    $vip_check = mysqli_fetch_array(mysqli_query($connn, $query_vip));
    if (isset($vip_check)) {
        if ($row_vip['expire_date'] > $currentDate) {
            $VIP_LICENSE = "1";
        } else {
            $VIP_LICENSE = "2";
        }
    } else {
        $VIP_LICENSE = "0";
    }


    $pro_check = mysqli_fetch_array(mysqli_query($connn, $query_pro));
    if (isset($pro_check)) {
        if ($row_pro['expire_date'] > $currentDate) {
            $PRO_LICENSE = "1";
        } else {
            $PRO_LICENSE = "0";
        }
    } else {
        $PRO_LICENSE = "0";
    }

    $homeAudioUrl = "";

    $stmt = $conn->prepare("SELECT * FROM audio_file  WHERE name='HomeAudio' AND active=1");
    if ($stmt->execute()) {
        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            $homeAudioUrl = $data['audioUrl'];
        }
    }

    if ((int)$row_marquee['status'] == 1) {
        $marqueText = $row_marquee['text'];
    } else {
        $marqueText = "n/a";
    }

    






    $response = array();

    $diff = date_diff(date_create(date('d-m-Y')) ,date_create($row_users['RegistrationDate']));

    $accAge = $diff->format("%a");

    $phone = $row_users['Phone'];

    $allowedPhone = array(
        "9062686255", "7477563533", "8967638048", "8777789686", "6290982736", "9907606277", "7001515932", "9091144939", "8101906046", "1122334455", "1778473031", 
    );

    if(in_array($phone, $allowedPhone)){
        $accAge = 100;
    }



    $locationCheckEnable = true;

    if($locationCheckEnable){
        if(in_array($phone, $allowedPhone) ){
            $locationCheckEnable = false;
        }
    
    }

    if($PRO_LICENSE=="1"){
        $row_audio = null;
    }else{

        $row_audio = null;
//        try{
//            $query_audio = "SELECT * FROM audio_tutorial WHERE active=1 ORDER BY RAND() LIMIT 1  ";
//            $result_audio = mysqli_query($connn, $query_audio);
//            $row_audio = mysqli_fetch_assoc($result_audio);
//
//
//            if($row_audio){
//                $filename = $row_audio['audio'];
//                $getID3 = new getID3;
//                $file = $getID3->analyze($filename);
//                $playtime_seconds = $file['playtime_seconds'];
//                $row_audio["duration"]=number_format($playtime_seconds, 2);
//            }
//        }catch(Exception $e){
//            $row_audio = null;
//        }
    }

    $settingStmt =  $conn->prepare("SELECT ytChannel, fbPage, homeWhatsapp FROM settings LIMIT 1");

    if($settingStmt->execute()){
        $settings  = $settingStmt->fetch(PDO::FETCH_ASSOC);
    }

   

    //license list checking
     $activePlan= array();
    $licenseList = [];
   try{


    //    $stmtLicense = $conn->prepare("SELECT id FROM paid_license WHERE UNIX_TIMESTAMP()<expire_date AND user_id=$UserId AND status=1 AND plan_type=2 LIMIT 1");
    //    $stmtLicense->execute();

    //    if($stmtLicense->rowCount()>0){
    //        for ($i=1; $i<8 ; $i++) {
    //            $plan = array();
    //            $plan['plan_type'] = $i;

    //            array_push($licenseList, $plan);
    //        }
    //    }else{
    //        $stmtLicense = $conn->prepare("SELECT * FROM paid_license WHERE UNIX_TIMESTAMP()<expire_date AND user_id=$UserId AND status=1");
    //        $stmtLicense->execute();

    //        if($stmtLicense->rowCount()>0){
    //            $licenseList = $stmtLicense->fetchAll(PDO::FETCH_ASSOC);

    //        }
    //    }


    $stmtLicense = $conn->prepare("SELECT * FROM paid_license WHERE UNIX_TIMESTAMP()<expire_date AND user_id=$UserId AND status=1");
    $stmtLicense->execute();

    if ($stmtLicense->rowCount() > 0) {
        $activePlan = $stmtLicense->fetchAll(PDO::FETCH_ASSOC);
    }


   }catch(Exception $e){
       
   }

   foreach ($activePlan as $p){
        $plan['plan_type']=$p['plan_type'];
        $plan['active'] = true;
        array_push($licenseList, $plan);
    }
    

    // for ($i = 1; $i <= 10; $i++) {
    //     $plan = array();
    //     $plan['plan_type'] = $i;
    //     if(count($activePlan)>0){
    //         foreach ($activePlan as $p){

    //             if((string)$i == $p['plan_type']){
    //                 $plan['active'] = false;
    //                 break;
    //             }else{
    //                 $plan['active'] = false;
    //             }
    //         }
    //     }else{
    //         $plan['active'] = false;
    //     }

    //     array_push($licenseList, $plan);
    // }



    $conn = null;

    $ImageUrl="";
    $TargetUrl="";
    $ActiveStatus="";

    if(!empty($row_ads_info)){
        $ImageUrl =  $row_ads_info['ImageUrl'];
        $TargetUrl = $row_ads_info['TargetUrl'];
        $ActiveStatus = $row_ads_info['ActiveStatus'];
    }


    $banner_image = "";
    $target_link = "";


    if(abs($current_version - 2.05)< PHP_FLOAT_EPSILON){
        $banner_image = "https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEhRH7X0Wt_McP606uiAJtdpR67YI_o5WkgWvoXSV51jwIXsknHZr9hixRl0HOktjSBkO-D_n6dBbx1V2tQANjZX1hPv72vx2ZUI0Pjfi2et90nRW44mqqGgO1lKlAKxCnJcH9bKkPykBRW529ILtUFZz7h8sEAYUj5g9COQ-_aW4DK7I3z-NdOpMNp2fQ/s320/20230313_131938%20(1).gif";
        $target_link = "https://play.google.com/store/apps/details?id=com.skithub.resultdear";
    }else{
        if (!empty($row_banner)) {
            $banner_image = $row_banner['banner_image'];
            $target_link = $row_banner['target_link'];
        }
    }

    
   

    
    $response[] = array(
        'paid_license' => $VIP_LICENSE,
        'pro_license' => $PRO_LICENSE,
        'ac_position' => $row_users['ac_position'],
        'account_age' => $accAge,
        'token' => $row_users['Token'] ? $row_users['Token'] : "",
        'active_status' => $row_users['ActiveStatus'] ? $row_users['ActiveStatus'] : " ",
        'version_code' => $row_update['version_code'],
        'video_thumbail' => $row_update['video_thumbail'],
        'video_link' => $row_update['video_link'],
        'description' => $row_update['description'],
        'update_position' => $row_update['update_position'],
        'banner_image' => $banner_image,
        'target_link' => $target_link,
        'ImageUrl' =>  $ImageUrl,
        'TargetUrl' => $TargetUrl,
        'ActiveStatus' => $ActiveStatus,
        'text' => $marqueText,
        'homeAudioUrl' => $homeAudioUrl,
        'ytChannel' => $settings['ytChannel'],
        'homeWhatsapp' => (int) $settings['homeWhatsapp'],
        'fbPage' => $settings['fbPage'],
        'adSource' => "admob",
        'audioPopup' => $row_audio,
        'licenseList' => $licenseList,
        'activePlan' => $activePlan,
        'phone'=> "7811041344",
        'locationCheck' => array(
            "enable" => $locationCheckEnable,
            "image" => "https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEgTALWn3c3t--6jwwhFB01v44fH6Tf5ILIXwCBxFYOIj-OSYHq1HKk4ozCL0iaQdtYoTQDLcykGwjVzQyQDuvFVXAe-s4FSy41BVdYrgwD13cPKKHKp7c-J4crKoqnEibP-aAXmkAuVfYa0fPd7wXmZ9GfARIPRWpmDqJKyovAowirQvBuy9VSMj2vYuw/s1280/IMG-20220808-WA0028.jpg",
            "link" => "https://www.youtube.com/watch?v=CLUL2eO9o9w&ab_channel=WarfazeTV",
            "body" => "Our number depend on your location, so getting the number please allow location permission on click okay button",
            "voice" => null,
            "image2" => "https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEi0CJjsJGi0sJeJxJkjTxboVoefQKAj-w0ep2BtWKW1cKXMHQJek8j1bufIlZtcGLLE7ryunsAtgW98NUSWrP2Sqplhm_J3nLNsRBmwBojjee60fIhUpdBgG7NDdIazMSImsM5ibnJpaXrdUz6jCM3ff1Xwl8h-cK2pwMaRbm4R_Bf_REgIYkNwNsF_IA/s3464/PicsArt_08-08-02.22.43.jpg",
            "link2" => "https://www.youtube.com/watch?v=CLUL2eO9o9w&ab_channel=WarfazeTV",
            "gotoSettingBody" => "Lorem Ipsum", ),
    );

    echo json_encode($response);

