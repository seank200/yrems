<?php
//var_dump($_POST);
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
check_admin();
//mysqli_report(MYSQLI_REPORT_ALL);

if(isset($_POST['action_type'])) {
    $err_msg="";
    require_once 'config.php';
    $ev_name_param=$_POST['ev_name'];
    $ev_type_param=$_POST['ev_type'];
    $ev_time_start_param=$_POST['ev_time_start'];
    $ev_time_end_param=$_POST['ev_time_end'];
    $ev_place_param=$_POST['ev_place'];
    $ev_points_param=$_POST['ev_points'];
    $ev_capacity_optn_param=$_POST['ev_capacity_optn'];
    $ev_capacity_param=$_POST['ev_capacity'];
    $sup_method_param=$_POST['sup_method'];
    $sup_time_st_param=$_POST['sup_time_st'];
    $sup_time_end_param=$_POST['sup_time_end'];
    $sup_participant_publicity_param=$_POST['sup_participant_publicity'];
    $sup_waiting_optn_param=$_POST['sup_waiting_optn'];
    $sup_waiting_publicity_param=$_POST['sup_waiting_publicity'];
    $ev_cancel_optn_param=$_POST['ev_cancel_optn'];
    $ev_att_param=$_POST['ev_att'];
    $ev_att_enable_param=$_POST['ev_att_enable'];
    $ev_supvsr_param=$_POST['ev_supvsr'];
    $ev_description=$_POST['ev_description'];
    $ev_house_param=$_POST["ev_house"];
    
    if($_POST['action_type']=="add") {
        $new_ev_code="";
        //create_ev_code();
        $sql2="SELECT ev_code FROM yicrc_events ORDER BY ev_code DESC LIMIT 1";
        $today=new DateTime();
        if($stmt=mysqli_prepare($link, $sql2)) {
            if(mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                mysqli_stmt_bind_result($stmt, $ev_code_db);
                if(mysqli_stmt_num_rows($stmt)<=0) {
                    $new_ev_code=date_format($today, 'Y')*10000 + 1;
                } else {
                    if(mysqli_stmt_fetch($stmt)) {
                        $old_year=$ev_code_db/10000;
                        $old_year=intval($old_year);
                        if($old_year==date_format($today, 'Y')) {
                            $old_num=$ev_code_db%10000;
                            $new_ev_code=date_format($today, 'Y')*10000 + $old_num + 1;
                        } else {
                            $new_ev_code=date_format($today, 'Y')*10000 + 1;
                        }
                    }
                } 
            } else {
                //$err_msg="Error: DB exec error(2). Please try again later";

            }
            mysqli_stmt_close($stmt);
        } else {
            $err_msg="Error: DB prep error(2). Please try again later";
        }
        $sql="INSERT INTO yicrc_events (ev_code, ev_name, ev_type, ev_time_start, ev_time_end, ev_place, ev_points, ev_capacity_optn, ev_capacity, sup_method, sup_time_st, sup_time_end, sup_participant_publicity, sup_waiting_optn, sup_waiting_publicity, ev_cancel_optn, ev_att, ev_att_enable, ev_supvsr, ev_description, ev_house) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if($stmt=mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssssssssssssssssssss", $new_ev_code, $ev_name_param, $ev_type_param, $ev_time_start_param, $ev_time_end_param, $ev_place_param, $ev_points_param, $ev_capacity_optn_param, $ev_capacity_param, $sup_method_param, $sup_time_st_param, $sup_time_end_param, $sup_participant_publicity_param, $sup_waiting_optn_param, $sup_waiting_publicity_param, $ev_cancel_optn_param, $ev_att_param, $ev_att_enable_param, $ev_supvsr_param, $ev_description, $ev_house_param);
            
            if(mysqli_stmt_execute($stmt)) {
                if(mysqli_stmt_affected_rows($stmt)==1) {
                    echo "Event successfully created.";
                } else {
                    $err_msg="Error: multiple/none affected (".mysqli_stmt_affected_rows($stmt)."). Please try again later";
                }
            } else {
                $err_msg="Error: DB exec error(1_1). Please try again later";
                printf("Error: %s.\n", mysqli_stmt_error($stmt));
            }
            mysqli_stmt_close($stmt);
        } else {
            $err_msg="Error: DB prep error(1_1). Please try again later";
        }
    } elseif($_POST['action_type']=="edit") {
        $sql="UPDATE yicrc_events SET ev_name = ?, ev_type = ?, ev_time_start = ?, ev_time_end = ?, ev_place = ?, ev_points = ?, ev_capacity_optn = ?, ev_capacity = ?, sup_method = ?, sup_time_st = ?, sup_time_end = ?, sup_participant_publicity = ?, sup_waiting_optn = ?, sup_waiting_publicity = ?, ev_cancel_optn = ?, ev_att = ?, ev_att_enable = ?, ev_supvsr = ?, ev_description = ?, ev_house = ? WHERE ev_code = ?";
        $ev_code_param=$_POST['ev_code'];
        if($stmt=mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sisssiiiissiiiiiisssi", $ev_name_param, $ev_type_param, $ev_time_start_param, $ev_time_end_param, $ev_place_param, $ev_points_param, $ev_capacity_optn_param, $ev_capacity_param, $sup_method_param, $sup_time_st_param, $sup_time_end_param, $sup_participant_publicity_param, $sup_waiting_optn_param, $sup_waiting_publicity_param, $ev_cancel_optn_param, $ev_att_param, $ev_att_enable_param, $ev_supvsr_param, $ev_description, $ev_house_param, $ev_code_param);
            if(mysqli_stmt_execute($stmt)) {
                if(mysqli_stmt_affected_rows($stmt)==1) {
                    echo "Changes successfully saved.";
                } elseif(mysqli_stmt_affected_rows($stmt)==0) {
                    echo "Changes successfully saved.(0)";
                } else {
                    $err_msg="Warning: multiple edits affected (".mysqli_stmt_affected_rows($stmt)."). Please try again later";
                }
            } else {
                $err_msg="Error: DB exec error(1_2). Please try again later";
            }
            mysqli_stmt_close($stmt);
        } else {
            $err_msg="Error: DB prep error(1_2). Please try again later";
        }
    } elseif($_POST['action_type']=="delete") {
        $sql="DELETE FROM yicrc_events WHERE ev_code = ? LIMIT 1";
        if($stmt=mysqli_prepare($link, $sql)) {
            $ev_code_param=trim($_POST['ev_code']);
            mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
            if(mysqli_stmt_execute($stmt)) {
                if(mysqli_stmt_affected_rows($stmt)==1) {
                    echo "Event was successfully deleted.";
                } else {
                    $err_msg="Error: multiple/none affected (".mysqli_stmt_affected_rows($stmt)."). Please try again later";
                }
            } else {
                $err_msg="Error: DB exec error(1_3). Please try again later";
            }
            mysqli_stmt_close($stmt);
        } else {
            $err_msg="Error: DB prep error(1_3). Please try again later";
        }
        
        $sql2="DELETE FROM yicrc_participants WHERE ev_code = ?";
        if($stmt2=mysqli_prepare($link, $sql2)) {
        	$ev_code_param=trim($_POST['ev_code']);
        	mysqli_stmt_bind_param($stmt2,"s",$ev_code_param);
        	if(mysqli_stmt_execute($stmt2)) {
        		echo " ".mysqli_stmt_affected_rows($stmt2)." participants records were deleted.";
        	} else {
        		echo " ".mysqli_stmt_affected_rows($stmt2)." participants records were deleted. (1_4)";
        	}
        } else {
        	$err_msg="Error: DB exec error(1_4). Please try again later";
        }
        
    } else {
        $err_msg="Error: a_type unrecognizable. Please close this window and try again.";
    }
    mysqli_close($link);
} else {
    $err_msg="Error: a_type not set. Please close this window and try again.";
}
if($err_msg!="") {
    echo $err_msg;
}
function create_ev_code() {
    global $new_ev_code, $link;
    require_once 'config.php';
    $sql2="SELECT ev_code FROM yicrc_events ORDER BY ev_code DESC LIMIT 1";
    $today=new DateTime();
    if($stmt=mysqli_prepare($link, $sql2)) {
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_code_db);
            if(mysqli_stmt_num_rows($stmt)<=0) {
                $new_ev_code=date_format($today, 'Y')*10000 + 1;
            } else {
                if(mysqli_stmt_fetch($stmt)) {
                    $old_year=$ev_code_db/10000;
                    if($old_year==date_format($today, 'Y')) {
                        $old_num=$ev_code_db%10000;
                        $new_ev_code=date_format($today, 'Y')*10000 + $old_num + 1;
                    } else {
                        $new_ev_code=date_format($today, 'Y')*10000 + 1;
                    }
                }
            } 
        } else {
            //$err_msg="Error: DB exec error(2). Please try again later";
            
        }
        mysqli_stmt_close($stmt);
    } else {
        $err_msg="Error: DB prep error(2). Please try again later";
    }
}
?>