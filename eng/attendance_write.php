<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
if($_SERVER["REQUEST_METHOD"]=="POST") {
    $err_msg="";
    $att_result="";
    $att_1_db_out="";
    $att_2_db_out="";
    $att_text_full=array("Not checked", "Present", "Late", "Absent");
    $name_result="";
    if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
        if(isset($_POST["user_id"]) && isset($_POST["ev_code"])) {
            require_once "config.php";
            $sql="UPDATE yicrc_participants SET ";
            $prcd=true;
            get_att($_POST["user_id"]);
            $old_att_db = $att_result;
            if($err_msg==" No attendance data found.") {
                $prcd=false;
                echo "[ERROR] Participant with the ID '".$_POST["user_id"]."' was not found.";
            }
            if(isset($_POST["att_1"]) && $_POST["att_1"]!="") {
                if(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                    if($att_2_db_out==$_POST["att_2"]) {
                        $prcd=false;
                        get_name($_POST["user_id"]);
                        echo "[NOTICE] ".$name_result."'s attendance is already set as '".$att_result."'";
                    } else {
                        $sql.="att_1, att_2 = ?";   
                    }
                } else {
                    if($att_1_db_out==$_POST["att_1"]) {
                        $prcd=false;
                        get_name($_POST["user_id"]);
                        echo "[NOTICE] ".$name_result."'s 1st attendance is already set as '".$att_text_full[$att_1_db_out]."'";
                    } else {
                        $sql.="att_1 = ?";
                    }
                }
            } elseif(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                if($att_2_db_out==$_POST["att_2"]) {
                    $prcd=false;
                    get_name($_POST["user_id"]);
                    echo "[NOTICE] ".$name_result."'s 2nd attendance is already set as '".$att_text_full[$att_2_db_out]."'";
                } else {
                    $sql.="att_2 = ?";
                }
            } else {
                $err_msg.=" Attendance parameter(s) not set.";
            }
            $sql.=" WHERE user_id = ? AND ev_code = ? AND status < 2";
            if($prcd) {
                if($err_msg=="") {
                    if($stmt=mysqli_prepare($link, $sql)) {
                        $att_1_param = $_POST["att_1"];
                        $att_2_param = $_POST["att_2"];
                        $user_id_param = $_POST["user_id"];
                        $ev_code_param = $_POST["ev_code"];
                        if(isset($_POST["att_1"]) && $_POST["att_1"]!="") {
                            if(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                                mysqli_stmt_bind_param($stmt, "ssss", $att_1_param, $att_2_param, $user_id_param, $ev_code_param);       
                            } else {
                                mysqli_stmt_bind_param($stmt, "sss", $att_1_param, $user_id_param, $ev_code_param);       
                            }
                        } elseif(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                            mysqli_stmt_bind_param($stmt, "sss", $att_2_param, $user_id_param, $ev_code_param);       
                        } else {
                            $err_msg.=" Attendance parameter(s) not bound";
                        }
                        if(mysqli_stmt_execute($stmt)) {
                            if(mysqli_stmt_affected_rows($stmt)==1) {
                                get_name($user_id_param);
                                get_att($user_id_param);
                                if($err_msg=="") {
                                    echo "[SUCCESS] ".$name_result." : '".$old_att_db."' > '".$att_result."'";
                                } else {
                                    echo "[ERROR] ".$err_msg;
                                }
                            } elseif(mysqli_stmt_affected_rows($stmt)==0) {
                                echo "[ERROR] Participant with the ID '".$_POST["user_id"]."' was not found.";
                            } else {
                                echo "[ERROR]".mysqli_stmt_affected_rows($stmt)." users were affected.";
                            }
                        } else {
                            echo "[ERROR] There has been a server error. Please try again later. (DB_EXEC)";
                        }
                        mysqli_stmt_close($stmt);
                    } else {
                        echo "[ERROR] There has been a server error. Please try again later. (DB_PREP) ";
                    }
                } else {
                    echo "[ERROR] ".$err_msg;
                }
            }
            mysqli_close($link);
        } else {
            echo "[ERROR] Parameters not set.";
        }
    } else {
        echo "[ERROR] Only RM/RAs can change attendance data.";
    }
}

function get_att($user_id_param) {
    global $link, $att_result, $err_msg, $att_1_db_out, $att_2_db_out;
    $ev_code_param=$_POST["ev_code"];
    $sql="SELECT att_1, att_2 FROM yicrc_participants WHERE user_id = ? AND ev_code = ? AND status < 2";
    $att_text=array("NC", "P", "L", "A");
    $att_text_full=array("Not checked", "Present", "Late", "Absent");
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ss", $user_id_param, $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)==1) {
                mysqli_stmt_bind_result($stmt, $att_1_db, $att_2_db);
                if(mysqli_stmt_fetch($stmt)) {
                    if(isset($_POST["att_1"]) && $_POST["att_1"]!="") {
                        if(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                            $att_result=$att_text[$att_1_db]." / ".$att_text[$att_2_db];
                        } else {
                            $att_result=$att_text_full[$att_1_db];
                        }
                    } elseif(isset($_POST["att_2"]) && $_POST["att_2"]!="") {
                        $att_result=$att_text_full[$att_2_db];
                    } else {
                        $err_msg.=" Attendance parameter(s) not set.";
                    }
                    $att_1_db_out=$att_1_db;
                    $att_2_db_out=$att_2_db;
                }
            } elseif(mysqli_stmt_num_rows($stmt)==0) {
                $err_msg.=" No attendance data found.";
            } else {
                $err_msg.=" ".mysqli_stmt_num_rows($stmt)." users found. (ATT)";
            }
        }
        mysqli_stmt_close($stmt);
    }
} 

function get_name($user_id_param) {
    global $link, $name_result, $err_msg;
    $sql="SELECT user_eng_name_first, user_eng_name_last, user_name FROM yicrc_users WHERE user_id = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $user_id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)==1) {
                mysqli_stmt_bind_result($stmt, $first_db, $last_db, $uname_db);
                if(mysqli_stmt_fetch($stmt)) {
                    $name_result=$first_db." ".$last_db." (".$uname_db.")";
                }
            } elseif(mysqli_stmt_num_rows($stmt)==0) {
                $err_msg.=" No user with this ID was found.";
            } else {
                $err_msg.=" ".mysqli_stmt_num_rows($stmt)." users found. (NAME)";
            }
        }
        mysqli_stmt_close($stmt);
    }
}
?>