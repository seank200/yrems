<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Consent for Personal Information Collection and Use - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td onclick="menu_click()">MENU</td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td onclick="logout_click()">LOG OUT</td> </tr>
</table>
<div class="container">
<h1>Policies</h1>
<h2 onclick="window.history.back();">< Return back</h2>
<table class="list">
    <tr><td>Consent for Personal Information Collection and Use</td></tr>    
    <tr>
        <td>
        Before using our services, you must give consent to the collection and usage of your personal information necessary for RM/RAs to run and administer House-run RC activities, according to Article 15, 22, and 24 of [Personal Information Protection Law].<br /><br />
        1. Purpose of Personal Information<br />
        - This site collects and uses your personal information for the following purposes: Manage your(user) participation in RC programs (checking participants of events, checking their attendance, awarding points), To plan and improve House RC Events that are suited for students in the house, During RA student counselling<br /><br />

        2. Items of Collected Personal Information<br />
        - Name, Yonsei ID, date of birth, gender, major, mobile phone number, email address, house, dormitory room number, your RA, nationality, experiences living abroad, name and location of your highschool, native language, language(s) you speak, what you want to tell your RA<br /><br />

        3. Period of Personal Information Retention and Use<br />
        - All personal information acquired through this site will be retained until you leave this house, and will be immediately deleted completely and permanently from all storages.<br /><br />

        4. You may not consent to personal information collection and use (However, if you do not consent you will not be able to register your account for this site and will not be able to use the services that we provide.)<br /><br />

        5. People who can view your personal information<br />
        -All information except for what you wrote in "what you want to tell your RA" field): Residential Master, Chief Residential Assistant of house, Residential Assistants<br />
        - What you wrote on "what you want to tell your RA" field: Only your designated RA can see this. Residential Masters and other RAs cannot view this information when they log in with their account.<br /><br />

        6. All RM/RAs who view/use the information on article "5. People who can view your personal informatio" have signed and abides by the "RA Personal Information Protection and Security Pledge"<br />   
        </td>
    </tr>
</table>

</div>
</body>
</html>