<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);

$stat_out=array();
$prcd=true;
$err_msg="";
$db_prb_msg="A problem was found in the database and we were unable to process your request. Please inform your RA immediately about this issue with a copy or screenshot of this error message.";
if($_SERVER["REQUEST_METHOD"]=="POST" && isset($_SESSION['user_id'])) {
    require_once "config.php";
    if(isset($_POST['user_id']) && isset($_POST['ev_code'])) {
        if($_POST['user_id']=="" || $_POST['ev_code']=="") {
            $prcd=false;
        }
        if($prcd) {
            $sql="SELECT status, sup_order FROM yicrc_participants WHERE user_id = ? AND ev_code = ?";
            if($stmt=mysqli_prepare($link, $sql)) {
                $user_id_param=trim($_POST['user_id']);
                $ev_code_param=trim($_POST['ev_code']);
                mysqli_stmt_bind_param($stmt, "ss", $user_id_param, $ev_code_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if(mysqli_stmt_num_rows($stmt)==0) {
                        $stat_out['status_text']="NOT_SIGNED_UP";
                    } elseif(mysqli_stmt_num_rows($stmt)==1) {
                        mysqli_stmt_bind_result($stmt, $status_db, $sup_order_db);
                        if(mysqli_stmt_fetch($stmt)) {
                            $stat_out['sup_order']=$sup_order_db;
                            $stat_out['status_db']=$status_db;
                        }
                        if($stat_out['status_db']==2) {
                            $stat_out['status_text']="CANCELLED";
                        } else {
                            check_status($stat_out['status_db'], $stat_out['sup_order']);   
                        }
                    } else {
                        $err_msg=$db_prb_msg." (MUL_EFF:".mysqli_stmt_num_rows($stmt)."/stat/".$_POST['ev_code']."/".$_POST['user_id'].")";
                    }
                } else {
                    $err_msg="There has been a server error. Please try again later. (DB_EXEC)";
                }
                mysqli_stmt_close($stmt);
            } else {
                $err_msg="There has been a server error. Please try again later. (DB_PREP)";
            }
        } else {
            $err_msg="There has been a server error. Please try again later. (PARAM_UNSET)";
        }
    }
    mysqli_close($link);
} else {
    $err_msg="There has been a server error. Please try again later. (PARAM_UNSET)";
}

if($err_msg!="") {
    $stat_out['err_msg']=$err_msg;
}

echo json_encode($stat_out);
//echo var_dump($stat_out);

function check_status($status_db, $sup_order_param) {
    global $stat_out, $link, $db_prb_msg, $err_msg;
    $sql="SELECT ev_capacity_optn, ev_capacity, sup_waiting_optn FROM yicrc_events WHERE ev_code = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        $ev_code_param=trim($_POST["ev_code"]);
        mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_capacity_optn_db, $ev_capacity_db, $sup_waiting_optn_db);
            if(mysqli_stmt_fetch($stmt)) {
                $stat_out['ev_capacity_optn']=$ev_capacity_optn_db;
                $stat_out['ev_capacity']=$ev_capacity_db;
                $stat_out['sup_waiting_optn']=$sup_waiting_optn_db;
            }
        } else {
            $err_msg="There has been a server error. Please try again later. (DB_EXEC)";
        }
        mysqli_stmt_close($stmt);
    } else {
        $err_msg="There has been a server error. Please try again later. (DB_PREP)";
    }
    unset($sql);
    if(isset($stmt)) { unset($stmt); }
    if($err_msg=="" && isset($stat_out['ev_capacity_optn'])) {
        if($stat_out['ev_capacity_optn']==0) {
            if($status_db==0) {
                $stat_out['status_text']="SIGNED_UP";   
            } elseif($status_db==1) {
                $stat_out['status_text']="WAITING_CANCEL_Y";
            } else {
                //$err_msg=$db_prb_msg." (STAT_DB:".$status_db.")";
                $err_msg="Error in server. Please try again later. 1";
            }
        } elseif($stat_out['ev_capacity_optn']==1) {
            $sql="SELECT status FROM yicrc_participants WHERE ev_code = ? AND sup_order <= ? AND status < 2";
            if($stmt=mysqli_prepare($link, $sql)) {
                $ev_code_param=trim($_POST['ev_code']);
                mysqli_stmt_bind_param($stmt, "ss", $ev_code_param, $sup_order_param);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    $cap=$stat_out['ev_capacity'];
                    $stat_out['signup_num']=mysqli_stmt_num_rows($stmt);
                    if(mysqli_stmt_num_rows($stmt)>$cap) {
                        if($stat_out['sup_waiting_optn']==1) {
                            if($status_db==0){
                                $stat_out['status_text']="WAITING";
                            } elseif($status_db==1) {
                                $stat_out['status_text']="WAITING_CANCEL_N";
                            } else {
                                //$err_msg=$db_prb_msg." (STAT_DB:".$status_db.")";
                                $err_msg="Error in server. Please try again later. 2";
                            }
                            $stat_out['wait_num']=mysqli_stmt_num_rows($stmt)-$cap;
                        } elseif($stat_out['sup_waiting_optn']==0) {
                            //delete_record($sup_order_param);
                            $stat_out['status_text'] = "SIGN UP FAILED - OVER MAX. CAPACITY";
                            //$err_msg="DELETE";
                        } else {
                            //$err_msg=$db_prb_msg." (W_OPT: ".$stat_out['sup_waiting_optn'].")";
                            $err_msg="Error in server. Please try again later. 3";
                        }
                    } else {
                        if($status_db==0) {
                            $stat_out['status_text']="SIGNED_UP";   
                        } elseif($status_db==1) {
                            $stat_out['status_text']="WAITING_CANCEL_Y";
                        } else {
                            //$err_msg=$db_prb_msg." (STAT_DB:".$status_db.")";
                            $err_msg="Error in server. Please try again later. 4";
                        }
                        $stat_out['wait_num']="";
                    }
                } else {
                    $err_msg="There has been a server error. Please try again later. (DB_EXEC_2)";
                }
                mysqli_stmt_close($stmt);
            } else {
                $err_msg="There has been a server error. Please try again later. (DB_PREP_2)";
            }
        } else {
            $err_msg=$db_prb_msg." (CAP_OPT:".$stat_out['ev_capacity_optn'].")";
        }
    }
}

function delete_record($sup_order_param) {
    global $stat_out, $link;
    $sql="DELETE FROM yicrc_participants WHERE sup_order = ? LIMIT 1";
    if($stmt=mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $sup_order_param);
        if(mysqli_stmt_execute($stmt)) {
            $stat_out['status_text']="DELETED";
        }
        mysqli_stmt_close($stmt);
    }
}

?>