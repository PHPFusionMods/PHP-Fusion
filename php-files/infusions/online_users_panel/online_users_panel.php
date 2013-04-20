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

if ($settings['maintenance'] != "1") {
	$cond = ($userdata['user_level'] != 0 ? "'".$userdata['user_id']."'" : "'0' AND online_ip='".USER_IP."'");
	$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user=".$cond."");
	if (dbrows($result) != 0) {
		$result = dbquery("UPDATE ".$db_prefix."online SET online_lastactive='".time()."' WHERE online_user=".$cond."");
	} else {
		$name = ($userdata['user_level'] != 0 ? $userdata['user_id'] : "0");
		$result = dbquery("INSERT INTO ".$db_prefix."online (online_user, online_ip, online_lastactive) VALUES ('$name', '".USER_IP."', '".time()."')");
	}
	$result = dbquery("DELETE FROM ".$db_prefix."online WHERE online_lastactive<".(time()-60)."");
	openside($locale['010']);
	$result = dbquery("SELECT * FROM ".$db_prefix."online WHERE online_user='0'");
	echo "<img src='".THEME."images/bullet.gif' alt=''> ".$locale['011'].dbrows($result)."<br>\n";
	$result = dbquery(
		"SELECT ton.*, user_id,user_name FROM ".$db_prefix."online ton
		LEFT JOIN ".$db_prefix."users tu ON ton.online_user=tu.user_id
		WHERE online_user!='0'"
	);
	$members = dbrows($result);
	if ($members != 0) {
		$i = 1;
		echo "<img src='".THEME."images/bullet.gif' alt=''> ".$locale['012'];
		while($data = dbarray($result)) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>";
			if ($i != $members) echo ", ";
			$i++;
		}
		echo "<br>\n";
	} else {
		echo $locale['013']."<br>\n";
	}
	echo "<br><img src='".THEME."images/bullet.gif' alt=''> ".$locale['014'].number_format(dbcount("(user_id)", "users", "user_status<='1'"))."<br>\n";
	if ($settings['admin_activation'] == "1") echo "<img src='".THEME."images/bullet.gif' alt=''> ".$locale['015'].dbcount("(user_id)", "users", "user_status='2'")."<br>\n";
	$data = dbarray(dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE user_status='0' ORDER BY user_joined DESC LIMIT 0,1"));
	echo "<img src='".THEME."images/bullet.gif' alt=''> ".$locale['016']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' class='side'>".$data['user_name']."</a>\n";
	closeside();
}
?>