<?php
session_start();
require_once 'config_2.php';
verify_user();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Participants - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
    <meta name="format-detection" content="telephone=no">
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
    <div id="loader_div" class="loader"></div>
    <div id="content_div" class="content_div" style="display: none;">
        <div class="col-2 hide_mobile"></div>
        <div class="col-4">
            <h1 id="title_h1" style="margin-bottom: 10px">Participants / Waiting List</h1>
            <h3 style="cursor: pointer;" onclick="window.location='rc_events_2.php'+<?php if(isset($_POST['ev_code'])) { echo "'?ev_code=".$_POST['ev_code']."'"; } ?>;">&lt; Return to Event Details</h3>
            <div id="err_div" class="status" style="display: none;"></div>
            <table class="list details">
                <tr>
                    <td colspan="2">Event Details</td>
                </tr>
                <tr>
                    <td>Event Name</td>
                    <td id="ev_name_td">Event date</td>
                </tr>
                <tr>
                    <td>Date/Time</td>
                    <td id="ev_time_td">Event date</td>
                </tr>
                <tr>
                    <td>Place</td>
                    <td id="ev_place_td">Event place</td>
                </tr>
                <tr>
                    <td>Sign-up period</td>
                    <td id="sup_time_td">sign-up period</td>
                </tr>
            </table>
            <p id="uptime">Last Updated: </p>
            <h2 id="participants_title"><br />Participants</h2>
            <div id="participants_div"></div>
        </div>
        <div class="col-4">
        <h2 id="waiting_title">Waiting List</h2>
            <div id="waiting_div"></div>
        </div>
        <div class="col-2 hide_mobile"></div>
    </div>
    
	<div style="display: none;" id="ev_code_div"><?php if(isset($_POST['ev_code'])) { echo trim($_POST['ev_code']); } ?></div>
    <div style="display: none;" id="part_status_div"><?php if(isset($_POST['status'])) { echo trim($_POST['status']); } ?></div>
    <div style="display: none;" id="user_id_div"><?php if(isset($_SESSION['user_id'])) { echo $_SESSION['user_id']; } ?></div>
    <div style="display: none;" id="user_type_div"><?php if(isset($_SESSION['user_type'])) { echo $_SESSION['user_type']; } ?></div>
    <div style="display: none" id="ev_capacity_optn_div"></div>
    <div style="display: none" id="ev_capacity_div"></div>
    <div style="display: none" id="sup_waiting_optn_div"></div>
        

