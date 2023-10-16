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
            $sql="SELECT user_id, status, sup_order, att_1, att_2 FROM yicrc_participants WHERE ev_code = ? AND status < 2 ORDER BY sup_order";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=$_POST["ev_code"];
                mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $part_user_id_db, $status_db, $sup_order_db, $att_1_db, $att_2_db);
                    $part_out=array();
                    while(mysqli_stmt_fetch($stmt)) {
                        get_partinfo($part_user_id_db, $status_db, $sup_order_db, $att_1_db, $att_2_db);
                    }
                    echo json_encode($part_out);
                } else {
                    echo "There has been a server error. Please try again later. (DB_EXEC_1)";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "There has been a server error. Please try again later. (DB_PREP_1)";
            }
        }
    } else {
        echo "Only RM/RAs can access this data. If you are a RM/RA, please close this window and log in again before retrying.";
    }
} else {
    echo "Error: Parameters not set";
}
mysqli_close($link);
function get_partinfo($id_param, $status_param, $sup_order_param, $att_1_param, $att_2_param) {
    global $link, $part_out;
    $sql="SELECT user_eng_name_first, user_eng_name_last, user_name, user_id, user_mobile FROM yicrc_users WHERE user_id = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $first_db, $last_db, $name_db, $user_id_db, $user_mobile_db);
            if(mysqli_stmt_num_rows($stmt)==1) {
                if(mysqli_stmt_fetch($stmt)) {
                    array_push($part_out, array("user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_name"=>$name_db, "user_id"=>$user_id_db, "user_mobile"=>$user_mobile_db, "user_status"=>$status_param, "sup_order"=>$sup_order_param, "att_1"=>$att_1_param, "att_2"=>$att_2_param));
                }
            } else {
                //array_push($part_out, array("user_eng_name_first"=>"ERROR", "user_eng_name_last"=>"ERROR", "user_id"=>"ERROR", "user_mobile"=>"ERROR", "user_status"=>"ERROR", "sup_order"=>"ERROR", "att_1"=>"ERROR", "att_2"=>"ERROR"));
                array_push($part_out, array("user_eng_name_first"=>"No data", "user_eng_name_last"=>"No data", "user_name"=>"No data", "user_id"=>$id_param, "user_mobile"=>"No data", "user_status"=>$status_param, "sup_order"=>$sup_order_param, "att_1"=>$att_1_param, "att_2"=>$att_2_param));
            }
        } else {
            echo "There has been a server error. Please try again later. (DB_EXEC_2)";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "There has been a server error. Please try again later. (DB_PREP_2)";
    }
}
?>