<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";

if (!iADMIN || $userdata['user_rights'] == "") fallback("../index.php");
if (!isset($pagenum) || !isNum($pagenum)) $pagenum = 1;

$admin_images = true;

// Work out which tab is the active default
if ($page1) { $default = 1; }
elseif ($page2) { $default = 2; }
elseif ($page3) { $default = 3; }
elseif ($page4) { $default = 4; }
else { fallback("../index.php"); }

// Ensure the admin is allowed to access the selected page
$pageon = true;
if ($pagenum == 1 && !$page1) $pageon = false;
if ($pagenum == 2 && !$page2) $pageon = false;
if ($pagenum == 3 && !$page3) $pageon = false;
if ($pagenum == 4 && !$page4) $pageon = false;
if ($pageon == false) redirect("index.php?pagenum=$default");

// Display admin panels & pages
opentable($locale['200']." - v".$settings['version']);
echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
if ($page1) {
	echo "<td align='center' width='25%' class='".($pagenum == 1 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($pagenum == 1 ? "<b>".$locale['ac01']."</b>" : "<a href='index.php?pagenum=1'>".$locale['ac01']."</a>")."</span></td>\n";
}
if ($page2) {
	echo "<td align='center' width='25%' class='".($pagenum == 2 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($pagenum == 2 ? "<b>".$locale['ac02']."</b>" : "<a href='index.php?pagenum=2'>".$locale['ac02']."</a>")."</span></td>\n";
}
if ($page3) {
	echo "<td align='center' width='25%' class='".($pagenum == 3 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($pagenum == 3 ? "<b>".$locale['ac03']."</b>" : "<a href='index.php?pagenum=3'>".$locale['ac03']."</a>")."</span></td>\n";
}
if ($page4) {
	echo "<td align='center' width='25%' class='".($pagenum == 4 ? "tbl1" : "tbl2")."'><span class='small'>\n";
	echo ($pagenum == 4 ? "<b>".$locale['ac04']."</b>" : "<a href='index.php?pagenum=4'>".$locale['ac04']."</a>")."</span></td>\n";
}
echo "</tr>
<tr>
<td colspan='4' class='tbl1'>\n";
$result = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_page='$pagenum' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4;
	$align = $admin_images ? "center" : "left";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			echo "<td align='$align' width='25%' class='tbl'>";
			if ($admin_images) {
				echo "<span class='small'><a href='".$data['admin_link']."'><img src='".ADMIN."images/".$data['admin_image']."' alt='".$data['admin_title']."' style='border:0px;'><br>\n".$data['admin_title']."</a></span>";
			} else {
				echo "<span class='small'><img src='".THEME."images/bullet.gif' alt=''> <a href='".$data['admin_link']."'>".$data['admin_title']."</a></span>";
			}
			echo "</td>\n";
			$counter++;
		}
	}
	echo "</tr>\n</table>\n";
} else {
	echo "<center><br>\n".$locale['401']."<br><br>\n</center>\n";
}
echo "</td>\n</tr>\n</table>\n";
closetable();
tablebreak();
opentable($locale['250']);
echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td width='33%' class='small'>
".$locale['251']." ".dbcount("(user_id)", "users", "user_status<='1'")."<br>
".$locale['252']." ".dbcount("(user_id)", "users", "user_status='2'")."<br>
".$locale['253']." ".dbcount("(user_id)", "users", "user_status='1'")."<br>
</td>
<td valign='top' width='33%' class='small'>
".$locale['254']." ".dbcount("(submit_id)", "submissions", "submit_type='n'")."<br>
".$locale['255']." ".dbcount("(submit_id)", "submissions", "submit_type='a'")."<br>
".$locale['256']." ".dbcount("(submit_id)", "submissions", "submit_type='l'")."
<td valign='top' width='33%' class='small'>
".$locale['257']." ".dbcount("(comment_id)", "comments")."<br>
".$locale['258']." ".dbcount("(shout_id)", "shoutbox")."<br>
".$locale['259']." ".dbcount("(post_id)", "posts")."
</td>\n</tr>\n</table>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>