<?php
if($_SERVER["REQUEST_METHOD"]=="POST") {
    if(isset($_POST['ev_code'])&&$_POST['ev_code']!="") {
        require_once 'config.php';
        get_evinfo();
        mysqli_close($link);
    }
} else {
    echo '<div class="status redd">Event not specified. Please close this window and try again.</div>';
}
function get_evinfo() {
    global $link;
    $sql = "SELECT ev_name, ev_time_start, ev_time_end, ev_place, ev_capacity_optn, ev_capacity, sup_time_st, sup_time_end, sup_participant_publicity, sup_waiting_publicity, sup_waiting_optn FROM yicrc_events WHERE ev_code = ?";
    if($stmt=mysqli_prepare($link, $sql)) {
        $ev_code_param=trim($_POST['ev_code']);
        mysqli_stmt_bind_param($stmt, "i", $ev_code_param);
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt, $ev_name_db, $ev_time_start_db, $ev_time_end_db, $ev_place_db, $ev_capacity_optn_db, $ev_capacity_db, $sup_time_st_db, $sup_time_end_db, $part_pub_db, $wait_pub_db, $wait_optn_db);
            while(mysqli_stmt_fetch($stmt)) {
                $ev_out=array();
                $ev_out['ev_name']=$ev_name_db;
                $ev_out['ev_time_start']=$ev_time_start_db;
                $ev_out['ev_time_end']=$ev_time_end_db;
                $ev_out['ev_place']=$ev_place_db;
                $ev_out['ev_capacity_optn']=$ev_capacity_optn_db;
                $ev_out['ev_capacity']=$ev_capacity_db;
                $ev_out['sup_time_st']=$sup_time_st_db;
                $ev_out['sup_time_end']=$sup_time_end_db;
                $ev_out['sup_participant_publicity']=$part_pub_db;
                $ev_out['sup_waiting_publicity']=$wait_pub_db;
                $ev_out['sup_waiting_optn']=$wait_optn_db;
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
fill_evinfo();
part_load();
function fill_evinfo() {
    if(document.getElementById("evinfo_get_div")!=null) {
        var info_div = document.getElementById("evinfo_get_div");
        var info = JSON.parse(info_div.innerText);
        document.getElementById("ev_name_td").innerHTML=info.ev_name;
        document.getElementById("ev_time_td").innerHTML=info.ev_time_start+" ~ "+info.ev_time_end;
        document.getElementById("ev_place_td").innerHTML=info.ev_place;
        document.getElementById("sup_time_td").innerHTML=info.sup_time_st+" ~ "+info.sup_time_end;
    } else {
        alert("Event not specified.");
    }
}
function goback() {
	window.location="/yicrc/event_details.php?ev_code="+document.getElementById("ev_code_div").innerText;
}
function error_div(msg, show) {
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
function user_type_num(t) {
    var user_type_n=0;
    switch(t) {
        case "RC Student":
            user_type_n=1;
            break;
        case "Non-RC Student":
            user_type_n=1;
            break;
        case "Administrative RA":
            user_type_n=2;
            break;
        case "House RA":
            user_type_n=3;
            break;
        case "Chief RA":
            user_type_n=4;
            break;
        case "RM":
            user_type_n=5;
            break;
        default:
            user_type_n=0;
            break;
    } 
    return user_type_n;
}
function part_load() {
    var id = document.getElementById("user_id_div").innerText;
    var user_type = document.getElementById("user_type_div").innerText;
    var ev_code = document.getElementById("ev_code_div").innerText;
    var info_div = document.getElementById("evinfo_get_div");
    var info=null;
    try {
        info = JSON.parse(info_div.innerText);
    } catch(e) {
        alert("Error: Failed to retrieve event information.");
        window.location="/yicrc/rc_events.php";
    }
    if(info!=null) {
        var capacity_optn = info.ev_capacity_optn;
        var capacity = info.ev_capacity;
        var waiting_optn = info.sup_waiting_optn;
        var out_text_part="";
        var out_text_wait="";
        var loader = document.getElementById("loader_div");
        var content = document.getElementById("content_div");
        var part_title = document.getElementById("participants_title");
        var wait_title = document.getElementById("waiting_title");
        loader.style.display="";
        content.style.display="none";
        part_title.innerHTML="<br />Participants ";
        wait_title.innerHTML="Waiting List ";
        var uptime = document.getElementById("uptime");
        uptime.innerHTML += " "+getTime("datetime24");
        $.ajax({
            url: 'participants_load.php',
            type: "POST",
            data: {"ev_code":ev_code},
            success: function(data) {
                out_text_part='<table class="list" style="text-align: center;"><tr><td>#</td><td>Name</td><td>Major</td></tr>';
                out_text_wait='<table class="list" style="text-align: center;"><tr><td>#</td><td>Name</td><td>Major</td></tr>';
                
                var type_num=0;
                switch(user_type) {
                    case "RC Student":
                        type_num=1;
                        break;
                    case "Non-RC Student":
                        type_num=1;
                        break;
                    case "Administrative RA":
                        type_num=2;
                        break;
                    case "House RA":
                        type_num=3;
                        break;
                    case "Chief RA":
                        type_num=4;
                        break;
                    case "RM":
                        type_num=5;
                        break;
                    default:
                        type_num=0;
                        break;
                }

                var part_pub = info.sup_participant_publicity; //1: RA, 2: RA+Part, 3: RA+Part+Waiting 4: All Students
                var wait_pub = info.sup_waiting_publicity;
                var part_view = false; //show participant list
                var wait_view = false; //show waiting list
                var part_status = document.getElementById("part_status_div").innerText; //0: not signed up, cancelled, 1: waiting, waiting_cancel_n, 2: signed up, waiting_cancel_y
                
                if(type_num>=3) {
                    part_view=true;
                    wait_view=true;
                } else if(type_num<3 && type_num>=1) {
                    if(part_status==2) { //2: signed up, waiting_cancel_y
                        if(part_pub>=2) {
                            part_view=true;
                        }
                        if(wait_pub>=2) {
                            wait_view=true;
                        }
                    } else if(part_status==1) { //1: waiting, waiting_cancel_n
                        if(part_pub>=3) {
                            part_view=true;
                        }
                        if(wait_pub>=3) {
                            wait_view=true;
                        }
                    } else if(part_status==0) { //0: Not signed up, cancelled
                        if(part_pub>=4) {
                            part_view=true;
                        }
                        if(wait_pub>=4) {
                            wait_view=true;
                        }
                    } else {
                        part_view=false;
                        wait_view=false;
                    }
                } else {
                    part_view=false;
                    wait_view=false;
                }
                
                var part=null;
                try {
                    part = JSON.parse(data);
                } catch (e) {
                    if(!part_view) {
                        out_text_part += "<tr><td colspan='3' style='color: #A9A9A9;'>";
                        switch(part_pub) {
                            case 1:
                                out_text_part += "Only visible to: RM/RAs";
                                break;
                            case 2:
                                out_text_part += "Only visible to: RM/RAs, participants";
                                break;
                            case 3:
                                out_text_part += "Only visible to: RM/RAs, participants..";
                                break;
                            case 4:
                                out_text_part += "Error (VIEW_PRIV_4)";
                                break;
                            default:
                                out_text_part += "Error (VIEW_PRIV: "+part_pub+")";
                                break;
                        }
                        out_text_part += "</td></tr>";
                    } else {
                        out_text_part += "<tr><td colspan='3' style='color: red;'>"+data+"</td></tr>";
                    }
                    
                    if(!wait_view) {
                        out_text_wait += "<tr><td colspan='3' style='color: #A9A9A9;'>";
                        switch(wait_pub) {
                            case 1:
                                out_text_wait += "Only visible to: RM/RAs";
                                break;
                            case 2:
                                out_text_wait += "Only visible to: RM/RAs, participants";
                                break;
                            case 3:
                                out_text_wait += "Only visible to: RM/RAs, participants..";
                                break;
                            case 4:
                                out_text_wait += "Error (VIEW_PRIV_4)";
                                break;
                            default:
                                out_text_wait += "Error (VIEW_PRIV: "+wait_pub+")";
                                break;
                        }
                        out_text_wait += "</td></tr>";
                    } else {
                        out_text_wait += "<tr><td colspan='3' style='color: red;'>"+data+"</td></tr>";
                    }
                    out_text_part+="</table>";
                    out_text_wait+="</table>";
                    document.getElementById("participants_div").innerHTML=out_text_part;
                    document.getElementById("waiting_div").innerHTML=out_text_wait;
                }
                
                if(part!=null) {
                    if(Object.keys(part).length>0) {
                        var part_num = 0; //number of participants
                        var wait_num = 0; //number of waiting list
                        var i=0;
                        if(capacity_optn==0) {
                            for(i=0; i<Object.keys(part).length; i++) {
                                if(part_view) {
                                    out_text_part += "<tr><td>";
                                    out_text_part += i+1;
                                    out_text_part += "</td><td>";
                                    out_text_part += part[i].user_eng_name_first+" "+part[i].user_eng_name_last;
                                    out_text_part += "</td><td>";
                                    out_text_part += part[i].user_major;
                                    out_text_part += "</td>";
                                    out_text_part += "</tr>";
                                }
                                part_num++;
                            }
                        } else {
                            if(waiting_optn==0) {
                                document.getElementById("waiting_title").style.display="none";
                                for(i=0; i<Object.keys(part).length; i++) {
                                    if(i<capacity) {
                                        if(part_view) {
                                            out_text_part += "<tr><td>";
                                            out_text_part += i+1;
                                            out_text_part += "</td><td>";
                                            out_text_part += part[i].user_eng_name_first+" "+part[i].user_eng_name_last;
                                            out_text_part += "</td><td>";
                                            out_text_part += part[i].user_major;
                                            out_text_part += "</td>";
                                            out_text_part += "</tr>";
                                        }
                                        part_num++;
                                    } else {
                                        break;
                                    }
                                }
                            } else {
                                document.getElementById("waiting_title").style.display="";
                                for(i=0; i<Object.keys(part).length; i++) {   
                                    if(i<capacity) {
                                        if(part_view) {
                                            out_text_part += "<tr><td>";
                                            out_text_part += i+1;
                                            out_text_part += "</td><td>";
                                            out_text_part += part[i].user_eng_name_first+" "+part[i].user_eng_name_last;
                                            out_text_part += "</td><td>";
                                            out_text_part += part[i].user_major;
                                            out_text_part += "</td>";
                                            out_text_part += "</tr>";
                                        }
                                        part_num++;
                                    } else {
                                        if(wait_view && waiting_optn==1) {
                                            out_text_wait += "<tr><td>";
                                            out_text_wait += i+1-capacity;
                                            out_text_wait += "</td><td>";
                                            out_text_wait += part[i].user_eng_name_first+" "+part[i].user_eng_name_last;
                                            out_text_wait += "</td><td>";
                                            out_text_wait += part[i].user_major;
                                            out_text_wait += "</td>";
                                            out_text_wait += "</tr>";
                                        }
                                        wait_num++;
                                    }
                                }
                            }
                        }
                        
                        if(!part_view) {
                            out_text_part += "<tr><td colspan='3' style='color: #A9A9A9;'>";
                            switch(part_pub) {
                                case 1:
                                    out_text_part += "Only visible to: RM/RAs";
                                    break;
                                case 2:
                                    out_text_part += "Only visible to: RM/RAs, participants";
                                    break;
                                case 3:
                                    out_text_part += "Only visible to: RM/RAs, participants..";
                                    break;
                                case 4:
                                    out_text_part += "Error (VIEW_PRIV_4)";
                                    break;
                                default:
                                    out_text_part += "Error (VIEW_PRIV: "+part_pub+")";
                                    break;
                            }
                            out_text_part += "</td></tr>";
                        }
                        if(!wait_view) {
                            out_text_wait += "<tr><td colspan='3' style='color: #A9A9A9;'>";
                            switch(wait_pub) {
                                case 1:
                                    out_text_wait += "Only visible to: RM/RAs";
                                    break;
                                case 2:
                                    out_text_wait += "Only visible to: RM/RAs, participants";
                                    break;
                                case 3:
                                    out_text_wait += "Only visible to: RM/RAs, participants..";
                                    break;
                                case 4:
                                    out_text_wait += "Error (VIEW_PRIV_4)";
                                    break;
                                default:
                                    out_text_wait += "Error (VIEW_PRIV: "+wait_pub+")";
                                    break;
                            }
                            out_text_wait += "</td></tr>";
                        }
                        if(part_view) {
                            part_title.innerHTML += "("+part_num+")";
                        }
                        if(Object.keys(part).length<=capacity) {
                            if(wait_view) {
                                out_text_wait+="<tr><td colspan='3' style='color: red;'>No data.</td></tr>";
                                wait_title.innerHTML += "(0)";
                            }
                        } else {
                            wait_title.innerHTML += "("+wait_num+")";
                        }
                    } else {
                        if(part_view) {
                            out_text_part+="<tr><td colspan='3' style='color: red;'>No data.</td></tr>";
                        }
                        if(wait_view) {
                            out_text_wait+="<tr><td colspan='3' style='color: red;'>No data.</td></tr>";
                        }
                        alert("There are no participants for this event.");
                        window.location="/yicrc/event_details.php?ev_code="+gei("ev_code_div").innerText;
                    }
                    out_text_part+="</table>";
                    out_text_wait+="</table>";
                    document.getElementById("participants_div").innerHTML=out_text_part;
                    document.getElementById("waiting_div").innerHTML=out_text_wait;
                }
            },
            error: function(e) {
                error_div("Error connecting to the server. (1)");
            },
            complete: function() {
                loader.style.display="none";
                content.style.display="";
            }
        });
    }   
}

</script>
</body>
</html>