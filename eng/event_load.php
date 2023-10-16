<?php
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
$err_msg="";
if(isset($_POST["ev_code"])) {
    require_once 'config.php';
    $sql="SELECT ev_code, ev_name, ev_type, ev_time_start, ev_time_end, ev_place, ev_points, ev_capacity_optn, ev_capacity, 
                    sup_method, sup_time_st, sup_time_end, sup_participant_publicity, sup_waiting_optn, sup_waiting_publicity,
                    ev_cancel_optn, ev_att, ev_att_enable, ev_supvsr, ev_description, ev_house FROM yicrc_events WHERE ev_code = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        $ev_code_param=$_POST["ev_code"];
        mysqli_stmt_bind_param($stmt, "s", $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt)==1) {
                    mysqli_stmt_bind_result($stmt, $ev_code, $ev_name, $ev_type, $ev_time_start, $ev_time_end, $ev_place, $ev_points, $ev_capacity_optn, $ev_capacity, $sup_method, $sup_time_st, $sup_time_end, $sup_participant_publicity, $sup_waiting_optn, $sup_waiting_publicity, $ev_cancel_optn, $ev_att, $ev_att_enable, $ev_supvsr, $ev_description, $ev_house);
                    if(mysqli_stmt_fetch($stmt)) {
                        $event_info = array();
                        $event_info['ev_code']=$ev_code;
                        $event_info['ev_name']=$ev_name;
                        $event_info['ev_type']=$ev_type;
                        $event_info['ev_time_start']=$ev_time_start;
                        $event_info['ev_time_end']=$ev_time_end;
                        $event_info['ev_place']=$ev_place;
                        $event_info['ev_points']=$ev_points;
                        $event_info['ev_capacity_optn']=$ev_capacity_optn;
                        $event_info['ev_capacity']=$ev_capacity;
                        $event_info['sup_method']=$sup_method;
                        $event_info['sup_time_st']=$sup_time_st;
                        $event_info['sup_time_end']=$sup_time_end;
                        $event_info['sup_participant_publicity']=$sup_participant_publicity;
                        $event_info['sup_waiting_optn']=$sup_waiting_optn;
                        $event_info['sup_waiting_publicity']=$sup_waiting_publicity;
                        $event_info['ev_cancel_optn']=$ev_cancel_optn;
                        $event_info['ev_att']=$ev_att;
                        $event_info['ev_att_enable']=$ev_att_enable;
                        $event_info['ev_supvsr']=$ev_supvsr;
                        $event_info['ev_description']=$ev_description;
                        $event_info['ev_house']=$ev_house;
                        echo json_encode($event_info);
                    }
            } else {
                $err_msg="Error: ".mysqli_stmt_num_rows($stmt)." events with this event code were found.";
            }
        } else {
            $err_msg="Error: DB exec. Please try again later.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $err_msg="Error: DB prep. Please try again later.";
    }
    mysqli_close($link);
} else {
    $err_msg="Error: event code not set. Please close this window and try again.";
}
if($err_msg!="") {
    echo $err_msg;
}
?>