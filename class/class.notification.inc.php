<?php
class notification
{
    // "to" => "djha0tvLTWqDhOejvJhY-Q:APA91bHpEbjEcZzyHJmAMMMyusgX5sKTycwfNm9bge2w4Q0jHRfMl5dfwkUuetCgHEIjz7CDjxlq0ocBDeZ-zz1Pstf7VDU1BWAcOJoECiJtUxa64Oqmjf4c0s1h7Y9WTSwSp76gymaa",
    public $KEY = "AAAAQYhqASI:APA91bFnEIkJDeoNuk-H_3R7QOR65HCxGe2RWl6HEcsfdI7ok5BQYUvenn6reyWWR8a_lva9zrV0nlcXBlwOezT-I0z4fXkMAl8Jexdln4iL0CDXKrN9B7U8EVPUt_wY2AMf0JM8MciS";
    public $data;
    public $to;
    public function __construct($to, $data) {
        $this->data = $data;
        $this->data["time"]=date('Y-m-d h:i:s a');
        $this->data["timezone"]=date_default_timezone_get();
        
        $this->to = $to;
    }
    public function sendNotification()
    {


        // $body = array(
        //     "to" => $this->to,
        //     "data" =>$this->data
        // );

        $body = array(
            "condition" => " 'UN_SUBSCRIPTION_1' in topics && 'SUBSCRIPTION_2' in topics && 'UN_SUBSCRIPTION_7' in topics",
            "data" =>$this->data
        );


        

        $headers = array(
            'Authorization: key=' . $this->KEY,
            'Content-Type: application/json'
        );

        $url = "https://fcm.googleapis.com/fcm/send";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body, JSON_UNESCAPED_SLASHES));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        return json_encode($response);
    }

   

    public function activeUser(){
        $stmt = $this->db->prepare("SELECT * FROM `users` WHERE TIMESTAMPDIFF(MINUTE, last_active, NOW()) <= 5");
        
        if($stmt->execute()){
            $result = array();
            $row = $stmt->fetchAll();
                

            echo json_encode($result);
        }
    }
}
