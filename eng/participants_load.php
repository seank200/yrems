<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config.php";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_POST["ev_code"])) {
            $sql="SELECT user_id FROM yicrc_participants WHERE ev_code = ? AND status = 0 ORDER BY sup_order";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=$_POST["ev_code"];
                mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt)==0) {
                        echo 'No participants';
                    } else {
                        mysqli_stmt_bind_result($stmt, $part_user_id_db);
                        $part_out=array();
                        while(mysqli_stmt_fetch($stmt)) {
                            get_partinfo($part_user_id_db);
                        }
                        echo json_encode($part_out);
                    }
                } else {
                    echo "Error executing DB.";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "Error preparing DB.";
            }
        
    }
} else {
    echo "Error: Parameters not set";
}
mysqli_close($link);
function get_partinfo($id_param) {
    global $link, $part_out;
    $sql="SELECT user_eng_name_first, user_eng_name_last, user_major, user_id, user_mobile FROM yicrc_users WHERE user_id = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $first_db, $last_db, $major_db, $user_id_db, $user_mobile_db);
            if(mysqli_stmt_num_rows($stmt)==1) {
                if(mysqli_stmt_fetch($stmt)) {
                    if($_SESSION['user_type']=="House RA" || $_SESSION['user_type']=="Chief RA" || $_SESSION['user_type']=="RM" || $_SESSION['user_type']=="Administrative RA") {
                        array_push($part_out, array("user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_major"=>$major_db, "user_id"=>$user_id_db, "user_mobile"=>$user_mobile_db));
                    } else {
                        $first_out=substr($first_db, 0, 1);
                        for($x=0; $x<(strlen($first_db)-1);$x++) {
                            $first_out.="*";
                        }
                        array_push($part_out, array("user_eng_name_first"=>$first_out, "user_eng_name_last"=>$last_db, "user_major"=>$major_db));
                    }
                }
            } else {
                array_push($part_out, array("user_eng_name_first"=>"ERROR", "user_eng_name_last"=>"ERROR", "user_major"=>"ERROR"));
            }
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing DB (func)";
    }
}
?>