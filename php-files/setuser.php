<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
include THEME."theme.php";

echo "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN'>
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta http-equiv='refresh' content='2; url=index.php'>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>
</head>
<body class='tbl2'>

<table width='100%' height='100%'>
<tr>
<td>

<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
<tr>
<td class='tbl1'>
<center><br>
<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."'><br><br>\n";

if (isset($_REQUEST['logout']) && $_REQUEST['logout'] == "yes") {
	header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
	setcookie("fusion_user", "", time() - 7200, "/", "", "0");
	setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_ip='".USER_IP."'");
	echo "<b>".$locale['192'].$userdata['user_name']."</b><br><br>\n";
} else {
	if (!isset($error)) $error = "";
	if ($error == 1) {
		echo "<b>".$locale['194']."</b><br><br>\n";
	} elseif ($error == 2) {
		echo "<b>".$locale['195']."</b><br><br>\n";
	} elseif ($error == 3) {
		echo "<b>".$locale['196']."</b><br><br>\n";
	} else {
		if (isset($_COOKIE['fusion_user'])) {
			$cookie_vars = explode(".", $_COOKIE['fusion_user']);
			$user_pass = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
			$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($user));
			if (!dbcount("(user_id)", "users", "user_name='$user_name' AND user_password='$user_pass'")) {
				echo "<b>".$locale['196']."</b><br><br>\n";
			} else {
				$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_user='0' AND online_ip='".USER_IP."'");
				echo "<b>".$locale['193'].$user."</b><br><br>\n";
			}
		}
	}
}

echo $locale['197']."<br><br>
</center>
</td>
</tr>
</table>

</td>
</tr>
</table>

</body>
</html>\n";

ob_end_flush();
?>