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
	<title>Account Settings - YREMS</title>
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
<div class="loader" id="loader"></div>
<div class="container" id="content_div" style="display: none;">
	<h1 id="main_title"><?php
        if(isset($_GET["user_id"])) {
			if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
				echo 'Student Details';
			} else {
				header("Location: index.php");
			}
		} else {
			echo 'My Account Settings';
		}
    ?></h1>
    <h2 id="sub_title" onclick="goback_click(this)"><?php
        if(isset($_GET["user_id"])) {
			if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
				echo '&lt; Return to previous page';
			}
		} else {
			echo '&lt; Return to menu';
		}
    ?></h2>
    
    <table id="account_view" class="details">
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
    
    <table id="account_edit" class="details" style="display: none;">
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
	<div class="status redd" id="err_msg_js" style="display: none;"></div>
	<?php
		if(isset($_GET["user_id"])) {
			if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
				echo '<button class="blue" style="width: 100%; margin-bottom: 30px;" onclick="edit_click()">Edit</button>';
			} else {
				header("Location: index.php");
			}
		} else {
			echo '<button class="blue" style="width: 100%; margin-bottom: 30px;" onclick="edit_click()">Edit</button>';
		}
	?>
	<?php
		if(isset($_GET["user_id"])) {
			if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
				echo '<button class="disabled" id="accept_btn" style="width: 100%; margin-bottom: 30px;" onclick="user_accepted_edit()">Loading..</button>';
                echo '<button class="blue" style="width: 100%; margin-bottom: 60px;" onclick="reset_pc()">Reset Password</button>';
                echo '<script>function reset_pc() { window.location="/yicrc/forgot_admin.php"; }</script>';
			} else {
				header("Location: index.php");
			}
		} else {
			echo '<button class="blue" style="width: 100%; margin-bottom: 60px;" onclick="change_pc()">Change Password</button>';
		}
	?>
    <?php
       if(isset($_GET["user_id"])) {
			if($_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
				echo '<div class="textbutton" style="color: red; border: solid red; border-width: 0 0 2px 0;" onclick="delete_user()">Delete Account</div>';
			} else {
				header("Location: index.php");
			}
		} else {
           echo '<div class="textbutton" style="color: red; border: solid red; border-width: 0 0 2px 0;" onclick="delete_user()">Delete Account</div>';
        }
    ?>
    
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

<div style="display: none;" id="my_user_id_div" x-apple-data-detectors="false"><?php if(isset($_SESSION["user_id"])) { echo $_SESSION["user_id"]; } ?></div>
<div style="display: none;" id="user_eng_name_first_div"><?php if(isset($_SESSION["user_eng_name_first"])) { echo $_SESSION["user_eng_name_first"]; } ?></div>
<div style="display: none;" id="user_type_div"><?php echo $_SESSION['user_type']; ?></div>
    <div style="display: none;" id="get_user_id_div"><?php if(isset($_GET["user_id"])) { echo trim($_GET["user_id"]); } ?></div>
<script type="text/javascript">
function gei(x) {
    return document.getElementById(x);
}

function edit_click() {
    if(gei("account_view").style.display=="none") {
        submit_frm();
        gei("account_edit").style.display="none";
        gei("account_view").style.display="";
    } else {
        gei("account_edit").style.display="";
        gei("account_view").style.display="none";
    }
}
    
function goback_click(x) {
    if(x.innerHTML=="&lt; Return to previous page") {
        //window.location="/yicrc/manage_students.php";
        window.history.back();
    } else {
        window.location="/yicrc/index.php";
    }
}
    
