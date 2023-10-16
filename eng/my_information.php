<?php
session_start();
//error_reporting(0);
//mysqli_report(MYSQLI_REPORT_OFF);
require_once 'config_2.php';
verify_user();
?>
<!DOCTYPE html>
<html>
<head>
	<title>RC Events - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
    <meta property="og:url" content="http://appenzeller.kr">
    <meta property="og:title" content="YREMS">  
    <meta property="og:type" content="website">
    <meta property="og:image" content="http://appenzeller.kr/yicrc/img/YREMS_thumbnail.png">
    <meta property="og:description" content="Yonsei RC Event Management System">
    <meta name="description" content="YREMS - Discover and participate in RC Events.">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english_3.css">
    <link rel="shortcut icon" href="/favicon.ico" />
	<script type="text/javascript" src="base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="background: white;" onresize="menu_check()">
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
<div id="loader" class="loader" style="display: none;"></div>
<div id="content_div" class="content_div">
    <div id="my_activity_div" class="col-5">
        <h1>My RC Activities</h1>
        <div id="err_div_2" class="status" style="display: none;"></div>
        <div id="my_ev_list"></div>
        <p style="color: gray; line-height: 1.4; font-size: 80%;">Click on an event to view more details.<br />This list only shows RC programs that you have signed up through YREMS. You can view the full list of all RC programs that you have participated in by clicking on the link below.</p>
        <p style="color: #0A3879; cursor: pointer;" onclick="check_points()"><br /><u>View RC Points I have earned..</u></p>
        
    </div>
    <div class="col-7">
        <h1>Account Information</h1>
        <div id="err_div" class="status" style="display: none;"></div>
        <table id="account_view" class="list details">
            <tr>
                <td colspan="2">Personal Information</td>
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
            if(isset($_GET["user_id"])) {
                if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                    echo '<tr><td>User status</td><td id="user_accepted_view"></td></tr>';
                }
            }
            ?>
        </table>
        <table id="account_edit" class="list details" style="display: none;">
            <tr>
                <td colspan="2">Edit Personal Information</td>
            </tr>
            <tr>
                <td>Account Type</td>
                <td>
                    <div class="sel_container" style="margin: 0;">
                        <select name="user_type" id="user_type">
                            <option value="RC Student">RC Student</option>
                            <option value="Non-RC Student">Non-RC Student</option>
                            <option value="Administrative RA">Administrative RA</option>
                            <option value="House RA">House RA</option>
                            <option value="Chief RA">Chief RA</option>
                            <option value="RM">RM</option>
                        </select>
                        <p>▼</p>
                    </div><br />
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
                    <div class="sel_container" style="margin: 0;">
                        <select id="user_gender" name="user_gender">
                            <option></option> 
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <p>▼</p>
                    </div><br />
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
                    <div class="sel_container" style="margin: 0;">
                        <select id="user_college" name="user_college">
                            <option></option> 
                            <option value="UIC">UIC</option>
                        </select>
                        <p>▼</p>
                    </div><br />
                </td>
            </tr>
            <tr>
                <td>Field/Major</td>
                <td>
                    <div class="sel_container" style="margin: 0;">
                        <select name="user_major" id="user_major">
                            <option></option>
                            <option value="UF">UF</option>
                            <option value="UF-LSBT">UF-LSBT</option>
                            <option value="HASSF">HASSF</option>
                            <option value="HASSF-ASD">HASSF-ASD</option>
                            <option value="ISEF">ISEF</option>
                        </select>
                        <p>▼</p>
                    </div><br />
                </td>
            </tr>
            <tr>
                <td>House</td>
                <td>
                    <div class="sel_container" style="margin: 0;">
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
                        <p>▼</p>
                    </div><br />
                </td>
            </tr>
            <tr>
                <td>Your RA</td>
                <td>
                    <div class="sel_container" style="margin: 0;">
                        <select name="user_ra" id="user_ra">
                        </select>
                        <p>▼</p>
                    </div><br />
                </td>
            </tr>
            <tr>
                <td>Building</td>
                <td>
                    <div class="sel_container" style="margin: 0;">
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
                        <p>▼</p>
                    </div><br />
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
            if(isset($_GET["user_id"])) {
                if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                    echo '<tr><td>User status</td><td id="user_accepted_td"></td></tr>';
                }
            }
            ?>
        </table>
        <div id="btn_div_all">
        	<div id="btn_div_1" class="col-6 btn_div half_btn_left" style="text-align: center;">
                <button id="edit_btn" class="blue" onclick="edit_click()">Edit</button>
                <div class="textbutton" id="discard_changes_btn" style="display: none;" onclick="discard_changes()">Discard changes</div>
            </div>
        	<div id="btn_div_2" class="col-6 btn_div half_btn_right" style="text-align: center;">
                <button id="change_pw_btn" class="blue" style="width: 100%; margin-bottom: 60px;" onclick="change_pw()">Change Password</button>
                <button id="delete_account_btn" class="red" style="display: none;">Delete Account</button>
            </div>
        </div>
    </div>
