<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
//mysqli_report(MYSQLI_REPORT_ALL);
require_once "config.php";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if($_SESSION['user_type']=="House RA" || $_SESSION['user_type']=="Chief RA" || $_SESSION['user_type']=="RM" || $_SESSION['user_type']=="Administrative RA") {
        if(isset($_POST["ev_code"])) {
            $sql="SELECT user_id, status, sup_order FROM yicrc_participants WHERE ev_code = ? ORDER BY sup_order";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=$_POST["ev_code"];
                mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $part_user_id_db, $status_db, $sup_order_db);
                    $part_out=array();
                    while(mysqli_stmt_fetch($stmt)) {
                        get_partinfo($part_user_id_db, $status_db, $sup_order_db);
                    }
                    echo json_encode($part_out);
                } else {
                    echo "Error executing DB.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing DB.";
            }
        }
    } else {
        echo "Only RM/RAs can access this data. If you are a RM/RA, please close this window and log in again before retrying.";
    }
} else {
    echo "Error: Parameters not set";
}
mysqli_close($link);
function get_partinfo($id_param, $status_param, $sup_order_param) {
    global $link, $part_out;
    $sql="SELECT user_eng_name_first, user_eng_name_last, user_name, user_id, user_mobile, user_ra FROM yicrc_users WHERE user_id = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $first_db, $last_db, $name_db, $user_id_db, $user_mobile_db, $user_ra_db);
            if(mysqli_stmt_num_rows($stmt)==1) {
                if(mysqli_stmt_fetch($stmt)) {
                    array_push($part_out, array("user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_id"=>$user_id_db,  "user_name"=>$name_db, "user_mobile"=>$user_mobile_db, "user_ra"=>$user_ra_db, "user_status"=>$status_param, "sup_order"=>$sup_order_param));
                }
            } else {
                array_push($part_out, array("user_eng_name_first"=>"-", "user_eng_name_last"=>"-", "user_id"=>$id_param,  "user_name"=>"-", "user_mobile"=>"-", "user_ra"=>"-", "user_status"=>$status_param, "sup_order"=>$sup_order_param));
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing DB (func)";
    }
}
?>