function user_accepted_edit() {
	var txt="";
    var new_user_accepted=0;
    if(gei("accept_btn").innerHTML=="Accept user") {
        new_user_accepted=1;
        txt="Accept";
    } else {
        new_user_accepted=0;
        txt="Block";
    }
    if(confirm(txt+" this user?")) {
        var id=gei("get_user_id_div").innerText;
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
}

function house_sel_change(x) {
    var i=0;
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
            url: "get_ra.php",
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
                        if(ra[i].user_id==info.user_ra) {
                            my_ra=ra[i].name;
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
                
                if(gei("user_type_div").innerHTML=="House RA" || gei("user_type_div")=="Chief RA") {
		        	var my_name_ra = "RA "+gei("user_eng_name_first_div").innerHTML;
		        	if(my_name_ra==my_ra) {
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
		        	if(info.user_id==gei("my_user_id_div").innerText) {
		        		gei("user_notes_view").innerHTML=info.user_notes;
						document.getElementById("user_notes").value=info.user_notes;
		        		gei("user_notes_view").style.display="";
			        	gei("user_notes").style.display="";
			        	gei("user_notes_tr_view_1").style.display="";
						gei("user_notes_tr_view_2").style.display="";
						gei("user_notes_tr_1").style.display="";
						gei("user_notes_tr_2").style.display="";
		        	} else {
		        		//alert("("+info.user_id+"), ("+gei("my_user_id_div").innerText+")");
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
                alert("There has been an error. Please try again later.");
                window.location="/yicrc/index.php";
            }
        });
    }
}
    
fill_userinfo();
    function fill_userinfo() {
        //var out=document.getElementById("userinfo_out_div").innerText; 
        var info=null;
        var err_div=document.getElementById("err_msg_js");
        //alert("TEST");
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        if(gei("main_title").innerHTML=="Student Details") {
            if(gei("user_type_div").innerText!="House RA" && gei("user_type_div").innerText!="Chief RA" && gei("user_type_div").innerText!="RM" && gei("user_type_div").innerText!="Administrative RA") {
                window.location="/yicrc/index.php";
            } else {
                var id=gei("get_user_id_div").innerText;
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
                        err_div.innerHTML="There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")";
                        err_div.style.display="";
                    }
                });
            }    
        } else {
            //alert(gei("main_title").innerHTML);
            $.ajax({
                url: "account_load.php",
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
                error: function(xhr,status,msg) {
                    //alert("There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")");
                    err_div.innerHTML="There was a problem connecting to the server. Please try again later. ("+status+" "+msg+")";
                    err_div.style.display="";
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
            } else if(gei("user_accepted").value==0 || gei("user_accepted").value==2 || gei("user_accepted").value==3) {
                btn.className="green";
                btn.innerHTML="Accept user";
                gei("user_accepted_td").innerHTML=wait_reason_text[gei("user_accepted").value];  
                gei("user_accepted_td").style.color="#EE220C";
                gei("user_accepted_view").innerHTML=wait_reason_text[gei("user_accepted").value];  
                gei("user_accepted_view").style.color="#EE220C";
            } else {
                btn.className="disabled";
                btn.innerHTML="Error";
            }
        }
        gei("loader").style.display="none";
        gei("content_div").style.display="";
    }
    function submit_frm() {
        if(confirm("Save changes?")) {
            var err_msg="";
            var err_div=document.getElementById("err_msg_js");
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
            /*
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
                gei("user_room_2").value, 
                gei("user_nationality").value, 
                gei("user_exp_abroad").value, 
                gei("user_highschool").value, 
                gei("user_lang_native").value];
                */
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
                err_div.innerHTML="All fields except for the last one are required.";
                err_div.style.display="";
            }
        }
    }
    function submit_edit() {
        var err_div=document.getElementById("err_msg_js");
        gei("loader").style.display="";
        gei("content_div").style.display="none";
        if(gei("main_title").innerHTML=="Student Details") {
            if(gei("user_type_div").innerText!="House RA" && gei("user_type_div").innerText!="Chief RA" && gei("user_type_div").innerText!="RM" && gei("user_type_div").innerText!="Administrative RA") {
                window.location="/yicrc/index.php";
            } else {
                var id=gei("get_user_id_div").innerText;
                
                $.ajax({
                    url: "account_load.php",
                    type: "POST",
                    data: {"action_type":"write", "user_type":gei("user_type").value, "user_eng_name_first":gei("user_eng_name_first").value, "user_eng_name_last":gei("user_eng_name_last").value, "user_name":gei("user_name").value, "user_id":gei("user_id_td").innerHTML, "user_bday":gei("user_bday").value, "user_gender":gei("user_gender").value, "user_mobile":gei("user_mobile").value, "user_email":gei("user_email").value, "user_college":gei("user_college").value, "user_major":gei("user_major").value, "user_house":gei("user_house").value, "user_ra":gei("user_ra").value, "user_room_1":gei("user_room_1").value, "user_room_2":gei("user_room_2").value, "user_nationality":gei("user_nationality").value, "user_accepted":gei("user_accepted").value},
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
                        fill_userinfo();
                    }
                });
            }    
        } else {
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
                    err_div.innerHTML="There was a problem connecting to the server. Please try again later.";
                    err_div.style.display="";
                },
                complete: function() {
                    gei("loader").style.display="none";
                    gei("content_div").style.display="";
                    fill_userinfo();
                }
            });
        }
    }
    function delete_user() {
        var cfrm=false;
        if(confirm("Are you sure you want to delete this account?")) {
            if(confirm("[WARNING] Doing this will immediately and permanently delete all information linked to this account, including sign-up data. You cannot undo this action. Do you wish to continue?")) {
                gei("loader").style.display="";
                gei("content_div").style.display="none";
                var err_div=document.getElementById("err_msg_js");
                $.ajax({
                    url: "account_load.php",
                    type: "POST",
                    data: {"action_type":"delete", "user_id":gei("user_id_td").innerText},
                    success: function(data) {
                        alert(data);
                        if(gei("main_title").innerHTML=="Student Details") {
                            window.location="manage_students.php";
                        } else {
                            window.location="logout.php";
                        }
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
    function change_pc() {
        window.location="/yicrc/change_password.php";
    }
</script>
</body>
</html>