<?php
session_start();
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
require_once "config.php";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if($_SESSION['user_type']=="House RA" || $_SESSION['user_type']=="Chief RA" || $_SESSION['user_type']=="RM" || $_SESSION['user_type']=="Administrative RA") { 
        if(isset($_POST["ev_code"])) {
            $sql="SELECT user_id, status, sup_order, att_1, att_2 FROM yicrc_participants WHERE ev_code = ? AND status < 2 ORDER BY att_1, att_2";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=$_POST["ev_code"];
                mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $part_user_id_db, $status_db, $sup_order_db, $att_1_db, $att_2_db);
                    $part_out=array();
                    header("Content-Type: text/csv; charset=utf-8");
                    //header("Content-Disposition: attachment; filename=\"YREMS.csv\"");
                    $output=fopen("php://output","w");
                    if($_POST["id_only"]==1) {
                        //echo "<table><tr><td>ID</td></tr>";   
                        fputcsv($output, array("ID"));
                    } else {
                        if($_POST["present_only"]==1) {
                            //echo "<table><tr><td>ID</td><td>Name(ENG)</td><td>Name</td></tr>";   
                            fputcsv($output, array("ID", "Name(ENG)", "Name"));
                        } else {
                            if($_POST["ev_att"]==1) {
                                //echo "<table><tr><td>ID</td><td>Name(ENG)</td><td>Name</td><td>1st Attendance</td></tr>";   
                                fputcsv($output, array("ID", "Name(ENG)", "Name", "1st_Attendance"));
                            } elseif($_POST["ev_att"]==2) {
                                //echo "<table><tr><td>ID</td><td>Name(ENG)</td><td>Name</td><td>1st Attendance</td><td>2nd Attendance</td></tr>"; 
                                fputcsv($output, array("ID", "Name(ENG)", "Name", "1st_Attendance", "2nd_Attendance"));
                            } else {
                                //echo "<table><tr><td>ID</td><td>Name(ENG)</td><td>Name</td></tr>";
                                fputcsv($output, array("ID", "Name(ENG)", "Name"));
                            }
                        }
                    }
                    while(mysqli_stmt_fetch($stmt)) {
                        get_partinfo($part_user_id_db, $status_db, $sup_order_db, $att_1_db, $att_2_db);
                    }
                    fclose($output);
                    //echo "</table>";
                } else {
                    echo "There has been a server error. Please try again later. (DB_EXEC_1)";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "There has been a server error. Please try again later. (DB_PREP_1)";
            }
        } else {
            echo "Error: Event not specified.";
        }
    } else {
        echo "Error: Only RM/RAs can access this data. If you are a RM/RA, please close this window and log in again before retrying.";
    }
} else {
    echo "Error: Parameters not set";
}
mysqli_close($link);
function get_partinfo($id_param, $status_param, $sup_order_param, $att_1_param, $att_2_param) {
    global $link, $part_out, $output;
    $sql="SELECT user_eng_name_first, user_eng_name_last, user_name, user_id, user_mobile FROM yicrc_users WHERE user_id = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $first_db, $last_db, $name_db, $user_id_db, $user_mobile_db);
            if(mysqli_stmt_num_rows($stmt)==1) {
                if(mysqli_stmt_fetch($stmt)) {
                    //array_push($part_out, array("user_eng_name_first"=>$first_db, "user_eng_name_last"=>$last_db, "user_name"=>$name_db, "user_id"=>$user_id_db, "user_mobile"=>$user_mobile_db, "user_status"=>$status_param, "sup_order"=>$sup_order_param, "att_1"=>$att_1_param, "att_2"=>$att_2_param));
                    if($_POST["id_only"]==1) {
                        /*echo "<tr><td>";
                        echo $user_id_db;
                        echo "</td></tr>";*/
                        fputcsv($output, array($user_id_db));
                    } else {
                        if($_POST["present_only"]==1) {
                            if($_POST["ev_att"]==1) {
                                if($att_1_param==1) {
                                    /*echo "<tr>";
                                    echo "<td>".$user_id_db."</td>";
                                    echo "<td>".$first_db." ".$last_db."</td>";
                                    echo "<td>".$name_db."</td>";
                                    echo "</tr>";*/
                                    $name_eng_db=$first_db."_".$last_db;
                                    fputcsv($output, array($user_id_db, $name_eng_db, $name_db));
                                }
                            } elseif($_POST["ev_att"]==2) {
                                if($att_1_param==1 && $att_2_param==1) {
                                    /*echo "<tr>";
                                    echo "<td>".$user_id_db."</td>";
                                    echo "<td>".$first_db." ".$last_db."</td>";
                                    echo "<td>".$name_db."</td>";
                                    echo "</tr>";*/
                                    $name_eng_db=$first_db."_".$last_db;
                                    fputcsv($output, array($user_id_db, $name_eng_db, $name_db));
                                }
                            } else {
                                /*echo "<tr>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "<td></td>";
                                echo "</tr>";*/
                                fputcsv($output, array("","",""));
                            }
                        } else {
                            $att_text=array("NC","P","L","A");
                            /*
                            echo "<tr>";
                            echo "<td>".$user_id_db."</td>";
                            echo "<td>".$first_db." ".$last_db."</td>";
                            echo "<td>".$name_db."</td>";
                            echo "<td>".$att_text[$att_1_param]."</td>";
                            if($_POST["ev_att"]==2) {
                                echo "<td>".$att_text[$att_2_param]."</td>";
                            }
                            echo "</tr>";*/
                            $name_eng_db=$first_db."_".$last_db;
                            fputcsv($output, array($user_id_db, $name_eng_db, $name_db, $att_text[$att_1_param], $att_text[$att_2_param]));
                        }   
                    }
                }
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