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
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
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
    <div class="col-5">
        <h1 id="ev_list_h1">RC Events</h1>
        <table style="border-collapse: collapse; border: none; background: none; padding: 0; margin: 0 0 10px 0; width: 100%;">
            <tr>
                <td style="width: 50%;vertical-align:top;">
                    <h3 style="margin: 0 0 10px 0;">Search by date:</h3>
                    <div id="search_date_div" style="margin: 0;">
                        <div class="sel_container" style="margin: 0 3px 0 0;">
                            <select id="ev_date_range_year" name="ev_date_range_year" onchange="load_events()">
                                <option value="0">Year</option>
                            </select>
                            <p>▼</p>
                        </div>
                        <div class="sel_container" style="margin: 0;">
                            <select id="ev_date_range_month" name="ev_date_range_month" onchange="load_events()">
                                <option value="0">Month</option>
                                <option value="1">1</option> <option value="2">2</option> <option value="3">3</option> <option value="4">4</option>
                                <option value="5">5</option> <option value="6">6</option> <option value="7">7</option> <option value="8">8</option>
                                <option value="9">9</option> <option value="10">10</option> <option value="11">11</option> <option value="12">12</option>
                            </select>
                            <p>▼</p>
                        </div>
                    </div>
                </td>
                <td style="width: 50%;vertical-align:top;">
                    <h3 style="margin: 0 0 10px 0;">Search by name:</h3>
                    <div id="search_name_div" style="margin: 0;padding: 0;">
                        <input style="width: 100%; margin-bottom: 10px; padding: 5px 10px 5px 10px;" class="small" id="ev_name_search" placeholder="Search by event name" onkeydown="if(event.keyCode==13) { ev_search(); }"/>
                        <button class="blue small" id="search_btn" onclick="ev_search()">Search</button>
                        <button class="red small" id="clr_btn" style="display: none;" onclick="clear_search()">Clear</button>
                    </div><br />
                </td>
            </tr>
        </table>
        <div id="ev_list_div"></div>
        <?php
            if(isset($_SESSION["user_type"])) {
                if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
                    echo '<button class="blue" onclick="add_event()">Add Event..</button>';
                    echo '<script type="text/javascript">
                    		function add_event() { 
	                    		var is_mobile_width=true;
							    if(window.innerWidth>768) {
							        is_mobile_width=false;
							    }
	                    		if(is_mobile_width) {
				                    window.location="/yicrc/eng/manage_event_2.php";   
				                } else {
				                    var add_event=window.open("/yicrc/eng/manage_event_2.php", "Add Event","toolbar=no,menubar=no,width=450px,height=700px,resizable=yes");
				                }
                    		}
                    	</script>';
                }
            }
        ?>
        <p style="color: #0A3879; cursor: pointer;" onclick="policy_read()"><br /><u>Read Sign-up/Cancellation Policy</u></p>
    </div>
    <div class="col-7">
        <h1 id="ev_details_h1">Event Details</h1>
        <h3 class="show_mobile" style="cursor: pointer;" onclick="window.scrollTo(0,0);">&lt; Return to Event List</h3>
        <table id="ev_details_table" class="list details" style="display: none;">
            <tr>
                <td colspan="2" id="td_ev_name"></td>
            </tr>
            <tr>
                <td>Date/Time</td>
                <td id="td_ev_time"></td>
            </tr>
            <tr>
                <td>Place</td>
                <td id="td_ev_place"></td>
            </tr>
            <tr>
                <td colspan="2" style="border-bottom:none; padding-bottom: 0;">Description</td>
            </tr>
            <tr>
                <td id="td_ev_description" colspan="2" style="font-family: Helvetica, Arial, nanumbarun_regular; font-weight: normal;"></td>
            </tr>
            <tr>
                <td>Event Type</td>
                <td id="td_ev_type"></td>
            </tr>
            <tr id="tr_ev_house">
                <td>House</td>
                <td id="td_ev_house"></td>
            </tr>
            <tr style="display: none;">
                <td>Event Code</td>
                <td id="td_ev_code"></td>
            </tr>
            <tr id="tr_ev_points">
                <td>RC Points</td>
                <td id="td_ev_points"></td>
            </tr>
            <tr>
                <td>Limit Capacity</td>
                <td id="td_ev_capacity_optn"></td>
            </tr>
            <tr id="tr_ev_capacity">
                <td>Capacity</td>
                <td id="td_ev_capacity"></td>
            </tr>
            <tr>
                <td>Sign-up method</td>
                <td id="td_sup_method"></td>
            </tr>
            <tr id="tr_sup_time">
                <td>Sign-up period</td>
                <td id="td_sup_time"></td>
            </tr>
            <tr>
                <td>Waiting List</td>
                <td id="td_sup_waiting_optn"></td>
            </tr>
            <tr id="tr_ev_supvsr" style="display: none;">
                <td>Event Manager</td>
                <td id="td_ev_supvsr"></td>
            </tr>
            <tr>
                <td>Attendance</td>
                <td id="td_ev_att"></td>
            </tr>
        </table>
        <input type="hidden" id="ev_supvsr_ip">
        <div id="ev_details_err" class="status">Click on an event to view event details</div>
        <h3 id="status_title" style="display: none">My status</h3>
        <div class="status" id="my_status_div" style="display: none">Loading..</div>
        <div id="btn_div_all">
        	<div id="btn_div_1" class="col-6 btn_div half_btn_left"></div>
        	<div id="btn_div_2" class="col-6 btn_div half_btn_right"></div>
        </div>
    </div>
