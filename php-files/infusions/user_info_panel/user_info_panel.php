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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

if (iMEMBER) {
	openside($userdata['user_name']);
	$msg_count = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."edit_profile.php' class='side'>".$locale['080']."</a><br>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."messages.php' class='side'>".$locale['081']."</a><br>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."members.php' class='side'>".$locale['082']."</a><br>\n";
	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['083']."</a><br>\n";
	}
	echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."setuser.php?logout=yes' class='side'>".$locale['084']."</a>\n";
	if ($msg_count) echo "<br><br><center><b><a href='".BASEDIR."messages.php' class='side'>".sprintf($locale['085'], $msg_count).($msg_count == 1 ? $locale['086'] : $locale['087'])."</a></b></center>\n";
} else {
	openside($locale['060']);
	echo "<div align='center'>".(isset($loginerror) ? $loginerror : "")."
<form name='loginform' method='post' action='".FUSION_SELF."'>
".$locale['061']."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".$locale['062']."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y' title='".$locale['063']."' style='vertical-align:middle;'>
<input type='submit' name='login' value='".$locale['064']."' class='button'><br>
</form>
<br>\n";
	if ($settings['enable_registration']) {
		echo "".$locale['065']."<br><br>\n";
	}
	echo $locale['066']."
</div>\n";
}
closeside();
?>