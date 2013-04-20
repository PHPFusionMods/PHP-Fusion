<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
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
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
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
<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."'><br><br>
".stripslashes(nl2br($settings['maintenance_message']))."<br><br>
Powered by <a href='http://www.php-fusion.co.uk'>PHP-Fusion</a> v".$settings['version']." &copy; 2003-2005<br><br>
</center>
</td>
</tr>
</table>\n";

if (!iMEMBER) {
	echo "<div align='center'><br>
<form name='loginform' method='post' action='".FUSION_SELF."'>
".$locale['061'].": <input type='text' name='user_name' class='textbox' style='width:100px'>
".$locale['062'].": <input type='password' name='user_pass' class='textbox' style='width:100px'>
<input type='checkbox' name='remember_me' value='y' title='".$locale['063']."'>
<input type='submit' name='login' value='".$locale['064']."' class='button'>
</form>
</div>\n";
}

echo "</td>
</tr>
</table>\n";

echo "</body>
</html>\n";

mysql_close();

ob_end_flush();
?>