</div>
<div style="display: none;" id="user_id_div"><?php echo $_SESSION["user_id"]; ?></div>
<div style="display: none;" id="user_type_div"><?php echo $_SESSION["user_type"]; ?></div>
<div style="display: none;" id="user_house_div"><?php echo $_SESSION["user_house"]; ?></div>
<div style="display: none;" id="sup_order_div"></div>
<div style="display: none;" id="my_status_text_div"></div>
<div style="display: none;" id="my_ra_div"><?php if(isset($_SESSION["user_ra"])) { echo $_SESSION["user_ra"]; } ?></div>
<form action="/yicrc/eng/participants_2.php" id="part_form" method="post">
    <input type="hidden" id="part_ev_code" name="ev_code" />
    <input type="hidden" id="part_status" name="status" />
</form>
<script type="text/javascript">
function gei(x) {
    return document.getElementById(x);
}
function chl(x,y) {
	document.getElementById(x).innerHTML = y;
}
function error_div(msg, show) {
    if(!msg) {
        gei("ev_details_err").style.display="none";
        gei("ev_details_err").innerHTML="";
    } else {
        gei("ev_details_err").innerHTML=msg;
        gei("ev_details_err").className="status redd";
        gei("ev_details_err").style.display="";
        if(show===undefined) {
            show=false;
        }
        if(show) {
            gei("ev_details_err").scrollIntoView(true); 
        }
    }
}
load_events();
<?php
    if(isset($_GET["ev_code"])) {
        echo 'gei("ev_details_err").innerHTML="Loading..";';
        echo 'ev_click('.trim($_GET["ev_code"]).');';
    }
