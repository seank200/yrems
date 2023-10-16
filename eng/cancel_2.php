<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Cancellation- YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
	<script type="text/javascript" src="base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background: white;" onresize="menu_check()">
<div class="header">
    <div class="header_content">
        <img id="menu_show" src="/yicrc/img/menu.png" class="menu" alt="MENU" onclick="toggle_menu()" />
        <img id="menu_close" src="/yicrc/img/menu_close.png" class="menu_close" alt="MENU" onclick="toggle_menu()" />
        <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" />
        <ul id="menu_list" class="header">
        </ul>
    </div>
</div>
<?php
    require_once 'config_2.php';
    load_menu();
?>
<div class="loader" id="loader" style="display: none;"></div>
<div class="content_div" id="content_div">
	<div class="col-2 hide_mobile"></div>
    <div class="col-4">
        <h1 style="color:red;">Sign-up Cancellation</h1>
        <table class="list details">
            <tr>
                <td colspan="2" id="ev_name_td">Event Name</td>
            </tr>
            <tr>
                <td>Sign-up period</td>
                <td id="sup_time_td">Event sign-up period</td>
            </tr>
            <tr>
                <td>Cancellation type</td>
                <td id="ev_cancel_optn_td"></td>
            </tr>
        </table>
        <h3>My status</h3>
        <div id="my_status_div"></div>
    </div>
    <div class="col-4">
        <div id="cancel_no" class="status redd">Cancellation is not allowed for this event.</div> 
        <div id="step_1" style="display: none;">
            <h3>I have read and agreed with the “<a href="/yicrc/policy.php"><u>RC Events Sign-up/Cancel Policy.</u></a>”</h3>
            <button class="blue" style="width: 100%;" onclick="agree_policy('agree')">YES</button><br />
            <button class="blue" style="width: 100%;" onclick="agree_policy('disagree')">NO</button>
        </div>
        <div id="step_2_1" style="display: none;">
            <h1 style="color: #0A3879;">Cancellation will take effect immediately. You cannot undo this action.</h1>
            <button class="red" style="width: 100%;" onclick="cancel_btn_click(this)">CANCEL SIGN-UP</button><br />
            <button class="blue" style="width: 100%;" onclick="goback()">DON'T CANCEL</button>
        </div>
        <div id="step_2_2" style="display: none;">
            <h1 style="color: #0A3879;">Your cancellation request must be manually approved by the event manager to take effect. <span style="color:#EE220C;">Please contact your RA</span> immediately after clicking the “REQUEST CANCELLATION” button below.</h1>
            <button class="red" style="width: 100%;" onclick="cancel_btn_click(this)">REQUEST CANCELLATION</button><br />
            <button class="blue" style="width: 100%;" onclick="goback()">DON'T CANCEL</button>
        </div>
    </div>
    <div class="col-2 hide_mobile"></div>
</div>
    
<div style="display: none;" id="user_id_div"><?php echo $_SESSION["user_id"]; ?></div>
<div style="display: none;" id="user_type_div"><?php echo $_SESSION["user_type"]; ?></div>
<div style="display: none;" id="ev_code_div"><?php if(isset($_GET['ev_code'])) { echo trim($_GET['ev_code']); } ?></div>
<div style="display: none;" id="sup_order_div"></div>
<div style="display: none;" id="status_text_div"></div>
    
<?php
if($_SERVER["REQUEST_METHOD"]=="GET") {
    if(isset($_GET['ev_code'])&&$_GET['ev_code']!="") {
        require_once 'config.php';
        get_evinfo();
        //mysqli_close($link);
    }
} else {
    echo '<div class="status redd">Event not specified. Please close this window and try again.</div>';
}
    
