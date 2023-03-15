<?php

use function PHPSTORM_META\type;

$url = "http://www.nagalandlotteries.com/todays.php?noon=pdf";

set_time_limit(0);
if (($data = @file_get_contents($url)) === false) {
            $error = error_get_last();

            echo $error;
            exit();
        } else {

            $path = "pdf/morn.pdf";

            //var_dump($data);
            //echo mime_content_type($data);
            //sizeof($data);

           $type =  mb_detect_encoding($data);

           if($type != 'UTF-8' ){
                echo "File not found";
           }


            $save = file_put_contents($path, $data);

            if (!$save) {
               
            }else{
                
            }

    }