<?php
include("getid3/getid3.php");

       $filename = 'uploads/audio_list/1646638077782.ogg';
       $getID3 = new getID3;
       $file = $getID3->analyze($filename);
       $playtime_seconds = $file['playtime_seconds'];
       echo $playtime_seconds;
?>