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
	<title>Manage RAs - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td onclick="menu_click()">MENU</td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td onclick="logout_click()">LOG OUT</td> </tr>
</table>
<div id="loader" class="loader" style="display: none;"></div>
<div class="container" id="content_div" >
<h1>Manage RAs</h1>
<h2 onclick="window.location='/yicrc/index.php';">&lt; Return to Main Menu</h2>
    <table class="details">
        <tr>
            <td colspan="2">Login Information</td>
        </tr>
        <tr>
            <td>Name</td>
            <td id="user_name_td">
            <?php
                if(isset($_SESSION['user_eng_name_first']) && $_SESSION["user_eng_name_first"]!="" && isset($_SESSION['user_eng_name_last']) && $_SESSION["user_eng_name_last"]!="") {
                    echo trim($_SESSION["user_eng_name_first"])." ".trim($_SESSION["user_eng_name_last"]);
                } else {
                    //header("Location: index.php");
                }
            ?>
            </td>
        </tr>
        <tr>
            <td>User Type</td>
            <td id="user_type_td">
            <?php
                if(isset($_SESSION["user_type"]) && $_SESSION["user_type"]!="") {
                    echo trim($_SESSION["user_type"]);
                    if($_SESSION["user_type"]!="House RA" && $_SESSION["user_type"]!="Chief RA" && $_SESSION["user_type"]!="RM") {
                        //header("Location: index.php");
                    }
                } else {
                    header("Location: index.php");
                }         
            ?>
            </td>
        </tr>
        <tr>
            <td>House</td>
            <td id="user_house_td">
            <?php
                if(isset($_SESSION["user_house"])) {
                    $house_name=array("Appenzeller", "Evergreen", "Wonchul", "Undrwood", "Yun, Dong-joo", "Muak", "Chiwon", "Baekyang", "Cheongsong", "Yongjae", "Avison", "Allen", "Other");
                    echo $house_name[$_SESSION["user_house"]];
                    
                } else {
                    header("Location: index.php");
                }
            ?>
            </td>
        </tr>
    </table>
    <table style="border: none; border-collapse: collapse; margin-bottom: 30px;" id="search_table">
        <tr>
            <td style="width: 80%;"><input class="small" id="search_input" placeholder="Search RAs.."/></td>
            <td style="width: 20%;"><button class="blue small" style="padding: 10px 25px 10px 25px;" onclick="search_part()">Search</button></td>
        </tr>
    </table>
    <button id="clear_search_btn" class="red small" style="padding: 10px 25px 10px 25px; margin: 0; display: none;" onclick="clear_search()">Clear Search</button>
<h2 id="waiting_title" style="display: none;">Waiting Approval</h2>
    <div id="waiting_div"></div>
<h2 id="house_students_title">All RAs</h2>
    <div id="house_students_div"></div>
</div>
<table class="footer">
    <tr>
        <td>
            <span class="title">Account</span><br /><?php  
                echo $_SESSION["user_eng_name_first"];
                echo ' ';
                echo $_SESSION["user_eng_name_last"];
                echo ' - ';
                echo $_SESSION["user_id"];
                echo '<br />';
                echo '('.$_SESSION["user_type"].')';
            ?><br /><br />
            <div class="textbutton_small" onclick="window.location='account.php';">Account Settings</div>&nbsp;&nbsp;|&nbsp;&nbsp;
            <div class="textbutton_small" onclick="logout_click()">Log out</div><br />
        </td>
    </tr>
    <tr>
        <td>
            <span class="title">YREMS</span><br />
            Yonsei Residential College Event Management System<br /><br />
            Created and powered by Appenzeller House.<br /><br />
            This site was not created by, nor managed by
            the Yonsei University Residential College office.<br /><br />
            Please direct any questions or requests
            regarding the usage of this site to the following contact:<br />
            sean0404@naver.com
        </td>
    </tr>
    <tr>
        <td>
            <span class="title">Important Links</span><br />
            <div class="textbutton_small" onclick="window.location='/yicrc/policy.php';">RC Events Sign-up/Cancel Policy</div><br />
            <div class="textbutton_small" onclick="window.location='/yicrc/consent_personal_info.php';">Consent for Personal Information Collection and Use</div><br />
            <div class="textbutton_small" onclick='window.location="https://yicrc.yonsei.ac.kr/activity.asp?mid=m02_04";'>Check how much points you earned</div>
        </td>
    </tr>