</div>
<div style="display: none;" id="user_id_div"><?php echo $_SESSION["user_id"]; ?></div>
<div style="display: none;" id="user_type_div"><?php echo $_SESSION["user_type"]; ?></div>
<div style="display: none;" id="user_house_div"><?php echo $_SESSION["user_house"]; ?></div>
<div style="display: none;" id="sup_order_div"></div>
<div style="display: none;" id="my_status_text_div"></div>
<form action="participants_2.php" id="part_form" method="post">
    <input type="hidden" id="part_ev_code" name="ev_code" />
    <input type="hidden" id="part_status" name="status" />
</form> 
<script type="text/javascript">
function gei(x) {
    return document.getElementById(x);
}
function error_div(msg, show, id) {
    if(id===undefined) {
        if(!msg) {
            gei("err_div").style.display="none";
            gei("err_div").innerHTML="";
        } else {
            gei("err_div").innerHTML=msg;
            gei("err_div").className="status redd";
            gei("err_div").style.display="";
            if(show===undefined) {
                show=false;
            }
            if(show) {
                gei("err_div").scrollIntoView(true); 
            }
        }
    } else {
        if(!msg) {
            gei(id).style.display="none";
            gei(id).innerHTML="";
        } else {
            gei(id).innerHTML=msg;
            gei(id).className="status redd";
            gei(id).style.display="";
            if(show===undefined) {
                show=false;
            }
            if(show) {
                gei(id).scrollIntoView(true); 
            }
        }  
    } 
}
function check_points() {
    window.open("https://yicrc.yonsei.ac.kr/activity.asp?mid=m02_04");
}
get_my_activity();
function get_my_activity() {
    if(gei("user_type_div").innerText=="House RA" || gei("user_type_div").innerText=="Administrative RA" || gei("user_type_div").innerText=="Chief RA" || gei("user_type_div").innerText=="RM") {
        //error_div("Your user type is: "+gei("user_type_div").innerText, true, "err_div_2");
        gei("my_activity_div").style.display="none";
    } else {
        gei("my_activity_div").style.display="";
        $.ajax({
            url:'/yicrc/eng/myactivity_load.php',
            success: function(data) {
                var ev = JSON.parse(data);
                //document.getElementById("test_div").innerHTML += ev[0].ev_name;

                var table = document.getElementById("my_ev_list");
                //var j=0;
                var out_text="";
                out_text='<table class="list"><tr><td>Event Name</td><td>Status</td><td>Attendance</td></tr>'
                var i=0;
                if(Object.keys(ev).length==0) {
                    out_text += '<tr><td colspan="3" style="text-align: center;">No data.</td></tr>';
                } else {
                    for(i=0; i<Object.keys(ev).length; i++) {
                        out_text += "<tr style='cursor: pointer;' onclick='ev_click("+ev[i].ev_code+")'><td>"+ev[i].ev_name+"</td><td style='text-align:center;'>"+ev[i].ev_status+"</td><td style='text-align:center;'>"+ev[i].ev_att+"</td></tr>";
                    }   
                }
                out_text += '</table>'
                table.innerHTML=out_text;
            },
            error: function(xhr,a,b) {
                var table = document.getElementById("my_ev_list");
                table.innerHTML += '<table class="list"><tr>';
                table.innerHTML += '<tr><td colspan="3" style="color: red; text-align: center;">No data.</td></tr>';
                table.innerHTML += '</tr></table>';
                error_div("Could not retrieve my RC Activities from server", true);
            }, 
            complete : function() {
                $(".loader").css("display","none");
                $("#content").css("display","");
            }
        });
    }   
}

