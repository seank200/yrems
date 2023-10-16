<?php
session_start();
error_reporting(0);
mysqli_report(MYSQLI_REPORT_OFF);
require_once "config_2.php";
verify_user();
$user_type=$user_eng_name_first=$user_eng_name_last=$user_name="";
$user_id="";
$user_bday=$user_gender=$user_mobile=$user_email=$user_college=$user_major=$user_hose=$user_ra="";
$user_room="";
//$user_nationality=$user_exp_abroad=$user_highschool=$user_lang_native=$user_lang_other=$user_notes="";
$user_accepted="";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";
    if($_POST["action_type"]=="load") {
        $sql="SELECT user_type, user_eng_name_first, user_eng_name_last, user_name, user_bday, user_id, user_gender, user_mobile, user_email, 
                user_college, user_major, user_house, user_ra, user_room, user_notes, user_accepted FROM yicrc_users WHERE user_id = ?";
        /*
        $sql="SELECT user_type, user_eng_name_first, user_eng_name_last, user_name, user_bday, user_id, user_gender, user_mobile, user_email, 
                user_college, user_major, user_house, user_ra, user_room, user_nationality, user_exp_abroad, user_highschool, user_lang_native, user_lang_other, user_notes, user_accepted FROM yicrc_users WHERE user_id = ?";
        */
        if($stmt2=mysqli_prepare($link, $sql)) {
            if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                if(isset($_POST["user_id"])) {
                    $user_id_param = $_POST["user_id"];
                } else {
                    $user_id_param = $_SESSION['user_id'];
                }
            } else {
                $user_id_param = $_SESSION['user_id'];
            }
            mysqli_stmt_bind_param($stmt2, "s", $user_id_param);
            if(mysqli_stmt_execute($stmt2)) {
                mysqli_stmt_store_result($stmt2);
                mysqli_stmt_bind_result($stmt2, $user_type, $user_eng_name_first, $user_eng_name_last, $user_name, $user_bday, $user_id, $user_gender, $user_mobile, $user_email, $user_college, $user_major, $user_house, $user_ra, $user_room, $user_notes, $user_accepted);
                while(mysqli_stmt_fetch($stmt2)) {
                    $userinfo_out=array();
                    $userinfo_out['user_type']=$user_type;
                    $userinfo_out['user_eng_name_first']=$user_eng_name_first;
                    $userinfo_out['user_eng_name_last']=$user_eng_name_last;
                    $userinfo_out['user_name']=$user_name;
                    $userinfo_out['user_gender']=$user_gender;
                    $userinfo_out['user_college']=$user_college;
                    $userinfo_out['user_major']=$user_major;
                    $userinfo_out['user_mobile']=$user_mobile;
                    $userinfo_out['user_room']=$user_room;
                    $userinfo_out['user_house']=$user_house;
                    $userinfo_out['user_ra']=$user_ra;
                    $userinfo_out['user_accepted']=$user_accepted;
                    if($user_id_param==$_SESSION["user_id"] || $user_ra==$_SESSION["user_id"] || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM") {
                        $userinfo_out['user_id']=$user_id;
                        $userinfo_out['user_bday']=$user_bday;
                        $userinfo_out['user_email']=$user_email;
                        /*
                        $userinfo_out['user_nationality']=$user_nationality;
                        $userinfo_out['user_exp_abroad']=$user_exp_abroad;
                        $userinfo_out['user_highschool']=$user_highschool;
                        $userinfo_out['user_lang_native']=$user_lang_native;
                        $userinfo_out['user_lang_other']=$user_lang_other;
                        */
                        $userinfo_out['user_notes']=$user_notes;   
                        /*
                        if($user_id_param==$_SESSION["user_id"]||$user_ra==$_SESSION["user_id"]) {
                            $userinfo_out['user_notes']=$user_notes;   
                        } else {
                            $userinfo_out['user_notes']="-";
                        }
                        */
                    } else {
                        /*
                        $userinfo_out['user_id']="-";
                        $userinfo_out['user_bday']="-";
                        $userinfo_out['user_email']="-";
                        $userinfo_out['user_nationality']="-";
                        $userinfo_out['user_exp_abroad']="-";
                        $userinfo_out['user_highschool']="-";
                        $userinfo_out['user_lang_native']="-";
                        $userinfo_out['user_lang_other']="-";*/
                        $userinfo_out['user_id']=$user_id;
                        $userinfo_out['user_bday']=$user_bday;
                        $userinfo_out['user_email']=$user_email;
                        /*
                        $userinfo_out['user_nationality']=$user_nationality;
                        $userinfo_out['user_exp_abroad']=$user_exp_abroad;
                        $userinfo_out['user_highschool']=$user_highschool;
                        $userinfo_out['user_lang_native']=$user_lang_native;
                        $userinfo_out['user_lang_other']=$user_lang_other;
                        */
                        $userinfo_out['user_notes']=$user_notes;   
                    }
                }
                echo json_encode($userinfo_out, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
            } else {
                $err_msg.="There was a server error. Please try again later. (DB_EXEC_1)";
            }
            mysqli_stmt_close($stmt2);
        } else {
            $err_msg.="There was a server error. Please try again later. (DB_PREP_1)";
        }
    } elseif($_POST["action_type"]=="write") {
        //change room
        if(!empty(trim($_POST["user_room_1"]))&&!empty(trim($_POST["user_room_2"]))) {
            switch($_POST["user_room_1"]) {
                case "Dorm 1 - A":
                    $param_room="A"; break;
                case "Dorm 1 - B":
                    $param_room="B"; break;
                case "Dorm 1 - C":
                    $param_room="C"; break;
                case "Dorm 2 - D":
                    $param_room="D"; break;
                case "Dorm 2 - E":
                    $param_room="E"; break;
                case "Dorm 2 - F":
                    $param_room="F"; break;
                case "Dorm 2 - G":
                    $param_room="G"; break;
                default: 
                    $err_msg.="An error occured.(ROOM)"; break; 
            }
            if(isset($param_room)) {
                $param_room .= trim($_POST["user_room_2"]);
            }
        } else {
            $err_msg="All fields except the last one are required. Please fill in any empty fields (ROOM)";
        }

        if($err_msg=="") {
            $sql="UPDATE yicrc_users 
            SET user_type = ?, 
            user_eng_name_first = ?, 
            user_eng_name_last = ?, 
            user_name = ?, 
            user_bday = ?, 
            user_gender = ?, 
            user_mobile = ?, 
            user_email = ?, 
            user_college = ?, 
            user_major = ?, 
            user_house = ?, 
            user_ra = ?, 
            user_room = ?,  
            user_notes = ?, 
            user_accepted = ? 
            WHERE user_id = ?";
            /*
            $sql="UPDATE yicrc_users 
            SET user_type = ?, 
            user_eng_name_first = ?, 
            user_eng_name_last = ?, 
            user_name = ?, 
            user_bday = ?, 
            user_gender = ?, 
            user_mobile = ?, 
            user_email = ?, 
            user_college = ?, 
            user_major = ?, 
            user_house = ?, 
            user_ra = ?, 
            user_room = ?, 
            user_nationality = ?, 
            user_exp_abroad = ?, 
            user_highschool = ?, 
            user_lang_native = ?, 
            user_lang_other = ?, 
            user_notes = ?, 
            user_accepted = ? 
            WHERE user_id = ?";
            */
            
            if($stmt = mysqli_prepare($link, $sql)) {
                $user_type=$_POST["user_type"];
                $user_eng_name_first=trim($_POST["user_eng_name_first"]);
                $user_eng_name_last=trim($_POST["user_eng_name_last"]);
                $user_name=trim($_POST["user_name"]);
                $user_bday=trim($_POST["user_bday"]);
                $user_gender=$_POST["user_gender"];
                $user_mobile=trim($_POST["user_mobile"]);
                $user_email=trim($_POST["user_email"]);
                $user_college=$_POST["user_college"];
                $user_major=$_POST["user_major"];
                $user_house=$_POST["user_house"];
                $user_ra=$_POST["user_ra"];
                /*
                $user_nationality=trim($_POST["user_nationality"]);
                $user_exp_abroad=$_POST["user_exp_abroad"];
                $user_highschool=trim($_POST["user_highschool"]);
                $user_lang_native=trim($_POST["user_lang_native"]); 
                $user_lang_other=trim($_POST["user_lang_other"]);
                */
                $user_notes=$_POST["user_notes"];
                $user_accepted=$_POST["user_accepted"];
                
                if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                    if(isset($_POST["user_id"])) {
                        $user_id_param = $_POST["user_id"];
                    } else {
                        $user_id_param = $_SESSION['user_id'];
                    }
                } else {
                    $user_id_param = $_SESSION['user_id'];
                }
                /*
                mysqli_stmt_bind_param($stmt,"ssssissssssssssssssis",
                $user_type, 
                $user_eng_name_first,
                $user_eng_name_last,
                $user_name, 
                $user_bday, //i
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
                $user_accepted, //i
                $user_id_param);
                */
                mysqli_stmt_bind_param($stmt,"ssssisssssssssis",
                $user_type, 
                $user_eng_name_first,
                $user_eng_name_last,
                $user_name, 
                $user_bday, //i
                $user_gender,
                $user_mobile,
                $user_email,
                $user_college,
                $user_major, 
                $user_house,
                $user_ra,
                $param_room,
                $user_notes,
                $user_accepted, //i
                $user_id_param);

                if(mysqli_stmt_execute($stmt)) {
                    echo "Changes were saved.";
                } else {
                    $err_msg.="There was a server error. Please try again later.(DB_EXEC_2)";
                }
                mysqli_stmt_close($stmt);
            } else {
                $err_msg.="There was a server error. Please try again later.(DB_PREP_2)";
            }
        }
        if($err_msg!="") {
            echo $err_msg;
        }
    } elseif($_POST["action_type"]=="user_accepted") {
        $sql="UPDATE yicrc_users SET user_accepted = ? WHERE user_id = ?";
        if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
            if(isset($_POST["user_id"])) {
                $user_id_param = $_POST["user_id"];
            } else {
                $err_msg="Parameter not set. (ID)";
            }
        } else {
            $err_msg="Only RM/RAs can do this.";
        }
        if(!(isset($_POST["user_accepted"]))) {
            $err_msg="Paramter not set. (ACC)";
        } else {
            $accepted_param = $_POST["user_accepted"];
        }
        if($err_msg=="") {
            if($stmt=mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $accepted_param, $user_id_param);
                if(mysqli_stmt_execute($stmt)) {
                    if($accepted_param==1) {
                        echo "User was granted access to the system.";
                    } else {
                        echo "User was blocked from the system.";
                    }
                }
                mysqli_stmt_close($stmt);
            }
        } else {
            echo $err_msg;
        }
    } elseif($_POST["action_type"]=="delete") {
        $sql="DELETE FROM yicrc_users WHERE user_id = ? LIMIT 2";
        $sql2="DELETE FROM yicrc_participants WHERE user_id = ?";
        if(isset($_POST["user_id"]) && $_POST["user_id"]!="") {
            if($stmt=mysqli_prepare($link, $sql)) {
                if($_SESSION["user_type"]=="House RA" || $_SESSION["user_type"]=="Chief RA" || $_SESSION["user_type"]=="RM" || $_SESSION["user_type"]=="Administrative RA") {
                    $user_id_param_del=mysqli_real_escape_string($link, trim($_POST["user_id"]));
                } else {
                    $user_id_param_del=$_SESSION["user_id"];
                    //$user_id_param_del="error";
                }
                mysqli_stmt_bind_param($stmt, "s", $user_id_param_del);
                if(mysqli_stmt_execute($stmt)) {
                    if(mysqli_stmt_affected_rows($stmt)==1) {
                        echo "User with the ID (".$user_id_param_del.") was deleted. ";
                    } else {
                        echo mysqli_stmt_affected_rows($stmt)." users with the ID (".$user_id_param_del.") was deleted. ";
                    }
                } else {
                    echo "There was a server error. Please try again later. (D0102_".$user_id_param_del.")";
                }
                mysqli_stmt_close($stmt);
                unset($stmt);
                if($stmt=mysqli_prepare($link, $sql2)) {
                    mysqli_stmt_bind_param($stmt, "s", $user_id_param_del);
                    if(mysqli_stmt_execute($stmt)) {
                        echo mysqli_stmt_affected_rows($stmt)." participation data was deleted.";
                    } else {
                        echo "There was a server error. Please try again later. (D0202_".$user_id_param_del.")";
                    }
                } else {
                    echo "There was a server error. Please try again later. (D0201_".$user_id_param_del.")";
                }
                //echo "Response test: delete ".$user_id_param_del;
                mysqli_stmt_close($stmt);
            } else {
                echo "There was a server error. Please try again later. (D0101_".$user_id_param_del.")";
            }
        } else {
            echo "Error: User ID not set.";
        }
        mysqli_close($link);
    } else {
        echo "Paramter not set. (ACT)";
    }
    mysqli_close($link);
} else {
    echo "Parameters not set.";
}

?>