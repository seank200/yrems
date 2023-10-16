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
        <form id="loginform" action="index.php" method="post">
            <input type="text" class="login" id="user_id" name="user_id" placeholder="Yonsei ID" />
            <input type="password" class="login" id="user_pw" name="user_pw" placeholder="Password" onkeydown="if(event.keyCode==13) { login(); }"/>
        </form>
        <button class="white" style="margin: 5px 0 15px 0;" onclick="login()">Login</button>
        <!--
        <div class="textbutton white" style="display: inline-block; margin-right: 15px; margin-bottom:10px;" onclick="b_click(this)">Forgot Password</div>
        -->
        <div class="textbutton white" style="display: inline-block; margin-right: 15px;" onclick="b_click(this)">Create Account</div>
        <?php
            require_once 'config.php';
            $err_msg="";
            if(isset($_SESSION["user_id"])) {
                require_once 'config_2.php';
                verify_user();
                header('Location: eng/rc_events_2.php');
            } else {
                if($_SERVER["REQUEST_METHOD"] == "POST") {
                    if(empty(trim($_POST["user_id"]))||empty(trim($_POST["user_pw"]))) {
                        $err_msg="Enter your ID and password";
                    } else {
                        if($err_msg=="") {
                            $sql="SELECT user_id ,user_pc, user_accepted, user_type FROM yicrc_users WHERE user_id = ?";
                            if($stmt = mysqli_prepare($link, $sql)) {
                                $user_id_param = trim($_POST["user_id"]);
                                mysqli_stmt_bind_param($stmt, "s", $user_id_param);
                                if(mysqli_stmt_execute($stmt)) {
                                    mysqli_stmt_store_result($stmt);
                                    if(mysqli_stmt_num_rows($stmt)==1) {
                                        $user_id = $hashed_password = "";
                                        mysqli_stmt_bind_result($stmt, $user_id, $hashed_password, $user_accepted, $user_type);
                                        if(mysqli_stmt_fetch($stmt)) {
                                            $user_pc=$_POST["user_pw"];
                                            if(password_verify($user_pc, $hashed_password)) {
                                                switch($user_accepted) {
                                                    case 0:
                                                        //$err_msg="Your account has been blocked.";
                                                        header('Location: eng/blocked_2.php');
                                                        break;
                                                    case 1:
                                                        $_SESSION["user_id"] = $user_id;
                                                        /*
                                                        if($user_type=="House RA" || $user_type=="Chief RA" || $user_type=="RM") {
                                                            header('Location: /yicrc/eng/rc_events_2.php');
                                                        } else {
                                                            header('Location: index.php');   
                                                        }
                                                        */
                                                        header('Location: eng/rc_events_2.php');
                                                        break;
                                                    case 2:
                                                        $err_msg="You have made critical changes to your account information and you can only log in after your RA confirms this change. Please contact your RA if you haven't done so already.";
                                                        break;
                                                    case 3:
                                                        $err_msg="Welcome, new user! You can log in after your account has been accepted by the RAs. This to stop any person who is not a Yonsei student accessing YREMS. Contact your RA to accept your account, and please wait for us while we verify your information and accept your account. Thank you for your consideration.";
                                                        break;
                                                }
                                            } else {
                                                $err_msg="The ID or password you entered was incorrect.";
                                            }
                                        } else {
                                            $err_msg="An error occured.(1)";
                                        }
                                    } elseif(mysqli_stmt_num_rows($stmt)==0) {
                                        $err_msg="The ID or password you entered was incorrect.";
                                    } else {
                                        $err_msg="An error occured.(2)";
                                    }
                                } else {
                                    $err_msg="An error occured.(3)";
                                }
                            } else {
                                $err_msg="An error occured.(4)";
                            }
                            mysqli_stmt_close($stmt);
                            mysqli_close($link);
                        }
                    }
                } 
            }
            if($err_msg!="") {
                echo '<div class="status redd" style="display: block; margin-top: 20px;">';
                echo $err_msg;
                echo '</div>';
            }
        ?>
        <p style="color: yellow; line-height: 1.4;">
            <br />
            <b>2018-1학기에 가입했더라도 새로 회원가입을 해야 로그인이 가능합니다.</b>
            <b>Please create your account again for 2018-2 semester.</b><br /><br />
            <span style="color: gray;">
                연세대학교 RC교육원의 학생 개인정보 보호 정책에 따라 한 학기가 종료된 후, YREMS 게정 정보를 포함한 학생 개인정보 데이터를 모두 지우는 과정에서 서버를 초기화하게 되었습니다. 불편을 드려 대단히 죄송합니다.
                In accordance to the Personal Information Policy of Yonsei Residential College, all personal data of students, including account data on YREMS has been reset. We sincerely apologize for the inconvenience.
                <br /><br />
                * Find password feature has been disabled during the start of semester to reduce server load.
            </span>
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
            //window.location="/yicrc/forgot.php";
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