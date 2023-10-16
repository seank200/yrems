<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Reset Password - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script type="text/javascript">
        function rcp_success() {
            document.getElementById("change_btn").className="blue";
        }
        function rcp_fail() {
            document.getElementById("change_btn").className="disabled";
        }
    </script>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td onclick="menu_click()">MENU</td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td onclick="logout_click()">LOG OUT</td> </tr>
</table>
<div class="container">
	<h1>Reset Password</h1>	
    <h2 onclick="window.location='index.php';">< Return to Login</h2>
    <form action="reset_password.php" method="post" id="reset_form">
		<table class="details">
			<tr>
				<td colspan="2">Password</td>
			</tr>
			<tr>
				<td>New<br />Password</td>
				<td><input type="password" id="user_new_pc" name="user_new_pc" placeholder="Enter new password" class="small"/></td>
			</tr>
            <tr>
				<td>Confirm New<br />Password</td>
				<td><input type="password" id="user_new_pc_cfrm" name="user_new_pc_cfrm" placeholder="Enter new password again" class="small"/></td>
			</tr>
		</table>
        <input type="hidden" name="key" value="<?php echo trim($_GET["key"]); ?>" />
        <input type="hidden" name="user_id" value="<?php echo trim($_GET["user_id"]); ?>" />
        <div style="margin-bottom: 60px;" class="g-recaptcha" data-sitekey="6LdiuEwUAAAAAHPSKjg5v2Rq3wy_tP1VMwQYpVkq" data-callback="rcp_success" data-expired-callback="rcp_fail"></div>
	</form>
	<div class="status redd" id="err_msg_js" style="display: none;"></div>
