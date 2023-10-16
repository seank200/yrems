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
	<title>Menu - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta property="og:url" content="http://appenzeller.kr">
    <meta property="og:title" content="YREMS">  
    <meta property="og:type" content="website">
    <meta property="og:image" content="http://appenzeller.kr/yicrc/img/YREMS_thumbnail.png">
    <meta property="og:description" content="Yonsei RC Event Management System">
    <meta name="description" content="YREMS - Discover and participate in RC Events.">
	<link rel="stylesheet" type="text/css" href="/yicrc/eng/yicrc_english_3.css">
    <link rel="shortcut icon" href="/favicon.ico" />
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background: white;">
<div class="header">
    <div class="header_content">
        <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" />
    </div>
</div>
<div class="content_div">
	<div class="col-4 hide_mobile"></div>
    <div class="col-4">
        <h1>Menu</h1>
        <h3>
        <?php
        require_once 'config.php';
        if(isset($_SESSION["user_id"])) {
            $sql="SELECT user_id, user_eng_name_first, user_eng_name_last FROM yicrc_users WHERE user_id = ?";
            if($stmt = mysqli_prepare($link, $sql)) {
                $user_id_param = $_SESSION["user_id"];
                mysqli_stmt_bind_param($stmt, "s", $user_id_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt)==1) {
                        mysqli_stmt_bind_result($stmt, $user_id, $user_eng_name_first, $user_eng_name_last);
                        if(mysqli_stmt_fetch($stmt)) {
                            //$_SESSION["user_type"]=$user_type;
                            $_SESSION["user_eng_name_first"]=$user_eng_name_first;
                            $_SESSION["user_eng_name_last"]=$user_eng_name_last;
                            echo $_SESSION["user_eng_name_first"];
                            echo ' ';
                            echo $_SESSION["user_eng_name_last"];
                            echo ' (';
                            echo $_SESSION["user_id"];
                            echo '), ';
                            echo $_SESSION["user_type"];
                        } else {
                            $err_msg="An error occured.(1)";
                        }
                    } else {
                        $err_msg="An error occured.(2)";
                        header("Location: logout.php");
                    }
                } else {
                    $err_msg="An error occured.(3)";
                }
            } else {
                $err_msg="An error occured.(4)";
            }
            mysqli_stmt_close($stmt);
            mysqli_close($link);
        } else {
            $err_msg="An error occured.(5)";
            header("Location: logout.php");
        }
        if($err_msg!="") {
            echo '<span>'.$err_msg.'</span>';
        }
        ?>
        </h3>
        <button class="blue_border" style="width: 100%;" onclick="b_click(this)">RC Events</button>
        <?php
            if(isset($_SESSION["user_type"])) {
                if($_SESSION["user_type"]=="RC Student" || $_SESSION["user_type"]=="Non-RC Student") {
                    echo '<button class="blue_border" style="width: 100%;" onclick="b_click(this)">My RC Activity</button>';
                }
            }
        ?>
        <button class="blue_border" style="width: 100%;" onclick="b_click(this)">Account Settings</button>
        <button class="blue_border" style="width: 100%;" onclick="b_click(this)">Log out</button>

        <?php
            if(isset($_SESSION["user_type"])) {
                if($_SESSION["user_type"]=="House RA"||$_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="RM") {
                    echo '
                        <div>
                            <h2><br />RM/RA Menu</h2>
                            <button class="blue" style="width: 100%;" onclick="admin_b_click(this)">Manage RC Students</button>
                            <button class="blue" style="width: 100%;" onclick="admin_b_click(this)">Manage RC Students (Desktop)</button>
                            <button class="blue_border" style="width: 100%;" onclick="admin_b_click(this)">YREMS 2.0 Beta</button>
                        </div>
                    ';
                }
                if($_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="RM") {
                    echo '<button class="blue" style="width: 100%;" onclick="admin_b_click(this)">Manage RAs</button>
                        <button class="blue" style="width: 100%;" onclick="admin_b_click(this)">Manage Policies</button>';
                }
                if($_SESSION["user_type"]=="House RA"||$_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="RM") {
                    echo '
                        <script type="text/javascript">
                            function admin_b_click(x) {
                                switch(x.innerText) {
                                    case "Manage RC Students":
                                        window.location="/yicrc/manage_students.php";
                                        break;
                                    case "Manage RC Students (Desktop)":
                                        window.location="/yicrc/manage_students_2.php";
                                        break;
                                    case "Manage RAs":
                                        window.location="/yicrc/manage_ra.php";
                                        break;
                                    case "YREMS 2.0 Beta":
                                        window.location="/yicrc/eng/rc_events_2.php";
                                        break;
                                    default:
                                        alert("This menu is not available at the moment. Please try again later.");
                                        break;
                                }
                            }
                        </script>
                    ';   
                }
            }
        ?>
    </div>
    <div class="col-4 hide_mobile"></div>
</div>
<script>
function b_click(x) {
	switch(x.innerText) {
		case "RC Events":
			window.location="/yicrc/rc_events.php";
			break;
        case "RC Events (2)":
            window.location="/yicrc/eng/rc_events_2.php";
            break;
		case "My RC Activity":
			//alert(x.innerText);
			window.location="/yicrc/myactivity.php";
			break;
		case "Account Settings":
			//alert(x.innerText);
			window.location="/yicrc/account.php";
			break;
		case "Log out":
			//alert(x.innerText);
			if(confirm("Do you wish to log out?")) {
                window.location="logout.php";
            }
			break;
		default:
			alert("This menu is not available at the moment. Please try again later.");
			break;
	}
}
</script>
</body>
</html>