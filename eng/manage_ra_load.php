<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();

if(isset($_SESSION["user_type"]) && isset($_SESSION["user_house"])) {
    if($_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
    	$sql="SELECT user_id, user_eng_name_first, user_eng_name_last, user_name, user_major, user_ra, user_room, user_accepted, user_type FROM yicrc_users WHERE user_house = ? AND (user_type = ? OR user_type = ?)";
    } else {
    	echo "Only Chief RA/RM can access this.";
    }
    require_once "config.php";
    if($stmt=mysqli_prepare($link, $sql)) {
        if(isset($_POST["user_house"])) {
            $house_param=$_POST["user_house"];
        } else {
            $house_param=$_SESSION["user_house"];   
        }
        if($_SESSION["user_type"]=="Chief RA") {
	    	$type_param_1="House RA";
            $type_param_2="Chief RA";
            mysqli_stmt_bind_param($stmt, "sss", $house_param, $type_param_1, $type_param_2);
	        //mysqli_stmt_bind_param($stmt, "ss", $house_param, $type_param_1);
	    } elseif($_SESSION["user_type"]=="RM") {
	    	$type_param_1="House RA";
	    	$type_param_2="Chief RA";
	        mysqli_stmt_bind_param($stmt, "sss", $house_param, $type_param_1, $type_param_2);
	    } else {
	    	echo "Only Chief RA/RM can access this.";
	    }
        
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)<1) {
                $house=array("Appenzeller", "Evergreen", "Wonchul", "Undrwood", "Yun, Dong-joo", "Muak", "Chiwon", "Baekyang", "Cheongsong", "Yongjae", "Avison", "Allen", "Other");
                echo "No registered RAs found in ".$house[$house_param]." house";
            } else {
                mysqli_stmt_bind_result($stmt, $id_db, $first_db, $last_db, $name_db, $major_db, $ra_db, $room_db, $accepted_db, $type_db);
                $students_out=array();
                while(mysqli_stmt_fetch($stmt)) {
                    array_push($students_out, array("user_id"=>$id_db, "user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_name"=>$name_db, "user_major"=>$major_db, "user_ra"=>$ra_db, "user_room"=>$room_db, "user_accepted"=>$accepted_db, "user_type"=>$type_db));
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