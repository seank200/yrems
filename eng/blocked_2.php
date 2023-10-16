<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Your Account Was Blocked - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/eng/yicrc_english_3.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body style="background: white;">
<div class="header">
    <div class="header_content">
        <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" />
        <ul id="menu_list" class="header">
            <li onclick="window.location='https://yscec.yonsei.ac.kr';">Go to YSCEC</li>
            <li onclick="window.location='/yicrc/logout.php';">Log out</li>
        </ul>
    </div>
</div>
<div class="content_div">
    <div class="col-2 hide_mobile"></div>
    <div class="col-8">
        <h1 style="color: red;">You cannot log-in now.</h1>
        <table class="list">
            <tr><td>Why was my account blocked?</td></tr>    
            <tr>
                <td>
                    There might be several reasons as to why your account was blocked.<br /><br />
                    <b>1. No-show on RC Events.</b><br />
                    In compliance with the cancellation policy that all users agreed to when they signed up, we block log-in of student accounts when a student does not show up for an RC Event without cancelling. Please read the cancellation policy at the very bottom of this page. You can also find the cancellation policy in the House announcement Kakao room.<br />
                    To get your account access back, you must schedule a brief office hour meeting with the Residential Master, and have a meeting. After the meeting, the Residential Master will decide whether or not to grant access to your account again. Only the RM can do this for you. The RAs do not have the right privileges in the system to grant you access again.<br /><br />
                    To schedule an appointment with your Residential Master, Professor Denton, log in to <a href="https://yscec.yonsei.ac.kr"><u style="color: blue;">YSCEC</u></a>, click on UIC Career Development, scroll down to CDC Office Hours and click on the “Denton” link. <span style="color: #FF644E;"><b>You are encouraged to schedule group office hours.</b></span>
                    <br /><br />
                    <b>2. Changing critical account information</b><br />
                    If you change certain information from the "Account Settings" menu(account type, name, Your RA), your log-in is blocked until the RM and RAs confirm that the changes that you have made are legitimate, and accept you back into the system. If you made a mistake and changed this information unintentionally, please contact your RA and explain the situation. Also, please tell your RA 1)the information you have changed, and 2)the correct information.
                    <br /><br />
                    <b>3. Other Possible Reasons</b><br />
                    1) If you are not an RC Student anymore, your account will be automatically blocked.<br />
                    2) The RM or any RA can manually block your account access under certain circumstances (including, but not limited to situations where the information you have entered was found to be wrong).<br />
                    <br /><br /><br />
                    <b>Cancellation Policy</b><br />
                    1. To cancel your attendance to an event, you must contact your Residential Assistant at least 24 hours before the event.<br />
                    2. If you don’t show up to the event without cancelling, you will need to have a face-to-face meeting with your Residential Master, Professor Denton, before you can sign up for future events.<br />
                </td>
            </tr>
        </table>
    </div>
    <div class="col-2 hide_mobile"></div>
</div>
<script>
function rm_meeting() {
    //alert("Sorry, Professor Denton has not opened this link yet. Please contact Professor Denton directly.");
    window.location="https://calendly.com/professordenton";
}
function menu_click_blocked() {
    alert("You can access other menus after your account has been accepted again.");
}    
</script>
</body>
</html>