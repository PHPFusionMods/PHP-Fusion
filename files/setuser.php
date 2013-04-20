<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: setuser.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
include THEME."theme.php";

$session_destroyed = false; $page_content = ""; $page_refresh = "2";

if (iMEMBER && (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes")) {
	if ($settings['login_method'] == "cookies") {
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		setcookie(COOKIE_PREFIX."user", "", time() - 7200, "/", "", "0");
		setcookie(COOKIE_PREFIX."lastvisit", "", time() - 7200, "/", "", "0");
	} elseif ($settings['login_method'] == "sessions") {
		session_destroy();
		$session_destroyed = true;
	}
	$result = dbquery("DELETE FROM ".DB_ONLINE." WHERE online_ip='".USER_IP."'");
	$page_content .= "<strong>".$locale['global_192'].$userdata['user_name']."</strong><br /><br />\n";
} else {
	if (isset($_GET['error']) && $_GET['error'] == 1) {
		$id = ((isset($_GET['id']) && isnum($_GET['id'])) ? $_GET['id'] : "0");
		$data = dbarray(dbquery("SELECT suspend_reason FROM ".DB_SUSPENDS." WHERE suspended_user='".$id."' ORDER BY suspend_date DESC LIMIT 1"));
		$page_content .= "<strong>".$locale['global_406']."<br /><br />\n";
		$page_content .= $data['suspend_reason']."</strong><br /><br />\n";
		$page_refresh = "15";
	} elseif (isset($_GET['error']) && $_GET['error'] == 2) {
		$page_content .= "<strong>".$locale['global_195']."</strong><br /><br />\n";
	} elseif (isset($_GET['error']) && $_GET['error'] == 3) {
		$id = ((isset($_GET['id']) && isnum($_GET['id'])) ? $_GET['id'] : "0");
		$data = dbarray(dbquery("SELECT suspend_reason FROM ".DB_SUSPENDS." WHERE suspended_user='".$id."' ORDER BY suspend_date DESC LIMIT 1"));
		$data2 = dbarray(dbquery("SELECT user_actiontime FROM ".DB_USERS." WHERE user_id='".$id."'"));
		$page_content .= "<strong>".$locale['global_407'].showdate('shortdate', $data2['user_actiontime']).$locale['global_408']."<br /><br />\n";
		$page_content .= $data['suspend_reason']."</strong><br /><br />\n";
		$page_refresh = "15";
	} elseif (isset($_GET['error']) && $_GET['error'] == 4) {
		$page_content .= "<strong>".$locale['global_409']."<br /><br />\n";
		$page_content .= $locale['global_410'].$data['suspend_reason']."</strong><br /><br />\n";
	} elseif (isset($_GET['error']) && $_GET['error'] == 5) {
		$page_content .= "<strong>".$locale['global_411']."</strong><br /><br />\n";
	} elseif (isset($_GET['error']) && $_GET['error'] == 6) {
		$page_content .= "<strong>".$locale['global_412']."</strong><br /><br />\n";
		$page_refresh = "7";
	} elseif (isset($_GET['error']) && $_GET['error'] == 8) {
		$page_content .= "<strong>".$locale['global_196']."</strong><br /><br />\n";
	} else {
		if (($settings['login_method'] == "cookies" && isset($_COOKIE[COOKIE_PREFIX.'user'])) || ($settings['login_method'] == "sessions" && isset($_SESSION[COOKIE_PREFIX.'user_id']) && isset($_SESSION[COOKIE_PREFIX.'user_pass']))) {
			if ($settings['login_method'] == "cookies") {
				$cookie_vars = explode(".", $_COOKIE[COOKIE_PREFIX.'user']);
				$user_pass = preg_check("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "";
			} elseif ($settings['login_method'] == "sessions") {
				$user_pass = preg_check("/^[0-9a-z]{32}$/", $_SESSION[COOKIE_PREFIX.'user_pass']) ? $_SESSION[COOKIE_PREFIX.'user_pass'] : "";
			}
			if (!isset($_GET['user'])) { redirect("index.php"); }
			$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_GET['user']));
			if (!dbcount("(user_id)", DB_USERS, "user_name='".$user_name."' AND user_password='".md5($user_pass)."'")) {
				$page_content .= "<strong>".$locale['global_196']."</strong><br /><br />\n";
			} else {
				$result = dbquery("DELETE FROM ".DB_ONLINE." WHERE online_user='0' AND online_ip='".USER_IP."'");
				$page_content .= "<strong>".$locale['global_193'].$_GET['user']."</strong><br /><br />\n";
			}
		}
	}
}
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta http-equiv='refresh' content='".$page_refresh."; url=".$settings['opening_page']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type='text/css'>html, body { height:100%; }</style>\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' />\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "</head>\n<body class='tbl2 setuser_body'>\n";

echo "<table style='width:100%;height:100%'>\n<tr>\n<td>\n";

echo "<table cellpadding='0' cellspacing='1' width='80%' class='tbl-border center'>\n<tr>\n";
echo "<td class='tbl1'>\n<div style='text-align:center'><!--setuser_pre_logo--><br />\n";
echo "<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' /><br /><br />\n";
echo $page_content.$locale['global_197']."<br /><br />\n";
echo "</div>\n</td>\n</tr>\n</table>\n";

echo "</td>\n</tr>\n</table>\n";

echo "</body>\n</html>\n";

if (ob_get_length() !== FALSE){
	ob_end_flush();
}

if ($settings['login_method'] == "sessions" && $session_destroyed == false) {
	session_write_close();
}

mysql_close($db_connect);
?>