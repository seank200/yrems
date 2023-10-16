<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Register - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE">
	<link rel="stylesheet" type="text/css" href="/yicrc/yicrc_english.css">
	<script type="text/javascript" src="/yicrc/base.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
</head>
<body style="background: white;">
<table class="header">
	<tr> <td></td> <td><img src="/yicrc/img/yonsei_white.png" id="yonsei_white_img" alt="Yonsei" style="width: 100%;" onclick="logo_click()"/></td> <td></td> </tr>
</table>
<div class="container">
	<h1>Register</h1>
	<h2>Welcome to Yonsei Residential College</h2>
<?php
$err_msg="";
if(isset($_SESSION["user_id"])) {
	header('Location: index.php');
} else {
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		require_once 'config.php';
		$user_type=$user_name_eng_first=$user_name_eng_last=$user_name="";
		$user_id=$user_pc=$user_pc_cfrm="";
		$user_bday=$user_gender=$user_mobile=$user_email=$user_college=$user_major=$user_hose=$user_ra="";
		$user_room="";
		$user_nationality=$user_exp_abroad=$user_highschool=$user_lang_native=$user_lang_other=$user_notes=$user_accepted="";
		//check if user with the same ID already exists
        $sql="SELECT user_id FROM yicrc_users WHERE user_id=?";
        if($stmt=mysqli_prepare($link, $sql)) {
            $user_id_param=mysqli_real_escape_string($link, trim($_POST["user_id"]));
            mysqli_stmt_bind_param($stmt, "s", $user_id_param);
            if(mysqli_stmt_execute($stmt)) {
                if(mysqli_stmt_num_rows($stmt)>0) {
                    $err_msg="An account with this ID already exists.";
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            $err_msg="Error: Server unavailable.";
        }
        
        
		//check password, confirm password
			if(empty(trim($_POST["user_pc"]))||empty(trim($_POST["user_pc_cfrm"]))) {
				$err_msg="Please set your passcode.";
			} elseif(strlen(trim($_POST["user_pc"]))<6||strlen(trim($_POST["user_pc"]))>20) {
				$err_msg="Password must be 6~20 characters.";
			} else {
				if($_POST["user_pc"]==$_POST["user_pc_cfrm"]) {
					$user_pc=$_POST["user_pc"];
				} else {
					$err_msg="Password does not match.";
				}
			}
			
			//change room
			if(!empty(trim($_POST["user_room_1"]))&&!empty(trim($_POST["user_room_2"]))) {
				switch($_POST["user_room_1"]) {
					case "Dorm 1 - A":
						$param_room="A";
						break;
					case "Dorm 1 - B":
						$param_room="B";
						break;
					case "Dorm 1 - C":
						$param_room="C";
						break;
					case "Dorm 2 - D":
						$param_room="D";
						break;
					case "Dorm 2 - E":
						$param_room="E";
						break;
					case "Dorm 2 - F":
						$param_room="F";
						break;
					case "Dorm 2 - G":
						$param_room="G";
						break;
					default: 
						$err_msg.="An error occured.(ROOM)";
						break;
				}
				$param_room .= $_POST["user_room_2"];
			} else {
				$err_msg="All fields except the last one are required. Please fill in any empty fields (ROOM)";
			}
		//check rest of the form
			//check ID
			if(empty(trim($_POST["user_id"]))) {
				$err_msg="Please enter your ID";
			} else {
				$sql="SELECT user_id FROM yicrc_users WHERE user_id = ?";
				if($stmt = mysqli_prepare($link, $sql)) {
					$user_id_param = trim($_POST["user_id"]);
					mysqli_stmt_bind_param($stmt, "s", $user_id_param);
					if(mysqli_stmt_execute($stmt)) {
						mysqli_stmt_store_result($stmt);
						if(mysqli_stmt_num_rows($stmt)==1) {
							$err_msg="ERROR: A user with the same ID already exists.";
						} else {
							$user_id = trim($_POST["user_id"]);
						}
					} else {
						$err_msg="An error occured.";
					}
				} else {
					$err_msg="An error occured.";
				}
			}
			
			if($err_msg=="") {
				$sql="INSERT INTO yicrc_users (user_type, user_eng_name_first, user_eng_name_last, user_name, user_bday, user_id, user_pc, user_gender, user_mobile, user_email, 
				user_college, user_major, user_house, user_ra, user_room, user_nationality, user_exp_abroad, user_highschool, user_lang_native, user_lang_other, user_notes, user_accepted)
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
				$user_type=$_POST["user_type"];
				$user_name_eng_first=trim($_POST["user_name_eng_first"]);
				$user_name_eng_last=trim($_POST["user_name_eng_last"]);
				$user_name=trim($_POST["user_name"]);
				$user_bday=trim($_POST["user_bday"]);
				$user_id=trim($_POST["user_id"]);
				$user_gender=$_POST["user_gender"];
				$user_mobile=trim($_POST["user_mobile"]);
				$user_email=trim($_POST["user_email"]);
				$user_college=$_POST["user_college"];
				$user_major=$_POST["user_major"];
				$user_house=$_POST["user_house"];
				$user_ra=$_POST["user_ra"];
				$user_nationality=trim($_POST["user_nationality"]);
				$user_exp_abroad=$_POST["user_exp_abroad"];
				$user_highschool=trim($_POST["user_highschool"]);
				$user_lang_native=trim($_POST["user_lang_native"]); 
				$user_lang_other=trim($_POST["user_lang_other"]);
				$user_notes=$_POST["user_notes"];
				//$user_accepted=$_POST["user_accepted"];
                $user_accepted="3";
				
				if($stmt = mysqli_prepare($link, $sql)) {
					mysqli_stmt_bind_param($stmt,"ssssissssssssssssssssi",
					$user_type, 
					$user_name_eng_first,
					$user_name_eng_last,
					$user_name, 
					$user_bday,
					$user_id,
					$param_password,
					$user_gender,
					$user_mobile,
					$user_email,
					$user_college,
					$user_major, 
					$user_house,
					$user_ra,
					$param_room,
					$user_nationality,
					$user_exp_abroad,
					$user_highschool,
					$user_lang_native, 
					$user_lang_other,
					$user_notes,
					$user_accepted);
					$param_password=password_hash($_POST["user_pc"], PASSWORD_DEFAULT);
					
					if(mysqli_stmt_execute($stmt)) {
						echo '<script type="text/javascript">alert("Registration was successful. You can log in after your account has been accepted by the RAs.");window.location="/yicrc/index.php";</script>';
						//header("location: login.php");
					} else {
						$err_msg.="An error occured. Please try again later.(1)";
						echo '<div class="status gray">';
						printf("Error: %s.\n", mysqli_stmt_error($stmt));
						echo '</div>';
					}
				} else {
					$err_msg.="An error occured. Please try again later.(2)";
					//$stmt = mysqli_prepare($link, $sql);
					echo '<div class="status gray">';
						printf("Error: %s.\n", mysqli_stmt_error($stmt));
						echo '</div>';
				}
				mysqli_stmt_close($stmt);
				mysqli_close($link);
			}
			
	} 
}
if($err_msg!="") {
	echo '<div class="status redd">';
	echo $err_msg;
	echo '</div>';
}
?>
    
    <div id="step_1">
        <table class="list">
            <tr><td>Consent for Personal Information Collection and Use</td></tr>    
            <tr>
                <td>
                Before using our services, you must give consent to the collection and usage of your personal information necessary for RM/RAs to run and administer House-run RC activities, according to Article 15, 22, and 24 of [Personal Information Protection Law].<br /><br />
                1. Purpose of Personal Information<br />
                - This site collects and uses your personal information for the following purposes: Manage your(user) participation in RC programs (checking participants of events, checking their attendance, awarding points), To plan and improve House RC Events that are suited for students in the house, During RA student counselling<br /><br />
                
                2. Items of Collected Personal Information<br />
                - Name, Yonsei ID, date of birth, gender, major, mobile phone number, email address, house, dormitory room number, your RA, nationality, experiences living abroad, name and location of your highschool, native language, language(s) you speak, what you want to tell your RA<br /><br />
                    
                3. Period of Personal Information Retention and Use<br />
                - All personal information acquired through this site will be retained until you leave this house, and will be immediately deleted completely and permanently from all storages.<br /><br />
                
                4. You may not consent to personal information collection and use (However, if you do not consent you will not be able to register your account for this site and will not be able to use the services that we provide.)<br /><br />
                    
                5. People who can view your personal information<br />
                -All information except for what you wrote in "what you want to tell your RA" field): Residential Master, Chief Residential Assistant of house, Residential Assistants<br />
                - What you wrote on "what you want to tell your RA" field: Only your designated RA can see this. Residential Masters and other RAs cannot view this information when they log in with their account.<br /><br />
                
                6. All RM/RAs who view/use the information on article "5. People who can view your personal informatio" have signed and abides by the "RA Personal Information Protection and Security Pledge"<br />   
                </td>
            </tr>
        </table>
        <h1 style="color: #0A3879;">Do you consent to the collection and use of your personal information according to what is mentioned above?</h1>
        <button class="blue" style="width: 100%;margin-bottom: 30px;" onclick="agree_policy('agree')">YES</button><br />
        <button class="blue" style="width: 100%;" onclick="agree_policy('disagree')">NO</button>
    </div>
    
    <div id="step_2" style="display: none;">
        <table class="list">
            <tr><td>RC Events Sign-up/Cancel Policy</td></tr>    
            <tr>
                <td>
                Cancellation Policy<br />
                1. To cancel your attendance to an event, please contact your RA at least 24 hours before the event.<br />
                2. If you don’t show up to the event without cancelling, you will need to have a face-to-face meeting with your Residential Master, Professor Denton, before you can sign up for future events.<br />
                </td>
            </tr>
        </table>
        <h1 style="color: #0A3879;">I have read and agreed with the “RC Events Sign-up/Cancel Policy.”</h1>
        <button class="blue" style="width: 100%;margin-bottom: 30px;" onclick="agree_rc_policy('agree')">YES</button><br />
        <button class="blue" style="width: 100%;" onclick="agree_rc_policy('disagree')">NO</button>
    </div>
    
    <div id="step_3" style="display: none;">
        <form action="register.php" method="post" id="reg_form">
            <table class="details" id="reg_form_table">
                <tr>
                    <td colspan="2">Personal Information</td>
                </tr>
                <tr>
                    <td>Account Type</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select id="user_type_sel" name="user_type">
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
                        <input type="text" id="user_name_eng_first" name="user_name_eng_first" placeholder="First Name" class="small" style="margin-bottom: 10px;"/>
                        <input type="text" id="user_name_eng_last" name="user_name_eng_last" placeholder="Last Name" class="small"/>
                    </td>
                </tr>
                <tr>
                    <td>Name<br />(in your language)</td>
                    <td><input type="text" id="user_name" name="user_name" placeholder="Your name in your language" class="small"/></td>
                </tr>
                <tr>
                    <td>Student ID</td>
                    <td><input type="text" id="user_id" name="user_id" placeholder="Yonsei ID (e.g. 2018000000)" class="small"/></td>
                </tr>
                <tr>
                    <td>Password</td>
                    <td><input type="password" id="user_pc" name="user_pc" placeholder="Password" class="small"/></td>
                </tr>
                <tr>
                    <td>Confirm Password</td>
                    <td><input type="password" id="user_pc_cfrm" name="user_pc_cfrm" placeholder="Confirm Password" class="small"/></td>
                </tr> 
                <tr>
                    <td>Date of Birth</td>
                    <td><input type="text" id="user_bday" name="user_bday" placeholder="e.g. 19990205" class="small"/></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select name="user_gender">
                                <option></option> 
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td>Mobile Phone #</td>
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
                                <option value="-">-</option> 
                                <option>UIC</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td>Field/Major</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select id="user_major" name="user_major">
                                <option value="-">-</option>
                                <option>UF</option>
                                <option>UF-LSBT</option>
                                <option>HASSF</option>
                                <option>HASSF-ASD</option>
                                <option>ISEF</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td>House</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select name="user_house" id="user_house_select" onchange="house_sel_change(this)">
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
                    <td onclick="clear_ra_list()">Your RA</td>
                    <td>
                        <div class="sel_container" style="margin: 0;" id="user_ra_select_div">
                            <select name="user_ra" id="user_ra_select">
                                <option disabled>Select House First</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td>Building</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select id="user_room_1" name="user_room_1">
                                <option></option><option>Dorm 2 - G</option>
                                <option>Dorm 1 - A</option> <option>Dorm 1 - B</option> <option>Dorm 1 - C</option>
                                <option>Dorm 2 - D</option> <option>Dorm 2 - E</option> <option>Dorm 2 - F</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td>Room</td>
                    <td><input type="number" id="user_room_2" name="user_room_2" placeholder="e.g. 604" class="small"/></td>
                </tr>
                <tr>
                    <td>Nationality<br />(Passport)</td>
                    <td><input type="text" id="user_nationality" name="user_nationality" placeholder="e.g. REPUBLIC OF KOREA" class="small"/></td>
                </tr>
                <tr>
                    <td>Experience<br />of living abroad</td>
                    <td>
                        <div class="sel_container" style="margin: 0;">
                            <select id="user_exp_abroad" name="user_exp_abroad">
                                <option></option>
                                <option>None</option>
                                <option>Less than a year</option>
                                <option>1~3 years</option>
                                <option>4~12 years</option>
                                <option>More than 12 years</option>
                            </select>
                            <p>▼</p>
                        </div><br />
                    </td>
                </tr>
                <tr>
                    <td colspan='2' style="border-bottom: none;">High school name/city/country</td>
                </tr>
                <tr>
                    <td colspan='2'><input type="text" id="user_highschool" name="user_highschool" placeholder="e.g. OO School / Seoul / Korea" class="small"/></td>
                </tr>
                <tr>
                    <td colspan='2' style="border-bottom: none;">Native Language (Language that you are most comfortable with)</td>
                </tr>
                <tr>
                    <td colspan='2'><input type="text" id="user_lang_native" name="user_lang_native" placeholder="Please write in English" class="small"/></td>
                </tr>
                <tr>
                    <td colspan='2' style="border-bottom: none;">Other languages that you can speak (Other than English - Even basic skills are fine)</td>
                </tr>
                <tr>
                    <td colspan='2'><input type="text" id="user_lang_other" name="user_lang_other" placeholder="Please write in English." class="small"/></td>
                </tr>
                <tr>
                    <td colspan='2' style="border-bottom: none;">Anything else you want to tell your RA (optional)</td>
                </tr>
                <tr>
                    <td colspan='2'><input type="text" id="user_notes" name="user_notes" placeholder="This is only visible to your RA and to no one else." class="small"/>
                    <input type="hidden" id="user_accepted" name="user_accepted" value="3" />
                    </td>
                </tr>
            </table>
        </form>
        <button class="blue" style="width: 100%; margin-bottom: 30px;" id="reg_btn" onclick="reg_form_submit()">Register</button>
    </div>
	<div class="center" style="width: 100%;"><div class="textbutton" id="cancel_btn" style="margin: 30px 0 150px 0;" onclick="cancel()">Cancel Registration</div></div>
