<?php
// Configuring
require_once('./config.php');

ini_set('display_errors', 0);

$mode = @$_GET['mode'];
$from = @$_GET['from'];

// Sending headers, which means that document always modified
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");// HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");// HTTP/1.0
header('Content-Type: text/html; charset=windows-1251');

// Selecting mode of administrator area
function select_mode($mode) {
	$submode = @$_GET['submode'];
	// Start buffering
	ob_start();
	include_once('./templates/header.inc.php');
	include_once('./templates/menu.inc.php');
	//$Mod = "qwe";
	// Select an part of admin system
	if (!empty($mode) && $mode != 'logout'){
		require_once('modules/'.$mode.'/'.$mode.'.php');
		$object = new $mode();
		echo $object->preview();
	}
	
	// Finish buffering and cleaning it
	$buffer = ob_get_contents();
//	echo ob_get_flush();
	ob_end_clean();
	echo $buffer;
	include_once('./templates/footer.inc.php');
}

// If login and password is correct
function auth_success($admin_password) {
	// Setting up session
	global $admin_default_mode;
	$_SESSION['auth_key'] = $admin_password;
	// Selecting default mode and exiting
	select_mode($admin_default_mode);
	exit();
}

// Log out from administrator area
if ($mode == 'logout') {
//	session_unregister('auth_key');
	session_destroy();
	header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/");
}
elseif (isset($_SESSION['auth_key']) && !empty($_SESSION['auth_key'])){
	if (empty($mode)) 
		select_mode($admin_default_mode);
	else 
		select_mode($mode);
	exit();
}

// POST-login
if (isset($_POST['post_auth']) && $_POST['post_auth'] == 'yes') {
	if (isset($_POST['login']) && !empty($_POST['login']) && isset($_POST['pass']) && !empty($_POST['pass'])) {
		$login = trim($_POST['login']);
		$pass = md5(trim($_POST['pass']));
		
		$db = mysql_connect($dbLocation, $dbUser, $dbPassword) or die ('I cannot connect to the database because: '.mysql_error());
		mysql_select_db($dbName, $db);
		mysql_query('SET NAMES `cp1251`');
		
		$count = mysql_result(mysql_query('SELECT COUNT(*) FROM `ausers` WHERE `name`="'.$login.'" AND `pass`="'.$pass.'"'), 0);
		
		if ($count == 1)
			auth_success($pass);
		
		mysql_close($db);
	}
}

include_once('./templates/auth.inc.php');
/*
echo <<<AUTH_FORM
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>UCMS Administrator area...</TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<META NAME="Author" CONTENT="Pavel Golovenko">
</HEAD>
<BODY bgcolor="#E5E5E5" style="margin:0px;" onload="document.auth_form.login.focus();">
<FORM METHOD="POST" NAME="auth_form" ID="auth_form">
<INPUT TYPE="hidden" NAME="post_auth" VALUE="yes">
<CENTER>
	<TABLE style="border-collaps:collapse; border:1px white solid;">
	<TR>
		<TD colspan="2" style="height:10px;"></TD>
	</TR>
	<TR>
		<TD style="width:100px;">Login: </TD>
		<TD style="width:200px;">
			<INPUT TYPE="text" NAME="login" 
				style="width:200px;border-color:#000000;background-color:#E6E6E6;border-style:solid;border-top-width:0px;border-bottom-width:1px;border-left-width:0px;border-right-width:1px;"
				onfocus="this.value='';">
		</TD>
	</TR>
	<TR>
		<TD style="width:100px;">Password: </TD>
		<TD style="width:200px;">
			<INPUT TYPE="password" NAME="pass" 
				style="width:200px;border-color:#000000;background-color:#E6E6E6;border-style:solid;border-top-width:0px;border-bottom-width:1px;border-left-width:0px;border-right-width:1px;"
				onfocus="this.value='';">
		</TD>
	</TR>
	<TR>
		<TD colspan="2" style="height:10px;"></TD>
	</TR>
	<TR>
		<TD colspan="2" style="width:300px;">
			<INPUT TYPE="submit" VALUE="Enter Administrator area" style="border-color:#000000;border-style:solid;border-width:1px;width:300px;">
		</TD>
	</TR>
	</TABLE>
</CENTER>
</FORM>
</BODY>
</HTML>
AUTH_FORM;
*/
?>