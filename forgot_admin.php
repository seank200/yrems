<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE"/>
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE"/>
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script type="text/javascript">
    function rcp_success() {
        document.getElementById("forgot_btn").className="blue";
    }
    function rcp_fail() {
        document.getElementById("forgot_btn").className="disabled";
    }
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td></td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td></td> </tr>
</table>
<div class="loader" id="loader" style="display: none;"></div>
<div class="container" id="content_div">
	<div id="step_2"  style="display: none;">
        <h1>Password Reset Ready</h1>
        <h2 style="margin-bottom: 60px;">Password reset link was generated. Click the button below to reset the password of this account.</h2>
        <button class="blue" style="width: 100%;" onclick="reset_go()">Reset Password</button>
    </div>
    <div id="step_1">
        <h1>Reset Password</h1>
        <h2 style="margin-bottom: 60px;">Please enter the folliwng information.</h2>
        <?php
            error_reporting(0);
            $err_msg="";
            $user_eng_name_first_db="";
            $user_eng_name_last_last_db="";
            if($_SERVER["REQUEST_METHOD"]=="POST") {
                if(isset($_POST['g-recaptcha-response'])) {
                    $captcha=$_POST['g-recaptcha-response'];
                }
                $skey="6LdiuEwUAAAAAP6XszWX783ugUrvMVazJG-ROv8w";
                $rawResponse=url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$skey."&response=".$captcha);
                $parsedResponse=json_decode($rawResponse);
                if($parsedResponse->success) {
                    if($_POST["user_id"]!="" && $_POST["user_eng_name_first"]!="" && $_POST["user_eng_name_last"]!="" && $_POST["user_email"]!="" && $_POST["user_bday"]!="") {
                        require_once 'config.php';
                        $sql="SELECT user_eng_name_first, user_eng_name_last, user_email FROM yicrc_users WHERE user_id = ? AND user_eng_name_first = ? AND user_eng_name_last = ? AND user_email = ? AND user_bday = ?";
                        if($stmt=mysqli_prepare($link, $sql)) {
                            $user_id_param=mysqli_real_escape_string($link, $_POST["user_id"]);
                            $user_eng_name_first_param=mysqli_real_escape_string($link, $_POST["user_eng_name_first"]);
                            $user_eng_name_last_param=mysqli_real_escape_string($link, $_POST["user_eng_name_last"]);
                            $user_email_param=mysqli_real_escape_string($link, $_POST["user_email"]);
                            $user_bday_param=mysqli_real_escape_string($link, $_POST["user_bday"]);
                            mysqli_stmt_bind_param($stmt, "sssss", $user_id_param, $user_eng_name_first_param, $user_eng_name_last_param, $user_email_param, $user_bday_param);
                            if(mysqli_stmt_execute($stmt)) {
                                mysqli_stmt_store_result($stmt);
                                if(mysqli_stmt_num_rows($stmt)==1) {
                                    mysqli_stmt_bind_result($stmt, $first_db, $last_db, $email_db);
                                    if(mysqli_stmt_fetch($stmt)) {
                                        $user_eng_name_first_db=$first_db;
                                        $user_eng_name_last_db=$last_db;
                                        cr_tok($user_id_param, $email_db);
                                    }
                                } else {
                                    $err_msg="User matching the information you have entered was not found.";
                                }
                            } else {
                                $err_msg="There was a server error. Please try again later (2)";
                            }
                            mysqli_stmt_close($stmt);
                        } else {
                            $err_msg="There was a server error. Please try again later (1)";
                        }
                        mysqli_close($link);    
                    }
                } else {
                    $err_msg="reCAPTCHA verification failed. Please make sure that the reCAPTCHA checkbox at the bottom is checked.";
                }  
                if($err_msg!="") {
                    echo '<div class="status redd">'.$err_msg.'</div>';
                } else {
                    echo '<script type="text/javascript">
                        document.getElementById("step_1").style.display="none";
                        document.getElementById("step_2").style.display="";
                    </script>';
                }
            }
            function cr_tok($user_id, $email_db) {
                global $link;
                $sql="UPDATE yicrc_users SET user_tok = ? WHERE user_id = ?";
                $new_tok=bin2hex(openssl_random_pseudo_bytes(40));
                if($stmt=mysqli_prepare($link, $sql)) {
                    $user_id_param=mysqli_real_escape_string($link, $_POST["user_id"]);
                    mysqli_stmt_bind_param($stmt,"ss",$new_tok,$user_id_param);
                    if(mysqli_stmt_execute($stmt)) {
                        echo '<script type="text/javascript">
                            function reset_go() {
                                window.location="/yicrc/reset_password.php?key='.$new_tok.'&user_id='.$user_id.'";
                            }
                        </script>';
                        //send_email($user_id, $email_db, $new_tok);
                    }
                }
            }
            function url_get_contents ($Url) {
                if (!function_exists('curl_init')){ 
                    die('CURL is not installed!');
                }
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $Url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $output = curl_exec($ch);
                curl_close($ch);
                return $output;
            }
        ?>
        <form id="forgot_frm" action="/yicrc/forgot_admin.php" method="post">
            <label for="user_id">Yonsei ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter user's Yonsei ID" style="margin-bottom: 30px;" />

            <label for="user_eng_name_first">First Name (English)</label>
            <input type="text" id="user_eng_name_first" name="user_eng_name_first" placeholder="Enter user's first name (in English)" style="margin-bottom: 30px;"/>

            <label for="user_eng_name_last">Last Name (English)</label>
            <input type="text" id="user_eng_name_first" name="user_eng_name_last" placeholder="Enter user's last name (in English)" style="margin-bottom: 30px;"/>

            <label for="user_email">Email</label>
            <input type="text" id="user_email" name="user_email" placeholder="Enter user's email" style="margin-bottom: 30px;"/>
            
            <label for="user_bday">Date of Birth</label>
            <input type="text" id="user_bday" name="user_bday" placeholder="Enter user's birthday (YYYYMMDD)" style="margin-bottom: 30px;"/>
            
            <div style="margin-bottom: 60px;" class="g-recaptcha" data-sitekey="6LdiuEwUAAAAAHPSKjg5v2Rq3wy_tP1VMwQYpVkq" data-callback="rcp_success" data-expired-callback="rcp_fail"></div>
        </form>

        <button class="disabled" style="width: 100%; margin-bottom: 150px;" id="forgot_btn" onclick="submit_frm();">Reset Password</button>
    </div>
</div>
<script type="text/javascript">
function submit_frm() {
    if(document.getElementById("forgot_btn").className=="disabled") {
        alert("Click on the reCAPTCHA checkbox first");
    } else {
        if(grecaptcha.getResponse()=="") {
            alert("Click on the reCAPTCHA checkbox again");
        } else {
            //alert("Submit");   
            if(document.getElementById("user_bday").value>19000101) {
                document.getElementById("loader").style.display="";
                document.getElementById("content_div").style.display="none";
                document.getElementById("forgot_frm").submit();   
            } else {
                alert("The date of birth must be entered in 8 digits (January 1st, 1998 -> 19980101).");
                document.getElementById("user_bday").value="";
            }
        }
    }
}    
</script>
</body>
</html>