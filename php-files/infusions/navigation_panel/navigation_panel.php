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
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }

openside($locale['001']);
$result = dbquery("SELECT * FROM ".$db_prefix."site_links WHERE link_position<='2' ORDER BY link_order");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		if (checkgroup($data['link_visibility'])) {
			if ($data['link_name'] != "---" && $data['link_url'] == "---") {
				echo "<div class='side-label'><b>".$data['link_name']."</b></div>\n";
			} else if ($data['link_name'] == "---" && $data['link_url'] == "---") {
				echo "<hr class='side-hr'>\n";
			} else {
				$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
					echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br>\n";
				} else {
					echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR.$data['link_url']."'".$link_target." class='side'>".$data['link_name']."</a><br>\n";
				}
			}
		}
	}
} else {
	echo $locale['002'];
}
closeside();
?>