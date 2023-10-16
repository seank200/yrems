<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
?>

<?php
	echo '로그아웃 중입니다. 잠시만 기다려주세요. Logging out. Please Wait..';
	session_unset();
	session_destroy();
	
	header('Location: index.php');
?>