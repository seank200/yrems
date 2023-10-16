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
	<title>Change Password - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td onclick="menu_click()">MENU</td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td onclick="logout_click()">LOG OUT</td> </tr>
</table>
<div class="container">
	<h1>Change Password</h1>	
    <h2 onclick="window.location='account.php';">< Return to Account Settings</h2>
    <form action="change_password.php" method="post" id="pc_form">
		<table class="details">
			<tr>
				<td colspan="2">Password</td>
			</tr>
			<tr>
                <td>ID</td>
                <td><?php if(isset($_SESSION['user_id'])) { echo $_SESSION['user_id']; } ?></td>
            </tr>
            <tr>
				<td>Current<br />Password</td>
				<td><input type="password" id="user_ori_pc" name="user_ori_pc" placeholder="Enter your original password" class="small"/></td>
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
	</form>
	<div class="status redd" id="err_msg_js" style="display: none;"></div>
<?php
$err_msg="";
require_once 'config.php';
$sql="SELECT user_pc FROM yicrc_users WHERE user_id = ?";
if($_SERVER["REQUEST_METHOD"] == "POST"&&isset($_POST['user_ori_pc'])&&isset($_POST['user_new_pc'])){
    if($stmt=mysqli_prepare($link, $sql)) {
        $user_id_param=$_SESSION['user_id'];
        mysqli_stmt_bind_param($stmt, "s", $user_id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)==1) {
                mysqli_stmt_bind_result($stmt, $pc_db);
                while(mysqli_stmt_fetch($stmt)) {
                    $ori_pc=$_POST['user_ori_pc'];
                    if(password_verify($ori_pc, $pc_db)) {
                        $new_pc=$_POST['user_new_pc'];
                        pc_change($new_pc);
                    } else {
                        $err_msg="The current password you have entered is wrong.";
                    }
                }
            } else {
                $err_msg="No user was found. Your login may have expired. Please log out, then log in again.";
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    }
}
function pc_change($new_p) {
    global $link;
    $sql2="UPDATE yicrc_users SET user_pc = ? WHERE user_id = ?";
    if($stmt2=mysqli_prepare($link, $sql2)) {
        $hash_new_pc=password_hash($_POST["user_new_pc"], PASSWORD_DEFAULT);
        $user_id_param=$_SESSION['user_id'];
        mysqli_stmt_bind_param($stmt2, "ss", $hash_new_pc, $user_id_param);
        if(mysqli_stmt_execute($stmt2)) {
            if(mysqli_stmt_affected_rows($stmt2)==1) {
                echo '<script type="text/javascript">alert("Password successfully changed.");window.location="/yicrc/eng/my_information.php";</script>';
                //header("Location: account.php");
            } else {
                echo '<script type="text/javascript">alert("An error occured. Password not changed.");</script>';
            }
        }
        mysqli_stmt_close($stmt2);
    }
}

if($err_msg!="") {
	echo '<div class="status redd">';
	echo $err_msg;
	echo '</div>';
}
?>
	<button class="blue" style="width: 100%; margin-bottom: 60px;" onclick="submit_frm()">Change Password</button>
    <div class="center" style="width: 100%;"><div class="textbutton" style="margin: 0 0 150px 0;" onclick="cancel_change()">Cancel</div></div>
	<br /><br />
</div>
<script type="text/javascript">
    function submit_frm() {
        var err_msg="";
        var err_div=document.getElementById("err_msg_js");
        err_div.style.display="none";
        var ori_pc=document.getElementById("user_ori_pc");
        var new_pc=document.getElementById("user_new_pc");
        var new_pc_cfrm=document.getElementById("user_new_pc_cfrm");
        if(new_pc.value==""||new_pc_cfrm.value=="") {
            err_msg="Please enter a new password.";
        }
        if(ori_pc.value=="") {
            err_msg="Please enter your current password.";
        }
        if(new_pc.value!=new_pc_cfrm.value) {
            err_msg="New passwords does not match.";
        }
        if(err_msg=="") {
            document.getElementById("pc_form").submit();
        } else {
            err_div.innerHTML=err_msg;
            err_div.style.display="";
        }
    }
    function cancel_change() {
        window.location="/yicrc/eng/my_information.php";
    }
</script>
</body>
</html>