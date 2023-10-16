<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();

if(isset($_SESSION["user_type"]) && isset($_SESSION["user_house"])) {
    $sql="SELECT user_id, user_eng_name_first, user_eng_name_last, user_name, user_major, user_ra, user_room, user_mobile, user_accepted FROM yicrc_users WHERE user_house = ? AND user_type = ?";
    if(isset($_POST["ra_id"])) {
        $sql.=" AND user_ra = ?";
    }
    $sql.=" ORDER BY user_accepted, user_ra, LENGTH(user_room), user_room";
    require_once "config.php";
    if($stmt=mysqli_prepare($link, $sql)) {
        $house_param=$_SESSION["user_house"];
        $type_param_1="RC Student";
        if(isset($_POST["ra_id"])) {
            if($_POST["ra_id"]==$_SESSION["user_id"]) {
                $ra_param=$_SESSION["user_id"];
            } else {
                if($_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
                    $ra_param=mysqli_real_escape_string($link, trim($_POST["user_type"]));
                } else {
                    $ra_param=$_SESSION["user_id"];
                }
            }
            mysqli_stmt_bind_param($stmt, "sss", $house_param, $type_param_1, $ra_param);
        } else {
            mysqli_stmt_bind_param($stmt, "ss", $house_param, $type_param_1);
        }
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)<1) {
                echo "No students found in this house";
            } else {
                mysqli_stmt_bind_result($stmt, $id_db, $first_db, $last_db, $name_db, $major_db, $ra_db, $room_db, $mobile_db, $accepted_db);
                $students_out=array();
                while(mysqli_stmt_fetch($stmt)) {
                    array_push($students_out, array("user_id"=>$id_db, "user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_name"=>$name_db, "user_major"=>$major_db, "user_ra"=>$ra_db, "user_room"=>$room_db, "user_mobile"=>$mobile_db, "user_accepted"=>$accepted_db));
                }
                echo json_encode($students_out);
            }
        } else {
            echo "There has been a server error. Please try again later. (DB_EXEC)";
        }
    } else {
        echo "There has been a server error. Please try again later. (DB_PREP)";
    }
}
?>