</div>
<script type="text/javascript">
function gei(x) {
    return document.getElementById(x);
}
    
function agree_rc_policy(x) {
    if(x=="agree") {
        gei("step_3").style.display="";
        gei("step_2").style.display="none";
    } else {
        alert("You cannot use our services if you do not consent.");
    }
}
    
function agree_policy(x) {
    if(x=="agree") {
        gei("step_2").style.display="";
        gei("step_1").style.display="none";
    } else {
        alert("You cannot use our services if you do not agree to the policy.");
    }
}

function house_sel_change(x) {
    clear_ra_list();
    if(x.value!="" && x.value!=null) {
        get_list_ra(x.value);   
    }
}
    
function clear_ra_list() {
    /*
    alert(gei("user_ra_select").length);
    for(var i=0;i<gei("user_ra_select").length;i++) {
        gei("user_ra_select").remove(i);
    }*/
    $("#user_ra_select").empty();
}
function get_list_ra(house_sel) {
    if(house_sel==12) {
        var option = document.createElement("option");
        option.text="Non-RC/RA/RM";
        option.value="0";
        gei("user_ra_select").add(option);
        //alert("12");
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
                    if(data!="There are no RAs listed for this house.") {
                        window.location="/yicrc/index.php";   
                    }
                }
                clear_ra_list();
                var option = document.createElement("option");
                option.text="Select RA"; 
                option.value="empty";
                if(ra!=null) {
                    var i=0;
                    gei("user_ra_select").add(option);
                    for(i=0; i<Object.keys(ra).length; i++) {
                        var option = document.createElement("option");
                        option.text=ra[i].name;
                        option.value=ra[i].user_id;
                        gei("user_ra_select").add(option);
                    }
                }
                var option = document.createElement("option");
                option.text="Non-RC/RA/RM";
                option.value="0";
                gei("user_ra_select").add(option);
                //gei("load_ra_btn").style.display="none";
                //gei("user_ra_select_div").style.display="";
            },
            error: function(e) {
                alert("There has been an error. Please try again later. 2");
                //window.location="/yicrc/index.php";
            }
        });
    }  
}

