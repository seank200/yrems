<?php
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_OFF);
?>
<!DOCTYPE html>
<html>
<head>
	<title>YREMS - YREMS</title>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
	<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE" />
	<META HTTP-EQUIV="CACHE-CONTROL" CONTENT="NO-CACHE" />
    <meta property="og:url" content="http://appenzeller.kr" />
    <meta property="og:title" content="YREMS" />  
    <meta property="og:type" content="website" />
    <meta property="og:image" content="http://appenzeller.kr/yicrc/img/YREMS_thumbnail.png" />
    <meta property="og:description" content="Yonsei RC Event Management System" />
    <meta name="description" content="YREMS - Discover and participate in RC Events." />
    <link rel="shortcut icon" href="/favicon.ico">
	<link rel="stylesheet" type="text/css" href="yicrc_english_3.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <style>
        
    </style>
</head>
<body>
    <div class="header">
        <div class="header_content">
            <img id="logo" src="/yicrc/img/yrems_logo.png" class="logo" alt="YREMS" />
            <ul id="menu_list" class="header"></ul>
        </div>
    </div>
    <div id="content_div" class="content_div" style="max-width: 768px;">
        <div id="step_1" class="col-12">
            <h1>Register Account (1/2)</h1>
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
                        - All personal information acquired through this site will be retained until you leave this house, and will be immediately deleted completely and permanently from all storages at that point.<br /><br />

                        4. You may not consent to personal information collection and use (However, if you do not consent you will not be able to register your account for this site and will not be able to use the services that we provide.)<br /><br />

                        5. People who can view your personal information<br />
                        -All information except for what you wrote in "what you want to tell your RA" field): Residential Master, Chief Residential Assistant of house, Residential Assistants<br />
                        - What you wrote on "what you want to tell your RA" field: Only your designated RA can see this. Residential Masters and other RAs cannot view this information when they log in with their account.<br /><br />

                        6. All RM/RAs who view/use the information on article "5. People who can view your personal information" have signed and abides by the "RA Personal Information Protection and Security Pledge"<br />   
                    </td>
                </tr>
            </table>
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
            <button class="blue" onclick="policy_agree()">I have read and understood.</button>
        </div>
        <div id="step_2" class="col-12" style="display: none;">
            <h1>Register Account (2/2)</h1>
            <form action="register_2.php" method="post" id="reg_form">
                <table class="list details" id="reg_form_table">
                    <tr><td colspan="2">Personal Information</td></tr>
                    <tr>
                        <td>Account Type</td>
                        <td>
                            <select id="user_type_sel" name="user_type">
                                <option>RC Student</option>
                                <option>Non-RC Student</option>
                                <option>Administrative RA</option>
                                <option>House RA</option>
                                <option>Chief RA</option>
                                <option>RM</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>English<br />Name</td>
                        <td>
                            <input type="text" id="user_name_eng_first" name="user_name_eng_first" placeholder="First Name" class="small" style="margin-bottom: 10px;"/>
                            <input type="text" id="user_name_eng_last" name="user_name_eng_last" placeholder="Last Name" class="small"/>
                        </td>
                    </tr>
                    <tr>
                        <td>Name<br /><span style="font-size: 80%;">in your language</span></td>
                        <td><input type="text" id="user_name" name="user_name" placeholder="Your name in your language" class="small"/></td>
                    </tr>
                    <tr>
                        <td>Yonsei ID</td>
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
                            <select name="user_gender">
                                <option></option> 
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>House</td>
                        <td>
                            <select name="user_house" id="user_house_select" onchange="house_sel_change(this)">
                                <option>(Select House)</option> 
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
                            <select name="user_ra" id="user_ra_select">
                                <option disabled>Select House First</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Building</td>
                        <td>
                            <select id="user_room_1" name="user_room_1">
                                <option></option><option>Dorm 2 - G</option>
                                <option>Dorm 1 - A</option> <option>Dorm 1 - B</option> <option>Dorm 1 - C</option>
                                <option>Dorm 2 - D</option> <option>Dorm 2 - E</option> <option>Dorm 2 - F</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Room</td>
                        <td><input type="number" id="user_room_2" name="user_room_2" placeholder="e.g. 604" class="small"/></td>
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
                                    <option>Other</option>
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
                                    <option>Other</option>
                                </select>
                                <p>▼</p>
                            </div><br />
                        </td>
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
                <p style="color: gray; line-height: 1.5;">You will be able to log in after your RA (or your supervisor) accepts your registration request. This may take up to a day.</p>
            </form>
            <button class="blue" onclick="reg_form_submit()">Register Account</button>
<?php
$err_msg="";
if(isset($_SESSION["user_id"])) {
	header('Location: index.php');
} else {
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		require_once 'config.php';
		$user_type=$user_name_eng_first=$user_name_eng_last=$user_name="";
		$user_id=$user_pc=$user_pc_cfrm="";
		$user_bday=$user_gender=$user_mobile=$user_email=$user_college=$user_major=$user_house=$user_ra="";
		$user_room="";
		//$user_nationality=$user_exp_abroad=$user_highschool=$user_lang_native=$user_lang_other=$user_notes="";
        $user_accepted="";
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
				user_college, user_major, user_house, user_ra, user_room, user_notes, user_accepted) 
				VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
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
				$user_notes=$_POST["user_notes"];
                $user_accepted="3";
				
				if($stmt = mysqli_prepare($link, $sql)) {
					$param_password=password_hash($_POST["user_pc"], PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt,"ssssisssssssssssi",
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
					$user_notes,
					$user_accepted);
					
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
					//var_dump($_POST);
                    //$stmt = mysqli_prepare($link, $sql);
					/*
                    echo '<div class="status gray">';
						printf("Error: %s.\n", mysqli_stmt_error($stmt));
						echo '</div>';
                    */
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
        </div>
    </div>
    <script>
        function gei(x) {
            return document.getElementById(x);
        }
        
        function policy_agree() {
            gei("step_1").style.display="none";
            gei("step_2").style.display="";
        }
        
        function house_sel_change(x) {
            clear_ra_list();
            if(x.value!="(Select House)" && x.value!=null) {
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
        
    </script>
</body>
</html>