?>
function load_events() {
    gei("content_div").style.display="none";
    gei("loader").style.display="";
    gei("ev_name_search").value="";
    var out_ev_list=null;
    out_ev_list='<table id="ev_list_table" class="list"><tr><td id="ev_list_thead">Event List</td></tr><tr id="search_none_tr" style="display: none; color: red; text-align: center;border-bottom: 4px solid #0E69B1;"><td></td></tr>';
    $.ajax({
        url: "event_list_load.php",
        type: "POST",
        data: {"ev_date_range_year":gei("ev_date_range_year").value, "ev_date_range_month":gei("ev_date_range_month").value},
        success: function(data) {
            var list=null; 
            try {
                list=JSON.parse(data);
            } catch(e) {
                alert(data);
            }
            if(list!=null) {
                if(Object.keys(list).length==0) {
                    out_ev_list +='<tr class="header"><td style="text-align: center;">No event to display.</td></tr>';
                } else {
                    for(var i=0; i<Object.keys(list).length; i++) {
                        out_ev_list += '<tr><td style="cursor: pointer;" onclick="ev_click('+list[i].ev_code+')">';
                        out_ev_list += '<span class="ev_name">'+list[i].ev_name+'</span><br />';
                        out_ev_list += '<span class="ev_details">'+list[i].ev_time_start+' ~ '+list[i].ev_time_end+'<br />@'+list[i].ev_place+'</span>';
                        out_ev_list += '</td></tr>';
                    }
                }
            } else {
                out_ev_list +='<tr><td style="color: red; text-align: center;">No events to display. (n)</td></tr>';
            }
            out_ev_list += '</table>';
        },
        error: function(xhr, status, msg) {
            out_ev_list+='<tr><td style="color: red; text-align: center;">Could not load events. Check your network connection.</td></tr>';
        },
        complete: function() {
            gei("content_div").style.display="";
            gei("loader").style.display="none";
            gei("ev_list_div").innerHTML=out_ev_list;
        }
    });
}
    
add_year();
function add_year() {
	var sel_year = document.getElementById("ev_date_range_year");
	var d = new Date();
	var i;
	var s;
    var u_type=document.getElementById("user_type_div").innerText;
	if(u_type=="House RA" || u_type=="Chief RA" || u_type=="RM") {
        $("#ev_date_range_year").empty();
        var option = document.createElement("option");
        option.text="Year";
        option.value="0";
        option.disabled=true;
        sel_year.add(option);
        for(i=0;i<5;i++) {
            var option = document.createElement("option");
            s=d.getFullYear()+i-2;
            option.text=s;
            option.value=s;
            sel_year.add(option);
        }
        sel_year.value=d.getFullYear();
    } else {
        $("#ev_date_range_year").empty();
        var option = document.createElement("option");
        option.text="Year";
        option.value="0";
        option.disabled=true;
        sel_year.add(option);
        
        var option = document.createElement("option");
        s=d.getFullYear();
        option.text=s;
        option.value=s;
        sel_year.add(option);
    }   
}

function policy_read() {
    if(window.innerWidth>768) {
        window.open("/yicrc/policy.php", "Policies","toolbar=no,menubar=no,width=400px,height=600px,resizable=yes");
    } else {
        window.location="/yicrc/policy.php";
    }
}

