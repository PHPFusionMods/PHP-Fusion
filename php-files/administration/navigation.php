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
if (!defined("IN_FUSION")) { header("location: ../index.php"); exit; }

include LOCALE.LOCALESET."admin/main.php";

echo "<td width='$theme_width_l' valign='top' class='side-border-left'>\n";

include INFUSIONS."user_info_panel/user_info_panel.php";

openside($locale['001']);
// Find out which panels and pages the admin can access
$usr_rghts = " (admin_rights='".str_replace(".", "' OR admin_rights='", $userdata['user_rights'])."')";
$page1 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='1'");
$page2 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='2'");
$page3 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='3'");
$page4 = dbcount("(*)", "admin", $usr_rghts." AND admin_link!='reserved' AND admin_page='4'");
if (iMEMBER) {
	if ($page1) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=1' class='side'>".$locale['ac01']."</a><br>\n";
	if ($page2) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=2' class='side'>".$locale['ac02']."</a><br>\n";
	if ($page3) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=3' class='side'>".$locale['ac03']."</a><br>\n";
	if ($page4) echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".ADMIN."index.php".$aidlink."&amp;pagenum=4' class='side'>".$locale['ac04']."</a><br>\n";
	echo "<hr class='side-hr'>
<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."index.php' class='side'>".$locale['151']."</a>\n";
}

closeside();

echo "</td>\n<td valign='top' class='main-bg'>\n";
?>