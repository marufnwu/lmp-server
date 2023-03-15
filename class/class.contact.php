<?php
require_once("../connection.php");
require_once("class.helper.php");
require_once("class.user.php");
class contact{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    function getCustContactList(){

        if(!user::isPremium()){
            return helper::errorResponse();
        }

        $stmt = $this->db->prepare("SELECT * FROM contact_number WHERE active=1");
        if($stmt->execute()){

            $nums = array();
            while($num = $stmt->fetch(PDO::FETCH_ASSOC)){
                array_push($nums, $num);
            }

            return array(
                "error"=> false,
                "msg"=>"Success",
                "numberList"=>$nums
            );
        }else{
            return helper::errorResponse($stmt->errorInfo());
        }
    }

    function getWhatsappWithCustNumber(){
        if (!user::isPremium()) {
            return helper::errorResponse();
        }

        $custNums = $this->getCustContactList();
        if($custNums['error']){
            return helper::errorResponse($custNums['msg']);
        }

        return array(
            "error"=> false,
            "numberList"=>$custNums['numberList'],
            "whatsapp"=>""
        );
    }

    function getContactWithBanner($call){
        if (!user::isPremium()) {
            return helper::errorResponse();
        }

        $helper = new helper();
        $banner = $helper->getActivityBanner($call);

        $notice = "Call Time : 10:00 Am To 05:00 Pm\nফোন করার সময়ঃ 10:00 Am থেকে 05:00 Pm";

        return array(
            "error"=>false,
            "banner"=>$banner,
            "notice"=>$notice,
            "contacts"=>$this->getWhatsappWithCustNumber()
        );
    }

}
?>