function ev_click(x) {
    fill_details(x);
    /*
    if(window.innerWidth<=768) {
        gei("ev_details_h1").scrollIntoView(true);   
    }
    */
}
function ev_search() {
    gei("loader").style.display="";
    gei("content_div").style.display="none";
    var query=document.getElementById("ev_name_search").value;
    if(document.getElementById("ev_list_table")!=null) {
        var list=document.getElementById("ev_list_table").children[0].children;
        //document.getElementById("ev_list_table").children[0].children[2].children[0].children[0]
        if(query=="") {
            clear_search();
        } else {
            var hide_count=1;
            for(var i=2; i<list.length; i++) {
                show=false;
                if(list[i].children[0].children[0].innerHTML.toUpperCase().indexOf(query.toUpperCase())>=0) {
                    show=true;
                }
                if(show) {
                    list[i].style.display="";
                } else {
                    list[i].style.display="none";
                    hide_count++;
                }
            }
            if(hide_count==list.length) {
                gei("search_none_tr").style.display="";
                gei("search_none_tr").children[0].innerHTML="No event with this name";
                if(gei("ev_date_range_year").value!=0) {
                    var month_text=["","January","February","March","April","May","June","July","August","September","October","November","December"];
                    gei("search_none_tr").children[0].innerHTML+=" in "+month_text[gei("ev_date_range_month").value];
                    if(gei("ev_date_range_month").value!=0) {
                        gei("search_none_tr").children[0].innerHTML+=", ";
                    }
                    gei("search_none_tr").children[0].innerHTML+=gei("ev_date_range_year").value;
                }
            } else {
                gei("search_none_tr").style.display="none";
                gei("search_none_tr").children[0].innerHTML="";
            }
            gei("clr_btn").style.display="";
            gei("ev_list_thead").innerText="Search Result";
        }
    }
    gei("loader").style.display="none";
    gei("content_div").style.display="";
}
function clear_search() {
    gei("loader").style.display="";
    gei("content_div").style.display="none";
    if(document.getElementById("ev_list_table")!=null) {
        var list=document.getElementById("ev_list_table").children[0].children;
        for(var i=0; i<list.length; i++) {
            list[i].style.display="";
        }
        list[1].style.display="none";
    }
    gei("ev_name_search").value="";
    gei("clr_btn").style.display="none";
    gei("loader").style.display="none";
    gei("ev_list_thead").innerText="Event List";
    gei("content_div").style.display="";
}
function fill_details(ev_code) {
	var ev = null;
    $.ajax({
        url:"event_load.php",
        type:"POST",
        data:{"ev_code":ev_code},
        success: function(data) {
            try {
                ev=JSON.parse(data);
            } catch(e) {
                alert(data);
                //window.location="/yicrc/rc_events.php";
            }
            if(ev!=null) {
                chl("td_ev_name",ev.ev_name);
                chl("td_ev_time",ev.ev_time_start+" ~ "+ev.ev_time_end);
                chl("td_ev_place",ev.ev_place);
                //chl("td_ev_description",ev.ev_description);
                document.getElementById("td_ev_description").innerText=ev.ev_description;
                //chl("td_ev_type",ev.ev_type);
                switch(ev.ev_type) {
                    case 1:
                        chl("td_ev_type", "House Event");
                        gei("tr_ev_house").style.display="";
                        break;
                    case 2:
                        chl("td_ev_type", "RA Individual Event");
                        gei("tr_ev_house").style.display="";
                        break;
                    case 3:
                        chl("td_ev_type", "RC Event");
                        gei("tr_ev_house").style.display="none";
                        break;
                    case 4:
                        chl("td_ev_type", "Unspecified");
                        gei("tr_ev_house").style.display="none";
                        break;
                    default:
                        chl("td_ev_type", "ERROR");
                        gei("tr_ev_house").style.display="";
                        break;
                }
                var house=["Appenzeller", "Evergreen", "Wonchul", "Undrwood", "Yun, Dong-joo", "Muak", "Chiwon", "Baekyang", "Cheongsong", "Yongjae", "Avison", "Allen", "Other"];
                chl("td_ev_house", house[ev.ev_house]);
                if(gei("user_house_div").innerHTML!=ev.ev_house) {
                    //alert("[WARNING] This is "+house[ev.ev_house]+" House event. Make sure "+house[gei("user_house_div").innerHTML]+" students can participate in this event before signing up.");
                    gei("td_ev_house").style.color="red";
                    gei("td_ev_house").style.fontWeight="bold";
                } else {
                    gei("td_ev_house").style.color="";
                    gei("td_ev_house").style.fontWeight="";
                }
                chl("td_ev_code",ev.ev_code);
                if(ev.ev_points==0) {
                    document.getElementById("tr_ev_points").style.display="none";
                } else {
                    document.getElementById("tr_ev_points").style.display="";
                    chl("td_ev_points",ev.ev_points);   
                }
                switch(ev.ev_capacity_optn) {
                	case 0:
                		chl("td_ev_capacity_optn", "NO");
                		document.getElementById("tr_ev_capacity").style.display="none";
                		chl("td_ev_capacity","");
                		break;
                	case 1:
                		chl("td_ev_capacity_optn", "YES");
                		document.getElementById("tr_ev_capacity").style.display="";
                		if(ev.ev_capacity>1) {
                			chl("td_ev_capacity",ev.ev_capacity+" people");
                		} else {
                			chl("td_ev_capacity",ev.ev_capacity+" person");
                		}
                		break;
                }
                
                switch(ev.sup_method) {
                    case 1: 
                    	chl("td_sup_method", "TBA");
                    	gei("tr_sup_time").style.display="none";
                    	break;
                    case 2:
                    	chl("td_sup_method", "Online sign-up required");
                    	gei("tr_sup_time").style.display="";
                    	break;
                    case 3:
                    	chl("td_sup_method", "First-Come-First-Serve, Sign-up not required");
                    	gei("tr_sup_time").style.display="none";
                    	break;
                    case 4:
                    	chl("td_sup_method", "Sign-up not required");
                    	gei("tr_sup_time").style.display="none";
                    	break;
                    default:
                    	chl("td_sup_method", ev.sup_method);
                    	gei("tr_sup_time").style.display="";
                    	break;
                }
                chl("td_sup_time",ev.sup_time_st+" ~ "+ev.sup_time_end);
                switch(ev.sup_waiting_optn) {
                    case 0: chl("td_sup_waiting_optn","NO"); break;
                    case 1: chl("td_sup_waiting_optn", "YES");break;
                }
                switch(ev.ev_att) {
                    case 1: chl("td_ev_att","Check attendance once"); break;
                    case 2: chl("td_ev_att","Check attendance twice"); break;
                    case 3: chl("td_ev_att","Electronic Roster (전자출결)"); break;
                    case 4: chl("td_ev_att","Attendance not checked"); break;
                    default: chl("td_ev_att", ev.ev_att); break;
                }//1: Check attendance once, 2: Check attendance twice 3: Electronic Roster (전자출결) 4: Do not check attendance
                //chl("td_ev_supvsr",ev.ev_supvsr);
                get_ev_supvsr(ev.ev_supvsr);
                gei("ev_supvsr_ip").value=ev.ev_supvsr;
                get_status(ev, ev_code);
                gei("ev_details_table").style.display="";
                gei("ev_details_err").style.display="none";
            }
        },
        error: function(e) {
            //window.location="/yicrc/rc_events.php";
            gei("ev_details_table").style.display="";
            gei("ev_details_err").style.display="";
            gei("ev_details_err").className="status redd";
            gei("ev_details_err").innerHTML="Unable to retrieve event information. Please try again later";
        }
    });
}
    