function ev_click(x) {
    //fill_details(x);
    window.location="rc_events_2.php?ev_code="+x;
}
function edit_click() {
    if(gei("account_view").style.display=="none") {
        submit_frm();
    } else {
        gei("account_edit").style.display="";
        gei("account_view").style.display="none";
        gei("edit_btn").innerHTML="Save changes";
        gei("discard_changes_btn").style.display="";
        gei("change_pw_btn").style.display="none";
        gei("delete_account_btn").style.display="";
        
    }
}
function discard_changes() {
    gei("account_edit").style.display="none";
    gei("account_view").style.display="";
    gei("discard_changes_btn").style.display="none";
    gei("edit_btn").innerHTML="Edit";
    gei("change_pw_btn").style.display="";
    gei("delete_account_btn").style.display="none";
    fill_userinfo();
}

fill_userinfo();
function fill_userinfo() {
    var info=null;
    var err_div=document.getElementById("err_div");
    gei("loader").style.display="";
    gei("content_div").style.display="none";
    $.ajax({
        url: "/yicrc/account_load.php",
        type: "POST",
        data: {"action_type":"load"},
        success: function(data) {
            try {
                info=JSON.parse(data);
                err_div.innerHTML="";
                err_div.style.display="none";
            } catch(e) {
                //alert(data);
                err_div.innerHTML=data;
                err_div.style.display="";
            }
            if(info!=null) {
                fill_userinfo_do(info);
            }
        },
        error: function(x,a,b) {
            error_div("Unable to retrieve user information from server.", true);
            gei("loader").style.display="none";
            gei("content_div").style.display="";
        }
    });
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
    gei("loader").style.display="none";
    gei("content_div").style.display="";
}
function house_sel_change(x) {
    $("#user_ra").empty();
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
            url: "/yicrc/eng/get_ra.php",
            type: "POST",
            data: {"house":house_sel},
            success: function(data) {
                var ra = null;
                try {
                    ra = JSON.parse(data);
                } catch(e) {
                    alert(data);
                    if(data!="There are no RAs listed for this house.") {
                        window.location="/yicrc/index.php";   
                    }
                }
                var my_ra=null;
                if(ra!=null) {
                    var i=0;
                    for(i=0; i<Object.keys(ra).length; i++) {
                        var option = document.createElement("option");
                        option.text=ra[i].name;
                        option.value=ra[i].user_id;
                        gei("user_ra").add(option);
                        if(info!=null) {
                            if(ra[i].user_id==info.user_ra) {
                                my_ra=ra[i].name;
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
                    if(my_ra==null) {
                        if(info.user_ra==0) {
                            gei("user_ra_view").innerHTML="Non-RC/RA/RM";
                            gei("user_ra_view_tr").style.display="none";
                        }
                    } else {
                        gei("user_ra_view").innerHTML=my_ra;
                        gei("user_ra_view_tr").style.display="";
                    }
                }
                
                if(gei("user_type_div").innerHTML=="House RA" || gei("user_type_div")=="Chief RA" || gei("user_type_div").innerHTML=="RM") {
                    gei("user_notes_view").style.display="none";
                    gei("user_notes").style.display="none";
                    gei("user_notes_tr_view_1").style.display="none";
                    gei("user_notes_tr_view_2").style.display="none";
                    gei("user_notes_tr_1").style.display="none";
                    gei("user_notes_tr_2").style.display="none";
		        } else {
		        	if(info!=null && info.user_id==gei("user_id_div").innerText) {
		        		gei("user_notes_view").innerHTML=info.user_notes;
						document.getElementById("user_notes").value=info.user_notes;
		        		gei("user_notes_view").style.display="";
			        	gei("user_notes").style.display="";
			        	gei("user_notes_tr_view_1").style.display="";
						gei("user_notes_tr_view_2").style.display="";
						gei("user_notes_tr_1").style.display="";
						gei("user_notes_tr_2").style.display="";
		        	} else {
		        		gei("user_notes_view").style.display="none";
			        	gei("user_notes").style.display="none";
			        	gei("user_notes_tr_view_1").style.display="none";
						gei("user_notes_tr_view_2").style.display="none";
						gei("user_notes_tr_1").style.display="none";
						gei("user_notes_tr_2").style.display="none";
		        	}
		        }
            },
            error: function(e) {
                error_div("An error occured while connecting to server. (1)");
            }
        });
    }
}
function change_pw() {
    window.location="/yicrc/change_password.php";
}
function submit_frm() {
    if(confirm("Save changes?")) {
        var err_msg="";
        var err_div=document.getElementById("err_div");
        err_div.style.display="none";
        document.getElementById("user_bday_label").style.color="black";
        document.getElementById("user_mobile_label").style.color="black";
        document.getElementById("user_room_2_label").style.color="black";
        var bday = document.getElementById("user_bday");
        if(bday.value<19000000 || bday.value>21000000) {
            err_msg="Date of birth should be written in YYYYMMDD format (e.g. 19980102).  "
            document.getElementById("user_bday_label").style.color="#EE220C";
        }
        var room_2 = document.getElementById("user_room_2");
        if(!(room_2.value>=300 && room_2.value<=1300)) {
            err_msg+="Room number should ONLY contain your room number. Buildings are specified in the 'buildings' section.  ";
            document.getElementById("user_room_2_label").style.color="#EE220C";
        }
        var items = [gei("user_type").value, 
            gei("user_eng_name_first").value, 
            gei("user_eng_name_last").value, 
            gei("user_name").value, 
            gei("user_id_td").innerHTML, 
            gei("user_bday").value, 
            gei("user_gender").value,  
            gei("user_email").value, 
            gei("user_college").value, 
            gei("user_major").value, 
            gei("user_house").value, 
            gei("user_ra").value, 
            gei("user_room_1").value, 
            gei("user_room_2").value];
        var prcd=true;
        for(var i=0; i<items.length;i++) {
            if(items[i]==""||items[i]==null) {
                prcd=false;
            }
        }

        if(prcd) {
            err_div.innerHTML="";
            err_div.style.display="none";
            if(err_msg=="") {
                var ori_user_type = document.getElementById("user_type_div").innerText;
                var new_user_type = document.getElementById("user_type").value;
                var str="";
                if(ori_user_type=="RC Student") {
                    str="your RA";
                } else if(ori_user_type=="House RA") {
                    str="the RM or Chief RA";
                } else if(ori_user_type=="Chief RA") {
                    str="the RM";
                } else {
                    str="the administrator";
                }
                if(gei("user_type_div").innerText!="House RA" && gei("user_type_div").innerText!="Chief RA" && gei("user_type_div").innerText!="RM" && gei("user_type_div").innerText!="Administrative RA") {
                    if(ori_user_type!=new_user_type) {
                        if(confirm("WARNING: You have changed your account type. If you change your account type, your account will be disabled and become unaccessible to you until "+str+" approves this change. Do you wish to continue?")) {
                            document.getElementById("user_accepted").value="2";
                            submit_edit();
                        }
                    } else {
                        document.getElementById("user_accepted").value="1";
                        submit_edit();
                    }
                } else {
                    if(ori_user_type!=new_user_type) {
                        if(new_user_type=="Chief RA" || new_user_type=="RM") {
                            if(ori_user_type=="RM") {
                                submit_edit();
                            } else {
                                alert("Only RMs can change the account type to "+new_user_type);
                            }
                        } else if(new_user_type=="House RA" || new_user_type=="Administrative RA") {
                            if(ori_user_type=="RM" || ori_user_type=="Chief RA") {
                                submit_edit();
                            } else {
                                alert("Only RMs and Chief RAs can change the account type to "+new_user_type);
                            }
                        } else {
                            submit_edit();
                        }
                    } else {
                        submit_edit();
                    }
                }
            } else {
                err_div.innerHTML=err_msg;
                err_div.style.display="";
            }
        } else {
            //err_div.innerHTML="All fields except for the last one are required.";
            error_div("All fields except for the last one are required.", true);
        }
    }
}
function submit_edit() {
    //alert("submit_edit()");
    var err_div=document.getElementById("err_div");
    gei("loader").style.display="";
    gei("content_div").style.display="none";
    $.ajax({
        url: "/yicrc/account_load.php",
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
            err_div.innerHTML="There was a problem connecting to the server. Please try again later.";
            err_div.style.display="";
        },
        complete: function() {
            gei("loader").style.display="none";
            gei("content_div").style.display="";
            gei("account_edit").style.display="none";
            gei("account_view").style.display="";
            gei("discard_changes_btn").style.display="none";
            gei("edit_btn").innerHTML="Edit";
            gei("change_pw_btn").style.display="";
            gei("delete_account_btn").style.display="none";
            fill_userinfo();
        }
    });
}
function delete_user() {
    alert("delete user");
}
function add_btn(name_class, text) {
    var str="";
    str="<button ";
    str+='class="'+name_class+'" onclick="sup_btn_click(this)">';
    str+=text;
    str+="</button>";
    return str;
}
</script>
</body>
</html>