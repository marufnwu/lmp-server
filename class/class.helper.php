<?php
require_once(__DIR__."/../connection.php");
require_once(__DIR__."/class.audio.php");
require_once(__DIR__."/class.auth.php");

class helper
{
    private $db;

    function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public static function successResponse($msg = "Success")
    {
        return array(
            "error" => false,
            "msg" => $msg
        );
    }

    public static function successResponseWithData($msg = "Success", $key="data", $value=null)
    {
        return array(
            "error" => false,
            "msg" => $msg,
            $key => $value
        );
    }

    public static function errorResponse($msg = "Something Went Wrong")
    {
        return array(
            "error" => true,
            "msg" => $msg
        );
    }

    public static function currentTimestamp(){
       return round(microtime(true) * 1000);
    }

    public function getActivityBanner($activity = " ")
    {



        $visible = true;
        $stmt = $this->db->prepare("SELECT * FROM activity_banner WHERE activity=(:activity) AND visible=(:visible)");
        $stmt->bindParam(":activity", $activity, PDO::PARAM_STR);
        $stmt->bindParam(":visible", $visible, PDO::PARAM_BOOL);

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                return array(
                    "error" => false,
                    "msg" => "Banner Found",
                    "id" => $row['id'],
                    "activity" => $row['activity'],
                    "imageUrl" => $row['imageUrl'],
                    "actionUrl" => $row['actionUrl'],
                    "actionType" => (int)$row['actionType'],
                    "visible" => (bool)$row['visible'],
                );
            } else {
                return array(
                    "error" => true,
                    "msg" => "Banner Not Found",

                );
            }
        } else {
            return array(
                "error" => true,
                "msg" => $stmt->errorInfo()
            );
        }
    }

    public static function isAuth()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            return false;
        } else {
            $valid_passwords = array("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

            return $validated;
        }

        return false;
    }

    public static function AuthMiddleware()
    {
        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            echo json_encode(helper::errorResponse("Request Not Authenticate"));
            die();
        } else {
            $valid_passwords = array("abdullah" => "563014");
            $valid_users = array_keys($valid_passwords);
            $user = $_SERVER['PHP_AUTH_USER'];
            $pass = $_SERVER['PHP_AUTH_PW'];
            $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

            return $validated;
        }

        echo json_encode(helper::errorResponse("Request Not Authenticate"));
        die();
    }

    public function getWhatsappNum($place)
    {

        if($place=='Pro' || $place == 'Vip'){
             return array(
                "error" => true,
                "msg" => "Number not found"
            );
        }

        $stmt = $this->db->prepare("SELECT * FROM whatsapp_button WHERE place='$place' AND active=1 LIMIT 1");

        if ($stmt->execute()) {

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                return array(
                    "error" => false,
                    "msg" => "Number found",
                    "number" => $row['number']
                );
            } else {
                return array(
                    "error" => true,
                    "msg" => "Number not found"
                );
            }
        } else {
            return array(
                "error" => true,
                "msg" => $stmt->errorInfo()
            );
        }
    }

    public function addDeviceData($userId, $phone,  $versionCode,  $versionName,  $androidVersion,  $device,  $deviceType,  $manufacturer,  $screenDensity,  $screenSize)
    {

        return array(
            "error" => false,
            "msg" => "Added"
        );

        // $stmt =  $this->db->prepare("INSERT IGNORE INTO device_metadata (userId,phone,versionCode,versionName,androidVersion,device,deviceType,manufacturer,screenDensity,screenSize) VALUES('$userId','$phone','$versionCode','$versionName','$androidVersion','$device','$deviceType','$manufacturer','$screenDensity','$screenSize')");

        // if ($stmt->execute()) {
        //     return array(
        //         "error" => false,
        //         "msg" => "Added"
        //     );
        // } else {
        //     return array(
        //         "error" => true,
        //         "msg" => $stmt->errorInfo()
        //     );
        // }
    }


    public function searchDeviceData($androidVersion,  $device,  $manufacturer,  $screenDensity,  $screenSize)
    {

        $stmt =  $this->db->prepare("SELECT  user_info_table.* FROM device_metadata INNER JOIN user_info_table ON user_info_table.Id = device_metadata.userId WHERE androidVersion LIKE '$androidVersion' AND device LIKE '$device' AND manufacturer LIKE '$manufacturer' AND screenDensity LIKE '$screenDensity' AND screenSize LIKE '$screenSize'");

        if ($stmt->execute()) {
            $searchResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return array(
                "error" => false,
                "msg" => "Success",
                "searchUsers" => $searchResult
            );
        } else {
            return array(
                "error" => true,
                "msg" => $stmt->errorInfo()
            );
        }
    }

    static function CallAPI($method, $url, $data = false)
    {
        $curl = curl_init();

        switch ($method) {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        // Optional Authentication:
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "username:password");

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);

        return $result;
    }

    public function getVideoType()
    {
        $stmt = $this->db->prepare("SELECT * FROM settings WHERE name='videoType' LIMIT 1");
        if ($stmt->execute()) {
            $row = $stmt->fetch();
            $type = 0;
            if ((int)$row['value'] == 1) {
                $type = 1;
            } else if ((int)$row['value'] == 1) {
                $type = 1;
            }

            return array(
                "error"=>false,
                "msg"=>"Success",
                "type"=>$type
            );
        }
    }

    public function getFbSharecontent(){
        $stmt = $this->db->prepare("SELECT * FROM fb_share LIMIT 1");

        if($stmt->execute()){

            $banner = $this->getActivityBanner("FbShareActivity");

            return array(
                "error"=>false,
                "msg"=>"Success",
                "fbShareContent"=>$stmt->fetch(PDO::FETCH_ASSOC),
                "banner"=>$banner
            );
        }else{
            return $this->errorResponse($stmt->errorInfo());
        }
    }

    public static function baseUrl()
    {
        $ds = DIRECTORY_SEPARATOR;
        $base_dir = realpath(dirname(__FILE__)  . $ds . '..') . $ds;

        return str_replace('\\', '/', $base_dir);
    }

    public static function rootUrl()
    {
        $root = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        return $root;
    }
   
}
