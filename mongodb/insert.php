<?php
require_once __DIR__."/../vendor/autoload.php";
try {
    $client = new MongoDB\Client('mongodb://localhost:27017');

    $name = $client->listDatabaseNames();
    $db = $client->test_database;


//insert
    $userTable  = $db->users;

    $insert = $userTable->insertOne(
        [
            'name'=>'Lulu',
            'phone'=>'018888'
        ]
    );

    echo "inserted id".$insert->getInsertedId()."\n";

//insert manyItem
    $insertMany = $userTable->insertMany([
        [
            'name'=>'Lulu '.rand(1,10),
            'phone'=>'018888'
        ],
        [
            'name'=>'Lulu'.rand(1,10),
            'phone'=>'018888'
        ]
    ]);

    echo "inserted id's".var_dump($insertMany->getInsertedIds())."\n";
}catch (Exception $e){
    echo $e->getMessage();
}

