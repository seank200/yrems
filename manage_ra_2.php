<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
if($_SESSION["user_type"]!="Chief RA" && $_SESSION["user_type"]!="RM") {
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage RAs - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
	<script type="text/javascript" src="base.js"></script>
    <script src="/yicrc/clipboard_js/dist/clipboard.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/yicrc/eng/yicrc_english_3.css">
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
</head>
<body style="background: white;">
    <div class="loader" id="loader" style="display: none;"></div>
    <div class="header">
        <div class="header_content">
            <img id="menu_show" src="/yicrc/img/menu.png" class="menu" alt="MENU" onclick="toggle_menu()" />
            <img id="menu_close" src="/yicrc/img/menu_close.png" class="menu_close" alt="MENU" onclick="toggle_menu()" />
            <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" onclick="window.location='/yicrc/eng/rc_events_2.php';"/>
            <ul id="menu_list" class="header">
            </ul>
        </div>
    </div>
    <?php
        require_once 'config_2.php';
        load_menu();
    ?>
    <div id="content_div" class="content_div" style="display: none;">
        <div class="col-7">
            <h2>Manage RAs</h2>
            <p>
                Last updated: <span id="last_updated_span"></span>&nbsp;&nbsp;&nbsp;&nbsp;
            </p>
            
            <input class="small" id="search_input" style="width: auto;" placeholder="Search Students.." onkeydown="if(event.keyCode==13) { search_part(); }" />
            <button class="blue small" onclick="search_part()">Search</button>
            <button id="clear_search_btn" class="red small" style="display: none;" onclick="clear_search()">Clear Search</button>

            <p id="waiting_title" style="color: #0E69B1"><br />Waiting approval</p>
            <div id="waiting_div"></div>

            <p id="show_all_p" style="color: #0E69B1; cursor: pointer;" onclick="get_stu(true)"><br />Click to load all House students</p>
            <p id="house_students_title" style="color: #0E69B1;display: none;"><br />All House Students</p>
            <div id="house_students_div" style="display: none;"></div>
        </div>
        <div class="col-5">
            <h2 id="account_info_h2">Account Information</h2>
            <p id="account_info_description">Click on a student to view detailed account information.</p>
            <p id="last_updated_p_account" style="display: none;"></p>
            <table id="account_view" class="user details" style="width: 100%; display: none; text-align: left;">
                <tr>
                    <td colspan="2" style="text-align: center;">Personal Information</td>
                </tr>
                <tr>
                    <td>Account Type</td>
                    <td id="user_type_view"></td>
                </tr>
                <tr>
                    <td style="width: 30%;">English Name</td>
                    <td style="width: 70%;" id="user_eng_name_view"></td>
                </tr>
                <tr>
                    <td>Name<br />(in your language)</td>
                    <td id="user_name_view"></td>
                </tr>
                <tr>
                    <td>Student ID</td>
                    <td id="user_id_view"></td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td id="user_bday_view"></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td id="user_gender_view"></td>
                </tr>
                <tr>
                    <td>Mobile Phone #</td>
                    <td id="user_mobile_view"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td id="user_email_view"></td>
                </tr>
                <tr>
                    <td>College</td>
                    <td id="user_college_view"></td>
                </tr>
                <tr>
                    <td>Field/Major</td>
                    <td id="user_major_view"></td>
                </tr>
                <tr>
                    <td>House</td>
                    <td id="user_house_view"></td>
                </tr>
                <tr id="user_ra_view_tr">
                    <td>Your RA</td>
                    <td id="user_ra_view"></td>
                </tr>
                <tr>
                    <td>Room</td>
                    <td id="user_room_view"></td>
                </tr>
                <tr id="user_notes_tr_view_1">
                    <td colspan='2' style="border-bottom: none; padding-bottom: none;">Anything else you want to tell your RA (optional)</td>
                </tr>
                <tr id="user_notes_tr_view_2">
                    <td colspan='2' id="user_notes_view" style="font-weight: normal;"></td>
                </tr>
                <?php
                    if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                        echo '<tr><td>User status</td><td id="user_accepted_view"></td></tr>';
                    }
                ?>
            </table>

            <table id="account_edit" class="user details"  style="width: 100%;display: none; text-align: left;">
                <tr>
                    <td colspan="2" style="text-align: center;">Edit Personal Information</td>
                </tr>
                <tr>
                    <td>Account Type</td>
                    <td>
                        <select name="user_type" id="user_type">
                            <option value="RC Student">RC Student</option>
                            <option value="Non-RC Student">Non-RC Student</option>
                            <option value="Administrative RA">Administrative RA</option>
                            <option value="House RA">House RA</option>
                            <option value="Chief RA">Chief RA</option>
                            <option value="RM">RM</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%;">English Name</td>
                    <td style="width: 70%;">
                        <input type="text" id="user_eng_name_first" name="user_eng_name_first" placeholder="First Name" class="small" style="margin-bottom: 10px;"/>
                        <input type="text" id="user_eng_name_last" name="user_eng_name_last" placeholder="Last Name" class="small"/>
                    </td>
                </tr>
                <tr>
                    <td>Name<br />(in your language)</td>
                    <td><input type="text" id="user_name" name="user_name" placeholder="Your name in your language" class="small"/></td>
                </tr>
                <tr>
                    <td>Student ID</td>
                    <td id="user_id_td"></td>
                </tr>
                <tr>
                    <td id="user_bday_label">Date of Birth</td>
                    <td><input type="text" id="user_bday" name="user_bday" placeholder="e.g. 19990205" class="small"/></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <select id="user_gender" name="user_gender">
                            <option></option> 
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td id="user_mobile_label">Mobile Phone #</td>
                    <td><input type="number" pattern="[0-9]*" id="user_mobile" name="user_mobile" placeholder="without '-'" class="small"/></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type="text" id="user_email" name="user_email" placeholder="example@example.com" class="small"/></td>
                </tr>
                <tr>
                    <td>College</td>
                    <td>
                        <select id="user_college" name="user_college">
                            <option></option> 
                            <option value="UIC">UIC</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Field/Major</td>
                    <td>
                        <select name="user_major" id="user_major">
                            <option></option>
                            <option value="UF">UF</option>
                            <option value="UF-LSBT">UF-LSBT</option>
                            <option value="HASSF">HASSF</option>
                            <option value="HASSF-ASD">HASSF-ASD</option>
                            <option value="ISEF">ISEF</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>House</td>
                    <td>
                        <select name="user_house" id="user_house" onchange="house_sel_change(this)">
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
                    </td>
                </tr>
                <tr>
                    <td>Your RA</td>
                    <td>
                        <select name="user_ra" id="user_ra">
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Building</td>
                    <td>
                        <select name="user_room_1" id="user_room_1">
                            <option></option>
                            <option value="Dorm 2 - G">Dorm 2 - G</option>
                            <option value="Dorm 1 - A">Dorm 1 - A</option>
                            <option value="Dorm 1 - B">Dorm 1 - B</option> 
                            <option value="Dorm 1 - C">Dorm 1 - C</option>
                            <option value="Dorm 2 - D">Dorm 2 - D</option>
                            <option value="Dorm 2 - E">Dorm 2 - E</option>
                            <option value="Dorm 2 - F">Dorm 2 - F</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td id="user_room_2_label">Room</td>
                    <td><input type="number" id="user_room_2" name="user_room_2" placeholder="e.g. 604" class="small"/></td>
                </tr>
                <tr id="user_notes_tr_1">
                    <td colspan='2' style="border-bottom: none;">Anything else you want to tell your RA (optional)</td>
                </tr>
                <tr id="user_notes_tr_2">
                    <td colspan='2'><input type="text" id="user_notes" name="user_notes" placeholder="This is only visible to your RA and to no one else." class="small"/></td>
                    <input type="hidden" id="user_accepted" name="user_accepted"/>
                </tr>
                <?php
                    if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                        echo '<tr><td>User status</td><td id="user_accepted_td"></td></tr>';
                    }
                ?>
            </table>
            <div class="status redd" id="err_msg_js" style="display: none;"></div>
            <button id="edit_btn" style="margin-top: 20px; display: none;" class="blue" onclick="edit_btn(this)">Edit</button>
            <button id="accept_btn" onclick="accept_user()" class="disabled" style="display: none;">Loading..</button>
            <button id="reset_pw_btn" class="blue" style="width: 100%; display: none;" onclick="reset_pw()">Reset Password</button>
        </div>
    </div>
    <div id="user_id_div" style="display: none;"><?php if(isset($_SESSION["user_id"])) { echo trim($_SESSION["user_id"]); } ?></div>
    <div id="user_type_div" style="display: none;"><?if(isset($_SESSION["user_type"])) { echo $_SESSION["user_type"]; } ?></div>
    <div id="user_house_div" style="display: none;"><?php if(isset($_SESSION["user_house"])) { echo trim($_SESSION["user_house"]); } ?></div>
    <select style="display: none;" id="ra_list_select"></select>
    <script>
        function gei(x) {
            return document.getElementById(x);
        }
        function reset_pw() { 
            window.location="/yicrc/forgot_admin.php"; 
        }
        function clear_search() {
	        gei("loader").style.display="";
	        gei("content_div").style.display="none";
	        if(gei("waiting_table")!=null) {
	            var waiting_table = gei("waiting_table").children[0].children;   
	        }
	        
	        if(gei("all_table")!=null) {
	            var all_table = gei("all_table").children[0].children;   
	        }
	        gei("search_input").value="";
	        gei("clear_search_btn").style.display="none";
	        if(gei("waiting_table")!=null) {
	            for(i=0;i<waiting_table.length;i++) {
	                waiting_table[i].style.display="";
	            }
	        }
	        
	        if(gei("all_table")!=null) {
	            for(i=0;i<all_table.length;i++) {
	                all_table[i].style.display="";
	            }
	        }
	        gei("loader").style.display="none";
	        gei("content_div").style.display="";
	    }
	    function search_part() {
	        gei("loader").style.display="";
	        gei("content_div").style.display="none";
	        var query = gei("search_input").value;
	        if(gei("waiting_table")!=null) {
	            var waiting_table = gei("waiting_table").children[0].children;   
	        }
	        
	        if(gei("all_table")!=null) {
	            var all_table = gei("all_table").children[0].children;   
	        }
	        var i=0;
	        var j=0;
	        var show=false;
	        if(query=="") {
	            clear_search();
	        } else {
	            if(gei("waiting_table")!=null) {
	                for(i=0;i<waiting_table.length;i++) {
	                    show=false;
	                    for(j=0; j<waiting_table[i].children.length;j++) {
	                        if(waiting_table[i].children[j].innerHTML.toUpperCase().includes(query.toUpperCase())) {
	                            show=true;
	                        }
	                    }
	                    if(show) {
	                        waiting_table[i].style.display="";
	                    } else {
	                        if(i>0) {
	                            waiting_table[i].style.display="none";
	                        }   
	                    }
	                }
	            }
	            if(gei("all_table")!=null) {
	                for(i=0;i<all_table.length;i++) {
	                    show=false;
	                    for(j=0; j<all_table[i].children.length;j++) {
	                        if(all_table[i].children[j].innerHTML.toUpperCase().includes(query.toUpperCase())) {
	                            show=true;
	                        }
	                    }
	                    if(show) {
	                        all_table[i].style.display="";
	                    } else {
	                        if(i>0) {
	                            all_table[i].style.display="none";
	                        }   
	                    }
	                }
	            }
	            gei("clear_search_btn").style.display="";
	        }
	        gei("loader").style.display="none";
	        gei("content_div").style.display="";
	    }
        
        function get_stu(showall) {
            var stu=null;
            gei("loader").style.display="";
            gei("content_div").style.display="none";
            var wait_reason_text=["<span style='color: red;'><b>BLOCKED</b></span>", "<span style='color: green;'>Accepted</span>", "<span style='color: red;'>Blocked<br />(Changes)</span>", "NEW"];
            $.ajax({
                url: "eng/manage_ra_load.php",
                success: function(data) {
                    try {
                        stu=JSON.parse(data);
                    } catch(e) {
                        alert(data);
                        if(data!="No students found in this house") {
                            //window.location="/yicrc/index.php";
                        }
                    }
                    if(stu!=null) {
                        var out_text_waiting = '<table id="waiting_table" class="user" style="width: 100%;"><tr><td>#</td><td>Name</td><td>ID</td><td>Major</td><td>Type</td><td>Status</td><td>Action</td></tr>'; //#, Name, ID, Major, RA, Status, Action
                        var out_text_my = '<table id="my_table" class="user" style="width: 100%;"><tr><td>#</td><td>Name</td><td>ID</td><td>Major</td><td>Status</td><td>Room</td><td>Mobile</td><td>Action</td></tr>'; // #, Name, ID, Major, Status, Room, Mobile
                        var out_text_all = '<table id="all_table" class="user" style="width: 100%;"><tr><td>#</td><td>Name</td><td>ID</td><td>Major</td><td>Type</td><td>Room</td><td>Status</td></tr>'; // #, Name, ID, Major, RA, Room, Status

                        var wait_num=0;
                        var my_num=0;
                        var all_num=0; 

                        var my_id=gei("user_id_div").innerText;

                        if(Object.keys(stu).length==0) {
                            out_text_all += '<tr><td colspan="7" style="color: red">No students.</td></tr>';
                        } else {
                            var i=0;
                            for(i=0;i<Object.keys(stu).length;i++) {
                                if(stu[i].user_accepted==1) {
                                    all_num++;
                                    if(stu[i].user_ra==my_id) {
                                        out_text_all += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">';    
                                    } else {
                                        if(gei("user_type_div").innerText=="RM" || gei("user_type_div").innerText=="Chief RA") {
                                            out_text_all += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">';
                                        } else {
                                            //out_text_all += '<tr>';   
                                            out_text_all += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">'; //for registration period only
                                        }
                                    }
                                    //out_text_all += '<tr onclick="stu_click('+stu[i].user_id+')">';
                                    out_text_all += '<td>'+all_num+'</td>';
                                    out_text_all += '<td>'+stu[i].user_eng_name_first+' '+stu[i].user_eng_name_last+' ('+stu[i].user_name+')</td>';
                                    out_text_all += '<td>'+stu[i].user_id+'</td>';
                                    out_text_all += '<td>'+stu[i].user_major+'</td>';
                                    out_text_all += '<td>'+stu[i].user_type+'</td>';
                                    out_text_all += '<td>'+stu[i].user_room+'</td>';
                                    out_text_all += '<td>'+wait_reason_text[stu[i].user_accepted]+'</td>';
                                    out_text_all += '</tr>';

                                } else {
                                    wait_num++;
                                    if(stu[i].user_ra==my_id) {
                                        out_text_waiting += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">';    
                                    } else {
                                        if(gei("user_type_div").innerText=="RM" || gei("user_type_div").innerText=="Chief RA") {
                                            out_text_waiting += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">';
                                        } else {
                                            //out_text_waiting += '<tr>';   
                                            out_text_waiting += '<tr style="cursor: pointer;" onclick="stu_click('+stu[i].user_id+')">'; //for registration period only
                                        }
                                    }
                                    out_text_waiting += '<td>'+wait_num+'</td>';
                                    out_text_waiting += '<td>'+stu[i].user_eng_name_first+' '+stu[i].user_eng_name_last+' ('+stu[i].user_name+')</td>';
                                    out_text_waiting += '<td>'+stu[i].user_id+'</td>';
                                    out_text_waiting += '<td>'+stu[i].user_major+'</td>';
                                    out_text_waiting += '<td>'+stu[i].user_type+'</td>';
                                    out_text_waiting += '<td>'+wait_reason_text[stu[i].user_accepted]+'</td>';
                                    out_text_waiting += '<td>';
                                    if(stu[i].user_accepted==2 || stu[i].user_accepted==3) { //new
                                        out_text_waiting += '<button class="green" style="width: auto;padding: 5px;" onclick="accept_user('+stu[i].user_id+',1)">Accept</button><button class="red" style="width: auto;padding: 5px;" onclick="delete_user('+stu[i].user_id+')">Decline</button>'
                                    } else {
                                        if(gei("user_type_div").innerText=="RM") {
                                            out_text_waiting += '<button class="green" style="width: auto;padding: 5px;" onclick="accept_user('+stu[i].user_id+',1)">Accept</button>'
                                        }
                                    }
                                    out_text_waiting += '</td>';
                                    out_text_waiting += '</tr>';

                                }
                            }
                        }
                        out_text_waiting += '</table>';
                        out_text_my += '</table>';
                        out_text_all += '</table>';

                        if(wait_num==0) {
                            gei("waiting_title").style.display="none";
                        } else {
                            gei("waiting_title").style.display="";
                            gei("waiting_title").innerHTML = "<br />Waiting Approval ("+wait_num+")";
                            gei("waiting_div").innerHTML = out_text_waiting;
                        }

                        gei("house_students_title").innerHTML = "<br />All House RAs ("+all_num+")";
                        if(all_num==0) {
                            gei("house_students_title").style.display="none";
                            gei("house_students_div").style.display="none";
                        } else {
                            gei("house_students_title").style.display="";
                            gei("house_students_div").innerHTML = out_text_all; 
                            gei("house_students_div").style.display="";
                        } 
                    } //if(stu!=null)
                    gei("show_all_p").style.display="none";
                    gei("last_updated_span").innerHTML=getTime("datetime24");
                },
                error: function(xhr, status, msg) {
                    alert("There has been an error while connecting to the server. Please try again later.");
                },
                complete: function() {
                    gei("loader").style.display="none";
                    gei("content_div").style.display="";
                    preselect_student();
                }
            });
        }
        get_ra_list(true);
        function get_ra_list(first_optn) {
            var house=gei("user_house_div").innerText;
            if(house && house!="") {
                $.ajax({
                    url: "eng/get_ra.php",
                    type: "POST",
                    data: {"house":house},
                    success: function(data) {
                        var ra=null
                        try {
                            ra=JSON.parse(data);
                        } catch(e) {
                            alert(data);
                        }
                        if(ra!=null) {
                            var i=0;
                            for(i=0; i<Object.keys(ra).length; i++) {
                                var option = document.createElement("option");
                                option.text=ra[i].name;
                                option.value=ra[i].user_id;
                                gei("ra_list_select").add(option);
                            }
                        }
                    },
                    complete: function() {
                        if(first_optn) {
                            get_stu(false);
                        }
                    }
                });
            }
        }

        function ra_name(user_id) {
            if(user_id==0) {
                return "Non RC/RM/RA";
            } else {
                var ra=gei("ra_list_select").children;
                var out=null;
                for(var i=0; i<ra.length; i++) {
                    if(ra[i].value==user_id) {
                        out=ra[i].text;
                        break;
                    }
                }
                if(out==null) {
                    out="Not found";
                }
                return out;
            }
        }

        function house_sel_change(x) {
            var i=0;
            $("user_ra").empty();
            if(x.value!="" && x.value!=null) {
                get_list_ra(x.value, null);   
            }
        }
        function get_list_ra(house_sel,info) {
            if(house_sel==12) {
                var option = document.createElement("option");
                option.text="Non-RC/RA/RM";
                option.value="0";
                gei("user_ra").add(option);
            } else {
                $.ajax({
                    url: "eng/get_ra.php",
                    type: "POST",
                    data: {"house":house_sel},
                    success: function(data) {
                        var ra = null;
                        try {
                            ra = JSON.parse(data);
                        } catch(e) {
                            alert(data);
                            $("user_ra").empty();
                        }
                        var my_ra_id=null;
                        var my_ra_name=null;
                        if(ra!=null) {
                            var i=0;
                            for(i=0; i<Object.keys(ra).length; i++) {
                                var option = document.createElement("option");
                                option.text=ra[i].name;
                                option.value=ra[i].user_id;
                                gei("user_ra").add(option);
                                if(info!=null) {
                                    if(ra[i].user_id==info.user_ra) {
                                        my_ra_id=ra[i].user_id;
                                        my_ra_name=ra[i].name;
                                    }   
                                }
                            }
                        }
                        var option = document.createElement("option");
                        option.text="Non-RC/RA/RM";
                        option.value="0";
                        gei("user_ra").add(option);
                        if(info!=null) {
                            document.getElementById("user_ra").value=info.user_ra;
                            if(my_ra_name==null) {
                                if(info.user_ra==0) {
                                    gei("user_ra_view").innerHTML="Non-RC/RA/RM";
                                    gei("user_ra_view_tr").style.display="none";
                                }
                            } else {
                                gei("user_ra_view").innerHTML=my_ra_name;
                                gei("user_ra_view_tr").style.display="";
                            }
                        }

                        if(gei("user_type_div").innerHTML=="House RA" || gei("user_type_div")=="Chief RA") {
                            //var my_name_ra = "RA "+gei("user_eng_name_first_div").innerHTML;
                            if(my_ra_id==gei("user_id_div").innerHTML) {
                                gei("user_notes_view").innerHTML=info.user_notes;
                                document.getElementById("user_notes").value=info.user_notes;
                                gei("user_notes_tr_view_1").style.display="";
                                gei("user_notes_tr_view_2").style.display="";
                                gei("user_notes_tr_1").style.display="";
                                gei("user_notes_tr_2").style.display="";
                                gei("user_notes_view").style.display="";
                                gei("user_notes").style.display="";
                            } else {
                                //alert("("+my_name_ra+", ("+gei("user_ra_view").innerText+")");
                                gei("user_notes_view").style.display="none";
                                gei("user_notes").style.display="none";
                                gei("user_notes_tr_view_1").style.display="none";
                                gei("user_notes_tr_view_2").style.display="none";
                                gei("user_notes_tr_1").style.display="none";
                                gei("user_notes_tr_2").style.display="none";
                            }
                        } else if(gei("user_type_div").innerHTML=="RM") {
                            gei("user_notes_view").style.display="none";
                            gei("user_notes").style.display="none";
                            gei("user_notes_tr_view_1").style.display="none";
                            gei("user_notes_tr_view_2").style.display="none";
                            gei("user_notes_tr_1").style.display="none";
                            gei("user_notes_tr_2").style.display="none";
                        } else {
                            gei("user_notes_view").innerHTML=info.user_notes;
                            document.getElementById("user_notes").value=info.user_notes;
                            gei("user_notes_view").style.display="";
                            gei("user_notes").style.display="";
                            gei("user_notes_tr_view_1").style.display="";
                            gei("user_notes_tr_view_2").style.display="";
                            gei("user_notes_tr_1").style.display="";
                            gei("user_notes_tr_2").style.display="";
                        }

                    },
                    error: function(e) {
                        alert("There has been an error. Please try again later.");
                        window.location="/yicrc/eng/rc_events_2.php";
                    }
                });
            }
        }
        function stu_click(id) {
            var info=null;
            var err_div=document.getElementById("err_msg_js");
            gei("loader").style.display="";
            gei("content_div").style.display="none";
            if(gei("user_type_div").innerText!="House RA" && gei("user_type_div").innerText!="Chief RA" && gei("user_type_div").innerText!="RM" && gei("user_type_div").innerText!="Administrative RA") {
                window.location="/yicrc/eng/rc_events_2.php";
            } else {
                //var id=gei("get_user_id_div").innerText;
                gei("account_view").style.display="";
                gei("account_edit").style.display="none";
                gei("edit_btn").innerHTML="Edit";
                $.ajax({
                    url: "account_load.php",
                    type: "POST",
                    data: {"action_type":"load", "user_id":id},
                    success: function(data) { 
                        try {
                            info=JSON.parse(data);
                            err_div.innerHTML="";
                            err_div.style.display="none";
                        } catch(e) {
                            err_div.innerHTML=data;
                            err_div.style.display="";
                        }
                        if(info!=null) {
                            fill_userinfo_do(info);
                        }
                    },
                    error: function(xhr,status,msg) {
                        //alert("There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")");
                        err_div.innerHTML="There was a problem connecting to the server. Please try again later.";
                        err_div.style.display="";
                        gei("reset_pw_btn").style.display="none";
                    },
                    complete: function() {
                        gei("account_view").style.display="";
                        gei("account_info_description").style.display="none";
                        gei("last_updated_p_account").innerHTML="Last updated: "+getTime("datetime24");
                        gei("last_updated_p_account").style.display="";
                        //gei("user_activity_div").innerHTML="";
                        gei("sel_user_p").innerHTML="Click on 'Load RC Activities' to look at RC events this user has participated in.";
                        <?php
                            if(isset($_GET["stu_id"])) {
                                echo 'gei("account_info_h2").scrollIntoView(true);';
                            }
                        ?>
                    }
                });
            }    
        }
        function fill_userinfo_do(info) {
            gei("user_type_view").innerHTML=info.user_type;
            document.getElementById("user_type").value=info.user_type;
            gei("user_eng_name_view").innerHTML=info.user_eng_name_first+" "+info.user_eng_name_last;
            document.getElementById("user_eng_name_first").value=info.user_eng_name_first;
            document.getElementById("user_eng_name_last").value=info.user_eng_name_last;
            gei("user_name_view").innerHTML=info.user_name;
            document.getElementById("user_name").value=info.user_name;
            gei("user_id_view").innerHTML=info.user_id;
            document.getElementById("user_id_td").innerHTML=info.user_id;
            gei("user_bday_view").innerHTML=info.user_bday;
            document.getElementById("user_bday").value=info.user_bday;
            gei("user_gender_view").innerHTML=info.user_gender;
            document.getElementById("user_gender").value=info.user_gender;
            gei("user_mobile_view").innerHTML=info.user_mobile;
            document.getElementById("user_mobile").value=info.user_mobile;
            gei("user_email_view").innerHTML=info.user_email;
            document.getElementById("user_email").value=info.user_email;
            gei("user_college_view").innerHTML=info.user_college;
            document.getElementById("user_college").value=info.user_college;
            gei("user_major_view").innerHTML=info.user_major;
            document.getElementById("user_major").value=info.user_major;
            var house=["Appenzeller", "Evergreen", "Wonchul", "Undrwood", "Yun, Dong-joo", "Muak", "Chiwon", "Baekyang", "Cheongsong", "Yongjae", "Avison", "Allen", "Other"];
            //document.getElementById("user_house").value=house[info.user_house];
            gei("user_house_view").innerHTML=house[info.user_house];
            document.getElementById("user_house").value=info.user_house;
            get_list_ra(info.user_house,info);
            //document.getElementById("user_ra").value=info.user_ra;
            gei("user_room_view").innerHTML=info.user_room;
            if(info.user_room!="-") {
                switch(info.user_room.slice(0,1)) {
                    case "G":
                        document.getElementById("user_room_1").value="Dorm 2 - G"; break;
                    case "A":
                        document.getElementById("user_room_1").value="Dorm 1 - A"; break;
                    case "B":
                        document.getElementById("user_room_1").value="Dorm 1 - B"; break;
                    case "C":
                        document.getElementById("user_room_1").value="Dorm 1 - C"; break;
                    case "D":
                        document.getElementById("user_room_1").value="Dorm 2 - D"; break;
                    case "E":
                        document.getElementById("user_room_1").value="Dorm 2 - E"; break;
                    case "F":
                        document.getElementById("user_room_1").value="Dorm 2 - F"; break;
                    default:
                        document.getElementById("user_room_1").value=""; break;
                }
                document.getElementById("user_room_2").value=info.user_room.slice(1);
            }
            /*
            gei("user_nationality_view").innerHTML=info.user_nationality;
            document.getElementById("user_nationality").value=info.user_nationality;
            gei("user_exp_abroad_view").innerHTML=info.user_exp_abroad;
            document.getElementById("user_exp_abroad").value=info.user_exp_abroad;
            gei("user_highschool_view").innerHTML=info.user_highschool;
            document.getElementById("user_highschool").value=info.user_highschool;
            gei("user_lang_native_view").innerHTML=info.user_lang_native;
            document.getElementById("user_lang_native").value=info.user_lang_native;
            gei("user_lang_other_view").innerHTML=info.user_lang_other;
            document.getElementById("user_lang_other").value=info.user_lang_other;
            */
            document.getElementById("user_accepted").value=info.user_accepted;
            if(gei("accept_btn")!=null) {
                var btn = gei("accept_btn");
                var wait_reason_text=["BLOCKED", "ACCEPTED", "CHANGES MADE (BLOCKED)", "NEW USER"]
                if(gei("user_accepted").value==1) {
                    btn.className="red";
                    btn.innerHTML="Block user";
                    gei("user_accepted_td").innerHTML=wait_reason_text[gei("user_accepted").value]; 
                    gei("user_accepted_td").style.color="#017100";
                    gei("user_accepted_view").innerHTML=wait_reason_text[gei("user_accepted").value]; 
                    gei("user_accepted_view").style.color="#017100";
                    btn.style.display="";
                    //gei("activity_btn").style.display="";
                } else if(gei("user_accepted").value==0 || gei("user_accepted").value==2 || gei("user_accepted").value==3) {
                    btn.className="green";
                    btn.innerHTML="Accept user";
                    gei("user_accepted_td").innerHTML=wait_reason_text[gei("user_accepted").value];  
                    gei("user_accepted_td").style.color="#EE220C";
                    gei("user_accepted_view").innerHTML=wait_reason_text[gei("user_accepted").value];  
                    gei("user_accepted_view").style.color="#EE220C";
                    btn.style.display="";
                    gei("reset_pw_btn").style.display="";
                    //gei("activity_btn").style.display="";
                } else {
                    btn.className="disabled";
                    btn.innerHTML="Error";
                    btn.style.display="none";
                    //gei("activity_btn").style.display="none";
                    gei("reset_pw_btn").style.display="";
                }
                var enable_edit=true; // for registration period only - allow RAs to edit other student info in other RA groups
                if(enable_edit || gei("user_id_div").innerText==info.user_ra || gei("user_type_div").innerText=="Chief RA" || gei("user_type_div").innerText=="RM") {
                    gei("edit_btn").style.display="";   
                } else {
                    gei("edit_btn").style.display="none";   
                }
            }
            gei("reset_pw_btn").style.display="";
            gei("loader").style.display="none";
            gei("content_div").style.display="";
        }
        function edit_btn(x) {
            if(x.innerText=="Edit") {
                x.innerHTML="Save changes";
                gei("account_view").style.display="none";
                gei("account_edit").style.display="";
            } else {
                if(confirm("Do you wish to save changes?")) {
                    account_save_changes();   
                }
            }
        }
        function account_save_changes() {
            var err_div=gei("err_msg_js");
            var edited_user_id=gei("user_id_td").innerText;
            $.ajax({
                url: "account_load.php",
                type: "POST",
                data: {"action_type":"write", "user_type":gei("user_type").value, "user_eng_name_first":gei("user_eng_name_first").value, "user_eng_name_last":gei("user_eng_name_last").value, "user_name":gei("user_name").value, "user_id":gei("user_id_td").innerHTML, "user_bday":gei("user_bday").value, "user_gender":gei("user_gender").value, "user_mobile":gei("user_mobile").value, "user_email":gei("user_email").value, "user_college":gei("user_college").value, "user_major":gei("user_major").value, "user_house":gei("user_house").value, "user_ra":gei("user_ra").value, "user_room_1":gei("user_room_1").value, "user_room_2":gei("user_room_2").value, "user_notes":gei("user_notes").value, "user_accepted":gei("user_accepted").value},
                success: function(data) {
                    try {
                        //info=JSON.parse(data);
                        alert(data);
                        err_div.innerHTML="";
                        err_div.style.display="none";
                    } catch(e) {
                        //alert(data);
                        err_div.innerHTML=data;
                        err_div.style.display="";
                    }
                },
                error: function(xhr,status,msg) {
                    //alert("There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")");
                    err_div.innerHTML="There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")";
                    err_div.style.display="";
                },
                complete: function() {
                    gei("loader").style.display="none";
                    gei("content_div").style.display="";
                    location.reload();
                }
            });
        }
        function accept_user(id, new_user_accepted) {
            var str=null;
            if(id===undefined) {
                id=gei("user_id_view").innerText;
            }
            if(new_user_accepted===undefined) {
                if(gei("accept_btn").innerHTML=="Accept user") {
                    new_user_accepted=1;
                    str="accept";
                } else {
                    new_user_accepted="0";
                    str="block"; 
                }
            } else {
                if(new_user_accepted==1) {
                    str="accept";
                } else if(new_user_accepted==0) {
                    str="block";
                    new_user_accepted="0";
                } else {
                    str="block";
                }   
            }
            
            if(confirm("Do you wish to "+str+" this user '"+id+"'?")) {
                gei("loader").style.display="";
                gei("content_div").style.display="none";
                if(id!="" && new_user_accepted!="") {
                    $.ajax({
                        url: "account_load.php",
                        type: "POST",
                        data: {"action_type":"user_accepted", "user_id":id, "user_accepted":new_user_accepted},
                        success: function(data) {
                            alert(data);
                        },
                        error: function(x,a,b) {
                            alert("There has been an problem while connecting to the server. Please try again later.");
                        },
                        complete: function() {
                            location.reload();
                        }
                    });
                } else {
                    //alert("There was an error while setting parameters. Try refreshing this page.");
                    alert("id: "+(id!="")+", new_user_accepted: "+new_user_accepted+" / "+(new_user_accepted!=""));
                }
            }
        }
        function delete_user(user_id) { //enable for registration periods only
            var enable=true;
            if(enable) {
                if(confirm("This will delete this account. Do you wish to continue?")) {
                    if(confirm("[WARNING] Doing this will immediately and permanently delete all information linked to this account, including sign-up data. You cannot undo this action. Do you wish to continue?")) {
                        gei("loader").style.display="";
                        gei("content_div").style.display="none";
                        var err_div=document.getElementById("err_msg_js");
                        if(user_id===undefined) {
                            user_id=gei("user_id_td").innerText;
                        }
                        if(user_id!=null && user_id!="") {
                            $.ajax({
                                url: "account_load.php",
                                type: "POST",
                                data: {"action_type":"delete", "user_id":user_id},
                                success: function(data) {
                                    alert(data);
                                    /*
                                    if(gei("main_title").innerHTML=="Student Details") {
                                        window.location="manage_students.php";
                                    } else {
                                        window.location="logout.php";
                                    }
                                    */
                                    location.reload();
                                },
                                error: function(xhr, a, b) {
                                    err_div.innerHTML="There was a problem connecting to the server. Please try again later.";
                                    err_div.style.display="";
                                },
                                complete: function() {
                                    gei("loader").style.display="none";
                                    gei("content_div").style.display="";
                                }
                            });   
                        }
                    }
                }   
            }
        }
        function get_activity(user_id) {
            var prcd=true;
            if(user_id===undefined) {
                if(gei("user_id_view")!=null) {
                    user_id=gei("user_id_view").innerText;   
                } else {
                    prcd=false;
                }
            }
            if(prcd) {
                $(".loader").css("display","");
                $.ajax({
                    url:'eng/myactivity_load.php',
                    type: "POST",
                    data: {"user_id":user_id},
                    success: function(data) {
                        var ev = JSON.parse(data);
                        //document.getElementById("test_div").innerHTML += ev[0].ev_name;
                        var table = document.getElementById("user_activity_div");
                        //var j=0;
                        var out_text="";
                        out_text='<table class="user" style="width: 100%;"><tr><td>Event Name</td><td>Status</td><td>Attendance</td></tr>'
                        var i=0;
                        if(Object.keys(ev).length==0) {
                            out_text += '<tr><td colspan="3" style="text-align: center;">This user has not participated in any events.</td></tr>';
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
                        var table = document.getElementById("user_activity_div");
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
                        gei("sel_user_p").innerHTML="Viewing: "+gei("user_eng_name_view").innerText+" ("+gei("user_name_view").innerText+") - "+user_id+"";
                    }
                });
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
        function preselect_student() {
            <?php
                if(isset($_GET["stu_id"])) {
                    echo 'stu_click('.trim($_GET['stu_id']).');';
                    echo 'gei("account_info_h2").scrollIntoView(true);';
                }
            ?>
        }
    </script>
</body>
</html>