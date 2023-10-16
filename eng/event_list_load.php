<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ALL);
$sql="SELECT ev_code, ev_name, ev_time_start, ev_time_end, ev_place FROM yicrc_events WHERE ev_time_start >= ? AND ev_time_start < ? AND ev_code > 20180030 ORDER BY ev_time_start DESC";
$err_msg="";
if($_SERVER["REQUEST_METHOD"]=="POST") {
    require_once 'config.php';
    if($stmt=mysqli_prepare($link, $sql)) {
        if(isset($_POST["ev_date_range_year"]) && isset($_POST["ev_date_range_month"])) {
            $today=new DateTime();
            if($_POST["ev_date_range_year"]==0) {
                $year_param=$today->format('Y');
            } else {
                $year_param = trim($_POST["ev_date_range_year"]);   
            }
            if($_POST["ev_date_range_month"]==0 || $_POST["ev_date_range_month"]<0) {
                $year_param_1=$year_param+1;
                $month_param_set_1="01";
                $range_start_query=$year_param."-".$month_param_set_1."-01 00:00:00";
                $range_end_query=$year_param_1."-".$month_param_set_1."-01 00:00:00";
                //mysqli_stmt_bind_param($stmt, "ssss", $year_param, $month_param_set_1, $year_param_1, $month_param_set_1);
                
            } else {
                $month_param=trim($_POST["ev_date_range_month"]);
                $month_param_1=$month_param+1;
                if($month_param_1>12) {
                    $month_param_1="01";
                    $year_param_1=$year_param+1;
                    $range_start_query=$year_param."-".$month_param."-01 00:00:00";
                    $range_end_query=$year_param_1."-".$month_param_1."-01 00:00:00";
                    //mysqli_stmt_bind_param($stmt, "ssss", $year_param, $month_param, $year_param_1, $month_param_1);
                } else {
                    if($month_param_1<10) {
                        $month_param_1="0".$month_param_1;
                    }
                    if($month_param<10) {
                        $month_param="0".$month_param;
                    }
                    $range_start_query=$year_param."-".$month_param."-01 00:00:00";
                    $range_end_query=$year_param."-".$month_param_1."-01 00:00:00";
                    //mysqli_stmt_bind_param($stmt, "ssss", $year_param, $month_param, $year_param, $month_param_1);
                }
            }
        } else {
            $year_param=$today->format('Y');
            $month_param=$today->format('m');
            $month_param_1=$month_param+1;
            if($month_param<10) {
                if($month_param_1<10) {
                    $month_param_1="0".$month_param_1;
                }
                $month_param="0".$month_param;
            } else {
                if($month_param==12) {
                    $month_param_1="01";
                    $year_param_1=$year_param+1;
                }
            }
            $range_start_query=$year_param."-".$month_param."-01 00:00:00";
            $range_end_query=$year_param_1."-".$month_param_1."-01 00:00:00";
            //mysqli_stmt_bind_param($stmt, "ssss", $year_param, $month_param, $year_param_1, $month_param_1);
        }
        mysqli_stmt_bind_param($stmt,"ss",$range_start_query, $range_end_query);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_code_db, $ev_name_db, $ev_time_start_db, $ev_time_end_db, $ev_place_db);
            $ev_list_out=array();
            while(mysqli_stmt_fetch($stmt)) {
                array_push($ev_list_out, array("ev_code"=>$ev_code_db, "ev_name"=>$ev_name_db, "ev_time_start"=>$ev_time_start_db, "ev_time_end"=>$ev_time_end_db, "ev_place"=>$ev_place_db));
            }
            echo json_encode($ev_list_out);
            //echo $range_start_query;
            //echo $range_end_query;
        } else {
            $err_msg="Server could not load event list. Please try again later. 2";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);
    } else {
        $err_msg="Server could not load event list. Please try again later. 1";
    }
} else {
    $err_msg="Server could not load event list. Please try again later. 3";
}
if($err_msg!="") {
    echo $err_msg;
}
?>