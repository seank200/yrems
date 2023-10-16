<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
$err_msg="";
if(isset($_SESSION['user_id'])){
    require_once 'config.php';
    $err_msg="";
    $sql="SELECT sup_order, ev_code, status, att_1, att_2 FROM yicrc_participants WHERE user_id = ? AND ev_code > 20180030 ORDER BY ev_code ASC";
    if($stmt=mysqli_prepare($link, $sql)) {
        if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
            if(isset($_POST["user_id"])) {
                $user_id_param=mysqli_real_escape_string($link, trim($_POST["user_id"]));
            } else {
                $user_id_param=$_SESSION['user_id'];
            }
        } else {
            $user_id_param=$_SESSION['user_id'];   
        }
        //$user_id_param="2018000000"; //DEBUG
        mysqli_stmt_bind_param($stmt, "s", $user_id_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $sup_order, $ev_code, $status, $att_1, $att_2);
            $my_ev=array();
            while(mysqli_stmt_fetch($stmt)) {
                array_push($my_ev, array("sup_order"=>$sup_order, "ev_code"=>$ev_code, "status"=>$status, "att_1"=>$att_1, "att_2"=>$att_2));
            }
        } else {
            $err_msg="An error occured.(3)";
        }
        mysqli_stmt_close($stmt);
    } else {
        $err_msg="An error occured.(4)";
    }
    
    //$my_ev_name=null;
    $my_ev_name=array();
    //$my_ev_status=null;
    $my_ev_status=array();
    $att_text=array("NC","P","L","<span style='color: #EE220C;'>A</span>");
    $my_ev_att=array();
    $my_ev_code=array();
    foreach($my_ev as $v1) {
        $sql="SELECT ev_name, ev_capacity_optn, ev_capacity, ev_att, ev_att_enable, sup_method, sup_waiting_optn FROM yicrc_events WHERE ev_code = ?";   
        if($link) {
            if($stmt=mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $v1['ev_code']);
                if(mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    mysqli_stmt_bind_result($stmt, $ev_name_db, $ev_capacity_optn_db, $ev_capacity_db, $ev_att_db, $ev_att_enable_db, $sup_method, $sup_waiting_optn_db);
                    while(mysqli_stmt_fetch($stmt)) {
                        array_push($my_ev_name, $ev_name_db);
                        array_push($my_ev_code, $v1['ev_code']);
                        if($v1['status']==0) {
                            if($ev_capacity_optn_db==0) {
                                $status_text="<span style='color: #1DB100;'>SIGNED UP</span>";
                                if($ev_att_enable_db=="1" && $ev_att_db=="1") { //attendance checked once
                                    array_push($my_ev_att, $att_text[$v1['att_1']]);
                                } elseif($ev_att_enable_db=="1" && $ev_att_db=="2") { //attendance checked twice
                                    array_push($my_ev_att, $att_text[$v1['att_1']]." / ".$att_text[$v1['att_2']]);
                                } else { //attendance not checked
                                    array_push($my_ev_att, "-");
                                }
                            } else {
                                $num_part_db = num_participants($v1['ev_code'], $v1['sup_order']);
                                if($sup_method==2) {
                                    if($num_part_db>$ev_capacity_db) {
                                        if($sup_waiting_optn_db==0) {
                                            $status_text="OVER CAP.";
                                            //delete_sup($v1['sup_order']);
                                        } else {
                                            $status_text="<span style='color: #FF9300;'>WAITING (";
                                            $status_text.=$num_part_db-$ev_capacity_db;
                                            $status_text.=")</span>";   
                                        }
                                        array_push($my_ev_att, "-");
                                    } else {
                                        $status_text="<span style='color: #1DB100;'>SIGNED UP</span>";
                                        if($ev_att_enable_db=="1" && $ev_att_db=="1") { //attendance checked once
                                            array_push($my_ev_att, $att_text[$v1['att_1']]);
                                        } elseif($ev_att_enable_db=="1" && $ev_att_db=="2") { //attendance checked twice
                                            array_push($my_ev_att, $att_text[$v1['att_1']]." / ".$att_text[$v1['att_2']]);
                                        } else { //attendance not checked
                                            array_push($my_ev_att, "-");
                                        }
                                    }
                                } else {
                                    if($ev_att_enable_db=="1" && $ev_att_db=="1") { //attendance checked once
                                        $status_text="-";
                                        array_push($my_ev_att, $att_text[$v1['att_1']]);
                                    } elseif($ev_att_enable_db=="1" && $ev_att_db=="2") { //attendance checked twice
                                        $status_text="-";
                                        array_push($my_ev_att, $att_text[$v1['att_1']]." / ".$att_text[$v1['att_2']]);
                                    } else { //attendance not checked
                                        array_push($my_ev_att, "-");
                                    }
                                }   
                            }
                            array_push($my_ev_status, $status_text);
                        } elseif($v1['status']==1) {
                            array_push($my_ev_status, "CANCEL REQUESTED");
                            array_push($my_ev_att, "-");
                        } elseif($v1['status']==2) {
                            array_push($my_ev_status, "<span style='color: #EE220C;'>CANCELLED</span>");
                            array_push($my_ev_att, "-");
                        } else {
                            array_push($my_ev_status, "<span style='color: #EE220C;'>ERROR: ".$v1['status'])."</span>";
                            array_push($my_ev_att, "-");
                        }
                    }
                } else {
                    $err_msg.=" Execute Error in \$my_ev(".$v1['ev_code'].")";
                }
                mysqli_stmt_close($stmt);
            } else {
                $err_msg.=" Prepare Error in \$my_ev(".$v1['ev_code'].")";
            }
        }
    }
    mysqli_close($link);
    unset($k1);
    unset($v1);
    $my_ev_output=array();
    foreach($my_ev_name as $k1 => $v1) {
        array_push($my_ev_output, array("ev_name"=>$v1, "ev_status"=>$my_ev_status[$k1], "ev_att"=>$my_ev_att[$k1], "ev_code"=>$my_ev_code[$k1]));
    }
    echo json_encode($my_ev_output);
    //print_arrays();
} else {
    header('Location: logout.php');
}

