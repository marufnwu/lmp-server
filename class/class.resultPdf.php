<?php
include_once("../vendor/autoload.php");
include_once("lotteryNumber.php");
include_once("class.notification.inc.php");
class resultpdf
{
    private $db;
    public function __construct()
    {
        $conn = new Connection();
        $this->db = $conn->getInstance();
    }

    public function downLoad($slotId = 0)
    {

        if ($this->isResultPublish($this->today(), $slotId)) {
            return array(
                "error" => true,
                "msg" => $this->today() . " slot id " . $slotId . " alrady publish"
            );
        }


        $url = resultpdf::buildPdfUrl($slotId);


        if (!$url) {
            return array(
                "error" => true,
                "msg" => "Slot Id is Inavlid"
            );
        }

        set_time_limit(0);

        if (($data = @file_get_contents($url)) === false) {
            $error = error_get_last();

            return array(
                "error" => true,
                "msg" => $error['message']
            );

            exit();
        } else {

            $path = "../pdf/" . resultPdf::getPdfName($slotId);


            $save = file_put_contents($path, $data);

            if (!$save) {
                return array(
                    "error" => true,
                    "msg" => "Pdf file saving error"
                );
            }

            $config = new Smalot\PdfParser\Config();
            $config->setDataTmFontInfoHasToBeIncluded(true);

            $parser = new \Smalot\PdfParser\Parser([], $config);
            $pdf = $parser->parseFile($path);

            $text = $pdf->getText();

            $arr = explode('NAGALAND STATE LOTTERIES', $text);
            $text = trim($arr[1]);



            if (!empty($text)) {
                $parsed = $this->extractedResult($text, $slotId);

                if ($parsed['error']) {
                    return $parsed;
                }

                $resultData = $parsed["data"];

                if (count($resultData) < 131) {
                    return array(
                        "error" => true,
                        "msg" => "Result parsed data error " . count($resultData)
                    );
                }

                $upload = $this->upload($resultData);

                if (!$upload['error']) {
                    $this->sendNotification($slotId);
                }
                return $upload;
            } else {
                return array(
                    "error" => "true",
                    "msg" => "Extract text is empty"
                );
            }
        }
    }

