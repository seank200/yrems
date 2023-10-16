<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();

if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_SESSION['user_id'])) {
        require_once 'config.php';
        $ev_info=array();
        $sql2="";
        if($_SESSION['user_type']=="RC Student"||$_SESSION['user_type']=="Non-RC Student") {
            $sql="SELECT ev_cancel_optn, sup_time_st, sup_time_end FROM yicrc_events WHERE ev_code = ?";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=$_POST['ev_code'];
                mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $ev_cancel_optn_db, $sup_time_st_db, $sup_time_end_db);
                    if(mysqli_stmt_fetch($stmt)) {
                        $ev_info['ev_cancel_optn']=$ev_cancel_optn_db;
                        $ev_info['sup_time_st']=$sup_time_st_db;
                        $ev_info['sup_time_end']=$sup_time_end_db;
                    }
                } else {
                    echo "Error: There was a server error. Please try again later. (CW_1)";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error: There was a server error. Please try again later. (CW_2)";
            }
            $within_date=false;
            $sup_st_date=new DateTime($ev_info['sup_time_st']);
            $today=new DateTime();
            $sup_end_date=new DateTime($ev_info['sup_time_end']);
            if($today>=$sup_st_date && $sup_end_date>=$today) {
                $within_date=true;
            }
            if($ev_info['ev_cancel_optn']!="") {
                switch($ev_info['ev_cancel_optn']) {
                    case "1":
                        echo "Student cancellation is not allowed for this event. Please check again. ";
                        break;
                    case "2":
                        if(isset($_POST['action_type_student'])) {
                            if($_POST['action_type_student']=="do_not_cancel") {
                                $sql2="UPDATE yicrc_participants SET status = 0 WHERE sup_order = ?";
                            } else {
                                echo "Error: There was a server error. Please try again later. (CW_3)";
                            }
                        } else {
                            if($within_date) {
                                $sql2="UPDATE yicrc_participants SET status = 2 WHERE sup_order = ?";
                            } else {
                                $sql2="UPDATE yicrc_participants SET status = 1 WHERE sup_order = ?";
                            }
                        }
                        break;
                    case "3":
                        if(isset($_POST['action_type_student'])) {
                            if($_POST['action_type_student']=="do_not_cancel") {
                                $sql2="UPDATE yicrc_participants SET status = 0 WHERE sup_order = ?";
                            } else {
                                echo "Error: There was a server error. Please try again later. (CW_4)";
                            }
                        } else {
                            $sql2="UPDATE yicrc_participants SET status = 1 WHERE sup_order = ?";   
                        }
                        break;
                }
            } else {
                echo "Error: ev_info null";
            }
        } elseif($_SESSION['user_type']=="House RA" || $_SESSION['user_type']=="Chief RA" || $_SESSION['user_type']=="RM") {
            if(isset($_POST['action_type'])) {
                switch($_POST['action_type']) {
                    case "accept":
                        $sql2="UPDATE yicrc_participants SET status = 2 WHERE sup_order = ?";
                        break;
                    case "wait_cancel":
                        $sql2="UPDATE yicrc_participants SET status = 1 WHERE sup_order = ?";
                        break;
                    case "decline":
                        $sql2="UPDATE yicrc_participants SET status = 0 WHERE sup_order = ?";
                        break;
                    case "delete":
                        if($_SESSION['user_type']=="House RA" || $_SESSION['user_type']=="Chief RA" || $_SESSION['user_type']=="RM") {
                            $sql2="DELETE FROM yicrc_participants WHERE sup_order = ? LIMIT 1";
                        } else {
                            echo "Error: Only RM or Chief RA can do this.";
                        }
                        break;
                    default:
                        $sql2="";
                        break;
                }
            } else {
                $sql2="";
                echo "Error: a_t not set. ";
            }
        } else {
            echo "Error: You have not logged in or have been logged out due to long unuse. Please log in again. (TYPE_ERROR) ";
        }
        
        if($sql2!="") {
            if(isset($_POST['sup_order'])) {
                if($stmt2=mysqli_prepare($link, $sql2)) {
                    $sup_order_param=trim($_POST['sup_order']);
                    mysqli_stmt_bind_param($stmt2, "s", $sup_order_param);
                    if(mysqli_stmt_execute($stmt2)) {
                        mysqli_stmt_store_result($stmt2);
                        if(mysqli_stmt_affected_rows($stmt2)==1) {
                            echo "Processing complete.";
                        } else {
                            echo "Processing complete, but ".mysqli_stmt_affected_rows($stmt2)." record(s) were affected.";
                        }
                    } else {
                        echo "Error: There was a server error. Please try again later. (CW_5)";
                    }
                    mysqli_stmt_close($stmt2);
                } else {
                    echo "Error: There was a server error. Please try again later. (CW_6)"; //prep error
                }
            } else {
                echo "Error: There was a server error. Please try again later. (CW_7)"; //target not specified
            }
        } else {
            echo "Error: There was a server error. Please try again later. (CW_8)"; //query not set
        }
        mysqli_close($link);
    } else {
        echo "Error: You have not logged in or have been logged out due to long unuse. Please log in again. (CW_9) ";
    }
} else {
    echo "Error: Cancellation request failed due to server error. Please try again later. (CW_10)"; //Method not post
}
?>