function get_ev_supvsr(supvsr_id) {
    if(supvsr_id==0) {
        gei("tr_ev_supvsr").style.display="none";
    } else {
        $.ajax({
            url: '/yicrc/eng/get_ra.php',
            type: "POST",
            data: {"user_id":supvsr_id},
            success: function(data) {
                var ra=null;
                try {
                    ra=JSON.parse(data);
                } catch(e) {
                    alert(data);
                }
                if(ra!=null) {
                    gei("td_ev_supvsr").innerHTML=ra[0].name;
                }
                //gei("tr_ev_supvsr").style.display="";
            },
            error: function(x,a,b) {
                alert("There was an error loading event information (Event manager).");
            }
        });   
    }
}
    
function get_status(ev, ev_code) {
    var user_type=gei("user_type_div").innerText;
    var user_id=gei("user_id_div").innerText;
    var stdiv = gei("my_status_div");
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
                    stdiv.className="status redd";
                    stdiv.innerHTML="Error: Could not load your sign-up status from server. (1)";
                }
                if(stat!=null) {
                    stdiv.style.display="";
                    gei("my_status_text_div").innerHTML=stat.status_text;
                    if(stat.status_text=="CANCELLED") {
                        stdiv.className="status redd";
                        stdiv.innerHTML="CANCELLED";
                    } else if(stat.status_text=="WAITING") {
                        stdiv.className="status yellowd";
                        var str_out="ON WAITING LIST #";
                        str_out += stat.wait_num;
                        str_out += " (NOT SIGNED UP)";
                        stdiv.innerHTML=str_out;
                    } else if(stat.status_text=="WAITING_CANCEL_N") {
                        stdiv.className="status yellowd";
                        var str_out="ON WAITING LIST #";
                        str_out += stat.wait_num;
                        str_out += " (NOT SIGNED UP) - WAITING CANCELLATION APPROVAL";
                        stdiv.innerHTML=str_out;
                    } else if(stat.status_text=="WAITING_CANCEL_Y") {
                        stdiv.className="status greend";
                        stdiv.innerHTML="SIGNED UP - WAITING CANCELLATION APPROVAL";
                    } else if(stat.status_text=="SIGNED_UP") {
                        stdiv.className="status greend";
                        stdiv.innerHTML="SIGNED UP";
                    } else if(stat.status_text=="NOT_SIGNED_UP") {
                        stdiv.className="status gray";
                        stdiv.innerHTML="NOT SIGNED UP";
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
                    show_btn(ev, stat.status_text);
                } else {
                    gei("my_status_div").style.display="none";
                }
                gei("status_title").style.display="";
                stdiv.style.display="";
            },
            error: function(e) {
                stdiv.className="status redd";
                stdiv.innerHTML+="Error: Could not load your sign-up status from server. (2)";
                gei("status_title").style.display="";
                stdiv.style.display="";
            },
            complete: function() {
                gei("loader").style.display="none";
                gei("content_div").style.display="";
                if(window.innerWidth<=768) {
                    gei("ev_details_h1").scrollIntoView(true);   
                }
            }
        });
    } else {
        gei("status_title").style.display="none";
        stdiv.style.display="none";
        show_btn(ev);
        if(window.innerWidth<=768) {
            gei("ev_details_h1").scrollIntoView(true);   
        }
    }
}
function show_btn(ev, my_status) {
    var btn_div_1=gei("btn_div_1");
    var btn_div_2=gei("btn_div_2");
    btn_div_1.innerHTML="";
    btn_div_2.innerHTML="";
    var user_type_div=gei("user_type_div").innerText;
    if(user_type_div=="RC Student"||user_type_div=="Non-RC Student") {
        if(my_status===undefined) {
            btn_div_1.style.display="none";
        } else {
            if(my_status!="" && my_status!=null) {
                switch(my_status) {
                    case "SIGNED_UP":
                        if(ev.sup_participant_publicity>=2||ev.sup_waiting_publicity>=2) {
                            btn_div_1.innerHTML += add_btn("blue", "Participants");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Participants");   
                        }
                        //btn_div_1.innerHTML += "<br />";
                        if(ev.ev_cancel_optn==1) {
                            btn_div_2.innerHTML += add_btn("disabled", "Cancel sign-up");   
                        } else {
                            btn_div_2.innerHTML += add_btn("red", "Cancel sign-up");
                        }
                        break;
                    case "WAITING_CANCEL_Y":
                        if(ev.sup_participant_publicity>=3||ev.sup_waiting_publicity>=3) {
                            btn_div_1.innerHTML += add_btn("blue", "Participants");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Participants");   
                        }
                        //btn_div_1.innerHTML += "<br />";
                        btn_div_2.innerHTML += add_btn("red", "Don't cancel");
                        break;
                    case "WAITING":
                        if(ev.sup_participant_publicity>=3||ev.sup_waiting_publicity>=3) {
                            btn_div_1.innerHTML += add_btn("blue", "Participants");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Participants");   
                        }
                        //btn_div_1.innerHTML += "<br />";
                        if(ev.ev_cancel_optn==1) {
                            btn_div_2.innerHTML += add_btn("disabled", "Cancel sign-up");   
                        } else {
                            btn_div_2.innerHTML += add_btn("red", "Cancel sign-up");
                        }
                        break;
                    case "WAITING_CANCEL_N":
                        if(ev.sup_participant_publicity>=3||ev.sup_waiting_publicity>=3) {
                            btn_div_1.innerHTML += add_btn("blue", "Participants");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Participants");   
                        }
                        //btn_div_1.innerHTML += "<br />";
                        btn_div_2.innerHTML += add_btn("red", "Don't cancel");
                        break;
                    case "CANCELLED":
                        if(within_sup_date(gei("td_sup_time").innerText)) {
                            btn_div_1.innerHTML += add_btn("blue", "Sign up");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Sign up");   
                        }
                        //btn_div_1.innerHTML += "<br />";
                        if(ev.sup_participant_publicity>=4||ev.sup_waiting_publicity>=4) {
                            btn_div_2.innerHTML += add_btn("blue", "Participants");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Participants");   
                        }
                        break;
                    case "NOT_SIGNED_UP":
                        if(within_sup_date(gei("td_sup_time").innerText)) {
                            btn_div_1.innerHTML += add_btn("blue", "Sign up");   
                        } else {
                            btn_div_1.innerHTML += add_btn("disabled", "Sign up");
                        }
                        //btn_div_1.innerHTML += "<br />";
                        if(ev.sup_participant_publicity>=4||ev.sup_waiting_publicity>=4) {
                            btn_div_2.innerHTML += add_btn("blue", "Participants");   
                        }
                        break;
                }
            }
        }
    } else if(user_type_div=="House RA" || user_type_div=="Chief RA" || user_type_div=="RM") {
        btn_div_1.innerHTML += add_btn("blue", "Edit event");
        btn_div_1.innerHTML += add_btn("blue_border", "Manage participants");
        btn_div_1.innerHTML += add_btn("blue_border", "Check attendance");
        btn_div_2.innerHTML += add_btn("red", "Delete event");
        //btn_div_2.innerHTML += add_btn("blue_border", "Participants");
        btn_div_1.style.display="";
        btn_div_2.style.display=""; 
        //btn_div_2.innerHTML += add_btn("red", "Stop sign-up");
    } else {
        error_div("Error retrieving user type", true);
    }
}
function delete_event() {
    if(confirm("Delete this event? All data including sign-up records will be permanently lost.")) {
        if(confirm("[WARNING] Are you really sure you want to delete this event? You CANNOT undo this action.")) {
            document.getElementById("content_div").style.display="none";
            document.getElementById("loader").style.display="";
            var ev_code=document.getElementById("td_ev_code").innerText;
            if(ev_code && ev_code!="" && ev_code!=null) {
                $.ajax({
                    url: "event_write.php",
                    type: "POST",
                    data: {"action_type":"delete", "ev_code":ev_code},
                    success: function(data) {
                        alert(data);
                        window.location="/yicrc/eng/rc_events_2.php";
                    }, 
                    error: function(xhr,status,msg) {
                        alert("There was an error connecting to the server. Please try again later.");
                    }
                });
            } else {
                error_div("Event not specified.", true);
            }
        }
    }
}
function sup_btn_click(x) {
    var ev_code=document.getElementById("td_ev_code").innerText;
    var is_mobile_width=true;
    if(window.innerWidth>768) {
        is_mobile_width=false;
    }
    if(x.className.indexOf("disabled")>=0) {
        if(x.innerHTML=="Sign up") {
            if(within_sup_date(gei("td_sup_time").innerText)) {
                gei("ev_details_err").innerHTML="Not within sign-up period.";
                gei("ev_details_err").className="status redd";
                gei("ev_details_err").style.display="";
                gei("ev_details_err").scrollIntoView(true);
            } else {
                gei("ev_details_err").innerHTML="Not within sign-up period.";
                gei("ev_details_err").className="status redd";
                gei("ev_details_err").style.display="";
                gei("ev_details_err").scrollIntoView(true);
            }
        } else {
            alert("Not available");
            gei("ev_details_err").style.display="none";
        }
    } else {
        //alert(x.innerHTML);
        switch(x.innerHTML) {
            case "Sign up":
                var stdiv = gei("my_status_div");
                var alert_str="Do you wish to sign up for this event?";
                if(stdiv.innerText.indexOf("CANCELLED")>=0) {
                    alert_str+=" Your original sign-up data will be permanently deleted."
                }
                if(within_sup_date(gei("td_sup_time").innerText)) {
                    gei("ev_details_err").style.display="none";
                    gei("ev_details_err").className="status grayd";
                    if(confirm(alert_str)) {
                        if(gei("td_ev_type").innerText=="RA Individual Event") {
                            if(gei("my_ra_div").innerText==gei("ev_supvsr_ip").value) {
                                window.location="signup_2.php?ev_code="+ev_code;
                            } else {
                                alert("This is a RA Individual Event. Only students in this RA's group can sign up.");
                            }
                        } else {
                            window.location="signup_2.php?ev_code="+ev_code;
                        }
                    }
                } else {
                    error_div("Not within sign-up period", true);
                }
				break;
            case "Edit event":
                if(is_mobile_width) {
                    window.location="manage_event_2.php?ev_code="+ev_code;   
                } else {
                    var mn_event=window.open("manage_event_2.php?ev_code="+ev_code, "Manage Event","toolbar=no,menubar=no,width=400px,height=700px,resizable=yes");
                }
                break;
            case "Delete event":
                //error_div("Feature in development", true);
                delete_event();
                break;
            case "Manage participants":
                /*
                if(is_mobile_width) {
                    window.location="/yicrc/manage_participants.php?ev_code="+ev_code;   
                } else {
                    //window.location="/yicrc/manage_participants_2.php?ev_code="+ev_code;
                    window.location="manage_participants_3.php?ev_code="+ev_code; 
                }*/
                window.location="manage_participants_3.php?ev_code="+ev_code; 
                break;
            case "Check attendance":
                if(is_mobile_width) {
                    window.location="check_attendance_2.php?ev_code="+ev_code;
                } else {
                    var chk_att_window=window.open("check_attendance_2.php?ev_code="+ev_code, "Check Attendance", "toolbar=no,menubar=no,width=400px,height=700px,resizable=yes");
                } 
                break;
            case "Participants":
                gei("part_ev_code").value=gei("td_ev_code").innerText;
                var stat_input = gei("part_status");
                switch(document.getElementById("my_status_text_div").innerText) {
                    case "SIGNED_UP":
                        stat_input.value="2";
                        break;
                    case "WAITING_CANCEL_Y":
                        stat_input.value="2";
                        break;
                    case "WAITING_CANCEL_N":
                        stat_input.value="1";
                        break;
                    case "WAITING":
                        stat_input.value="1";
                        break;
                    case "CANCELLED":
                        stat_input.value="0";
                        break;
                    case "NOT SIGNED UP":
                        stat_input.value="0";
                        break;
                    default:
                        stat_input.value="0";
                        break;
                }
                if(stat_input.value!="" && gei("part_ev_code").value!="") {
                    gei("part_form").submit();   
                }
                break;
            case "Cancel sign-up":
                window.location="cancel_2.php?ev_code="+ev_code;
                break;
            case "Don't cancel":
            	if(confirm("Do you wish not to cancel?")) {
                    //var ev_code_div = document.getElementById("ev_code_div").innerText;
                    var action_type_student = "do_not_cancel";
                    if(document.getElementById("sup_order_div")!=null) {
                        var sup_order_div = document.getElementById("sup_order_div").innerText;
                        $.ajax({
                            url: "cancel_write.php",
                            type: "POST",
                            data: {"ev_code":ev_code, "sup_order":sup_order_div, "action_type_student":action_type_student},
                            success: function(data) {
                                alert(data);
                            },
                            error: function(e) {
                                alert("There has been an error. Please try again later. ("+e.message+")");
                            },
                            complete: function() {
                                location.reload();
                            }
                        });
                    }
                }
            	break
            default:
                error_div("Feature in development", true); 
                break;
        }
    }
}
function within_sup_date(sup_time_text) {
    var sup_st_var = sup_time_text.split(" ~ ")[0];
    var sup_end_var = sup_time_text.split(" ~ ")[1];
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