function get_evinfo() {
    global $link;
    $sql = "SELECT ev_name, ev_time_start, ev_time_end, ev_place, sup_time_st, sup_time_end, ev_supvsr, ev_cancel_optn FROM yicrc_events WHERE ev_code = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        $ev_code_param=trim($_GET['ev_code']);
        mysqli_stmt_bind_param($stmt, "i", $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_name_db, $ev_time_start_db, $ev_time_end_db, $ev_place_db, $sup_time_st_db, $sup_time_end_db, $ev_supvsr_db, $ev_cancel_optn_db);
            while(mysqli_stmt_fetch($stmt)) {
                $ev_out=array();
                $ev_out['ev_name']=$ev_name_db;
                $ev_out['ev_time_start']=$ev_time_start_db;
                $ev_out['ev_time_end']=$ev_time_end_db;
                $ev_out['ev_place']=$ev_place_db;
                $ev_out['sup_time_st']=$sup_time_st_db;
                $ev_out['sup_time_end']=$sup_time_end_db;
                $ev_out['ev_supvsr']=$ev_supvsr_db;
                $ev_out['ev_cancel_optn']=$ev_cancel_optn_db;
            }
            echo '<div id="evinfo_get_div" style="display: none">';
            echo json_encode($ev_out);
            echo '</div>';
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: executing database";
        }
    } else {
        echo 'Error: preparing database';
    }
    mysqli_close($link);
}
?>
<script type="text/javascript">
    fill_evinfo();
