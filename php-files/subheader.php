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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) fallback(BASEDIR."maintenance.php");
if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>
<script type='text/javascript' src='".INCLUDES."jscript.js'></script>
</head>
<body bgcolor='$body_bg' text='$body_text'>\n";

render_header("<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' title='".$settings['sitename']."'>");
?>