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
	<title>Manage Participants - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
	<script type="text/javascript" src="base.js"></script>
    <script src="/yicrc/clipboard_js/dist/clipboard.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english_2.css">
    <link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body style="background: white;">
    <div class="loader" id="loader"></div>
    <div class="header">
        <div class="header_content">
            <img id="menu_show" src="/yicrc/img/menu.png" class="menu" alt="MENU" onclick="toggle_menu()" />
            <img id="menu_close" src="/yicrc/img/menu_close.png" class="menu_close" alt="MENU" onclick="toggle_menu()" />
            <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" onclick="logo_click_new()"/>
            <ul id="menu_list" class="header">
            </ul>
        </div>
    </div>
    <?php
        require_once 'config_2.php';
        load_menu();
    ?>
    <div id="content_div" class="content_div">
        <div class="col-8" style="text-align: left;">
            <!--
            <div id="new_feature" class="status greend" style="font-weight: normal; line-height: 1.3; font-size: 110%; position: relative; top: 0px; left: 0px;">
                <span style="font-weight: bold; margin: auto;">New Features</span><br />
                - You can now click on a student's ID to view student account information.<br />
                - Filter participants by their attendance using the dropdown menu next to the search field
                <span onclick="document.getElementById('new_feature').style.display='none';" style="cursor: pointer; font-weight: bold; padding: 3px; margin: 3px; position: absolute; right: 5px; top: 5px;">X</span>
            </div>
            -->
            <h2>Event Information</h2>
            <table class="user w10">
                <tr><td>Field</td><td>Data</td></tr>
                <tr>
                    <td>Event Name</td>
                    <td id="ev_name_td">Loading..</td>
                </tr>
                <tr id="ev_present_num_tr">
                    <td>Participants<br />(Present)</td>
                    <td id="ev_present_num_td">Loading..</td>
                </tr>
                <tr>
                    <td>Participants<br />(Total)</td>
                    <td id="ev_part_num_td">Loading..</td>
                </tr>
            </table>
            <h2><br />Participants</h2>
            <p>
                Last updated: <span id="last_updated_span"></span>&nbsp;&nbsp;&nbsp;&nbsp;
                <span id="selected_num_span" style="color: blue;"></span>
            </p>
            <div id="status_msg_div" class="status" style="display: none; position: fixed; bottom: 0; left:0; padding: 20px; font-size: 120%;"></div>
            <div id="filter_div" style="display: inline-block;">
                <select id="filter_part_select" style="display: inline-block;" onchange="filter_part(this.value)">
                    <option value="all">All participants</option>
                    <option value="present">Present</option>
                    <option value="no_show">Not present</option>
                </select>
            </div>
            
            <input class="small" id="search_input" style="width: auto;" placeholder="Search Participants.." onkeydown="if(event.keyCode==13) { search_part(); }" />
            <button class="blue small" onclick="search_part()">Search</button>
            <button id="clear_search_btn" class="red small" style="display: none;" onclick="clear_search()">Clear Search</button>
            <div id="show_cancelled_div" style="display: inline-block;"><input id="show_cancelled_chk" type="checkbox" style="display: inline-block; width: auto;" onchange="show_cancelled(this)" checked> <b><div id="show_cancelled_label" style="display: inline-block;">Show cancelled participants</div></b></div>
            
            <div id="ev_part_div"></div>
            <button class="blue_border small" style="margin-top: 10px; width: auto; display: inline-block;" onclick="add_part()">Add participant</button>
            <button class="blue_border small" style="margin-top: 10px; width: auto; display: inline-block;" onclick="edit_part_click(this)">Edit</button>
            <h2 id="ev_wait_title"><br />Waiting List</h2>
            <div id="ev_wait_div"></div>
            <h2><br />How to use</h2>
            <p style="line-height: 1.3">
                <b>1. Copying attendance data</b><br />
                1) Click the "Copy attendance data" button.<br />
                2) Paste(ctrl+v) that in to the first cell of an empty excel file.<br />
                3) Save that excel file as an ".xls" format, and upload that on the yicrc.yonsei.ac.kr website.<br />
                <span style="color: red;">* Only the selected students will be copied.</span><br /><br />
                <b>2. Uploading Points</b><br />
                1) Open the point management window in yicrc.yonsei.ac.kr by clicking on the number of students (You must create the RC program first in yicrc.yonsei.ac.kr).<br />
                2) Copy the link of that window and paste it into the text field, and click the "Get uid" button.<br />
                3) After you see the green words "SUCCESS", buttons to upload the points will appear on the participant list table. Click on that button to upload points to that student.<br />
                <span style="color: red;">* [IMPORTANT]<br />When nothing happens when you click "upload", DO NOT KEEP CLICKING, but login to the portal once again by clicking on the "portal login" button, and THEN try again.</span><br /><br />
            </p>
        </div>
        <div class="col-4" style="text-align: left;">
            <h2>Menu</h2>
            <button id="chk_att_btn" class="blue" onclick='window.open("check_attendance_2.php?ev_code="+document.getElementById("ev_code_div").innerText, "Check Attendance", "toolbar=no,menubar=no,width=400px,height=700px,resizable=yes");'>Check attendance</button>
            <button id="copy_all_btn" class="blue_border">Copy attendance data (all)</button>
            <button id="copy_sel_btn" class="blue_border">Copy attendance data (selected)</button>
            <!--<input type="checkbox" id="copy_only_id" checked> Only copy student ID -->
            <button id="sel_present_btn" class="blue_border" onclick="select_students('1')">Select students who were present</button>
            <h2><br />Student RC Activity</h2>
            <p id="sel_user_p">Click "View.." button to see the list of RC Activities a student participated in.</p>
            <div id="user_activity_div"></div>
            <h2><br />Upload Points</h2>
            <p style="color: red;">READ "HOW TO USE" BEFORE USING</p>
            <div id="upload_points_div" style="">
                <input type="text" id="url_paste" placeholder="Paste URL here">
                <button class="blue" style="width: auto;padding: 5px;" onclick="get_uid()">Get uid</button>
                <button id="portal_login_btn" class="blue" style="width: auto;padding: 5px; display: none;" onclick="document.getElementById('login').style.display='';">Portal Login</button>
                <p id="uid_p" style="color: blue;display: inline;"></p>
                <br /><br />
                <form name="ffmod" target="iframe_up" method="post" action="https://yicrc.yonsei.ac.kr/rc_popform.asp?mid=m02_04&aact=pointumng&lang=k&mSem=201810" style="display: none">
                    <input type="hidden" id="aactt" name="aactt" value="aok">
                    <div>uid</div>
                    <input type="text" id="uid" name="uid" value="">
                    <input type="hidden" id="page" name="page" value="1">
                    <div>Student ID</div>
                    <input type="text" id="mHakbun" name="mHakbun" value="">
                </form>	
                <iframe id="iframe_up" style="width: 100%; border: 1px solid gray; padding: 0; margin: 5px 0 0 0;"></iframe>
                <iframe id="login" src="https://yicrc.yonsei.ac.kr/rc_auth_sender.asp?mid=m02_04&lang=k" style="width: 100%; border: none; padding: 0; margin: 5px 0 0 0;height: 250px; display:none;"></iframe>
            </div>
        </div>
        <textarea rows="3" id="part_out_text" style="border: none; color: white;"></textarea>
    </div>
    <div style="display: none;" id="ev_code_div"><?php if(isset($_GET['ev_code'])) { echo trim($_GET['ev_code']); } ?></div>
    <input type="hidden" id="ev_att_ip" name="ev_att">
    <input type="hidden" id="ev_att_enable_ip">
    <input type="hidden" id="ev_capacity_optn_ip">
    <input type="hidden" id="ev_capacity_ip">
    <input type="hidden" id="sup_waiting_optn_ip">
    <script>
        function gei(x) {
            return document.getElementById(x);
        }
        function clear_search() {
	        gei("loader").style.display="";
	        gei("content_div").style.display="none";
	        if(gei("ev_part_table")!=null) {
	            var ev_part_table = gei("ev_part_table").children[0].children;   
	        }
            if(gei("ev_wait_table")!=null) {
	            var ev_wait_table = gei("ev_wait_table").children[0].children;   
	        }
	        gei("search_input").value="";
	        gei("clear_search_btn").style.display="none";
	        if(gei("ev_part_table")!=null) {
	            if(gei("show_cancelled_chk").checked) {
                    for(i=0;i<ev_part_table.length;i++) {
                        ev_part_table[i].style.display="";
                    }
                } else {
                    for(i=0;i<ev_part_table.length;i++) {
                        if(ev_part_table[i].className.indexOf("ev_cancelled_tr")<0) {
                            ev_part_table[i].style.display="";
                        } else {
                            ev_part_table[i].style.display="none";
                        }
                    }
                }  
	        }
            if(gei("ev_wait_table")!=null) {
	            if(gei("show_cancelled_chk").checked) {
                    for(i=0;i<ev_wait_table.length;i++) {
                        ev_wait_table[i].style.display="";
                    }
                } else {
                    for(i=0;i<ev_wait_table.length;i++) {
                       if(ev_wait_table[i].className.indexOf("ev_cancelled_tr")<0) {
                           ev_wait_table[i].style.display="";
                       } else {
                           ev_part_table[i].style.display="none";
                       }
                    }   
                }
	        }
            gei("show_cancelled_div").style.display="inline";
	        gei("loader").style.display="none";
	        gei("content_div").style.display="";
	    }
	    function search_part() {
	        gei("loader").style.display="";
	        gei("content_div").style.display="none";
            gei("show_cancelled_div").style.display="none";
	        var query = gei("search_input").value;
	        if(gei("ev_part_table")!=null) {
	            var ev_part_table = gei("ev_part_table").children[0].children;   
	        }
            if(gei("ev_wait_table")!=null) {
                var ev_wait_table = gei("ev_wait_table").children[0].children;
            }
	        var i=0;
	        var j=0;
	        var show=false;
	        if(query=="") {
	            clear_search();
	        } else {
	            if(gei("ev_part_table")!=null) {
	                for(i=2;i<ev_part_table.length;i++) {
	                    show=false;
	                    for(j=0; j<ev_part_table[i].children.length;j++) {
	                        if(ev_part_table[i].children[j].innerHTML.toUpperCase().includes(query.toUpperCase())) {
	                            show=true;
	                        }
	                    }
	                    if(show) {
	                        ev_part_table[i].style.display="";
	                    } else {
	                        if(i>0) {
	                            ev_part_table[i].style.display="none";
	                        }   
	                    }
	                }
	            }
                if(gei("ev_wait_table")!=null) {
	                for(i=1;i<ev_wait_table.length;i++) {
	                    show=false;
	                    for(j=0; j<ev_wait_table[i].children.length;j++) {
	                        if(ev_wait_table[i].children[j].innerHTML.toUpperCase().includes(query.toUpperCase())) {
	                            show=true;
	                        }
	                    }
	                    if(show) {
	                        ev_wait_table[i].style.display="";
	                    } else {
	                        if(i>0) {
	                            ev_wait_table[i].style.display="none";
	                        }   
	                    }
	                }
	            }
	            gei("clear_search_btn").style.display="";
	        }
	        gei("loader").style.display="none";
	        gei("content_div").style.display="";
	    }
        get_ev();
        function status_msg(msg, clname) {
            var div=document.getElementById("status_msg_div");
            if(msg===undefined) {
                div.style.display="none";
            } else {
                if(msg=="" || msg==null) {
                    div.style.display="none";
                } else {
                    div.innerHTML=msg;
                    div.className=clname;
                    div.style.display="";
                }
            }
            var t=setTimeout(function() {document.getElementById("status_msg_div").style.display="none";},5000);
        }
        function show_cancelled(x) {
            if(x.checked==true) {
                if(document.getElementsByClassName("ev_cancelled_tr")!=null) {
                    var cancelled = document.getElementsByClassName("ev_cancelled_tr");   
                    if(cancelled!=null) {
                        for(var i=0; i<cancelled.length; i++) {
                            cancelled[i].style.display="";
                        }
                    }
                }
            } else {
                if(document.getElementsByClassName("ev_cancelled_tr")!=null) {
                    var cancelled = document.getElementsByClassName("ev_cancelled_tr");   
                    if(cancelled!=null) {
                        for(var i=0; i<cancelled.length; i++) {
                            cancelled[i].style.display="none";
                        }
                    }
                }
            }
        }
        function get_ev() {
            gei("loader").style.display="";
            var ev_code = gei("ev_code_div").innerText;
            if(ev_code && ev_code!="") {
                $.ajax({
                    url: "event_load.php",
                    type: "POST",
                    data: {"ev_code":ev_code},
                    success: function(data) {
                        var ev=null;
                        try {
                            ev=JSON.parse(data);
                        } catch(e) {
                            alert(data);
                        }
                        if(ev!=null) {
                            gei("ev_name_td").innerHTML=ev.ev_name;
                            if(ev.ev_att_enable==1) {
                                gei("ev_present_num_tr").style.display="";
                                gei("sel_present_btn").style.display="";
                            } else {
                                gei("ev_present_num_tr").style.display="none";
                                gei("sel_present_btn").style.display="none";
                            }
                            gei("ev_att_ip").value=ev.ev_att;
                            gei("ev_att_enable_ip").value=ev.ev_att_enable;
                            gei("ev_capacity_optn_ip").value=ev.ev_capacity_optn;
                            gei("ev_capacity_ip").value=ev.ev_capacity;
                            gei("sup_waiting_optn_ip").value=ev.sup_waiting_optn;
                        }
                        get_part();
                    },
                    error: function(x,a,b) {
                        alert("Could not retrieve event information from server. Please try again later.");
                    }
                });
            }
        }
        function get_part() {
            var ev_code = gei("ev_code_div").innerText;
            if(ev_code && ev_code!="") {
                gei("loader").style.display="";
                $.ajax({
                    url: "attendance_load_admin_2.php",
                    type:"POST",
                    data: {"ev_code":ev_code},
                    success: function(data) {
                        var part=null;
                        try{
                            part=JSON.parse(data);
                        } catch(e) {
                            alert(data);
                        }
                        var part_out="";
                        part_out='<table id="ev_part_table" class="user w10"><tr><td rowspan="2"><input type="checkbox" onchange="check_all(this.checked)"></td><td rowspan="2">#</td><td rowspan="2">ID</td><td rowspan="2">Name</td><td colspan="2" style="border-bottom: none;" class="td_grp_1">Attendance</td><td class="td_grp_1" rowspan="2">Other<br />Activities</td><td class="td_grp_1" rowspan="2">Upload<br />Points</td><td class="td_grp_2" rowspan="2" style="min-width: 140px;">Edit</td></tr><tr><td style="border-bottom: 2px solid #0E69B1;" class="td_grp_1">1st</td><td style="border-bottom: 2px solid #0E69B1;" class="td_grp_1">2nd</td></tr>';
                        var wait_out="";
                        wait_out='<table id="ev_wait_table" class="user w10"><tr><td>#</td><td>ID</td><td>Name</td><td class="td_grp_1">Other<br />Activities</td><td class="td_grp_2" style="min-width: 140px;">Edit</td></tr>'; //# ID, Name, Other Activities, Edit
                        var ev_capacity_var=0;
                        var part_count=0;
                        var part_present_count=0;
                        var wait_count=0;
                        var cancel_count=0;
                        var att_text=["NC","P","L","A"];
                        if(part!=null) {
                            if(Object.keys(part).length>0) {
                                if(gei("ev_capacity_optn_ip").value==1) {
                                    ev_capacity_var=gei("ev_capacity_ip").value;
                                } else {
                                    ev_capacity_var=0;
                                }
                                if(gei("ev_att_enable_ip").value==1) {
                                    if(gei("ev_att_ip").value==1) { //check attendance once
                                        for(var i=0;i<Object.keys(part).length;i++) {
                                            if(ev_capacity_var==0 || part_count<ev_capacity_var) {
                                                if(part[i].user_status<2) {
                                                    part_count++;
                                                    part_out+='<tr>';
                                                    if(part[i].att_1==1) {
                                                        part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')" checked></td>';
                                                        part_present_count++;
                                                    } else {
                                                        part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')"></td>';
                                                    }
                                                    part_out+='<td>'+part_count+'</td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';   
                                                    } else {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    }
                                                    //part_out+='<td>'+att_text[part[i].att_1]+'</td>';
                                                    part_out+='<td class="td_grp_1"><span style="display: none;">'+part[i].att_1+'</span><select onchange="att_sel_change(this, 1, '+part[i].user_id+')">';
                                                    switch(part[i].att_1) {
                                                        case 0: 
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 1:
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 2:
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 3:
                                                            part_out += '<option value="3">A</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            break;
                                                    }
                                                    part_out += '</select></td>';
                                                    part_out+='<td class="td_grp_1">-</td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"><span class="upload_points_span" onclick="upload_points('+part[i].user_id+')">UPLOAD</span></td>';
                                                    part_out+='<td class="td_grp_2">';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    } else {
                                                        part_out+='<button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    }
                                                    part_out += '</td>';
                                                    part_out+='</tr>';   
                                                } else { // status:2 (=cancelled)
                                                    cancel_count++;
                                                    part_out+='<tr class="ev_cancelled_tr">';
                                                    part_out+='<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    part_out+='<td></td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    part_out += '<td colspan="2" class="td_grp_1"><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"></td>';
                                                    part_out+='<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    part_out+='</tr>';
                                                }
                                            } else { //over capacity -> waiting list
                                                //#, ID, Name, Other Activities, Edit
                                                if(part[i].user_status==0) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: #F8BA00;"><b>WAITING</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                } else if(part[i].user_status==1) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button></td>';
                                                    wait_out += '</tr>';
                                                } else {
                                                    cancel_count++;
                                                    wait_out += '<tr class="ev_cancelled_tr">';
                                                    wait_out += '<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                }
                                            }
                                        }
                                    } else if(gei("ev_att_ip").value==2) { //check attendance twice
                                        for(var i=0;i<Object.keys(part).length;i++) {
                                            if(ev_capacity_var==0 || part_count<ev_capacity_var) {
                                                if(part[i].user_status<2) {
                                                    part_count++;
                                                    part_out+='<tr>';
                                                    if(part[i].att_1==1 && part[i].att_2==1) {
                                                        part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')" checked></td>';
                                                        part_present_count++;
                                                    } else {
                                                        part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')"></td>';
                                                    }
                                                    part_out+='<td>'+part_count+'</td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';   
                                                    } else {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    }
                                                    //part_out+='<td>'+att_text[part[i].att_1]+'</td>';
                                                    part_out+='<td class="td_grp_1"><span style="display: none;">'+part[i].att_1+'</span><select onchange="att_sel_change(this, 1, '+part[i].user_id+')">';
                                                    switch(part[i].att_1) {
                                                        case 0: 
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 1:
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 2:
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 3:
                                                            part_out += '<option value="3">A</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            break;
                                                    }
                                                    part_out += '</select></td>';
                                                    //part_out+='<td>'+att_text[part[i].att_2]+'</td>';
                                                    part_out+='<td class="td_grp_1"><span style="display: none;">'+part[i].att_2+'</span><select onchange="att_sel_change(this, 2, '+part[i].user_id+')">';
                                                    switch(part[i].att_2) {
                                                        case 0: 
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 1:
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 2:
                                                            part_out += '<option value="2">L</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="3">A</option>';
                                                            break;
                                                        case 3:
                                                            part_out += '<option value="3">A</option>';
                                                            part_out += '<option value="0">NC</option>';
                                                            part_out += '<option value="1">P</option>';
                                                            part_out += '<option value="2">L</option>';
                                                            break;
                                                    }
                                                    part_out += '</select></td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"><span class="upload_points_span" onclick="upload_points('+part[i].user_id+')">UPLOAD</span></td>';
                                                    part_out+='<td class="td_grp_2">';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    } else {
                                                        part_out+='<button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    }
                                                    part_out += '</td>';
                                                    part_out+='</tr>';   
                                                } else { // status:2 (=cancelled)
                                                    cancel_count++;
                                                    part_out+='<tr class="ev_cancelled_tr">';
                                                    part_out+='<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    part_out+='<td></td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    part_out += '<td colspan="2" class="td_grp_1"><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"></td>';
                                                    part_out+='<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    part_out+='</tr>';
                                                }
                                            } else { // over capacity -> waiting list
                                                if(part[i].user_status==0) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: #F8BA00;"><b>WAITING</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                } else if(part[i].user_status==1) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button></td>';
                                                    wait_out += '</tr>';
                                                } else {
                                                    cancel_count++;
                                                    wait_out += '<tr class="ev_cancelled_tr">';
                                                    wait_out += '<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                }
                                            }
                                        }
                                    } else { //ev_att_ip is neither 1 nor 2
                                        for(var i=0;i<Object.keys(part).length;i++) {
                                            if(ev_capacity_var==0 || part_count<ev_capacity_var) {
                                                if(part[i].user_status<2) {
                                                    part_count++;
                                                    part_out+='<tr>';
                                                    part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')" checked></td>';
                                                    part_out+='<td>'+part_count+'</td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';   
                                                    } else {
                                                        part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    }
                                                    part_out+='<td class="td_grp_1">-</td>';
                                                    part_out+='<td class="td_grp_1">-</td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"><span class="upload_points_span" onclick="upload_points('+part[i].user_id+')">UPLOAD</span></td>';
                                                    part_out+='<td class="td_grp_2">';
                                                    if(part[i].user_status==1) {
                                                        part_out+='<button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    } else {
                                                        part_out+='<button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                    }
                                                    part_out += '</td>';
                                                    part_out+='</tr>';   
                                                } else { // status:2 (=cancelled)
                                                    cancel_count++;
                                                    part_out+='<tr class="ev_cancelled_tr">';
                                                    part_out+='<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    part_out+='<td></td>';
                                                    part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                    part_out += '<td colspan="2" class="td_grp_1"><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    part_out+='<td class="td_grp_1"></td>';
                                                    part_out+='<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    part_out+='</tr>';
                                                }
                                            } else { // over capacity -> waiting list
                                                if(part[i].user_status==0) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: #F8BA00;"><b>WAITING</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                } else if(part[i].user_status==1) {
                                                    wait_count++;
                                                    wait_out += '<tr>';
                                                    wait_out += '<td>'+wait_count+'</td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button></td>';
                                                    wait_out += '</tr>';
                                                } else {
                                                    cancel_count++;
                                                    wait_out += '<tr class="ev_cancelled_tr">';
                                                    wait_out += '<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                    wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                    wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                    wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                    wait_out += '<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                    wait_out += '</tr>';
                                                }
                                            } 
                                        }
                                    }
                                } else { //ev_att_enable is not 1 > attendance feature disabled
                                    for(var i=0;i<Object.keys(part).length;i++) {
                                        if(ev_capacity_var==0 || part_count<ev_capacity_var) {
                                            if(part[i].user_status<2) {
                                                part_count++;
                                                part_out+='<tr>';
                                                part_out+='<td><input type="checkbox" class="part_chk" id="chk_'+part[i].user_id+'" onchange="checkbox_change(this,'+part[i].user_id+')" checked></td>';
                                                part_out+='<td>'+part_count+'</td>';
                                                part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                if(part[i].user_status==1) {
                                                    part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';   
                                                } else {
                                                    part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                }
                                                part_out+='<td class="td_grp_1">-</td>';
                                                part_out+='<td class="td_grp_1">-</td>';
                                                part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                part_out+='<td class="td_grp_1"><span class="upload_points_span" onclick="upload_points('+part[i].user_id+')">UPLOAD</span></td>';
                                                part_out+='<td class="td_grp_2">';
                                                if(part[i].user_status==1) {
                                                    part_out+='<button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                } else {
                                                    part_out+='<button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button>';
                                                }
                                                part_out += '</td>';
                                                part_out+='</tr>';
                                            } else { // status:2 (=cancelled)
                                                cancel_count++;
                                                part_out+='<tr class="ev_cancelled_tr">';
                                                part_out+='<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                part_out+='<td></td>';
                                                part_out+='<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                part_out+='<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'</td>';
                                                part_out += '<td colspan="2" class="td_grp_1"><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                part_out+='<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                part_out+='<td class="td_grp_1"></td>';
                                                part_out+='<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                part_out+='</tr>';
                                            }
                                        } else {
                                            if(part[i].user_status==0) {
                                                wait_count++;
                                                wait_out += '<tr>';
                                                wait_out += '<td>'+wait_count+'</td>';
                                                wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: #F8BA00;"><b>WAITING</b></span></td>';
                                                wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                wait_out += '<td class="td_grp_2"><button class="small red_border" onclick="user_b_click(this, '+part[i].sup_order+')">Cancel</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                wait_out += '</tr>';
                                            } else if(part[i].user_status==1) {
                                                wait_count++;
                                                wait_out += '<tr>';
                                                wait_out += '<td>'+wait_count+'</td>';
                                                wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>Requested Cancellation</b></span></td>';
                                                wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                wait_out += '<td class="td_grp_2"><button class="small green" onclick="user_b_click(this, '+part[i].sup_order+')">Accept</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Decline</button></td>';
                                                wait_out += '</tr>';
                                            } else { //status:2 (=cancelled)
                                                cancel_count++;
                                                wait_out += '<tr class="ev_cancelled_tr">';
                                                wait_out += '<td><input type="checkbox" id="chk_'+part[i].user_id+'" style="display: none;"></td>';
                                                wait_out += '<td onclick="view_student_details('+part[i].user_id+')" style="cursor: pointer;">'+part[i].user_id+'</td>';
                                                wait_out += '<td>'+part[i].user_eng_name_first+' '+part[i].user_eng_name_last+' ('+part[i].user_name+')'+'<br /><span style="color: red;"><b>CANCELLED<b></span></td>';
                                                wait_out += '<td class="td_grp_1"><span style="cursor: pointer;" onclick="get_activity(this,'+part[i].user_id+')">View..</span></td>';
                                                wait_out += '<td class="td_grp_2"><button class="disabled small">Cancelled</button><button class="small red" onclick="user_b_click(this, '+part[i].sup_order+')">Delete</button></td>';
                                                wait_out += '</tr>';
                                            }
                                        }
                                    }
                                }
                            } else { //Object.keys(part).length<=0
                                part_out+='<tr><td colspan="8" style="text-align: center;">No data.</td></tr>';
                                status_msg("There are no participants for this event.", "status redd");
                            }   
                        } else { //part==null
                            part_out+='<tr><td colspan="8" style="text-align: center;">No data.</td></tr>';
                            status_msg("There are no participants for this event.", "status redd");
                        }
                        part_out+='</table>';
                        wait_out+='</table>';
                        gei("ev_part_div").innerHTML=part_out;
                        gei("ev_part_num_td").innerHTML=part_count;
                        if(gei("sup_waiting_optn_ip").value==1) {
                            if(wait_count>0) {
                                gei("ev_wait_div").innerHTML=wait_out;
                            } else {
                                gei("ev_wait_div").innerHTML="<p>No one on the waiting list.</p>";
                            }
                        } else {
                            gei("ev_wait_title").style.color="gray";
                            gei("ev_wait_title").innerHTML="Waiting list is disabled for this event.";
                        }
                        if(gei("ev_att_enable_ip").value==1) {
                            gei("ev_present_num_td").innerHTML=part_present_count;
                            var percentage=0;
                            if(part_count!=0) {
                                percentage = part_present_count/part_count*100;
                                percentage = percentage.toFixed(2);
                            } else {
                                percentage=0.00;
                            }
                            gei("ev_present_num_td").innerHTML+="<br />("+percentage+"%)";
                        }
                        if(cancel_count>0) {
                            gei("show_cancelled_label").innerHTML="Show cancelled participants ("+cancel_count+")"
                            gei("show_cancelled_div").style.display="inline";
                        } else {
                            gei("show_cancelled_div").style.display="none";
                        }
                        gei("last_updated_span").innerHTML=getTime("datetime24");
                        if(gei("uid").value=="") {
                            var pts_btn=document.getElementsByClassName("upload_points_span");
                            for(var j=0;j<pts_btn.length;j++) {
                                document.getElementsByClassName("upload_points_span")[j].style.display="none";
                            }
                        }
                    },
                    error: function(x,a,b) {
                        var part_out="";
                        part_out='<table id="ev_part_table" class="user w10"><tr><td rowspan="2"></td><td rowspan="2">#</td><td rowspan="2">ID</td><td rowspan="2">Name</td><td colspan="2" style="border-bottom: none;">Attendance</td><td rowspan="2">Action</td></tr><tr><td style="border-bottom: 2px solid #0E69B1;">1st</td><td style="border-bottom: 2px solid #0E69B1;">2nd</td></tr>';
                        part_out+='<tr><td colspan="7" style="text-align: center; color: red;">Could not connect to server.</td></tr>';
                        part_out+='</table>';
                        gei("ev_part_div").innerHTML=part_out;
                    },
                    complete: function() {
                        copy_selected_btn();
                        gei("loader").style.display="none";
                        show_cancelled(gei("show_cancelled_chk"));
                        filter_part(gei("filter_part_select").value);
                    }
                });   
            }
        }
        function get_activity(user_name_obj, user_id) {
            //alert("Get activity: "+user_id);
            $(".loader").css("display","");
            $.ajax({
                url:'myactivity_load.php',
                type: "POST",
                data: {"user_id":user_id},
                success: function(data) {
                    var ev = JSON.parse(data);
                    //document.getElementById("test_div").innerHTML += ev[0].ev_name;
                    var table = document.getElementById("user_activity_div");
                    //var j=0;
                    var out_text="";
                    out_text='<table class="user w10"><tr><td>Event Name</td><td>Status</td><td>Attendance</td></tr>'
                    var i=0;
                    if(Object.keys(ev).length==0) {
                        out_text += '<tr><td colspan="3" style="text-align: center;">No data.</td></tr>';
                    } else {
                        for(i=0; i<Object.keys(ev).length; i++) {
                            out_text += "<tr onclick='view_ev_details(this,"+ev[i].ev_code+")'><td>"+ev[i].ev_name+"</td><td style='text-align:center;'>"+ev[i].ev_status+"</td><td style='text-align:center;'>"+ev[i].ev_att+"</td></tr>";
                        }   
                    }
                    out_text += '</table>'
                    table.innerHTML=out_text;
                },
                error: function(xhr,a,b) {
                    console.log(e.message);
                    var table = document.getElementById("user_activity_list");
                    table.innerHTML += '<table class="user w10"><tr>';
                    /*
                    table.innerHTML += '<td colspan="2" style="color: red;">';
                    table.innerHTML += a+" "+b;
                    table.innerHTML += "</td>";*/
                    table.innerHTML += '<tr><td colspan="3" style="text-align: center;">No data.</td></tr>';
                    table.innerHTML += '</tr></table>';
                }, 
                complete : function() {
                    $(".loader").css("display","none");
                    gei("sel_user_p").innerHTML="Viewing: "+user_name_obj.parentElement.parentElement.children[3].innerText+" - "+user_id+"";
                }
            });
        }
        function view_ev_details(tr_obj,ev_code) {
            if(ev_code!=gei("ev_code_div").innerText) {
                if(confirm("Do you wish to leave this page and go to participants page of '"+tr_obj.children[0].innerText+"'?")) {
                    window.location="/yicrc/eng/manage_participants_3.php?ev_code="+ev_code;   
                } 
            }
        }
        function copy_data(optn) {
            //alert("Copy data: "+optn);
            var only_id = true;
            var part_table_tr = gei("ev_part_table").children[0].children;//tr
            var text_area = gei("part_out_text");
            var out_text="ID\r\n"; //string to become textarea value
            if(only_id) {
                for(var i=2;i<part_table_tr.length;i++) {
                    out_text+=part_table_tr[i].children[2].innerText+"\r\n";
                    //alert(part_table_tr[i].children[2].innerText+"\r\n");
                }
            } else {
                out_text=gei("ev_part_div").innerHTML;
            }
            //alert(out_text);
            text_area.value=out_text;
        }
        var clipboardJS_copy_all = new ClipboardJS('#copy_all_btn', {
            target: function(trigger) {
                //var only_id = gei("copy_only_id").checked;
                var only_id = true;
                var part_table_tr = gei("ev_part_table").children[0].children;//tr
                var text_area = gei("part_out_text");
                var out_text="ID\r\n"; //string to become textarea value
                if(only_id) {
                    for(var i=2;i<part_table_tr.length;i++) {
                        out_text += part_table_tr[i].children[2].innerText+"\r\n";
                    }
                } else {
                    out_text=gei("ev_part_div").innerHTML;
                }
                text_area.value=out_text;
                //alert("All students were copied to the clipboard.");
                status_msg("All students were copied to the clipboard.", "status greend");
                return text_area;
            }
        });
        var clipboardJS_copy_sel = new ClipboardJS('#copy_sel_btn', {
            target: function(trigger) {
                //var only_id = gei("copy_only_id").checked;
                var only_id = true;
                var part_table_tr = gei("ev_part_table").children[0].children;//tr
                var text_area = gei("part_out_text");
                var out_text="ID\r\n"; //string to become textarea value
                if(only_id) {
                    for(var i=2;i<part_table_tr.length;i++) {
                        if(part_table_tr[i].children[0].children[0].checked) {
                            out_text += part_table_tr[i].children[2].innerText+"\r\n";
                        }
                    }
                } else {
                    out_text=gei("ev_part_div").innerHTML;
                }
                text_area.value=out_text;
                //alert("Selected students were copied to the clipboard.");
                status_msg("Selected students were copied to the clipboard.", "status greend");
                return text_area;
            }
        });
        function filter_part(optn) {
            var part_table_tr = gei("ev_part_table").children[0].children;//tr
            if(optn=="present") {
                if(gei("show_cancelled_chk").checked) {
                    gei("show_cancelled_chk").checked=false;   
                } else {
                    show_cancelled(gei("show_cancelled_chk"));
                }
                for(var i=2;i<part_table_tr.length;i++) {
                    if(part_table_tr[i].children[0].children[0].checked) {
                        part_table_tr[i].style.display="";
                    } else {
                        part_table_tr[i].style.display="none";
                    }
                }
            } else if(optn=="no_show") {
                for(var i=2;i<part_table_tr.length;i++) {
                    if(part_table_tr[i].children[0].children[0].checked) {
                        part_table_tr[i].style.display="none";
                    } else {
                        part_table_tr[i].style.display="";
                    }
                }
                if(gei("show_cancelled_chk").checked) {
                    gei("show_cancelled_chk").checked=false;   
                    show_cancelled(gei("show_cancelled_chk"));
                } else {
                    show_cancelled(gei("show_cancelled_chk"));
                }
            } else {
                if(gei("show_cancelled_chk").checked) {
                    show_cancelled(gei("show_cancelled_chk"));
                } else {
                    gei("show_cancelled_chk").checked=true;
                }
                for(var i=2;i<part_table_tr.length;i++) {
                    part_table_tr[i].style.display="";
                }
            }
        }
        function checkbox_change(chkObj, user_id) {
            //alert("Checkbox_change: "+chkObj.checked+", "+user_id);
            copy_selected_btn();
        }
        function check_all(chk) {
            var chks=document.getElementsByClassName("part_chk");
            for(var i=0;i<chks.length;i++) {
                chks[i].checked=chk;
            }
            copy_selected_btn();
        }
        function copy_selected_btn() {
            var chks=document.getElementsByClassName("part_chk");
            var cnt=0;
            for(var i=0;i<chks.length;i++) {
                if(chks[i].checked) {
                    cnt++;
                }
            }
            if(cnt>0) {
                //gei("copy_sel_btn").className="blue";
                gei("copy_sel_btn").style.display="";
                gei("selected_num_span").innerHTML=cnt+" selected.";
            } else {
                //gei("copy_sel_btn").className="disabled";
                gei("copy_sel_btn").style.display="none";
                gei("selected_num_span").innerHTML="";
            }
        }
        function select_students(optn) {
            //alert("select_students: "+optn);
            var part_table_tr = gei("ev_part_table").children[0].children;//tr
            var text_area = gei("part_out_text");
            var out_text="ID\r\n"; //string to become textarea value
            var cnt=0;
            var query='<span style="display: none;">'+optn+'</span>'
            if(gei("ev_att_enable_ip").value=="1") {
                if(gei("ev_att_ip").value==1) {
                    for(var i=2;i<part_table_tr.length;i++) {
                        if(part_table_tr[i].children[4].innerHTML.indexOf(query)>=0) {
                            part_table_tr[i].children[0].children[0].checked=true;
                            cnt++;
                        } else {
                            part_table_tr[i].children[0].children[0].checked=false;
                        }
                    }
                }
                if(gei("ev_att_ip").value==2) {
                    for(var i=2;i<part_table_tr.length;i++) {
                        if(part_table_tr[i].children[4].innerHTML.indexOf(query)>=0 && part_table_tr[i].children[5].innerHTML.indexOf(query)>=0) {
                            part_table_tr[i].children[0].children[0].checked=true;
                            cnt++;
                        } else {
                            part_table_tr[i].children[0].children[0].checked=false;
                        }
                    }
                }
            } else {
                gei("sel_present_btn").className="disabled";
                gei("sel_present_btn").style.display="none";
            }
            copy_selected_btn();
            //alert(cnt+" students selected.");
        }
        function upload_points() {
            alert("upload_points");
        }
        function upload_points(user_id) {
            if(!(user_id===undefined)) {
                document.getElementById("mHakbun").value=user_id;
            }
            if(document.getElementById("aactt").value!="" && document.getElementById("uid").value!="" && document.getElementById("page").value!="" && document.getElementById("mHakbun").value!="") {
                if(confirm("Give points to '"+document.getElementById("mHakbun").value+"'?")) {
                    document.ffmod.submit();
                }
            } else {
                alert("All fields are required.");
            }
            return false;
        }
        function get_uid() {
            var url=document.getElementById("url_paste").value;
            var url_sp = url.split("&");
            var uid_sp=null;
            var uid_found=false;
            for(var i=0; i<url_sp.length;i++) {
                if(url_sp[i].includes("uid")) {
                    uid_sp=url_sp[i].split("=")[1];
                    if(uid_sp!="") {
                        document.getElementById("uid").value=uid_sp;
                        uid_found=true;
                    } else {
                        uid_found=false;
                    }
                    break;
                }
            }
            if(!uid_found) {
                document.getElementById("uid_p").style.color="red";
                document.getElementById("uid_p").innerHTML="FAILED: Invalid URL";
                var pts_btn=document.getElementsByClassName("upload_points_span");
                for(var j=0;j<pts_btn.length;j++) {
                    document.getElementsByClassName("upload_points_span")[j].style.display="none";
                }
                document.getElementById("portal_login_btn").style.display="none";
                document.getElementById("url_paste").value="";
                //alert("Unable to find uid in the link you have pasted.");
            } else {
                document.getElementById("uid_p").style.color="green";
                document.getElementById("uid_p").innerHTML="SUCCESS";
                var pts_btn=document.getElementsByClassName("upload_points_span");
                for(var j=0;j<pts_btn.length;j++) {
                    document.getElementsByClassName("upload_points_span")[j].style.display="";
                }
                document.getElementById("portal_login_btn").style.display="";
                document.getElementById("iframe_up").src=url;
                document.getElementById("url_paste").value="";
            }
        }
        function add_part() {
            var id_input=prompt("Enter Yonsei ID of a user to add.", "Yonsei ID");
            var ev_code=document.getElementById("ev_code_div").innerText;
            if(ev_code!="") {
                if(id_input!=null && id_input!="") {
                    $.ajax({
                        url: 'signup_write.php',
                        type: "POST",
                        data: {"ev_code":ev_code, "user_id":id_input},
                        beforeSend: function() {
                            document.getElementById("loader").style.display="";
                        },
                        success: function(data) {
                            alert(data);
                            if(ev_code!=null && ev_code!="") {
                                //window.location="/yicrc/manage_participants.php?ev_code="+ev_code;
                                location.reload();
                            } else {
                                window.location="/yicrc/eng/rc_events_2.php";
                            }
                        },
                        error: function(e) {
                            alert("Error: "+e.message);
                            document.getElementById("loader").style.display="none";
                        }
                    });
                }   
            } else {
                alert("Event not specified. Please try again.");
                window.location="/yicrc/eng/rc_events_2.php";
            }
        }
        function getTime(x) {
            var d = new Date();
            var out_str="";
            var month=d.getMonth()+1;
            if(d.getMonth()<10) {
                month="0"+d.getMonth();
            }
            var date=d.getDate();
            if(d.getDate()<10) {
                date="0"+d.getDate();
            }
            var hour=d.getHours();
            if(d.getHours()<10) {
                hour="0"+d.getHours();
            }
            var minutes=d.getMinutes();
            if(d.getMinutes()<10) {
                minutes="0"+d.getMinutes();
            }
            var seconds=d.getSeconds();
            if(d.getSeconds()<10) {
                seconds="0"+d.getSeconds();
            }
            switch (x) {
                case "date":
                    out_str = d.getFullYear()+"."+month+"."+date;
                    break;
                case "time12":
                    if(d.getHours()>12) {
                        out_str = (d.getHours()-12)+":"+minutes+":"+seconds+" PM";
                    } else {
                        out_str = d.getHours()+":"+minutes+":"+seconds+" AM";
                    }
                    break;
                case "time24":
                    out_str = hour+":"+minutes+":"+seconds;
                    break;
                case "datetime12":
                    out_str = d.getFullYear()+"."+month+"."+date;
                    out_str += "&nbsp;&nbsp;";
                    if(d.getHours()>12) {
                        out_str += (d.getHours()-12)+":"+minutes+":"+seconds+" PM";
                    } else {
                        out_str += d.getHours()+":"+minutes+":"+seconds+" AM";
                    }
                    break;
                case "datetime24":
                    out_str = d.getFullYear()+"."+month+"."+date;
                    out_str += "&nbsp;&nbsp;";
                    out_str += hour+":"+minutes+":"+seconds;
                    break;
            }
            return out_str;
        }
        function att_sel_change(selObj, att, id) {
            gei("loader").style.display="";
            //gei("content_div").style.display="none";
            var ev_code=gei("ev_code_div").innerText;
            var att_value=selObj.value;
            var att_1="";
            var att_2="";
            if(att==2) {
                att_2=att_value;
            } else {
                att_1=att_value;
            }
            $.ajax({
                url: "attendance_write.php",
                type: "POST",
                data: {"ev_code":ev_code, "user_id":id, "att_1":att_1, "att_2":att_2},
                success: function(data) {
                    if(data.indexOf("ERROR")>=0) {
                        //alert(data);   
                        status_msg(data,"status redd");
                    } else {
                        status_msg(data,"status greend");
                    }
                    get_ev();
                }, 
                error: function(xhr, status, err) {
                    //alert("[ERROR] Could not connect to server. Please try again later ("+status+": "+err+")");
                    status_msg("[ERROR] Could not connect to server. Please try again later","status redd");
                }, 
                complete: function() {
                    gei("loader").style.display="none";
                    //gei("content_div").style.display="";
                }
            });
        }
        function edit_part_click(x) {
            var g1 = document.getElementsByClassName("td_grp_1");
            var g2 = document.getElementsByClassName("td_grp_2");
            if(x.className=="blue_border small") {
                for(var i=0; i<g1.length; i++) {
                    g1[i].style.display="none";
                    //g1[i].visibility="collapse";
                }
                for(var i=0; i<g2.length; i++) {
                    g2[i].style.display="table-cell";
                    //g2[i].visibility="visible";
                }
                x.className="blue small";
                x.innerHTML="Done";
            } else {
                for(var i=0; i<g1.length; i++) {
                    g1[i].style.display="table-cell";
                    //g1[i].visibility="visible";
                }
                for(var i=0; i<g2.length; i++) {
                    g2[i].style.display="none";
                    //g2[i].visibility="collapse";
                }
                x.className="blue_border small";
                x.innerHTML="Edit";
            }
        }
        function user_b_click(btnObject,user_param) {
            var action_type=null;
            var action_type_text=null;
            switch(btnObject.innerText) {
                case "Details":
                    //alert("Details "+user_param);
                    action_type=null;
                    //window.location="/yicrc/account.php?user_id="+user_param;
                    window.location="/yicrc/manage_students_2.php?user_id="+user_param;
                    break;
                case "Accept":
                    //alert("Accept "+user_param);
                    action_type="accept";
                    action_type_text="Accept cancellation request for this student?";
                    break;
                case "Decline":
                    //alert("Decline "+user_param);
                    action_type="decline";
                    action_type_text="Decline cancellation request for this student?";
                    break;
                case "Revert":
                    //alert("Revert "+user_param);
                    action_type="decline";
                    action_type_text="This will change this student's status back to 'SIGNED UP.' Do you wish to continue?";
                    break;
                case "Delete":
                    //alert("Delete "+user_param);
                    action_type="delete";
                    action_type_text="WARNING: This will permenantly delete the student's sign-up record from the server. Under most circumstances, please use the 'cancel' option instead of this 'delete' option. You CANNOT undo this action. Do you wish to continue?";
                    break;
                case "Cancel":
                    //alert("Cancel "+user_param);
                    action_type="accept";
                    action_type_text="Do you wish to cancel this student's sign-up?";
                    break;
            }
            if(action_type!=null) {
                if(confirm(action_type_text)) {
                    gei("loader").style.display="";
                    gei("content_div").style.display="none";
                    $.ajax({
                        url: "/yicrc/eng/cancel_write.php",
                        type: "POST",
                        data: {"action_type": action_type, "sup_order":user_param},
                        success: function(data) {
                            //alert(data);
                            if(data.indexOf("Error")>=0) {
                                status_msg(data, "status redd");
                            } else {
                                status_msg(data, "status greend");   
                            }
                            get_ev();
                            //location.reload();
                        },
                        error: function(e) {
                            alert("There was an error connecting to the server.");
                        },
                        complete: function() {
                            //var get_p = setTimeout(get_part(),50);
                            //var show = setTimeout(show_content(), 80);
                            gei("content_div").style.display="";
                        }
                    });
                }
            }
        }
        function view_student_details(id) {
            window.open("/yicrc/manage_students_2.php?stu_id="+id, "Student Details", "toolbar=no,menubar=no,width=400px,height=700px,resizable=yes");
        }
    </script>
</body>
</html>