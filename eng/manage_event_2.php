<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
check_admin();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage Event - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
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
<div id="loader" class="loader" style="display: none"></div>
<div id="content_div" class="content_div">
    <div class="col-3 hide_mobile"></div>
    <div class="col-6">
    	<h1 id="title_h1"><?php 
	        if(isset($_GET['ev_code'])) {
	            echo 'Edit Event';
	        } else {
	            echo 'Add New Event';
	        }
	        ?></h1>
    	<form id="event_form" action="event_write.php" method="post">
	        <table class="list details" style="text-align: left;">
	            <tr><td colspan="2" style="text-align: center;">Event Details</td></tr>
	            <tr>
	                <td id="ev_name_label">Event Name</td>
	                <td> <input id="ev_name_input" name="ev_name" type="text" class="small" placeholder="Enter name of event" /> </td>
	            </tr>
	            <tr>
	                <td id="ev_type_label">Event Type</td>
	                <td>
	                    <div class="sel_container" style="margin: 0;" onclick="document.getElementById('ev_type_select').focus();">
							<select id="ev_type_select" name="ev_type_select" onchange="sel_change(this)">
	                            <option value="House Event">House Event</option>
								<option value="RA Individual Event">RA Individual Event</option>
								<option value="RC Event">RC Event</option>
	                            <option value="Unspecified">Unspecified</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="ev_type_input_int" name="ev_type" />
	                </td>
	            </tr>
	            <tr id="ev_house_tr">
	                <td id="ev_house_label">House</td>
	                <td>
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('ev_house_select').focus();">
							<select id="ev_house_select" name="ev_house_select" onchange="sel_change(this)">
								<option></option> 
	                            <option value="0">Appenzeller</option>
	                            <option value="1">Evergreen</option>
	                            <option value="2">Wonchul</option>
	                            <option value="3">Underwood</option>
	                            <option value="4">Yun, Dong-joo</option>
	                            <option value="5">Muak</option>
	                            <option value="6">Chiwon</option>
	                            <option value="7">Baekyang</option>
	                            <option value="8">Cheongsong</option>
	                            <option value="9">Yongjae</option>
	                            <option value="10">Avison</option>
	                            <option value="11">Allen</option>
	                            <option value="12">Other</option>
							</select>
							<p>▼</p>
						</div><br />
	                </td>
	            </tr>
	            <tr>
	                <td id="ev_time_label" colspan="2" style="border-bottom: none;">Event Date/Time</td>
	            </tr>
	            <tr>
	                <td colspan="2" class="font_normal">
	                    Starts: <br />
	                    <div class="sel_container" style="margin: 0 0 20px 0;"><select id="ev_time_start_year" name="ev_time_start_year" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_start_month" name="ev_time_start_month" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_start_date" name="ev_time_start_date" onchange="date_range_start_change(this)"></select><p>▼</p></div><br />
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_start_hour" name="ev_time_start_hour" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_start_minute" name="ev_time_start_minute" onchange="date_range_start_change(this)"></select><p>▼</p></div><br />
	                    <br />Ends:<br />
	                    <div class="sel_container" style="margin: 0 0 20px 0;"><select id="ev_time_end_year" name="ev_time_end_year"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_end_month" name="ev_time_end_month"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_end_date" name="ev_time_end_date"></select><p>▼</p></div><br />
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_end_hour" name="ev_time_end_hour"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="ev_time_end_minute" name="ev_time_end_minute"></select><p>▼</p></div><br />
	                    <input type="hidden" id="ev_time_start_input" name="ev_time_start" />
	                    <input type="hidden" id="ev_time_end_input" name="ev_time_end" />
	                </td>
	            </tr>
	            <tr>
	                <td id="ev_place_label">Event Place</td>
	                <td> <input id="ev_place_input" name="ev_place" type="text" class="small" placeholder="Enter event venue" /> </td>
	            </tr>
	            <tr>
	                <td id="ev_points_label">RC Points</td>
	                <td> <input id="ev_points_input" name="ev_points" type="number" pattern="[0-9]*" class="small" placeholder="Points"/></td>
	            </tr>
	            <tr>
	                <td>Limit Capacity</td>
	                <td>
	                    <div class="sel_container" style="margin: 0;">
							<select id="ev_capacity_optn" name="ev_capacity_optn" onchange="sel_change(this)">
								<option value="YES">YES</option>
								<option value="NO">NO</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" name="ev_capacity_optn" id="ev_capacity_optn_input" />
	                </td>
	            </tr>
	            <tr id="ev_capacity_tr">
	                <td id="ev_capacity_label">Capacity<br />(Max. # of ppl)</td>
	                <td><input id="ev_capacity_input" name="ev_capacity" type="number" pattern="[0-9]*" class="small" placeholder="Capacity"/></td>
	            </tr>
	            <tr>
	                <td id="sup_method_label" colspan="2" style="border-bottom: none;">Sign-up method</td>
	            </tr>
	            <tr>
	                <td colspan="2" class="font_normal">
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('sup_method_select').click();">
							<select id="sup_method_select" name="sup_method_select" onchange="sel_change(this)">
								<option value="TBA">TBA</option>
	                            <option value="Online sign-up required">Online sign-up required</option>
	                            <option value="FCFS, Sign-up not required">FCFS, Sign-up not required</option>
	                            <option value="Sign-up not required">Sign-up not required</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" name="sup_method" id="sup_method_input" />
	                </td>
	            </tr>
	            <tr id="sup_time_tr_1" style="display: none;">
	                <td id="sup_time_label" colspan="2" style="border-bottom: none;">Sign-up period</td>
	            </tr>
	            <tr id="sup_time_tr_2" style="display: none;">
	                <td colspan="2" class="font_normal">
	                    Starts: <br />
	                    <div class="sel_container" style="margin: 0 0 20px 0;"><select id="sup_time_st_year" name="sup_time_st_year" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_st_month" name="sup_time_st_month" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_st_date" name="sup_time_st_date" onchange="date_range_start_change(this)"></select><p>▼</p></div><br />
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_st_hour" name="sup_time_st_hour" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_st_minute" name="sup_time_st_minute" onchange="date_range_start_change(this)"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_st_second" name="sup_time_st_second" onchange="date_range_start_change(this)"></select><p>▼</p></div><br />
	                    <br />Ends:<br />
	                    <div class="sel_container" style="margin: 0 0 20px 0;"><select id="sup_time_end_year" name="sup_time_end_year"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_end_month" name="sup_time_end_month"></select><p>▼</p></div>&nbsp;-&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_end_date" name="sup_time_end_date"></select><p>▼</p></div><br />
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_end_hour" name="sup_time_end_hour"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_end_minute" name="sup_time_end_minute"></select><p>▼</p></div>&nbsp;:&nbsp;
	                    <div class="sel_container" style="margin: 0;"><select id="sup_time_end_second" name="sup_time_end_second"></select><p>▼</p></div><br />
	                    <input type="hidden" id="sup_time_st_input" name="sup_time_st" />
	                    <input type="hidden" id="sup_time_end_input" name="sup_time_end" />
	                </td>
	            </tr>
	            <tr>
	                <td id="sup_participant_publicity_label" colspan="2" style="border-bottom: none;">Participant List Publicity</td>
	            </tr>
	            <tr>
	                <td colspan="2">
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('sup_participant_publicity_select').click();">
							<select id="sup_participant_publicity_select" name="sup_participant_publicity_select">
								<option value="Only RM/RAs">Only RM/RAs</option>
	                            <option value="RM/RAs + Participants">RM/RAs + Participants</option>
	                            <option value="RM/RAs + Participants + Waiting">RM/RAs + Participants + Waiting</option>
	                            <!--<option value="RM/RAs + All students">RM/RAs + All students</option>-->
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="sup_participant_publicity_input" name="sup_participant_publicity" />
	                </td>
	            </tr>
	            <tr>
	                <td>Enable<br />Waiting list</td>
	                <td>
	                    <div class="sel_container" style="margin: 0;">
							<select id="sup_waiting_optn_select" name="sup_waiting_optn_select" onchange="sel_change(this)">
								<option value="YES">YES</option>
								<option value="NO">NO</option>
							</select>
							<p>▼</p>
						</div><br />
	                </td>
	                <input type="hidden" id="sup_waiting_optn_input" name="sup_waiting_optn" />
	            </tr>
	            <tr id="sup_waiting_publicity_tr_1">
	                <td colspan="2" style="border-bottom: none;">Waiting List Publicity</td>
	            </tr>
	            <tr id="sup_waiting_publicity_tr_2">
	                <td colspan="2">
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('sup_waiting_publicity_select').click();">
							<select id="sup_waiting_publicity_select" name="sup_waiting_publicity_select">
								<option value="Only RM/RAs">Only RM/RAs</option>
	                            <option value="RM/RAs + Participants">RM/RAs + Participants</option>
	                            <option value="RM/RAs + Participants + Waiting">RM/RAs + Participants + Waiting</option>
	                            <!--<option value="RM/RAs + All students">RM/RAs + All students</option>-->
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="sup_waiting_publicity_input" name="sup_waiting_publicity" />
	                </td>
	            </tr>
	            <tr>
	                <td colspan="2" style="border-bottom: none;">Allow Cancellation</td>
	            </tr>
	            <tr>
	                <td colspan="2">
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('ev_cancel_optn_select').click();">
							<select id="ev_cancel_optn_select" name="ev_cancel_optn_select">
								<option value="Don't allow students to cancel">Don't allow students to cancel</option>
								<option value="Allow students to cancel">Allow students to cancel</option>
	                            <option value="Manual Approval">Manual Approval</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="ev_cancel_optn_input" name="ev_cancel_optn" />
	                </td>
	            </tr>
	            <tr>
	                <td colspan="2" style="border-bottom: none;">Attendance</td>
	            </tr>
	            <tr>
	                <td colspan="2" class="font_normal">
	                    <div class="sel_container" style="margin: 0; width: 100%;" onclick="document.getElementById('ev_att_select').click();">
							<select id="ev_att_select" name="ev_att_select" onchange="sel_change(this)">
	                            <option value="Do not check attendance">Do not check attendance</option>
								<option value="Check attendance once">Check attendance once</option>
								<option value="Check attendance twice">Check attendance twice</option>
	                            <option value="Electronic Roster(전자출결)">Electronic Roster(전자출결)</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="ev_att_input" name="ev_att" />
	                </td>
	            </tr>
	            <tr id="ev_att_enable_tr" style="display: none;">
	                <td>Enable<br />Attendance<br />Check Feature</td>
	                <td>
	                    <div class="sel_container" style="margin: 0;">
							<select id="ev_att_enable_select" name="ev_att_enable_select">
								<option value="YES">YES</option>
								<option value="NO">NO</option>
							</select>
							<p>▼</p>
						</div><br />
	                    <input type="hidden" id="ev_att_enable_input" name="ev_att_enable" />
	                </td>
	            </tr>
	            <tr>
	                <td>Event Manager</td>
	                <td id="ev_supvsr_td">
	                    <?php 
	                        /*
	                        if(isset($_SESSION['user_id']) && isset($_SESSION['user_eng_name_first']) && isset($_SESSION['user_eng_name_last'])) { 
	                            if(!isset($_GET['ev_code'])) {
	                                echo trim($_SESSION['user_eng_name_first']);
	                                echo " ";
	                                echo trim($_SESSION['user_eng_name_last']);
	                            }
	                        }*/
	                    ?>
                        <!--
	                    <input id="ev_supvsr_input" name="ev_supvsr" type="text" class="small" placeholder="Enter event manager" />
                        -->
                        <div class="sel_container" style="margin: 0;">
                            <select id="ev_supvsr_select" name="ev_supvsr">
                                <option disabled>Select House First</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                        <input type="hidden" id="ev_supvsr_ip">
	                </td>
	            </tr>
	            <tr id="ev_description_label">
	                <td colspan="2" style="border-bottom: none;">Event Description</td>
	            </tr>
	            <tr>
	                <td colspan="2"><textarea rows="8" class="small" id="ev_description_textarea" name="ev_description"></textarea></td>
	            </tr>
	        </table>
	        <input type="hidden" id="ev_code_input" name="ev_code" />
	        <input type="hidden" id="action_type_input" name="action_type" />
	    </form>
	    <button class="blue" style="width: 100%; margin-bottom: 20px;" onclick="submit_frm()">Save Changes</button>
	    <div class="center" style="width: 100%; margin-bottom: 50px;"><div class="textbutton" onclick="goback()">Discard Changes</div></div>
	    <div class="center" style="width: 100%;"><div class="textbutton" onclick="duplicate_event()">Duplicate Event</div></div>
    </div>
    <div class="col-3 hide_mobile"></div>
</div>
<div id="ev_code_div" style="display: none;"><?php if(isset($_GET['ev_code'])) { echo trim($_GET['ev_code']); } ?></div>
<div id="user_type_div" style="display: none;"><?php if(isset($_SESSION["user_type"])) { echo $_SESSION["user_type"]; } ?></div>
<div id="user_house_div" style="display: none;"><?php if(isset($_SESSION["user_house"])) { echo $_SESSION["user_house"]; } ?></div>
<script type="text/javascript">
    var today_date = new Date();
    add_years(document.getElementById("ev_time_start_year"));
    add_months(document.getElementById("ev_time_start_month"));
    add_dates(document.getElementById("ev_time_start_date"));
    add_hours(document.getElementById("ev_time_start_hour"));
    add_min_sec(document.getElementById("ev_time_start_minute"));
    document.getElementById("ev_time_start_year").value=today_date.getFullYear();
    if((today_date.getMonth()+1)<10) {
        document.getElementById("ev_time_start_month").value="0"+(today_date.getMonth()+1);
    } else {
        document.getElementById("ev_time_start_month").value=today_date.getMonth()+1;
    }
    if(today_date.getDate()<10) {
        document.getElementById("ev_time_start_date").value="0"+today_date.getDate();
    } else {
        document.getElementById("ev_time_start_date").value=today_date.getDate();   
    }
    
    add_years(document.getElementById("ev_time_end_year"));
    add_months(document.getElementById("ev_time_end_month"));
    add_dates(document.getElementById("ev_time_end_date"));
    add_hours(document.getElementById("ev_time_end_hour"));
    add_min_sec(document.getElementById("ev_time_end_minute"));
    document.getElementById("ev_time_end_year").value=today_date.getFullYear();
    if((today_date.getMonth()+1)<10) {
        document.getElementById("ev_time_end_month").value="0"+(today_date.getMonth()+1);
    } else {
        document.getElementById("ev_time_end_month").value=today_date.getMonth()+1;
    }
    if(today_date.getDate()<10) {
        document.getElementById("ev_time_end_date").value="0"+today_date.getDate();   
    } else {
        document.getElementById("ev_time_end_date").value=today_date.getDate();
    }

    add_years(document.getElementById("sup_time_st_year"));
    add_months(document.getElementById("sup_time_st_month"));
    add_dates(document.getElementById("sup_time_st_date"));
    add_hours(document.getElementById("sup_time_st_hour"));
    add_min_sec(document.getElementById("sup_time_st_minute"));
    add_min_sec(document.getElementById("sup_time_st_second"));
    document.getElementById("sup_time_st_year").value=today_date.getFullYear();
    if((today_date.getMonth()+1)<10) {
        document.getElementById("sup_time_st_month").value="0"+(today_date.getMonth()+1);
    } else {
        document.getElementById("sup_time_st_month").value=today_date.getMonth()+1;
    }
    if(today_date.getDate()<10) {
        document.getElementById("sup_time_st_date").value="0"+today_date.getDate();
    } else {
        document.getElementById("sup_time_st_date").value=today_date.getDate();
    }
    add_years(document.getElementById("sup_time_end_year"));
    add_months(document.getElementById("sup_time_end_month"));
    add_dates(document.getElementById("sup_time_end_date"));
    add_hours(document.getElementById("sup_time_end_hour"));
    add_min_sec(document.getElementById("sup_time_end_minute"));
    add_min_sec(document.getElementById("sup_time_end_second"));
    document.getElementById("sup_time_end_year").value=today_date.getFullYear();
    if((today_date.getMonth()+1)<10) {
        document.getElementById("sup_time_end_month").value="0"+(today_date.getMonth()+1);
    } else {
        document.getElementById("sup_time_end_month").value=today_date.getMonth()+1;
    }
    if(today_date.getDate()<10) {
        document.getElementById("sup_time_end_date").value="0"+today_date.getDate();
    } else {
        document.getElementById("sup_time_end_date").value=today_date.getDate();
    }
    function gei(x) {
        return document.getElementById(x);
    }
    
    if(gei("ev_code_div").innerText!="" && gei("title_h1").innerHTML!="Add New Event") {
        gei("content_div").style.display="none";
        gei("loader").style.display="";
        fill_evinfo();
    }
    
    function duplicate_event() {
        gei("ev_name_input").value="[COPY] "+gei("ev_name_input").value;
        submit_frm("add");
    }
    
    function date_range_start_change(x) {
        switch(x.id) {
            case "ev_time_start_year":
                if(x.value>gei("ev_time_end_year").value) {
                    gei("ev_time_end_year").value=x.value;   
                }
                break;
            case "ev_time_start_month":
                if(x.value>gei("ev_time_end_month").value) {
                    gei("ev_time_end_month").value=x.value;   
                }
                break;
            case "ev_time_start_date":
                if(x.value>gei("ev_time_end_date").value) {
                    gei("ev_time_end_date").value=x.value;   
                }
                break;
            case "ev_time_start_hour":
                if(x.value>gei("ev_time_end_hour").value) {
                    gei("ev_time_end_hour").value=x.value;   
                }
                break;
            case "ev_time_start_minute":
                if(x.value>gei("ev_time_end_minute").value) {
                    gei("ev_time_end_minute").value=x.value;   
                }
                break;
            case "sup_time_st_year":
                if(x.value>gei("sup_time_end_year").value) {
                    gei("sup_time_end_year").value=x.value;   
                }
                break;
            case "sup_time_st_month":
                if(x.value>gei("sup_time_end_month").value) {
                    gei("sup_time_end_month").value=x.value;   
                }
                break;
            case "sup_time_st_date":
                if(x.value>gei("sup_time_end_date").value) {
                    gei("sup_time_end_date").value=x.value;   
                }
                break;
            case "sup_time_st_hour":
                if(x.value>gei("sup_time_end_hour").value) {
                    gei("sup_time_end_hour").value=x.value;   
                }
                break;
            case "sup_time_st_minute":
                if(x.value>gei("sup_time_end_minute").value) {
                    gei("sup_time_end_minute").value=x.value;   
                }
                break;
            case "sup_time_st_second":
                if(x.value>gei("sup_time_end_second").value) {
                    gei("sup_time_end_second").value=x.value;   
                }
                break;
        }
    }
    
    function fill_evinfo() {
        if(gei("ev_code_div").innerText!="" && gei("title_h1").innerHTML!="Add New Event") {
            var ev_code = gei("ev_code_div").innerText;
            $.ajax({
                url: "/yicrc/eng/event_load.php", 
                type: "POST", 
                data: {"ev_code":ev_code}, 
                success: function(data) {
                    try {
                        var ev_data = JSON.parse(data);
                        gei("ev_code_input").value=ev_data.ev_code;
                        gei("ev_name_input").value=ev_data.ev_name;
                        
                        switch(ev_data.ev_type) {
                            case 1:
                                gei("ev_type_select").value="House Event";
                                gei("ev_house_tr").style.display="";
                                break;
                            case 2:
                                gei("ev_type_select").value="RA Individual Event";
                                gei("ev_house_tr").style.display="";
                                break;
                            case 3:
                                gei("ev_type_select").value="RC Event";
                                gei("ev_house_tr").style.display="none";
                                break;
                            case 4:
                                gei("ev_type_select").value="Unspecified";
                                gei("ev_house_tr").style.display="none";
                                break;
                            default:
                                gei("ev_type_select").value="";
                                break;
                        }
                        
                        gei("ev_house_select").value=ev_data.ev_house;
                        
                        gei("ev_time_start_year").value=ev_data.ev_time_start.split(" ", 2)[0].split("-")[0];
                        gei("ev_time_start_month").value=ev_data.ev_time_start.split(" ", 2)[0].split("-")[1];
                        gei("ev_time_start_date").value=ev_data.ev_time_start.split(" ", 2)[0].split("-")[2];
                        gei("ev_time_start_hour").value=ev_data.ev_time_start.split(" ", 2)[1].split(":")[0];
                        gei("ev_time_start_minute").value=ev_data.ev_time_start.split(" ", 2)[1].split(":")[1];
                        
                        gei("ev_time_end_year").value=ev_data.ev_time_end.split(" ", 2)[0].split("-")[0];
                        gei("ev_time_end_month").value=ev_data.ev_time_end.split(" ", 2)[0].split("-")[1];
                        gei("ev_time_end_date").value=ev_data.ev_time_end.split(" ", 2)[0].split("-")[2];
                        gei("ev_time_end_hour").value=ev_data.ev_time_end.split(" ", 2)[1].split(":")[0];
                        gei("ev_time_end_minute").value=ev_data.ev_time_end.split(" ", 2)[1].split(":")[1];
                        
                        gei("ev_place_input").value=ev_data.ev_place;
                        gei("ev_points_input").value=ev_data.ev_points;
                        
                        if(ev_data.ev_capacity_optn==1) {
                            gei("ev_capacity_optn").value="YES";
                            document.getElementById("ev_capacity_tr").style.display="";
                        } else if(ev_data.ev_capacity_optn==0) {
                            gei("ev_capacity_optn").value="NO";
                            document.getElementById("ev_capacity_tr").style.display="none";
                        } else {
                            gei("ev_capacity_optn").value=="";
                        }
                        
                        gei("ev_capacity_input").value=ev_data.ev_capacity;
                        switch(ev_data.sup_method) {
                            case 1:
                                gei("sup_method_select").value="TBA";
                                break;
                            case 2:
                                gei("sup_method_select").value="Online sign-up required";
                                document.getElementById("sup_time_tr_1").style.display="";
                                document.getElementById("sup_time_tr_2").style.display="";
                                break;
                            case 3:
                                gei("sup_method_select").value="FCFS, Sign-up not required";
                                break;
                            case 4:
                                gei("sup_method_select").value="Sign-up not required";
                                break;
                            default:
                                gei("sup_method_select").value="";
                                break;
                        }
                        
                        gei("sup_time_st_year").value=ev_data.sup_time_st.split(" ", 2)[0].split("-")[0];
                        gei("sup_time_st_month").value=ev_data.sup_time_st.split(" ", 2)[0].split("-")[1];
                        gei("sup_time_st_date").value=ev_data.sup_time_st.split(" ", 2)[0].split("-")[2];
                        gei("sup_time_st_hour").value=ev_data.sup_time_st.split(" ", 2)[1].split(":")[0];
                        gei("sup_time_st_minute").value=ev_data.sup_time_st.split(" ", 2)[1].split(":")[1];
                        gei("sup_time_st_second").value=ev_data.sup_time_st.split(" ", 2)[1].split(":")[2];
                        
                        gei("sup_time_end_year").value=ev_data.sup_time_end.split(" ", 2)[0].split("-")[0];
                        gei("sup_time_end_month").value=ev_data.sup_time_end.split(" ", 2)[0].split("-")[1];
                        gei("sup_time_end_date").value=ev_data.sup_time_end.split(" ", 2)[0].split("-")[2];
                        gei("sup_time_end_hour").value=ev_data.sup_time_end.split(" ", 2)[1].split(":")[0];
                        gei("sup_time_end_minute").value=ev_data.sup_time_end.split(" ", 2)[1].split(":")[1];
                        gei("sup_time_end_second").value=ev_data.sup_time_end.split(" ", 2)[1].split(":")[2];
                        
                        gei("sup_participant_publicity_select").value=ev_data.sup_participant_publicity;
                        switch(ev_data.sup_participant_publicity) {
                            case 1:
                                gei("sup_participant_publicity_select").value="Only RM/RAs";
                                break;
                            case 2:
                                gei("sup_participant_publicity_select").value="RM/RAs + Participants";
                                break;
                            case 3:
                                gei("sup_participant_publicity_select").value="RM/RAs + Participants + Waiting";
                                break;
                            case 4:
                                gei("sup_participant_publicity_select").value="RM/RAs + All students";
                                break;
                            default:
                                gei("sup_participant_publicity_select").value="";
                                break;
                        }
                        
                        if(ev_data.sup_waiting_optn==1) {
                            gei("sup_waiting_optn_select").value="YES";
                            gei("sup_waiting_publicity_tr_1").style.display="";
                            gei("sup_waiting_publicity_tr_2").style.display="";
                        } else if(ev_data.sup_waiting_optn==0) {
                            gei("sup_waiting_optn_select").value="NO";
                            gei("sup_waiting_publicity_tr_1").style.display="none";
                            gei("sup_waiting_publicity_tr_2").style.display="none";
                        } else {
                            gei("sup_waiting_optn_select").value="";
                            gei("sup_waiting_publicity_tr_1").style.display="none";
                            gei("sup_waiting_publicity_tr_2").style.display="none";
                        }
                        
                        switch(ev_data.sup_waiting_publicity) {
                            case 1:
                                gei("sup_waiting_publicity_select").value="Only RM/RAs";
                                break;
                            case 2:
                                gei("sup_waiting_publicity_select").value="RM/RAs + Participants";
                                break;
                            case 3:
                                gei("sup_waiting_publicity_select").value="RM/RAs + Participants + Waiting";
                                break;
                            case 4:
                                gei("sup_waiting_publicity_select").value="RM/RAs + All students";
                                break;
                            default:
                                gei("sup_waiting_publicity_select").value="";
                                break;
                        }
                        
                        switch(ev_data.ev_cancel_optn) {
                            case 1:
                                gei("ev_cancel_optn_select").value="Don't allow students to cancel";
                                break;
                            case 2:
                                gei("ev_cancel_optn_select").value="Allow students to cancel";
                                break;
                            case 3:
                                gei("ev_cancel_optn_select").value="Manual Approval";
                                break;
                        }
                        
                        switch(ev_data.ev_att) {
                            case 1:
                                gei("ev_att_select").value="Check attendance once";
                                gei("ev_att_enable_tr").style.display="";
                                break;
                            case 2:
                                gei("ev_att_select").value="Check attendance twice";
                                gei("ev_att_enable_tr").style.display="";
                                break;
                            case 3:
                                gei("ev_att_select").value="Electronic Roster(전자출결)";
                                gei("ev_att_enable_tr").style.display="none";
                                break;
                            case 4:
                                gei("ev_att_select").value="Do not check attendance";
                                gei("ev_att_enable_tr").style.display="none";
                                break;
                            default:
                                gei("ev_att_select").value="";
                                gei("ev_att_enable_tr").style.display="";
                                break;
                        }
                        
                        if(ev_data.ev_att_enable==1) {
                            gei("ev_att_enable_select").value="YES";
                        } else if(ev_data.ev_att_enable==0) {
                            gei("ev_att_enable_select").value="NO";
                        } else {
                            gei("ev_att_enable_select").value="";
                        }
                        
                        //gei("ev_supvsr_td").innerHTML=ev_data.ev_supvsr;
                        gei("ev_supvsr_ip").value=ev_data.ev_supvsr;
                        get_list_ra(ev_data.ev_house);
                        gei("ev_description_textarea").value=ev_data.ev_description;
                        
                    } catch(e) {
                        alert(data);
                    }
                }, 
                error: function(e) {
                    alert(e.message);
                }, 
                complete: function() {
                    gei("content_div").style.display="";
                    gei("loader").style.display="none";
                }
            });
        }
    }

    function sel_change(x) {
        //alert("sel_change: id>"+x.id+", value>"+x.value);
        switch(x.id) {
            case "ev_capacity_optn":
                if(x.value=="YES") {
                    document.getElementById("ev_capacity_tr").style.display="";
                } else if(x.value=="NO") {
                    document.getElementById("ev_capacity_tr").style.display="none";
                } else {
                    document.getElementById("ev_capacity_tr").style.display="none";
                }
                break;
            case "sup_method_select":
                if(x.value=="TBA"||x.value=="FCFS, Sign-up not required"||x.value=="Sign-up not required") {
                    document.getElementById("sup_time_tr_1").style.display="none";
                    document.getElementById("sup_time_tr_2").style.display="none";
                } else {
                    document.getElementById("sup_time_tr_1").style.display="";
                    document.getElementById("sup_time_tr_2").style.display="";
                }
                break;
            case "sup_waiting_optn_select":
                if(x.value=="YES") {
                    document.getElementById("sup_waiting_publicity_tr_1").style.display="";
                    document.getElementById("sup_waiting_publicity_tr_2").style.display="";
                } else if (x.value=="NO") {
                    document.getElementById("sup_waiting_publicity_tr_1").style.display="none";
                    document.getElementById("sup_waiting_publicity_tr_2").style.display="none";
                } else {
                    document.getElementById("sup_waiting_publicity_tr_1").style.display="none";
                    document.getElementById("sup_waiting_publicity_tr_2").style.display="none";
                }
                break;
            case "ev_att_select":
                if(x.value=="Check attendance once" || x.value=="Check attendance twice") {
                    document.getElementById("ev_att_enable_tr").style.display="";
                } else {
                    document.getElementById("ev_att_enable_tr").style.display="none";
                }
                break;
            case "ev_house_select":
                if(gei("user_house_div").innerHTML!="" && gei("user_type_div").innerHTML!="") {
                    if(x.value!=gei("user_house_div").innerHTML) {
                        if(x.value!="" && x.value!=12 && gei("user_type_div")!="RM") {
                            alert("You can only create events for your house. Only RMs can create events for other houses.");
                            x.value=gei("user_house_div").innerHTML;
                        }
                    }
                }
                $("#ev_supvsr_select").empty();
                if(x.value!="" && x.value!=null) {
                    get_list_ra(x.value);   
                }
                break;
            case "ev_type_select":
                if(x.value=="RC Event" || x.value=="Unspecified") {
                    gei("ev_house_tr").style.display="none";
                    gei("ev_house_select").value="12";
                } else {
                    gei("ev_house_tr").style.display="";
                    gei("ev_house_select").value="";
                }
                break;
        }
    }
    
    function get_list_ra(house_sel) {
        if(house_sel==12) {
            var option = document.createElement("option");
            option.text="Other";
            option.value="0";
            gei("ev_supvsr_select").add(option);
        } else {
            $.ajax({
                url: "/yicrc/eng/get_ra.php",
                type: "POST",
                data: {"house":house_sel},
                success: function(data) {
                    var ra = null;
                    try {
                        ra = JSON.parse(data);
                    } catch(e) {
                        if(data!="There are no RAs listed for this house.") {
                            window.location="/yicrc/index.php";   
                        }
                    }
                    //clear_ra_list();
                    $("#ev_supvsr_select").empty();
                    var option = document.createElement("option");
                    option.text="Select RA"; 
                    option.value="empty";
                    if(ra!=null) {
                        var i=0;
                        gei("ev_supvsr_select").add(option);
                        for(i=0; i<Object.keys(ra).length; i++) {
                            var option = document.createElement("option");
                            option.text=ra[i].name;
                            option.value=ra[i].user_id;
                            gei("ev_supvsr_select").add(option);
                        }
                    }
                    /*
                    var option = document.createElement("option");
                    option.text="Non-RC/RA/RM";
                    option.value="0";
                    gei("ev_supvsr_select").add(option);
                    */
                    //gei("load_ra_btn").style.display="none";
                    //gei("user_ra_select_div").style.display="";
                },
                error: function(e) {
                    alert("There has been an error. Please try again later. 2");
                    window.location="/yicrc/index.php";
                },
                complete: function() {
                    if(gei("ev_code_div").innerText!="" && gei("title_h1").innerHTML!="Add New Event") {
                        gei("ev_supvsr_select").value=gei("ev_supvsr_ip").value;
                    }
                }
            });
        }  
    }
    
    function goback() {
        if(confirm("Do you wish to leave this page? All unsaved changes will be lost.")) {
            if(window.opener==null) {
            	window.history.back();
            } else {
            	window.close();
            }
        }
    }
    function add_years(x) {
        var i=0;
        var s=0;
        var d = new Date();
        for(i=0;i<6;i++) {
            var option = document.createElement("option");
            s=d.getFullYear()+i-2;
            option.text=s;
            option.value=s;
            x.add(option);
        }
    }
    function add_months(x) {
        var i=0;
        var s=0;
        for(i=0;i<12;i++) {
            var option = document.createElement("option");
            s = i+1;
            if(s<10) {
                option.text="0"+s;
                option.value="0"+s;
            } else {
                option.text=s;
                option.value=s;
            }
            x.add(option);
        }
    }
    function add_dates(x) {
        var i=0;
        var s=0;
        for(i=0;i<31;i++) {
            var option = document.createElement("option");
            s = i+1;
            if(s<10) {
                option.text="0"+s;
                option.value="0"+s;
            } else {
                option.text=s;
                option.value=s;
            }
            x.add(option);
        }
    }
    function add_hours(x) {
        var i=0;
        for(i=0;i<24;i++) {
            var option = document.createElement("option");
            if(i<10) {
                option.text="0"+i;
                option.value="0"+i;
            } else {
                option.text=i;
                option.value=i;
            }
            x.add(option);
        }
    }
    function add_min_sec(x) {
        var i=0;
        for(i=0;i<60;i++) {
            var option = document.createElement("option");
            if(i<10) {
                option.text="0"+i;
                option.value="0"+i;
            } else {
                option.text=i;
                option.value=i;
            }
            x.add(option);
        }
    }
    function submit_frm(a_type) {
        //alert("submit_frm");
        var submit_frm=true;
        var err_msg="";
        
        if(gei("ev_name_input").value=="") {
            gei("ev_name_label").style.color="red";
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            gei("ev_name_label").style.color="black";
        }
        
        if(gei("ev_type_select").value=="") {
            gei("ev_type_label").style.color="red";
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            gei("ev_type_label").style.color="black";
            switch(gei("ev_type_select").value) {
                case "House Event":
                    gei("ev_type_input_int").value="1";
                    break;
                case "RA Individual Event":
                    gei("ev_type_input_int").value="2";
                    break;
                case "RC Event":
                    gei("ev_type_input_int").value="3";
                    break;
                case "Unspecified":
                    gei("ev_type_input_int").value="4";
                    break;
            }
            if(gei("ev_type_input_int").value=="") {
                gei("ev_type_label").style.color="red";
                err_msg="Error: event_type cannot be set";
                submit_frm=false;
            }
        }
        
        if(gei("ev_house_select").value=="") {
            submit_frm=false;
            err_msg="Please choose house.";
            gei("ev_house_label").style.color="red";
        } else {
            gei("ev_house_label").style.color="";
        }
        
        gei("ev_time_start_input").value = gei("ev_time_start_year").value + "-"+gei("ev_time_start_month").value + "-" + gei("ev_time_start_date").value + " " + gei("ev_time_start_hour").value + ":" + gei("ev_time_start_minute").value + ":00";
        
        gei("ev_time_end_input").value = gei("ev_time_end_year").value + "-"+gei("ev_time_end_month").value + "-" + gei("ev_time_end_date").value + " " + gei("ev_time_end_hour").value + ":" + gei("ev_time_end_minute").value + ":00";
        
        var ev_time_start_check = (parseInt(gei("ev_time_start_year").value)*10000 + parseInt(gei("ev_time_start_month").value)*100 + parseInt(gei("ev_time_start_date").value))*1000000 + parseInt(gei("ev_time_start_hour").value)*10000 + parseInt(gei("ev_time_start_minute").value)*100;
        
        var ev_time_end_check = (parseInt(gei("ev_time_end_year").value)*10000 + parseInt(gei("ev_time_end_month").value)*100 + parseInt(gei("ev_time_end_date").value))*1000000 + parseInt(gei("ev_time_end_hour").value)*10000 + parseInt(gei("ev_time_end_minute").value)*100;
        
        if(ev_time_end_check<=ev_time_start_check) {
            gei("ev_time_label").style.color="red";
            err_msg="Event start date/time must be earlier than event end date/time.";
            submit_frm=false;
        } else {
            gei("ev_time_label").style.color="black";
        }
        
        if(gei("ev_place_input").value=="") {
            gei("ev_place_label").style.color="red";
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            gei("ev_place_label").style.color="black";
        }
        
        if(gei("ev_points_input").value=="") {
            gei("ev_points_label").style.color="red";
            err_msg="All fields are required. Please enter '0' in the 'RC Points' field if you wish not to give any RC points to participants.";
            submit_frm=false;
        } else {
            gei("ev_points_label").style.color="black";
        }
        
        if(gei("ev_capacity_optn").value=="NO") {
            gei("ev_capacity_optn_input").value="0";
            gei("ev_capacity_input").value="0";
        } else if(gei("ev_capacity_optn").value=="YES") {
            gei("ev_capacity_optn_input").value="1";
            if(gei("ev_capacity_input").value=="") {
                gei("ev_capacity_label").style.color="red";
                err_msg="All fields are required.";
                submit_frm=false;
            } else if(gei("ev_capacity_input").value=="0") {
                err_msg="You cannot set the capacity to 0. If you wish to accept an unlimited number of participants, check the 'Limit Capacity' option to 'NO.'";
                submit_frm=false;
            } else {
                gei("ev_capacity_label").style.color="black";
            }
        }
        
        if(gei("sup_method_select").value=="") {
            err_msg="All fields are required.";
            submit_frm=false;
            gei("sup_method_label").style.color="red";
        } else {
            switch(gei("sup_method_select").value) {
                case "TBA":
                    gei("sup_method_input").value="1";
                    break;
                case "Online sign-up required":
                    gei("sup_method_input").value="2";
                    break;
                case "FCFS, Sign-up not required":
                    gei("sup_method_input").value="3";
                    break;
                case "Sign-up not required":
                    gei("sup_method_input").value="4";
                    break;
            }
            if(gei("sup_method_input")=="") {
                err_msg="Error: in setting sign-up method";
                submit_frm=false;
            }
        }
        
        gei("sup_time_st_input").value = gei("sup_time_st_year").value + "-"+gei("sup_time_st_month").value + "-" + gei("sup_time_st_date").value + " " + gei("sup_time_st_hour").value + ":" + gei("sup_time_st_minute").value + ":" + gei("sup_time_st_second").value;
        
        gei("sup_time_end_input").value = gei("sup_time_end_year").value + "-"+gei("sup_time_end_month").value + "-" + gei("sup_time_end_date").value + " " + gei("sup_time_end_hour").value + ":" + gei("sup_time_end_minute").value + ":" + gei("sup_time_end_second").value;
        
        var sup_time_st_check = (parseInt(gei("sup_time_st_year").value)*10000 + parseInt(gei("sup_time_st_month").value)*100 + parseInt(gei("sup_time_st_date").value))*1000000 + parseInt(gei("sup_time_st_hour").value)*10000 + parseInt(gei("sup_time_st_minute").value)*100 + parseInt(gei("sup_time_st_second"));
        
        var sup_time_end_check = (parseInt(gei("sup_time_end_year").value)*10000 + parseInt(gei("sup_time_end_month").value)*100 + parseInt(gei("sup_time_end_date").value))*1000000 + parseInt(gei("sup_time_end_hour").value)*10000 + parseInt(gei("sup_time_end_minute").value)*100 + parseInt(gei("sup_time_end_second"));
        
        if(sup_time_end_check<=sup_time_st_check) {
            gei("sup_time_label").style.color="red";
            err_msg="Sign up period start date/time must be earlier than end date/time.";
            submit_frm=false;
        } else {
            gei("sup_time_label").style.color="black";
        }
        
        if(gei("sup_participant_publicity_select").value=="") {
            gei("sup_participant_publicity_label").style.color="red";
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            gei("sup_participant_publicity_label").style.color="black";
            switch(gei("sup_participant_publicity_select").value) {
                case "Only RM/RAs":
                    gei("sup_participant_publicity_input").value="1";
                    break;
                case "RM/RAs + Participants":
                    gei("sup_participant_publicity_input").value="2";
                    break;
                case "RM/RAs + Participants + Waiting":
                    gei("sup_participant_publicity_input").value="3";
                    break;
                case "RM/RAs + All students":
                    gei("sup_participant_publicity_input").value="4";
                    break;
            }
            if(gei("sup_participant_publicity_input").value=="") {
                gei("sup_participant_publicity_label").style.color="red";
                err_msg="Error: setting participant publicity";
                submit_frm=false;
            } else {
                gei("sup_participant_publicity_label").style.color="black";
            }
        }
        
        if(gei("sup_waiting_optn_select").value=="NO") {
            gei("sup_waiting_optn_input").value="0";
            gei("sup_waiting_publicity_input").value="0";
        } else if(gei("sup_waiting_optn_select").value=="YES"){
            gei("sup_waiting_optn_input").value="1";
            switch(gei("sup_waiting_publicity_select").value) {
                case "Only RM/RAs":
                    gei("sup_waiting_publicity_input").value="1";
                    break;
                case "RM/RAs + Participants":
                    gei("sup_waiting_publicity_input").value="2";
                    break;
                case "RM/RAs + Participants + Waiting":
                    gei("sup_waiting_publicity_input").value="3";
                    break;
                case "RM/RAs + All students":
                    gei("sup_waiting_publicity_input").value="4";
                    break;
            }
            if(gei("sup_waiting_publicity_input").value=="") {
                err_msg="All fields are required.";
                gei("sup_waiting_publicity_tr_1").style.color="red";
                submit_frm=false;
            } else {
                gei("sup_waiting_publicity_tr_1").style.color="black";
            }
        } else {
            err_msg="Error: setting waiting list option";
            submit_frm=false;
        }
        
        if(gei("ev_cancel_optn_select").value=="") {
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            switch(gei("ev_cancel_optn_select").value) {
                case "Don't allow students to cancel":
                    gei("ev_cancel_optn_input").value="1";
                    break;
                case "Allow students to cancel":
                    gei("ev_cancel_optn_input").value="2";
                    break;
                case "Manual Approval":
                    gei("ev_cancel_optn_input").value="3";
                    break;
            }
            if(gei("ev_cancel_optn_input").value=="") {
                err_msg="Error: setting sign-up cancel option";
                submit_frm=false;
            }
        }
        
        if(gei("ev_att_select").value=="") {
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            switch(gei("ev_att_select").value) {
                case "Check attendance once":
                    gei("ev_att_input").value="1";
                    break;
                case "Check attendance twice":
                    gei("ev_att_input").value="2";
                    break;
                case "Electronic Roster(전자출결)":
                    gei("ev_att_input").value="3";
                    gei("ev_att_enable_select").value="NO";
                    break;
                case "Do not check attendance":
                    gei("ev_att_input").value="4";
                    gei("ev_att_enable_select").value="NO";
                    break;
            }
            if(gei("ev_att_input").value=="") {
                err_msg="Error: setting attendance option";
                submit_frm=false;
            }
        }
        
        if(gei("ev_att_enable_select")=="") {
            err_msg="All fields are required.";
            submit_frm=false;
        } else {
            if(gei("ev_att_enable_select").value=="YES") {
                gei("ev_att_enable_input").value="1";
            } else if(gei("ev_att_enable_select").value=="NO") {
                gei("ev_att_enable_input").value="0";
            } else {
                gei("ev_att_enable_input").value="";
            }
            if(gei("ev_att_enable_input").value=="") {
                err_msg="Error: setting attendance feature enable option";
                submit_frm=false;
            }
        }
        /*
        if(gei("ev_supvsr_td").innerText=="") {
            err_msg="Error: setting event manager";
            submit_frm=false;
        } else {
            gei("ev_supvsr_input").value=gei("ev_supvsr_td").innerText;
            if(gei("ev_supvsr_input").value=="") {
                err_msg="Error: setting event manager input";
                submit_frm=false;
            }
        }
        */
        if(gei("ev_supvsr_select").value=="") {
            err_msg="Error: Event Manager is empty";
            submit_frm=false;
        }
        
        if(gei("ev_description_textarea").value=="") {
            if(confirm("You have not entered a description for this event. Do you wish to leave this field empty?")) {
                gei("ev_description_label").style.color="black";
            } else {
                gei("ev_description_label").style.color="red";
                err_msg="Enter event description.";
                submit_frm=false;
            } 
        } else {
            gei("ev_description_label").style.color="black";
        }
        
        if(a_type === undefined) {
            if(document.getElementById("title_h1").innerText=="Add New Event") {
                document.getElementById("ev_code_input").value="";
                document.getElementById("action_type_input").value="add";
            } else {
                document.getElementById("ev_code_input").value=document.getElementById("ev_code_div").innerText;
                document.getElementById("action_type_input").value="edit";
            }
        } else {
            document.getElementById("ev_code_input").value="";
            document.getElementById("action_type_input").value=a_type;
        }
        
        if(submit_frm) {
            if(confirm("Do you wish to save changes?")) {
                //alert("Form validation success");
                //gei("event_form").submit();
                gei("loader").style.display="";
                gei("content_div").style.display="none";
                $.ajax({
                    url: "event_write.php",
                    type: "POST",
                    data: {"ev_name":gei("ev_name_input").value, "ev_type":gei("ev_type_input_int").value, "ev_time_start":gei("ev_time_start_input").value, "ev_time_end":gei("ev_time_end_input").value, "ev_place":gei("ev_place_input").value, "ev_points":gei("ev_points_input").value, "ev_capacity_optn":gei("ev_capacity_optn_input").value, "ev_capacity":gei("ev_capacity_input").value, "sup_method":gei("sup_method_input").value, "sup_time_st":gei("sup_time_st_input").value, "sup_time_end":gei("sup_time_end_input").value, "sup_participant_publicity":gei("sup_participant_publicity_input").value, "sup_waiting_optn":gei("sup_waiting_optn_input").value, "sup_waiting_publicity":gei("sup_waiting_publicity_input").value, "ev_cancel_optn":gei("ev_cancel_optn_input").value, "ev_att":gei("ev_att_input").value, "ev_att_enable":gei("ev_att_enable_input").value, "ev_supvsr":gei("ev_supvsr_select").value, "ev_description":gei("ev_description_textarea").value, "ev_house":gei("ev_house_select").value, "ev_code":gei("ev_code_input").value, "action_type":gei("action_type_input").value},
                    success: function(data) {
                        alert(data);
                    },
                    error: function(e) {
                        alert("There has been an error. Please try again later.")
                    },
                    complete: function() {
                        gei("loader").style.display="none";
                        gei("content_div").style.display="";
                        if(window.opener==null) {
                            window.location='rc_events_2.php';
                        } else {
                            window.opener.location='rc_events_2.php'; 
                            window.close();   
                        }
                    }
                });
            }
        } else {
            alert(err_msg);
        }

    }
</script>
</body>
</html>