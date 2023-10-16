<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
//echo "start";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    $prcd=true;
    if(isset($_SESSION['user_id'])) {
        $user_id_param = $_SESSION['user_id'];
        $delete_result=false;
        if(isset($_POST['ev_code'])) {
            $ev_code_param = trim($_POST['ev_code']);
            require_once 'config.php';
            $sup_order_get="";
            $delete_sup=false;
            $sql="SELECT sup_order FROM yicrc_participants WHERE ev_code = ? AND user_id = ?";
            if($stmt=mysqli_prepare($link, $sql)) {
                if($_SESSION["user_type"]=="RM"||$_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="House RA"||$_SESSION["user_type"]=="Administrative RA") {
                    if(isset($_POST["user_id"])) {
                        $prcd=true;
                        $user_id_param=mysqli_real_escape_string($link, trim($_POST["user_id"]));
                        check_user($user_id_param);
                    } else {
                        $prcd=false;
                    }
                } else {
                    $user_id_param = $_SESSION['user_id'];
                }
                if(prcd) {
                    mysqli_stmt_bind_param($stmt, "ss", $ev_code_param, $user_id_param);
                    if(mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt)>0) {
                            $delete_sup=true;
                            mysqli_stmt_store_result($stmt);
                            mysqli_stmt_bind_result($stmt, $sup_order_db);
                            if(mysqli_stmt_fetch($stmt)) {
                                $sup_order_get = $sup_order_db;
                            }
                        } else {
                            $delete_sup=false;
                            //echo mysqli_stmt_num_rows($stmt);
                        }
                    } else {
                        echo "There was a server error. Please try again later (DB_EXEC_1)";
                    }   
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "There was a server error. Please try again later (DB_PREP_1)";
            }
            if($delete_sup && $sup_order_get!="") {
                $sql = "DELETE FROM yicrc_participants WHERE sup_order = ? LIMIT 1";
                if($stmt=mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "s", $sup_order_get);
                    if(mysqli_stmt_execute($stmt)) {
                        $delete_result=true;
                    } else {
                        $delete_result=false;
                    }
                    mysqli_stmt_close($stmt);
                } else {
                    $delete_result=false;
                }
            }   
        }
        if($prcd) {
            $sql="INSERT INTO yicrc_participants (ev_code, user_id, status, att_1, att_2) VALUES (?, ?, 0, 0, 0)";
            if(isset($_POST['ev_code'])) {
                $ev_code_param = trim($_POST['ev_code']);
                //$user_id_param = $_SESSION['user_id'];
                if($_SESSION["user_type"]=="RM"||$_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="House RA"||$_SESSION["user_type"]=="Administrative RA") {
                    if(isset($_POST["user_id"])) {
                        $user_id_param=mysqli_real_escape_string($link, trim($_POST["user_id"]));
                    }
                } else {
                    $user_id_param = $_SESSION['user_id'];
                }
                if($stmt2=mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt2, "ss", $ev_code_param, $user_id_param);
                    if(mysqli_stmt_execute($stmt2)) {
                        if($_SESSION["user_type"]=="RM"||$_SESSION["user_type"]=="Chief RA"||$_SESSION["user_type"]=="House RA"||$_SESSION["user_type"]=="Administrative RA") {
                            if(isset($_POST["user_id"]) && isset($user_name_db_admin)) {
                                echo $user_name_db_admin."(".$user_id_param.") was added to the participants list.";
                            } else {
                                echo "Participant added.";
                            }
                        } else {
                            if($delete_result) {
                                printf("Sign-up requested. Click 'OK' to check sign-up results. The original sign-up data has been deleted.");
                            } else {
                                printf("Sign-up requested. Click 'OK' to check sign-up results.");   
                            }
                        }
                    } else {
                        printf("Sign-up failed due to server error: %s", mysqli_stmt_error($stmt2));
                    }
                    mysqli_stmt_close($stmt2);
                } else {
                    printf("Sign-up failed due to server error: %s", mysqli_stmt_error($stmt2));
                }
            }   
        }
        mysqli_close($link);
    } else {
        printf("You have not logged in or have been logged out due to long unuse. Please log in again.");
    }
} else {
    printf("Sign-up failed due to server error. Please try again later.");
}

function check_user($user_id_param) {
    global $link, $prcd, $user_name_db_admin;
    $sql="SELECT user_eng_name_first, user_eng_name_last FROM yicrc_users WHERE user_id = ?";
    if($stmt2=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt2, "s", $user_id_param);
        if(mysqli_stmt_execute($stmt2)) {
            mysqli_stmt_store_result($stmt2);
            if(mysqli_stmt_num_rows($stmt2)==1) {
                mysqli_stmt_bind_result($stmt2, $first_db, $last_db);
                if(mysqli_stmt_fetch($stmt2)) {
                    $user_name_db_admin=$first_db." ".$last_db;
                }
            } else {
                echo "Failed to find user - there was no user with that user id in the system.";
                $prcd=false;
            }
        } else {
            echo "Failed to find user - user search failed. (2_".$user_id_param.")";
            $prcd=false;
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed to find user - user search failed. 1";
        $prcd=false;
    }
}
?>