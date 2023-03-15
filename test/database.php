<?php
$C['DB_HOST'] = "localhost";                        //localhost or your db host
$C['DB_USER'] = "sikderit_lmp";                //your db user
$C['DB_PASS'] = "SikderITHub00@@";                         //your db password
$C['DB_NAME'] = "sikderit_lmp";

foreach ($C as $name => $val) {
    define($name, $val);
}

$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
$dbo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . "; charset=utf8mb4", DB_USER, DB_PASS);
$dbo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Connected";
