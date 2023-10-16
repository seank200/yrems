<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_POST["house"])) {
        $sql = "SELECT user_id, user_eng_name_first FROM yicrc_users WHERE (user_type = ? OR user_type = ?) AND user_house = ? AND user_accepted = 1";
        require_once "config.php";
        if($stmt=mysqli_prepare($link, $sql)) {
            $type_param_1="House RA";
            $type_param_2="Chief RA";
            $house_param=trim($_POST["house"]);
            mysqli_stmt_bind_param($stmt, "sss", $type_param_1, $type_param_2, $house_param);
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $id_db, $name_db);
                if(mysqli_stmt_num_rows($stmt)>=1) {
                    $ra_out=array();
                    while(mysqli_stmt_fetch($stmt)) {
                        array_push($ra_out, array("user_id"=>$id_db, "name"=>"RA ".$name_db));
                    }
                    echo json_encode($ra_out);
                } else {
                    echo "There are no RAs listed for this house.";
                }
            } else {
                echo "There has been a server error. Please try again later. (DB_EXEC_1)";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "There has been a server error. Please try again later. (DB_PREP_1)";
        }
    } else {
        if(isset($_POST["user_id"])) {
            $sql = "SELECT user_eng_name_first FROM yicrc_users WHERE user_id = ? AND user_accepted = 1";
            require_once "config.php";
            if($stmt=mysqli_prepare($link, $sql)) {
                $id_param = trim($_POST["user_id"]);
                mysqli_stmt_bind_param($stmt, "i", $id_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $name_db);
                    if(mysqli_stmt_num_rows($stmt)==1) {
                        $ra_out=array();
                        if(mysqli_stmt_fetch($stmt)) {
                            array_push($ra_out, array("name"=>$name_db));
                        }
                        echo json_encode($ra_out);
                    } else {
                        echo "RA not found.";
                    }  
                } else {
                    echo "There has been a server error. Please try again later. (DB_EXEC_2)";
                }
            } else {
                echo "There has been a server error. Please try again later. (DB_PREP_2)";
            }
        }
    }
    mysqli_close($link);
}
?>