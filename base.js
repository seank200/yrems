function menu_click() {
	window.location="/yicrc/index.php";
}
function logo_click() {
	//window.location="/yicrc/index.php";
    document.getElementById("yonsei_white_img").style.display="none";
}
function logo_click_dev() {
	alert("This system is powered by an Appenzeller House RA.");
}
function logout_click() {
	if(confirm("Do you wish to log out?")) {
		window.location="/yicrc/logout.php";
	}
}
function view_policy() {
    window.location="/yicrc/policy.php";
}
function show_myactivity() {
    window.location="/yicrc/myactivity.php";
}
function toggle_menu() {
    if(document.getElementById("menu_list").style.display=="none") {
        document.getElementById("menu_list").style.display="block";
        document.getElementById("menu_show").style.display="none";
        document.getElementById("menu_close").style.display="inline-block";
    } else {
        document.getElementById("menu_list").style.display="none";
        document.getElementById("menu_show").style.display="inline-block";
        document.getElementById("menu_close").style.display="none";
    }
}
function menu_check() {
    var w = window.innerWidth
        || document.documentElement.clientWidth
        || document.body.clientWidth;
    if(w>768) {
        document.getElementById("menu_show").style.display="none";
        document.getElementById("menu_close").style.display="none";
        document.getElementById("menu_list").style.display="inline-block";
    } else {
        document.getElementById("menu_show").style.display="";
        document.getElementById("menu_close").style.display="";
        document.getElementById("menu_list").style.display="";
    }
}