    private function upload($resultData)
    {

        $serial = $resultData[0]->LotterySerialNumber;
        $winDate = $resultData[0]->WinDate;
        $winTime = $resultData[0]->WinTime;


        $stmt = $this->db->prepare("SELECT count(id) FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");

        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                $dlStmt = $this->db->prepare("DELETE FROM lottery_number_table WHERE WinDate='$winDate' AND WinTime='$winTime' ");

                $dlStmt->execute();
            }
        }



        foreach ($resultData as $single) {
            $lotteryNumber = $single->LotteryNumber;
            $lotterySerialNumber = $single->LotterySerialNumber;
            $winType = $single->WinType;
            $winDate = $single->WinDate;
            $winTime = $single->WinTime;
            $winDayName = $single->WinDayName;
            $slotId = $single->SlotId;
            $name = $single->Name;


            try {


                $insertStm = "INSERT INTO lottery_number_table (LotteryNumber, LotterySerialNumber,WinType,WinDate,WinTime,WinDayName, SlotId, Name)
            VALUES ('$lotteryNumber', '$lotterySerialNumber','$winType','$winDate','$winTime','$winDayName', '$slotId', '$name')";
                $insertResult = $this->db->exec($insertStm);
                $response["error"] = false;
                $response["msg"] = "Lottery uploaded successfully";
            } catch (Exception $e) {
                $response["error"] = false;
                $response["msg"] = "Sorry, Failed to upload post for " . $e->getMessage();
            }
        }


        return $response;
    }

    private  function extractedResult($string, $slotId)
    {
        $string = "STARTHERE " . $string;

        $lotteryName =  $this->getBetwen($string, "PM", "0");

        $winDate =  $this->getBetwen($string, "STARTHERE", "DEAR");
        $winTime =  $this->getBetwen($string, $lotteryName, "\n");

        $winDate  = resultpdf::formatDate($winDate);

        $serialFirstPrize =  $this->getBetwen($string, "1Crore/-", "(Including");
        $secondPrize =  $this->getBetwen($string, "2nd Prize ₹9000/-", "3rd Prize ₹450/-");
        $thirdPrize =  $this->getBetwen($string, "3rd Prize ₹450/-", "4th Prize ₹250/-");
        $fourthPrize =  $this->getBetwen($string, "4th Prize ₹250/-", "5th Prize ₹120/-");
        $fifthPrize =  $this->getBetwen($string, "5th Prize ₹120/-", "Please check the results");


        $serial = explode(" ", $serialFirstPrize)[0];
        $firstPrize = explode(" ", $serialFirstPrize)[1];

        $uploadableList = array();
        $secondPrizeList = explode(" ", $secondPrize);
        $thirdPrizeList = explode(" ", $thirdPrize);
        $fourthPrizeList = explode(" ", $fourthPrize);
        $fifthPrizeList = explode(" ", $fifthPrize);

        if (empty($winDate)) {
            return array(
                "error" => true,
                "msg" => "Lottery winDate parsing error"
            );
        }

        if (empty($winTime)) {
            return array(
                "error" => true,
                "msg" => "Lottery winTime parsing error"
            );
        }

        if (empty($lotteryName)) {
            return array(
                "error" => true,
                "msg" => "Lottery name parsing error"
            );
        }

        if (empty($serial)) {
            return array(
                "error" => true,
                "msg" => "Lottery serial parsing error"
            );
        }

        if (empty($firstPrize)) {
            return array(
                "error" => true,
                "msg" => "Lottery first prize parsing error"
            );
        }

        if (count($secondPrizeList) < 10) {
            return array(
                "error" => true,
                "msg" => "Lottery secondPrizeList parsing error"
            );
        }

        $dateName = date('l', strtotime($winDate));

        array_push($uploadableList, new LotteryNumber(null, $firstPrize, $serial, "1st", $winDate, $winTime, $dateName, $slotId, $lotteryName));


        foreach ($secondPrizeList as  $prize) {
            $prize = trim($prize);
            array_push($uploadableList, new LotteryNumber(null, $prize, $serial, "2nd", $winDate, $winTime, $dateName, $slotId, $lotteryName));
        }

        foreach ($thirdPrizeList as  $prize) {
            $prize = trim($prize);

            array_push($uploadableList, new LotteryNumber(null, $prize, $serial, "3rd", $winDate, $winTime, $dateName, $slotId, $lotteryName));
        }
        foreach ($fourthPrizeList as  $prize) {
            $prize = trim($prize);

            array_push($uploadableList, new LotteryNumber(null, $prize, $serial, "4th", $winDate, $winTime, $dateName, $slotId, $lotteryName));
        }
        foreach ($fifthPrizeList as  $prize) {
            $prize = trim($prize);

            array_push($uploadableList, new LotteryNumber(null, $prize, $serial, "5th", $winDate, $winTime, $dateName, $slotId, $lotteryName));
        }

        

        return array(
            "error" => false,
            "data" => $uploadableList
        );
    }

    private function getBetwen($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;

        return trim(substr($string, $ini, $len));
    }

    public function isResultPublish($WinDate, $slotId)
    {
        $stmt = $this->db->prepare("SELECT Id FROM lottery_number_table WHERE WinDate = '$WinDate' AND SlotId='$slotId' LIMIT 1");
        if ($stmt->execute()) {
            if ($stmt->rowCount() > 0) {
                return true;
            }

            return false;
        }

        return true;
    }


    public function sendNotification($slotId)
    {

        if ($slotId == 1) {
            $title = "Morning 01:00pm";
        } else if ($slotId == 2) {
            $title = "Day 06:00pm";
        } else if ($slotId == 3) {
            $title = "Night 08:00pm";
        }


        $desc = "💐------All The Best------💐";

        $to = "/topics/FreeUser";
        $data = array(
            "notiType" => 2,
            "tittle" => $title,
            "description" => $desc,
            "action" => 0,
            "actionActivity" => ".ui.splash.SplashActivity",
            "notiClearAble" => 1,
            "actionUrl" => "",
            "imgUrl" => "",
            "transactionRef" => "",
            "userEmail" => "",

        );


        $noti = new notification($to, $data);
        $noti->sendNotification();
    }

    static public function  getFullYear()
    {
        return $fullYear = date("Y");
    }


    static public function  getShortYear()
    {
        return $fullYear = date("y");
    }

    static public function  getMonth()
    {
        return $month = date("m");
    }

    static public function today()
    {
        return date("d-m-Y");
    }

    static public function  getDate()
    {
        return $day = date("d");
    }

    static  function buildPdfUrl($slotId)
    {
        $staticUrl = "https://lotterysambadresult.in/wp-content/uploads/";

        $pdfname = resultpdf::getPdfName($slotId);


        if (empty($pdfname)) {
            return false;
        }


        $url = $staticUrl . resultPdf::getFullYear() . "/" . resultPdf::getMonth() . "/" . $pdfname;
        return $url;
    }

    static  function getPdfName($slotId)
    {
        $name = "";

        switch ($slotId) {
            case 1:
                $name = "ML";
                break;
            case 2:
                $name = "DL";
                break;
            case 3:
                $name = "EL";
                break;
            default:
                $name = "";
        }

        if (empty($name)) {
            return false;
        }

        $pdfName = $name . resultPdf::getDate() . resultpdf::getMonth() . resultpdf::getShortYear() . ".pdf";

        return $pdfName;
    }

    static  function formatDate($date)
    {
        $date = str_replace('/', '-', $date);

        $newDate = date("d-m-Y", strtotime($date));

        return $newDate;
    }
}
