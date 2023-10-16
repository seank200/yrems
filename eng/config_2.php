<?php
if(session_status()==PHP_SESSION_DISABLED) {
    session_start();   
}
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

if(!(defined('DB_SERVER'))) { define('DB_SERVER', 'localhost'); }
if(!(defined('DB_USERNAME'))) { define('DB_USERNAME', 'yicrc'); }
if(!(defined('DB_PASSWORD'))) { define('DB_PASSWORD', 'dustpeogkrry#'); }
if(!(defined('DB_NAME'))) { define('DB_NAME', 'yicrc'); }

function verify_user() {
    if(isset($_SESSION["user_id"])) {
		$sql="SELECT user_type, user_accepted, user_house, user_ra FROM yicrc_users WHERE user_id = ?";
		$user_id_param = trim($_SESSION["user_id"]);
		if($link2 = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME)) {
			mysqli_set_charset($link2, "utf8mb4");
			if($stmt2 = mysqli_prepare($link2, $sql)) {
				mysqli_stmt_bind_param($stmt2, "s", $user_id_param);
				if(mysqli_stmt_execute($stmt2)) {
					mysqli_stmt_store_result($stmt2);
					if(mysqli_stmt_affected_rows($stmt2)==1) {
						mysqli_stmt_bind_result($stmt2, $user_type_2, $user_accepted_2, $user_house_2, $user_ra_2);
						if(mysqli_stmt_fetch($stmt2)) {
							$_SESSION["user_type"]=$user_type_2;
							$_SESSION["user_accepted"]=$user_accepted_2;
                            $_SESSION["user_house"]=$user_house_2;
                            $_SESSION["user_ra"]=$user_ra_2;
                            if($_SESSION["user_accepted"]=="0"||$user_accepted_2=="2") {
                                header("Location: blocked.php");
                            } elseif ($_SESSION["user_accepted"]==3) {
                                header("Location: logout.php");
                            } else {
                                
                            }
						} else {
							$err_msg="You are not logged in. Please log in again.(GUI_1)";
						}
					} else {
						$err_msg_2="An error occured (GUI_2)";
						header("Location: logout.php");
					}
				} else {
					$err_msg_2="An error occured (GUI_3)";
				}
				mysqli_stmt_close($stmt2);
			} else {
				$err_msg_2="An error occured (GUI_4)";
			}
			mysqli_close($link2);
		} else {
			$err_msg_2="Unable to connect to server. Please try again later.";
		}
	} else {
		$err_msg_2="You are not logged in. Please log in again. (GUI_5)";
		header("Location: logout.php");
	}
}
function check_admin() {
    if($_SESSION['user_type']!="House RA" && $_SESSION['user_type']!="Administrative RA" && $_SESSION['user_type']!="Chief RA" && $_SESSION['user_type']!="RM") {
        header("Location: index.php");
    }
}
function load_menu() {
    if($_SESSION['user_type']!="House RA" && $_SESSION['user_type']!="Administrative RA" && $_SESSION['user_type']!="Chief RA" && $_SESSION['user_type']!="RM") {
        echo '<script type="text/javascript">$("#menu_list").load("menu_items.html");</script>';
    } else {
        echo '<script type="text/javascript">$("#menu_list").load("menu_items_admin.html");</script>';
    }
}
?>