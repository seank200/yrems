<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
if($_SESSION["user_type"]!="House RA" && $_SESSION["user_type"]!="Chief RA" && $_SESSION["user_type"]!="RM") {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Check Attendance - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"
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
<div class="loader" id="loader"></div>
<div class="content_div" id="content_div" style="display: none;">
	<div class="col-3 hide_mobile"></div>
    <div class="col-6">
        <h1>Check Attendance</h1>
        <div id="tod" class="status yellowd" style="color: black; text-align: center;">TIME OF DAY</div>
        <table class="list details" style="margin-bottom: 30px;">
            <tr>
                <td colspan="2" id="ev_name_td">Loading Event Details..</td>
            </tr>
            <tr>
                <td>Date/Time</td>
                <td id="ev_time_td"> </td>
            </tr>
            <tr>
                <td>Place</td>
                <td id="ev_place_td"> </td>
            </tr>
            <tr>
                <td>Attendance</td>
                <td style="color: #EE220C;" id="ev_att_td"> </td>
            </tr>
        </table>
        <div class="status gray" style="margin-bottom: 20px; font-size: 90%;" id="result_div">NC: Not checked, P: Present, L: Late, A: Absent</div>
        <h3>Student ID</h3>
        <input type="number" pattern="[0-9]*" id="user_id" name="user_id" onchange="input_change(this)" placeholder="Enter your student ID" style="margin-bottom: 30px; font-size: 110%;" onkeydown="if(event.keyCode==13) { change_att(); }"/>
        <button class="blue" style="width: 100%; margin-bottom: 150px" id="check_att_btn" onclick="change_att()">Check Attendance</button>
        
        <div id="att_sel_div">
            <div class="textd">Attendance: &nbsp;</div>
            <div class="sel_container" style="margin: 0;">
                <select id="att_sel" onchange="sel_change(this)">
                    <option value="1st attendance">1st attendance</option>
                    <option value="2nd attendance">2nd attendance</option>
                </select>
                <p>▼</p>
            </div> <br />
        </div>
        <br />
        <div class="textd">Change to: &nbsp;</div>
        <div class="sel_container" style="margin: 0;">
            <select id="att_value_sel">
                <option value="Present">Present</option>
                <option value="Late">Late</option>
                <option value="Absent">Absent</option>
                <option value="Not checked">Not checked</option>
            </select>
            <p>▼</p>
        </div> <br />

        <div id="search_optn_div" style="display: none">
            <div class="textd">Search by: &nbsp;</div>
            <div class="sel_container" style="margin: 0;">
                <select>
                    <option>Student ID</option>
                    <option>Mobile Phone #</option>
                </select>
                <p>▼</p>
            </div>
        </div>
    </div>
    <div class="col-3 hide_mobile"></div>
</div>
    <div style="display: none" id="user_type_div"><?php if(isset($_SESSION["user_type"])) { echo trim($_SESSION["user_type"]); } ?></div>
    <div style="display: none" id="ev_code_div"><?php if(isset($_GET["ev_code"])) { echo trim($_GET["ev_code"]); } ?></div>
<script type="text/javascript">
    function gei(x) {
        return document.getElementById(x);
    }
    var tod = setInterval(function() {update_time()}, 1000);
    function update_time() {
        var d = new Date();
        var hr=0;
        var min=0;
        var sec=0;
        if(d.getHours()<10) {
            hr="0"+d.getHours();
        } else {
            hr=d.getHours();
        }
        if(d.getMinutes()<10) {
            min="0"+d.getMinutes();
        } else {
            min = d.getMinutes();
        }
        if(d.getSeconds()<10) {
            sec="0"+d.getSeconds();
        } else {
            sec=d.getSeconds();
        }
        gei("tod").innerHTML="TIME OF DAY: "+hr+":"+min+":"+sec;
    }
    function sel_change(x) {
        switch(x.value) {
            case "1st attendance":
                gei("check_att_btn").innerHTML="Check 1st Attendance";
                break;
            case "2nd attendance":
                gei("check_att_btn").innerHTML="Check 2nd Attendance";
                break;
            default:
                gei("check_att_btn").innerHTML="Check Attendance";
                break;
        }
    }
    function input_change(x) {
        if(x.value=="") {
            gei("check_att_btn").className="disabled";
        } else {
            gei("check_att_btn").className="blue";
        }
    }
    get_ev();
    function get_ev() {
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        var ev_code = gei("ev_code_div").innerText;
        if(ev_code=="") {
            alert("Error: Event not specified.");
            window.location="/yicrc/rc_events.php";
        } else {
            $.ajax({
                url: "event_load.php",
                type: "POST",
                data: {"ev_code":ev_code},
                success: function(data) {
                    var ev=null;
                    try {
                        ev = JSON.parse(data);
                    } catch(e) {
                        alert(data);
                    }
                    if(ev!=null) {
                        if(ev.ev_att>=3 || ev.ev_att_enable==0) {
                            alert("Attendance feature is disabled for this event. Please check event settings.");
                            window.location="/yicrc/manage_event.php?ev_code="+gei("ev_code_div").innerText;
                        }
                        if(ev.ev_att==1) {
                            gei("att_sel_div").style.display="none";
                            gei("att_sel").value="1st attendance";
                            gei("check_att_btn").innerHTML="Check Attendance";
                            gei("ev_name_td").innerHTML=ev.ev_name;
                            gei("ev_time_td").innerHTML=ev.ev_time_start+" ~ "+ev.ev_time_end;
                            gei("ev_place_td").innerHTML=ev.ev_place;
                            switch(ev.ev_att) {
                                case 1:
                                    gei("ev_att_td").innerHTML="Check attendance once";
                                    break;
                                case 2:
                                    gei("ev_att_td").innerHTML="Check attendance twice";
                                    break;
                                case 3:
                                    gei("ev_att_td").innerHTML="Electronic roster (전자출결)";
                                    break;
                                case 4:
                                    gei("ev_att_td").innerHTML="Attendance not checked";
                                    break;
                                default:
                                    gei("ev_att_td").innerHTML="Error: "+ev.ev_att;
                                    break;
                            }
                        } else if(ev.ev_att==2) {
                            gei("att_sel").value="1st attendance";
                            sel_change(gei("att_sel"));
                            gei("att_sel_div").style.display="";
                            gei("ev_name_td").innerHTML=ev.ev_name;
                            gei("ev_time_td").innerHTML=ev.ev_time_start+" ~ "+ev.ev_time_end;
                            gei("ev_place_td").innerHTML=ev.ev_place;
                            switch(ev.ev_att) {
                                case 1:
                                    gei("ev_att_td").innerHTML="Check attendance once";
                                    break;
                                case 2:
                                    gei("ev_att_td").innerHTML="Check attendance twice";
                                    break;
                                case 3:
                                    gei("ev_att_td").innerHTML="Electronic roster (전자출결)";
                                    break;
                                case 4:
                                    gei("ev_att_td").innerHTML="Attendance not checked";
                                    break;
                                default:
                                    gei("ev_att_td").innerHTML="Error: "+ev.ev_att;
                                    break;
                            }
                        } else {
                            alert("Unable to load attendance feature for this event. Please try again later.");
                            window.location="/yicrc/manage_event.php?ev_code="+gei("ev_code_div").innerText;
                        }
                    }
                },
                error: function(e) {
                    alert("Unable to connect to server. Please try again later. ("+e.message+")");
                },
                complete: function() {
                    gei("loader").style.display="none";
                    gei("content_div").style.display="";
                }
            });   
        }
    }
    function change_att() {
        if(gei("user_id").value!="") {
            gei("loader").style.display="";
            gei("content_div").style.display="none";
            var att_value=null;
            var att_1="";
            var att_2="";
            switch(gei("att_value_sel").value) {
                case "Not checked":
                    att_value=0;
                    break;
                case "Present":
                    att_value=1;
                    break;
                case "Late":
                    att_value=2;
                    break;
                case "Absent":
                    att_value=3;
                    break;
            }
            if(gei("att_sel").value=="2nd attendance") {
                att_2=att_value;
            } else {
                att_1=att_value;
            }
            //alert(gei("att_sel").value);
            var user_id=gei("user_id").value;
            var ev_code=gei("ev_code_div").innerText;
            $.ajax({
                url: "attendance_write.php",
                type: "POST",
                data: {"ev_code":ev_code, "user_id":user_id, "att_1":att_1, "att_2":att_2},
                success: function(data) {
                    gei("result_div").innerHTML=data;
                    if(data.indexOf("SUCCESS")>=0) {
                        gei("result_div").className="status greend";
                    } else if(data.includes("ERROR")) {
                        gei("result_div").className="status redd";
                    } else {
                        gei("result_div").className="status gray";
                    }
                    gei("user_id").value="";
                }, 
                error: function(xhr, status, err) {
                    gei("result_div").className="status redd";
                    gei("result_div").innerHTML="[ERROR] Could not connect to server. Please try again later ("+status+": "+err+")";
                }, 
                complete: function() {
                    gei("loader").style.display="none";
                    gei("content_div").style.display="";
                    gei("user_id").focus();
                }
            });
        } else {
            gei("result_div").innerHTML="[ERROR] Please input student ID first.";
            gei("result_div").className="status redd";
            gei("user_id").focus();
        }
    }
</script>
</body>
</html>