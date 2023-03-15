<?php
require_once("../class/class.audio.php");
require_once("../class/class.helper.php");
require_once("../class/class.contact.php");

$contact = new contact();
$helper = new helper();
$contact = $contact->getCustContactList();

$audio = new audio();
$audioInfo  = $audio->getAudio("ButtonBuyRule");


$banner = $helper->getActivityBanner("ButtonBuyRule");


echo json_encode(
    array(
        "error"=>false,
        "msg"=>"Success",
        "banner"=>$banner,
        "audio"=>$audioInfo,
        "contacts"=>$contact
    )
);