function reg_form_submit() {
	if(gei("user_pc").value==gei("user_pc_cfrm").value) {
        if(gei("user_house_select").value=="0") {
            if(gei("user_ra_select").value!="empty") {
                var not_empty=true;
                /*
                var items=[gei("user_name_eng_first").value, 
                            gei("user_name_eng_last").value, 
                            gei("user_name").value,
                            gei("user_id").value,
                            gei("user_pc").value,
                            gei("user_pc_cfrm").value,
                            gei("user_bday").value,
                            gei("user_email").value,
                            gei("user_house_select").value,
                            gei("user_room_1").value,
                            gei("user_room_2").value,
                            gei("user_nationality").value,
                            gei("user_exp_abroad").value,
                            gei("user_highschool").value,
                            gei("user_lang_native").value,
                            gei("user_lang_other").value];
                */
                var items=[gei("user_name_eng_first").value, 
                            gei("user_name_eng_last").value, 
                            gei("user_name").value,
                            gei("user_id").value,
                            gei("user_pc").value,
                            gei("user_pc_cfrm").value,
                            gei("user_bday").value,
                            gei("user_email").value,
                            gei("user_house_select").value,
                            gei("user_room_1").value,
                            gei("user_room_2").value];
                for(var i=0;i<items.length;i++) {
                    if(items[i]==null || items[i]=="") {
                        not_empty=false;
                    }
                }
                if(gei("user_college").value=="-" || gei("user_major").value=="-") {
                    not_empty=false;
                }
                if(not_empty) {
                    if(confirm("Do you wish to sign up as "+gei("user_type_sel").value+"?")) {
                        document.getElementById("reg_form").submit();  
                    }
                } else {
                    alert("All fields except the last one are required.");
                    /*
                    if(confirm("Do you wish to sign up as "+gei("user_type_sel").value+"?")) {
                        document.getElementById("reg_form").submit();  
                    }*/
                }
            } else {
                alert("Please choose your RA.");
            }
        } else {
            alert("This system is for Appenzeller House students only.");
        }
    } else {
        alert("Password and password confirm does not match.");
    }
    //document.getElementById("reg_form").submit();
}

function cancel() {
	window.location="/yicrc/index.php";
}
</script>
</body>
</html>