function gei(x) {
    return document.getElementById(x);
}
function agree_policy(x) {
    var info_div = document.getElementById("evinfo_get_div");
    var info = JSON.parse(info_div.innerText);
    var sup_st_var = info.sup_time_st;
    var sup_end_var = info.sup_time_end;
    var within_sup_date = false;
    var d = new Date();
    
    var sup_st_date = ((parseInt(sup_st_var.split(" ", 2)[0].split("-")[0])*10000)+(parseInt(sup_st_var.split(" ", 2)[0].split("-")[1])*100)+(parseInt(sup_st_var.split(" ", 2)[0].split("-")[2])));
    sup_st_date = sup_st_date*1000000 + (parseInt(sup_st_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(sup_st_var.split(" ", 2)[1].split(":")[1])*100);

    var today_date = d.getFullYear()*10000+(d.getMonth()+1)*100+d.getDate();
    today_date = today_date*1000000 + d.getHours()*10000 + d.getMinutes()*100 + d.getSeconds();

    var sup_end_date = (parseInt(sup_end_var.split(" ", 2)[0].split("-")[0])*10000)+(parseInt(sup_end_var.split(" ", 2)[0].split("-")[1])*100)+(parseInt(sup_end_var.split(" ", 2)[0].split("-")[2]));
    sup_end_date = sup_end_date*1000000 + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[1])*100);

    if(today_date>=sup_st_date && sup_end_date>=today_date) {
        within_sup_date=true;
    }
    
    var info_div = document.getElementById("evinfo_get_div");
    var info=null;
    try {
        info = JSON.parse(info_div.innerText);
    } catch(e) {
        alert("Unable to retrieve event information.");
    }
    var ev_time_start_var=null;
    var ev_time_start_year=null;
    var ev_time_start_month=null;
    var ev_time_start_day=null;
    var ev_time_start_24=null;
    var within_24=false;
    if(info!=null) {
        ev_time_start_var=info.ev_time_start;
        //ev_time_start = ((parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[0])*10000)+(parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[1])*100)+(parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[2])));
        ev_time_start_year=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[0]);
        ev_time_start_month=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[1]);
        ev_time_start_day=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[2]);
        if(ev_time_start_day==1) {
            if(ev_time_start_month==1) {
                ev_time_start_day=31;
                ev_time_start_month=12;
                ev_time_start_year--;
            } else if(ev_time_start_month==3) {
                ev_time_start_day=28;
                ev_time_start_month--;
            } else if(ev_time_start_month==2 ||ev_time_start_month==4 || ev_time_start_month==6 || ev_time_start_month==8 ||ev_time_start_month==9 || ev_time_start_month==11) { //1 3 5 7 8 10 12
                ev_time_start_day=31;
                ev_time_start_month--;
            } else {
                ev_time_start_day=30;
                ev_time_start_month--;
            }
        } else {
            ev_time_start_day--;
        }
        ev_time_start_24=ev_time_start_year*10000+ev_time_start_month*100+ev_time_start_day;
        ev_time_start_24 = ev_time_start_24*1000000 + (parseInt(ev_time_start_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(ev_time_start_var.split(" ", 2)[1].split(":")[1])*100);
        if(ev_time_start_24<=today_date) {
            within_24=true;
        }
    } else {
        within_24=true;
    }
    
    
    if(x=="agree") {
        if(within_24) {
            alert("You can only cancel events before 24 hours from event start time");
            //window.location="/yicrc/event_details.php?ev_code="+document.getElementById("ev_code_div").innerText;
            window.location="rc_events_2.php?ev_code="+document.getElementById("ev_code_div").innerText;
        } else {
            document.getElementById("step_1").style.display="none";
            var c_optn = JSON.parse(document.getElementById("evinfo_get_div").innerText).ev_cancel_optn;
            if(c_optn==1) {
                cancel_no.style.display="";
                step_1.style.display="none";
                step_2_1.style.display="none";
                step_2_2.style.display="none";
            } else if(c_optn==2) {
                if(within_24) {
                    step_2_2.style.display="none";
                } else {
                    if(within_sup_date) {
                        step_2_1.style.display="";
                    } else {
                        step_2_2.style.display="";
                    }
                }
            } else if(c_optn==3) {
                if(within_24) {
                    step_2_2.style.display="none";
                } else {
                    step_2_2.style.display="";   
                }
            } else {
                alert("An error has occrued. Please close this window and try again later.")
            }
        } 
    } else {
        alert("You cannot cancel your sign-up if you do not agree to the RC Events Sign-up/Cancel Policy.");
    }
}
function goback() {
	if(confirm("Do you wish to leave this page?")) {
        //window.location="/yicrc/event_details.php?ev_code="+document.getElementById("ev_code_div").innerText;
        window.location="rc_events_2.php?ev_code="+document.getElementById("ev_code_div").innerText;
    }
}
get_status();
function get_status() {
    var user_type=gei("user_type_div").innerText;
    var user_id=gei("user_id_div").innerText;
    var ev_code=gei("ev_code_div").innerText;
    if(user_type=="RC Student" || user_type=="Non-RC Student") {
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        $.ajax({
            url: "status_load.php",
            type: "POST",
            data: {"ev_code":ev_code, "user_id":user_id},
            success: function(data) {
                var stat=null;
                try {
                    stat=JSON.parse(data);
                } catch(e) {
                    alert("Error in reading data. Please close this window and try again.");
                }
                if(stat!=null) {
                    var stdiv = gei("my_status_div");
                    stdiv.style.display="";
                    stdiv.innerHTML="";
                    if(stat.status_text=="CANCELLED") {
                        stdiv.className="status redd";
                        stdiv.innerHTML+="CANCELLED";
                    } else if(stat.status_text=="WAITING") {
                        stdiv.className="status yellowd";
                        var str_out="ON WAITING LIST #";
                        str_out += stat.wait_num;
                        str_out += " (NOT SIGNED UP)";
                        stdiv.innerHTML+=str_out;
                    } else if(stat.status_text=="WAITING_CANCEL_N") {
                        stdiv.className="status yellowd";
                        var str_out="ON WAITING LIST #";
                        str_out += stat.wait_num;
                        str_out += " (NOT SIGNED UP) - WAITING CANCELLATION APPROVAL";
                        stdiv.innerHTML+=str_out;
                    } else if(stat.status_text=="WAITING_CANCEL_Y") {
                        stdiv.className="status greend";
                        stdiv.innerHTML+="SUCCESSFULLY SIGNED UP - WAITING CANCELLATION APPROVAL";
                    } else if(stat.status_text=="SIGNED_UP") {
                        stdiv.className="status greend";
                        stdiv.innerHTML+="SUCCESSFULLY SIGNED UP";
                    } else if(stat.status_text=="NOT_SIGNED_UP") {
                        stdiv.className="status greend";
                        stdiv.innerHTML+="NOT SIGNED UP";
                    } else {
                        stdiv.className="status redd";
                        if(stat.status_text) {
                            stdiv.innerHTML=stat.status_text;   
                        }
                        if(stat.err_msg) {
                            stdiv.innerHTML+=stat.err_msg;   
                        }
                    }
                    if(stat.sup_order) {
                        gei("sup_order_div").innerHTML=stat.sup_order;
                    }
                    if(stat.status_text) {
                        gei("status_text_div").innerHTML=stat.status_text;
                    }
                } else {
                    gei("my_status_div").style.display="none";
                }
            },
            error: function(e) {
                alert("Error connecting to server. Please try again later. ("+e.message+")");
            },
            complete: function() {
                gei("loader").style.display="none";
                gei("content_div").style.display="";
            }
        });
    }
}
    
