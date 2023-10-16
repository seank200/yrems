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
	<title>Sign up - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background: white;">
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
<div id="loader" class="loader" style="display: none;"></div>
<div id="content_div" class="content_div">
    <div class="col-4 hide_mobile"></div>
    <div class="col-4">
        <h1 id="title_h1">Sign-up</h1>
        <div id="ev_info_div"></div>
        <table class="list details">
            <tr>
                <td colspan="2" id="ev_name_td">Event Name Here</td>
            </tr>
            <tr>
                <td>Date/Time</td>
                <td id="ev_time_td">-</td>
            </tr>
            <tr>
                <td>Place</td>
                <td id="ev_place_td">-</td>
            </tr>
            <tr>
                <td>Sign-up period</td>
                <td id="sup_time_td">-</td>
            </tr>
        </table>

        <div id="step_1">
            <h3>I have read and agreed with the “<a href="/yicrc/policy.php"><u>RC Events Sign-up/Cancel Policy.</u></a>”</h3>
            <button class="blue" onclick="agree_policy('agree')">YES</button><br />
            <button class="blue" style="width: 100%;" onclick="agree_policy('disagree')">NO</button>
        </div>
        <div id="step_2" style="display: none;">
            <label for="user_id">Student ID</label>
            <input type="text" id="user_id" name="user_id" placeholder="Enter Student ID"/>
            <button class="blue" style="width: 100%;" onclick="signup()">Sign up!</button><br />
            <div class="center" style="width: 100%;"><div class="textbutton" onclick="goback()">Cancel Sign-up</div></div>
        </div>
    </div>
    <div class="col-4 hide_mobile"></div> 
</div>
<div style="display: none;" id="ev_code_div"><?php if(isset($_GET['ev_code'])) { echo trim($_GET['ev_code']); } ?></div>
<div style="display: none;" id="user_id_div"><?php if(isset($_SESSION['user_id'])) { echo $_SESSION['user_id']; } ?></div>

<?php
if($_SERVER["REQUEST_METHOD"]=="GET") {
    if(isset($_GET['ev_code'])&&$_GET['ev_code']!="") {
        require_once 'config.php';
        get_evinfo();
        mysqli_close($link);
    } else {
        echo '<script type="text/javascript">document.getElementById("content_div").style.display="none"; alert("Error: event not specified."); window.location="index.php";</script>';
    }
} else {
    //echo '<div class="status redd">Event not specified. Please close this window and try again.</div>';
    echo '<script type="text/javascript">document.getElementById("content_div").style.display="none"; alert("Error: event not specified."); window.location="index.php";</script>';
}
function get_evinfo() {
    global $link;
    $sql = "SELECT ev_name, ev_time_start, ev_time_end, ev_place, sup_time_st, sup_time_end FROM yicrc_events WHERE ev_code = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        $ev_code_param=trim($_GET['ev_code']);
        mysqli_stmt_bind_param($stmt, "i", $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_name_db, $ev_time_start_db, $ev_time_end_db, $ev_place_db, $sup_time_st_db, $sup_time_end_db);
            while(mysqli_stmt_fetch($stmt)) {
                $ev_out=array();
                $ev_out['ev_name']=$ev_name_db;
                $ev_out['ev_time_start']=$ev_time_start_db;
                $ev_out['ev_time_end']=$ev_time_end_db;
                $ev_out['ev_place']=$ev_place_db;
                $ev_out['sup_time_st']=$sup_time_st_db;
                $ev_out['sup_time_end']=$sup_time_end_db;
            }
            echo '<div id="evinfo_get_div" style="display: none">';
            echo json_encode($ev_out);
            echo '</div>';
            mysqli_stmt_close($stmt);
        }
    }
}
?>
<script type="text/javascript">
function check_within_date() {
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
    sup_end_date = sup_end_date*1000000 + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[0])*10000) + (parseInt(sup_end_var.split(" ", 2)[1].split(":")[1])*100) + 59;

    if(today_date>=sup_st_date && sup_end_date>=today_date) {
        within_sup_date=true;
    }
    return within_sup_date;
}
if(!(check_within_date())) {
    alert("Not within sign-up period.");
    window.location="/yicrc/event_details.php?ev_code="+document.getElementById("ev_code_div").innerHTML;
}
    
    fill_evinfo();
function agree_policy(x) {
    if(x=="agree") {
        document.getElementById("step_1").style.display="none";
        document.getElementById("step_2").style.display="";
    } else {
        alert("You cannot sign up for RC events if you do not agree to the RC Events Sign-up/Cancel Policy.");
    }
}
function fill_evinfo() {
    if(document.getElementById("evinfo_get_div")!=null) {
        var info_div = document.getElementById("evinfo_get_div");
        var info = JSON.parse(info_div.innerText);
        document.getElementById("ev_name_td").innerHTML=info.ev_name;
        document.getElementById("ev_time_td").innerHTML=info.ev_time_start+" ~ "+info.ev_time_end;
        document.getElementById("ev_place_td").innerHTML=info.ev_place;
        document.getElementById("sup_time_td").innerHTML=info.sup_time_st+" ~ "+info.sup_time_end;
    } else {
        alert("event not specified.");
    }
}
function goback() {
	if(confirm("Cancel sign-up and return to event details?")) {
        window.location="rc_events_2.php?ev_code="+document.getElementById("ev_code_div").innerText;
    }
}
function signup() {
    if(document.getElementById("content_div").style.display!="none") {
        if(check_within_date()) {
            document.getElementById("content_div").style.display="none";
            document.getElementById("title_h1").innerHTML="Processing.. Please wait"
            document.getElementById("loader").style.display="inline-block";
            var id_db=document.getElementById("user_id_div").innerText;
            var id_input=document.getElementById("user_id").value;
            var ev_code=document.getElementById("ev_code_div").innerText;
            if(id_db==id_input) {
                if(ev_code!="") {
                    $.ajax({
                        url: '/yicrc/eng/signup_write.php',
                        type: "POST",
                        data: {"ev_code":ev_code},
                        success: function(data) {
                            alert(data);
                            if(ev_code!=null && ev_code!="") {
                                window.location="rc_events_2.php?ev_code="+ev_code;
                            } else {
                                window.location="/yicrc/index.php";
                            }
                        },
                        error: function(e) {
                            alert("Error: "+e.message);
                            document.getElementById("title_h1").innerHTML="Sign-up";
                            document.getElementById("content_div").style.display="";
                            document.getElementById("loader").style.display="none";
                            window.location="rc_events_2.php";
                        }
                    });
                } else {
                    alert("Event not specified. Please try again.");
                    window.location="/yicrc/rc_events.php";
                }
            } else {
                alert("You have entered the wrong student ID.");
                document.getElementById("title_h1").innerHTML="Sign-up";
                document.getElementById("content_div").style.display="";
                document.getElementById("loader").style.display="none";
                //alert("id_db: "+id_db+", id_input: "+id_input);
            }   
        } else {
            alert("Not within sign-up period.");
            window.location="rc_events_2.php?ev_code="+ev_code;
        }
    } else {
        alert("Sign-up in progress. Please wait.");
    }
}
function is_mobile_width() {
    if(window.innerWidth>768) {
        return false;
    } else {
        return true;
    }
}
</script>
</body>
</html>