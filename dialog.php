<?php
define("LMP", "lmp");
define("VG", "vg");
define("FD", "fd");
define("BA", "ba");

class dialog{

    function call(){
        $app =  isset($_GET['app']) ? $_GET['app'] : '';

        $app = strtolower($app);

        switch ($app) {
            case LMP:
                return $this->generic();
                break;
            case VG:
                return $this->generic();
                break;
            default:
                return $this->undefined();
        }

    }


    private function generic(){

        $data = array(
            "isCancelable" => false,
            "thumbUrl"=>"https://sidkerithub.com/thumb.png",
            "thumbAction"=>"https://sikderithub.com",
            "title"=>"Here is demo title",
            "body"=>"Here is demo body. Btw How Are You Man?",
            "negativeButtonText"=>"Cancel",
            "positiveButtonText"=>"Okay",
            "showNegativeButton"=>true,
            "showPositiveButton"=>true,
            "negativeButtonAction"=>"https://negativebuttonClick.com",
            "positiveButtonAction"=>"https://positivebuttonClick.com",
        );

        $res = array("error"=>false, "msg"=>"Dialog Found", "data"=>$data);
        return $res;
    }

    private function undefined(){
        $res = array("error" => false, "msg" => "Dialog Found");
        return $res;
    }
}

$dialog = new dialog();

echo json_encode($dialog->call());


