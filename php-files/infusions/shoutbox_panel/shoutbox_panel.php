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

openside($locale['120']);
if (iMEMBER || $settings['guestposts'] == "1") {
	if (isset($_POST['post_shout'])) {
		$flood = false;
		if (iMEMBER) {
			$shout_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$shout_name = trim(stripinput($_POST['shout_name']));
			$shout_name = preg_replace("(^[0-9]*)", "", $shout_name);
			if (isNum($shout_name)) $shout_name="";
		}
		$shout_message = str_replace("\n", " ", $_POST['shout_message']);
		$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
		$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
		$shout_message = trim(stripinput(censorwords($shout_message)));
		$shout_message = str_replace("\n", "<br>", $shout_message);
		if ($shout_name != "" && $shout_message != "") {
			$result = dbquery("SELECT MAX(shout_datestamp) AS last_shout FROM ".$db_prefix."shoutbox WHERE shout_ip='".USER_IP."'");
			if (!iSUPERADMIN || dbrows($result) > 0) {
				$data = dbarray($result);
				if ((time() - $data['last_shout']) < $settings['flood_interval']) {
					$flood = true;
					$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
					if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
						if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
					}
				}
			}
			if (!$flood) $result = dbquery("INSERT INTO ".$db_prefix."shoutbox (shout_name, shout_message, shout_datestamp, shout_ip) VALUES ('$shout_name', '$shout_message', '".time()."', '".USER_IP."')");
		}
		fallback(FUSION_SELF.(FUSION_QUERY ? "?".str_replace("&amp;", "&", FUSION_QUERY) : ""));
	}
	echo "<form name='chatform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td colspan='2'>\n";
	if (iGUEST) {
		echo $locale['121']."<br>
<input type='text' name='shout_name' value='' class='textbox' maxlength='30' style='width:140px;'><br>
".$locale['122']."<br>\n";
	}
	echo "<textarea name='shout_message' rows='4' cols='25' class='textbox'></textarea>
</td>
</tr>
<tr>
<td><input type='submit' name='post_shout' value='".$locale['123']."' class='button'></td>
<td align='right' class='small'><a href='".INFUSIONS."shoutbox_panel/shoutboxhelp.php' class='side'>".$locale['124']."</a></td>
</tr>
</table>
</form>
<br>\n";
} else {
	echo "<center>".$locale['125']."</center><br>\n";
}
$result = dbquery("SELECT count(shout_id) FROM ".$db_prefix."shoutbox");
$numrows = dbresult($result, 0);
$result = dbquery(
	"SELECT * FROM ".$db_prefix."shoutbox LEFT JOIN ".$db_prefix."users
	ON ".$db_prefix."shoutbox.shout_name=".$db_prefix."users.user_id
	ORDER BY shout_datestamp DESC LIMIT 0,".$settings['numofshouts']
);
if (dbrows($result) != 0) {
	$i = 0;
	while ($data = dbarray($result)) {
		echo "<span class='shoutboxname'><img src='".THEME."images/bullet.gif' alt=''> ";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='side'>".$data['user_name']."</a>\n";
		} else {
			echo $data['shout_name']."\n";
		}
		echo "</span><br>\n<span class='shoutboxdate'>".showdate("shortdate", $data['shout_datestamp'])."</span>";
		if (iADMIN && checkrights("S")) {
			echo "\n[<a href='".ADMIN."shoutbox.php".$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."' class='side'>".$locale['048']."</a>]";
		}
		echo "<br>\n<span class='shoutbox'>".parsesmileys($data['shout_message'])."</span><br>\n";
		if ($i != $numrows) echo "<br>\n";
	}
	if ($numrows > $settings['numofshouts']) {
		echo "<center>\n<img src='".THEME."images/bullet.gif' alt=''>
<a href='".INFUSIONS."shoutbox_panel/shoutbox_archive.php' class='side'>".$locale['126']."</a>
<img src='".THEME."images/bulletb.gif' alt=''></center>\n";
	}
} else {
	echo "<div align='left'>".$locale['127']."</div>\n";
}
closeside();
?>