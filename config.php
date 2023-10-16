<?php
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

if(!(defined('DB_SERVER'))) { define('DB_SERVER', 'localhost'); }
if(!(defined('DB_USERNAME'))) { define('DB_USERNAME', 'yicrc'); }
if(!(defined('DB_PASSWORD'))) { define('DB_PASSWORD', 'dustpeogkrry#'); }
if(!(defined('DB_NAME'))) { define('DB_NAME', 'yicrc'); }
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
if($link === false){
    //die("ERROR: Could not connect. " . mysqli_connect_error());
    //header("Location: logout.php");
    $err_msg_2="An error occured in the server. Please try again later. (1)";
}
header("Location: index.php");
mysqli_set_charset($link, "utf8mb4");
?>