function delete_sup($sup_order_param) {
    global $link;
    $sql = "DELETE FROM yicrc_participants WHERE sup_order = ? LIMIT 1";
    if($sup_order_param!="") {
        if($stmt=mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $sup_order_param);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }   
    }
}

function print_arrays() {
    global $my_ev, $my_ev_name, $my_ev_status, $my_ev_output;
    echo "===Array \"\$my_ev\"===<br />";
    foreach($my_ev as $k1 => $v1) {
        echo "Array \"$k1\" start <br />"; 
        foreach($v1 as $k2 => $v2) {
            echo "key: $k2, value: $v2";
            echo '<br />';
        }
        echo '<br />';
    }
    unset($k1);
    unset($v1);
    unset($k2);
    unset($v2);

    echo "===Array \"\$my_ev_name\"===<br />";
    foreach ($my_ev_name as $k1 => $v1) {
        echo "key: $k1, value: $v1<br />";
    }
    unset($k1);
    unset($v1);
    echo '<br />';
    echo "===Array \"\$my_ev_status\"===<br />";
    foreach ($my_ev_status as $k1 => $v1) {
        echo "key: $k1, value: $v1<br />";
    }
    unset($k1);
    unset($v1);
    echo '<br />';
    echo "===Array \"\$my_ev_output\"===<br />";
    foreach ($my_ev_output as $k1 => $v1) {
        echo "Array \"$k1\" start <br />"; 
        foreach($v1 as $k2 => $v2) {
            echo "key: $k2, value: $v2<br />";
        }
        echo '<br />';
    }
    unset($k1);
    unset($v1);
    unset($k2);
    unset($v2);
}

function num_participants($ev_code_param, $sup_order_param) {
    global $err_msg, $link;
    if($link) {
        $sql="SELECT sup_order FROM yicrc_participants WHERE ev_code = ? AND sup_order <= ? AND status < 2";
        if($stmt2=mysqli_prepare($link,$sql)) {
            mysqli_stmt_bind_param($stmt2, "is", $ev_code_param, $sup_order_param);
            if(mysqli_stmt_execute($stmt2)) {
                mysqli_stmt_store_result($stmt2);
                return mysqli_stmt_num_rows($stmt2);
            } else {
                //return "err_execute";
                $err_msg.=" Error: execute(num_part)";
            }
            mysqli_stmt_close($stmt2);
        } else {
            //return "err_prepare";
            $err_msg.=" Error: prepare(num_part)";
        }
    } else {
        $err_msg.=" Error: LINK_FALSE";
    }
}

if($err_msg!="") {
    echo '<br />"err_msg": '.$err_msg;
}
?>