<?php
require_once("../connection.php");
require_once("class.license.php");
require_once("class.lotttery.php");
require_once("class.helper.php");
require_once("class.contact.php");
class page{
    private $db;
    public function __construct(){
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    private function buildMaxMiddleUnsubscribeData(){
        $helper = new helper();
        $banner = $helper->getActivityBanner("MoreMiddlePlaysBefore");
        $cont  = new contact();
        return array(
            "banner"=>$banner,
            "contact"=>$cont->getCustContactList()
        );
    }

    private function buildFirstPrizeLastDigitUnsubscribeData(){
        $helper = new helper();
        $banner = $helper->getActivityBanner("FirstPrizeLastDigitBefore");
        $cont = new contact();

        return array(
            "banner"=>$banner,
            
        );
    }
    private function buildFreshNumber3rd4thUnsubscribeData(){
        $helper = new helper();
        $banner = $helper->getActivityBanner("FreshNumber3rd4th");
        $cont = new contact();

        return array(
            "banner"=>$banner,

        );
    }
    private function buildFreshNumber5thUnsubscribeData(){
        $helper = new helper();
        $banner = $helper->getActivityBanner("FreshNumber5th");
        $cont = new contact();

        return array(
            "banner"=>$banner,

        );
    }

    private function buildMiddlePartUnsubscribeData(){
        $helper = new helper();
        $banner = $helper->getActivityBanner("MiddlePartBefore");
        $cont  = new contact();
        return array(
            "banner"=>$banner,
            "contact"=>$cont->getCustContactList()
        );
    }

    public function buildTwoDigitSequence(){
        $numbers = array();
        $start = 00;

        for ($i=$start; $i<100 ; $i++) { 
            if($i<10){
                array_push($numbers, "0".$i);
            }else{
                array_push($numbers, $i);
            }
            
        }

       return $numbers;
    }


    public function firstPrizeLastDigit($userId){

        if(empty($userId)){
            return helper::errorResponse("User not logged");
        }

        if(!helper::isAuth()){
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $firstPrizeLastDigitLisence = $lisence->checkLicense(4);


        if(!$firstPrizeLastDigitLisence["error"]){
            //have subscription
            //retun data
            
            $lotetry = new lottery();
            

            return array(
                "error"=>false,
                "license"=>true,
                "unSubscribeData"=>null,
                "subscribeData"=>$lotetry->getFirstPrizeLastDigitNumber()
            );


        }else{
            //subscription not valid
            //retun unsbscribe data

            


            return array(
                "error"=>false,
                "license"=>false,
                "unSubscribeData"=>$this->buildFirstPrizeLastDigitUnsubscribeData(),
                "subscribeData"=>null
            );
        }

    }
    public function freshNumber5th($userId){

        if(empty($userId)){
            return helper::errorResponse("User not logged");
        }

        if(!helper::isAuth()){
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $freshNumberLicense = $lisence->checkLicense(9);


        if(!$freshNumberLicense["error"]){
            //have subscription
            //retun data

            $lotetry = new lottery();


            return array(
                "error"=>false,
                "license"=>true,
                "unSubscribeData"=>null,
                "subscribeData"=>$lotetry->getNotPlayedNumber5th()
            );


        }else{
            //subscription not valid
            //retun unsbscribe data


            return array(
                "error"=>false,
                "license"=>false,
                "unSubscribeData"=>$this->buildFreshNumber5thUnsubscribeData(),
                "subscribeData"=>null
            );
        }

    }
    public function freshNumber3rd4th($userId){

        if(empty($userId)){
            return helper::errorResponse("User not logged");
        }

        if(!helper::isAuth()){
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $freshNumberLicense = $lisence->checkLicense(8);


        if(!$freshNumberLicense["error"]){
            //have subscription
            //retun data

            $lotetry = new lottery();


            return array(
                "error"=>false,
                "license"=>true,
                "unSubscribeData"=>null,
                "subscribeData"=>$lotetry->getNotPlayedNumber3rd4th()
            );


        }else{
            //subscription not valid
            //retun unsbscribe data


            return array(
                "error"=>false,
                "license"=>false,
                "unSubscribeData"=>$this->buildFreshNumber3rd4thUnsubscribeData(),
                "subscribeData"=>null
            );
        }

    }

    public function middlePart($userId){

        if(empty($userId)){
            return helper::errorResponse("User not logged");
        }

        if(!helper::isAuth()){
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $lisenceRes = $lisence->checkLicense(5);


        if(!$lisenceRes["error"]){
            //have subscription
            //retun data
            
            $lotetry = new lottery();
            

            return array(
                "error"=>false,
                "license"=>true,
                "unSubscribeData"=>null,
                "subscribeData"=>$this->buildTwoDigitSequence()
            );


        }else{
            //subscription not valid
            //retun unsbscribe data

            


            return array(
                "error"=>false,
                "license"=>false,
                "unSubscribeData"=>$this->buildMiddlePartUnsubscribeData(),
                "subscribeData"=>null
            );
        }
    }




    public function maxMiddle($userId, $days){
        if(empty($userId)){
            return helper::errorResponse("User not logged");
        }

        if(!helper::isAuth()){
            return helper::errorResponse("Request Authentication Failed");
        }

        $lisence = new license($userId);

        $lisenceRes = $lisence->checkLicense(6);


        if(!$lisenceRes["error"]){
            //have subscription
            //retun data
            
            $lotetry = new lottery();
            

            return array(
                "error"=>false,
                "license"=>true,
                "unSubscribeData"=>null,
                "subscribeData"=>$lotetry->getMiddleMaxByDays($days)
            );


        }else{
            //subscription not valid
            //retun unsbscribe data

            


            return array(
                "error"=>false,
                "license"=>false,
                "unSubscribeData"=>$this->buildMaxMiddleUnsubscribeData(),
                "subscribeData"=>null
            );
        }
    }

    
    
}