function fill_evinfo() {
    if(document.getElementById("evinfo_get_div")!=null && document.getElementById("ev_code_div").innerText!="") {
        var info_div = document.getElementById("evinfo_get_div");
        var info="";
        try {
            info = JSON.parse(info_div.innerText);
        } catch(e) {
            alert(e);
        }
        document.getElementById("ev_name_td").innerHTML=info.ev_name;
        //document.getElementById("ev_time_td").innerHTML=info.ev_time_start+" ~ "+info.ev_time_end;
        //document.getElementById("ev_place_td").innerHTML=info.ev_place;
        document.getElementById("sup_time_td").innerHTML=info.sup_time_st+" ~ "+info.sup_time_end;
        var sup_st_var = info.sup_time_st;
        var sup_end_var = info.sup_time_end;
        var within_sup_date = false;
        var d = new Date();
        var sup_st_date = ((parseInt(sup_st_var.split(" ", 2)[0].split("-")[0])*10000)+(parseInt(sup_st_var.split(" ", 2)[0].split("-")[1])*100)+(parseInt(sup_st_var.split(" ", 2)[0].split("-")[2])));
        sup_st_date = sup_st_date*1000000 + (parseInt(sup_st_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(sup_st_var.split(" ", 2)[1].split(":")[1])*100);
        
        var today_date = d.getFullYear()*10000+(d.getMonth()+1)*100+d.getDate();
        today_date = today_date*1000000 + d.getHours()*10000 + d.getMinutes()*100 + d.getSeconds();
        
        var sup_end_date = (parseInt(sup_end_var.split(" ", 2)[0].split("-")[0])*10000)+(parseInt(sup_end_var.split(" ", 2)[0].split("-")[1])*100)+(parseInt(sup_end_var.split(" ", 2)[0].split("-")[2]));
        sup_end_date = sup_end_date*1000000 + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[1])*100) + 59;
        
        if(today_date>=sup_st_date && sup_end_date>=today_date) {
            within_sup_date=true;
        }
        //alert("within_sup_date: "+within_sup_date+ ", sup_st_date: "+sup_st_date+", today_date: "+today_date+", sup_end_date: "+sup_end_date);
        
        //document.getElementById("ev_supvsr_span").innerHTML=info.ev_supvsr;
        var c_optn = document.getElementById("ev_cancel_optn_td");
        var cancel_no = document.getElementById("cancel_no");
        var step_1 = document.getElementById("step_1");
        var step_2_1 = document.getElementById("step_2_1");
        var step_2_2 = document.getElementById("step_2_2");
        switch(info.ev_cancel_optn) {
            case 1:
                cancel_no.style.display="";
                step_1.style.display="none";
                step_2_1.style.display="none";
                step_2_2.style.display="none";
                c_optn.innerHTML="Student cancellation not allowed.";
                break;
            case 2:
                if(within_sup_date) {
                    cancel_no.style.display="none";
                    step_1.style.display="";
                    step_2_1.style.display="none";
                    step_2_2.style.display="none";
                    c_optn.innerHTML="Student cancellation allowed during sign-up period. Cancellation is effective immediately after cancel button is pressed.";
                } else {
                    cancel_no.style.display="none";
                    step_1.style.display="";
                    step_2_1.style.display="none";
                    step_2_2.style.display="none";
                    c_optn.innerHTML="Student can request for cancellation, but cancellation is NOT effective until the event manager individually accepts the cancel request. (Out of sign-up period)";
                }
                
                break;
            case 3:
                cancel_no.style.display="none";
                step_1.style.display="";
                step_2_1.style.display="none";
                step_2_2.style.display="none";
                c_optn.innerHTML="Student can request for cancellation, but cancellation is NOT effective until the event manager individually accepts the cancel request.";
                break;
            default:
                cancel_no.style.display="";
                step_1.style.display="none";
                step_2_1.style.display="none";
                step_2_2.style.display="none";
                c_optn.innerHTML="Cancellation is not possible now due to error in the server. Please try again later. ("+info.ev_cancel_optn+")";
                break;
        }
    } else {
        alert("event not specified.");
        //window.location="/yicrc/rc_events.php";
    }
}
function cancel_btn_click(x) {
    if(x.innerHTML=="CANCEL SIGN-UP") {
        if(confirm("Do you wish to cacnel? You cannot undo this action.")) {
            do_cancel();
        }
    } else if(x.innerHTML=="REQUEST CANCELLATION") {
        if(confirm("Be aware that your sign-up will NOT be cancelled before the event manager approves your cancellation request. Click OK to send cancellation request.")) {
            do_cancel();
        }
    } else {
        alert("Error: "+x.innerHTML);
    }
}
function do_cancel() {
    document.getElementById("content_div").style.display="none";
    document.getElementById("loader").style.display="";
    
    var d = new Date();
    var today_date = d.getFullYear()*10000+(d.getMonth()+1)*100+d.getDate();
    today_date = today_date*1000000 + d.getHours()*10000 + d.getMinutes()*100 + d.getSeconds();
    
    var info_div = document.getElementById("evinfo_get_div");
    var info=null;
    try {
        info = JSON.parse(info_div.innerText);
    } catch(e) {
        alert("Unable to retrieve event information.");
    }
    var ev_time_start_var=null;
    var ev_time_start_year=null;
    var ev_time_start_month=null;
    var ev_time_start_day=null;
    var ev_time_start_24=null;
    var within_24=false;
    if(info!=null) {
        ev_time_start_var=info.ev_time_start;
        ev_time_start_year=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[0]);
        ev_time_start_month=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[1]);
        ev_time_start_day=parseInt(ev_time_start_var.split(" ", 2)[0].split("-")[2]);
        if(ev_time_start_day==1) {
            if(ev_time_start_month==1) {
                ev_time_start_day=31;
                ev_time_start_month=12;
                ev_time_start_year--;
            } else if(ev_time_start_month==3) {
                if(ev_time_start_year%4==0 && ev_time_start_year%100==0 && !(ev_time_start_year%400==0)) {
                    //leap year(윤년): https://en.wikipedia.org/wiki/Leap_year
                    ev_time_start_month=29;
                } else {
                    ev_time_start_day=28;   
                }
                ev_time_start_month--;
            } else if(ev_time_start_month==2 ||ev_time_start_month==4 || ev_time_start_month==6 || ev_time_start_month==8 ||ev_time_start_month==9 || ev_time_start_month==11) { //1 3 5 7 8 10 12
                ev_time_start_day=31;
                ev_time_start_month--;
            } else {
                ev_time_start_day=30;
                ev_time_start_month--;
            }
        } else {
            ev_time_start_day--;
        }
        ev_time_start_24=ev_time_start_year*10000+ev_time_start_month*100+ev_time_start_day;
        ev_time_start_24 = ev_time_start_24*1000000 + (parseInt(ev_time_start_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(ev_time_start_var.split(" ", 2)[1].split(":")[1])*100);
        if(ev_time_start_24<=today_date) {
            within_24=true;
        }
    } else {
        within_24=true;
    }
    if(within_24) {
        alert("You can only cancel events before 24 hours from event start time");
        //window.location="/yicrc/event_details.php?ev_code="+document.getElementById("ev_code_div").innerText;
        window.location="rc_events_2.php?ev_code="+document.getElementById("ev_code_div").innerText;
    }
    
    var ev_code_div = document.getElementById("ev_code_div").innerText;
    if(document.getElementById("sup_order_div")!=null && !within_24) {
        var sup_order_div = document.getElementById("sup_order_div").innerText;
        $.ajax({
            url: "cancel_write.php",
            type: "POST",
            data: {"ev_code":ev_code_div, "sup_order":sup_order_div},
            success: function(data) {
                alert(data);
            },
            error: function(e) {
                alert("There has been an error. Please try again later. ("+e.message+")");
            },
            complete: function() {
                //window.location="/yicrc/event_details.php?ev_code="+ev_code_div;
                window.location="rc_events_2.php?ev_code="+ev_code_div;
            }
        });
    }
}
</script>
</body>
</html>