<?php
session_start();
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>YREMS - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta property="og:url" content="http://appenzeller.kr">
    <meta property="og:title" content="YREMS">  
    <meta property="og:type" content="website">
    <meta property="og:image" content="http://appenzeller.kr/yicrc/img/YREMS_thumbnail.png">
    <meta property="og:description" content="Yonsei RC Event Management System">
    <meta name="description" content="YREMS - Discover and participate in RC Events.">
    <link rel="shortcut icon" href="/favicon.ico" />
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background-color: #0A3879;
        }
        input.login {
            border: solid white;
            border-width: 0 0 1px 0;
            padding: 0 10px 5px 10px;
            color: white;
            background: none;
            margin-bottom: 25px;
            width: 100%;
            border-radius: 0;
            font-size: 130%;
        }
        input.login::placeholder {
            color: white;
        }
        input.login::-ms-placeholder {
            color: white;
        }
        input.login::-webkit-placeholder {
            color: white;
        }
        button.white {
            background: white;
            color: #0A3879;
        }
        div.white {
            border: solid white;
            border-width: 0 0 1px 0;
            color: white;
        }
    </style>
</head>
<body>
    <div class="col-4 hide_mobile"></div>
    <div class="col-4" style="padding: 30px;">
        <img src="/yicrc/img/yonsei_white.png" class="login_logo" style="width: 30%; display: block; margin: auto;"/>
        <div style="font-size: 220%; color: white; font-familY: nanumbarun_bold; margin: 20px 0 40px 0; width: 100%; text-align: center;">YREMS</div>
        
        <p style="color: gray; line-height: 1.4;">
            <br />
            YREMS 운영이 종료되었습니다. RA에게 문의해주세요.<br />
            YREMS is closed. Please contact your RA for any inquiries.
        </p>
    </div>
    <div class="col-4 hide_mobile"></div>
<script>
function b_click(x) {
	switch(x.innerText) {
		case "Create Account":
			window.location="/yicrc/register_2.php";
            //alert("System under maintenance. Please try later. 서버점검중입니다. 나중에 시도해주세요.")
			break;
        case "Forgot Password":
            //alert("Due to server load in the start of term, some emails might not get sent. If you do not receive the password reset email, please contact your RA.")
            window.location="/yicrc/forgot.php";
            break;
		default:
            alert("Not available.");
			break;
	}
}
function login() {
	if(document.getElementById("user_id").value!="" && document.getElementById("user_pw").value!="") {
        document.getElementById("loginform").submit();
	} else {
		alert("Please enter both your ID and password.");
	}
}
</script>
</body>
</html>