</table>
<div id="user_id_div" style="display: none;"><?if(isset($_SESSION["user_id"])) { echo $_SESSION["user_id"]; } ?></div>
<div id="user_house_div" style="display: none;"><?if(isset($_SESSION["user_house"])) { echo $_SESSION["user_house"]; } ?></div>
<script type="text/javascript">
    function gei(x) {
        return document.getElementById(x);
    }
    function clear_search() {
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
    }
    function search_part() {
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
    }
    get_students();
    function get_students() {
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        var stu=null;
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
                    var out_text_waiting = '<table id="waiting_table"class="user"><tr><td>#</td><td>Name (ID)</td><td>Major</td><td>Action</td></tr>';
                    var out_text_all = '<table id="all_table" class="user"><tr><td>#</td><td>Name (ID)</td><td>Major</td><td>Room</td></tr>';
                    
                    var wait_num=0;
                    var all_num=0;
                    
                    var my_id=gei("user_id_div").innerText;
                    
                    if(Object.keys(stu).length==0) {
                        out_text_all += '<tr><td colspan="4" style="color: red">No students.</td></tr>';
                    } else {
                        var wait_reason_text=["Blocked", "Accepted", "Changes made", "New"]
                        var i=0;
                        for(i=0;i<Object.keys(stu).length;i++) {
                            if(stu[i].user_accepted==1) {
                                all_num++;
                                out_text_all += '<tr onclick="stu_click('+stu[i].user_id+')">';
                                out_text_all += '<td>'+all_num+'</td>';
                                out_text_all += '<td>'+stu[i].user_eng_name_first+' '+stu[i].user_eng_name_last+'<br />('+stu[i].user_id+')<span style="display: none">'+stu[i].user_name+'</span></td>';
                                out_text_all += '<td>'+stu[i].user_major+'</td>';
                                out_text_all += '<td>'+stu[i].user_room+'</td>';
                                out_text_all += '</tr>';
                            } else {
                                wait_num++;
                                out_text_waiting += '<tr>';
                                out_text_waiting += '<td>'+wait_num+'</td>';
                                out_text_waiting += '<td>'+stu[i].user_eng_name_first+' '+stu[i].user_eng_name_last+'<br />('+stu[i].user_id+')<br /><span style="color:red">'+wait_reason_text[stu[i].user_accepted]+'</span><span style="display: none">'+stu[i].user_name+'</span></td>';
                                out_text_waiting += '<td>'+stu[i].user_major+'</td>';
                                out_text_waiting += '<td><button class="small green" onclick="wait_stu_click(this,'+stu[i].user_id+')">Accept</button><br /><button class="small blue" onclick="stu_click('+stu[i].user_id+')">Details</button></td>';
                                out_text_waiting += '</tr>';
                            }
                        }
                    }
                    out_text_waiting += '</table>';
                    out_text_all += '</table>';
                    
                    if(wait_num==0) {
                        gei("waiting_title").style.display="none";
                    } else {
                        gei("waiting_title").style.display="";
                        gei("waiting_title").innerHTML = "Waiting Approval ("+wait_num+")";
                        gei("waiting_div").innerHTML = out_text_waiting;
                    }
                    
                    gei("house_students_title").innerHTML = "All RAs ("+all_num+")";
                    if(all_num==0) {
                        gei("house_students_div").style.display="none";
                    } else {
                        gei("house_students_div").innerHTML = out_text_all; 
                        gei("house_students_div").style.display="";
                    }
                    
                }
            },
            error: function(xhr, status, msg) {
                alert("There has been an error while connecting to the server. Please try again later. ("+e.status+" "+e.msg+")");
            },
            complete: function() {
                gei("loader").style.display="none";
                gei("content_div").style.display="";
            }
        });
    }
    function stu_click(id) {
        //alert(id);
        window.location="/yicrc/account.php?user_id="+id;
    }
    function wait_stu_click(btnObj,id) {
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        var new_user_accepted=0;
        if(btnObj.innerHTML=="Accept") {
            new_user_accepted=1;
        } else {
            new_user_accepted=0;
        }
        $.ajax({
            url: "account_load.php",
            type: "POST",
            data: {"action_type":"user_accepted","user_id":id, "user_accepted":new_user_accepted},
            success: function(data) {
                alert(data);
                location.reload();
            },
            error: function(xhr, status, msg) {
                alert("There has been an error while connecting to the server. Please try again later. ("+e.status+" "+e.msg+")");
            }
        });
    }
</script>
</body>
</html>