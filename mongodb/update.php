<?php
require_once __DIR__."/../vendor/autoload.php";
try {
    $client = new MongoDB\Client('mongodb://localhost:27017');

    $name = $client->listDatabaseNames();
    $db = $client->lmp;


//insert
    $table  = $db->lottery_numbers;



    $res = $table->aggregate(
        [
            ['$match'=>['Id'=>'2632']],
            ['$addFields'=>['SlotId'=>['$convert'=>['input'=>'$SlotId', 'to'=>'int']]]]
        ]
    );

    var_dump($res);


}catch (Exception $e){
    echo $e->getMessage();
}

