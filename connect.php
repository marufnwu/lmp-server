<?php 

define('host','localhost');
define('name', 'root');
define('pass', '');
define('dbase', 'lotterym_lottery_master_pro');

//
// define('host','localhost');
// define('name', 'lotterym_lottery_master_pro');
// define('pass', 'SDITHub00');
// define('dbase', 'lotterym_lottery_master_pro');

// define('host','lmp-aws-db.cmzqtlazrybz.ap-south-1.rds.amazonaws.com');
// define('name', 'admin');
// define('pass', 'LetMeDown');
// define('dbase', 'lmp');

$connn = mysqli_connect(host, name, pass, dbase) or die('Unable to connect '.mysqli_connect_error());
$connn->set_charset("utf8");

?>