<?php
$err_msg="";
error_reporting(E_ALL);
if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST['user_new_pc']) && isset($_POST['user_new_pc_cfrm']) && isset($_POST["user_id"]) && isset($_POST["key"])) {
    if(isset($_POST['g-recaptcha-response'])) {
        $captcha=$_POST['g-recaptcha-response'];
    }
    $skey="6LdiuEwUAAAAAP6XszWX783ugUrvMVazJG-ROv8w";
    $rawResponse=url_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$skey."&response=".$captcha);
    $parsedResponse=json_decode($rawResponse);
    if($parsedResponse->success) {
        require_once 'config.php';
        $sql="SELECT user_tok FROM yicrc_users WHERE user_id = ?";
        if($stmt=mysqli_prepare($link, $sql)) {
            $user_id_param=mysqli_real_escape_string($link, $_POST['user_id']);
            mysqli_stmt_bind_param($stmt, "s", $user_id_param);
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt)==1) {
                    mysqli_stmt_bind_result($stmt, $tk_db);
                    while(mysqli_stmt_fetch($stmt)) {
                        if(isset($_POST["key"])) {
                            if($_POST["key"]===$tk_db) {
                                pc_change($_POST["user_new_pc"]);
                            } else {
                                //echo '<script type="text/javascript">alert("Invalid password reset key - Unable to reset password.");</script>';
                                $err_msg="Invalid password reset key - Unable to reset password.";
                            }
                        } else {
                            //echo '<script type="text/javascript">alert("An error occured. Password not changed. 5");</script>';
                            $err_msg="An error occured. Password not changed. 5";
                        }
                    }
                } else {
                    //echo '<script type="text/javascript">alert("An error occured. Password not changed. 6");</script>';
                    $err_msg="An error occured. Password not changed. 6";
                }
            } else {
                //echo '<script type="text/javascript">alert("An error occured. Password not changed. 7");</script>';
                $err_msg="An error occured. Password not changed. 7";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($link);
        } else {
            //echo '<script type="text/javascript">alert("An error occured. Password not changed. 8");</script>';
            $err_msg="An error occured. Password not changed. 8";
        }
    } else {
        echo '<script type="text/javascript">alert("reCAPTCHA verification failed. Please check your network status and try again."); window.location="/yicrc/index.php";</script>';
    }   
}
function pc_change($new_p) {
    global $link;
    if($_POST["user_new_pc"]==$_POST["user_new_pc_cfrm"]) {
        $sql2="UPDATE yicrc_users SET user_pc = ? WHERE user_id = ?";
        if($stmt2=mysqli_prepare($link, $sql2)) {
            $hash_new_pc=password_hash($new_p, PASSWORD_DEFAULT);
            $user_id_param=mysqli_real_escape_string($link, $_POST['user_id']);
            mysqli_stmt_bind_param($stmt2, "ss", $hash_new_pc, $user_id_param);
            if(mysqli_stmt_execute($stmt2)) {
                if(mysqli_stmt_affected_rows($stmt2)==1) {
                    clr_tok($user_id_param);
                    //header("Location: account.php");
                } else {
                    //echo '<script type="text/javascript">alert("An error occured. Password not changed.");</script>';
                    $err_msg="An error occured. Password not changed. 1";
                }
            } else {
                //echo '<script type="text/javascript">alert("An error occured. Password not changed. 1");</script>';
                $err_msg="An error occured. Password not changed. 2";
            }
            mysqli_stmt_close($stmt2);
        } else {
            //echo '<script type="text/javascript">alert("An error occured. Password not changed. 1");</script>';
            $err_msg="An error occured. Password not changed. 3";
        }
    } else {
        echo '<script type="text/javascript">alert("Password and password confirm does not match.");</script>';
    }   
}
function clr_tok($user_id_param) {
    global $link;
    $sql3="UPDATE yicrc_users SET user_tok = 0 WHERE user_id = ?";
    if($stmt3=mysqli_prepare($link, $sql3)) {
        mysqli_stmt_bind_param($stmt3, "s", $user_id_param);
        if(mysqli_stmt_execute($stmt3)) {
            echo '<script type="text/javascript">alert("Password successfully changed. Please log in with the new credentials."); window.location="/yicrc/index.php";</script>';
        } else {
            //echo '<script type="text/javascript">alert("An error occured. Password not changed. 3");</script>';
            $err_msg="An error occured. Password not changed. 4";
        }
    } else {
        //echo '<script type="text/javascript">alert("An error occured. Password not changed. 4");</script>';
        $err_msg="An error occured. Password not changed. 5";
    }
}
function url_get_contents ($Url) {
    if (!function_exists('curl_init')){ 
        //die('CURL is not installed!');
        $err_msg="An error occured. Password not changed. 10";
    } else {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }
}

if($err_msg!="") {
	echo '<div class="status redd">';
	echo $err_msg;
	echo '</div>';
}
?>
	<button class="disabled" style="width: 100%; margin-bottom: 60px;" id="change_btn" onclick="submit_frm()">Change Password</button><br />
</div>
<script type="text/javascript">
    function submit_frm() {
        if(document.getElementById("change_btn").className!="disabled") {
            var err_msg="";
            var err_div=document.getElementById("err_msg_js");
            err_div.style.display="none";
            var new_pc=document.getElementById("user_new_pc");
            var new_pc_cfrm=document.getElementById("user_new_pc_cfrm");
            if(new_pc.value==""||new_pc_cfrm.value=="") {
                err_msg="Please enter a new password.";
            }
            if(new_pc.value!=new_pc_cfrm.value) {
                err_msg="Password and password confirm does not match.";
            }
            if(err_msg=="") {
                if(document.getElementById("change_btn").className=="blue") {
                    if(grecaptcha.getResponse()=="") {
                        alert("Please click on the reCAPTCHA checkbox again");
                    } else {
                        document.getElementById("reset_form").submit();
                    }
                } else {
                    alert("Please click on the reCAPTCHA checkbox.");
                }
            } else {
                err_div.innerHTML=err_msg;
                err_div.style.display="";
            }
        }
    }
</script>
</body>
</html>