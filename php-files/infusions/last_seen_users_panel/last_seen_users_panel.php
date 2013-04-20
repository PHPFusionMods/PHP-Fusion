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
if (file_exists(INFUSIONS."last_seen_users_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."last_seen_users_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."last_seen_users_panel/locale/English.php";
}

openside($locale['lsup000']);
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_lastvisit>'0' AND user_status='0' ORDER BY user_lastvisit DESC LIMIT 0,10");
echo "<table cellpadding='0' cellspacing='0' width='100%'>";
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$lastseen = time() - $data['user_lastvisit'];
		$iW=sprintf("%2d",floor($lastseen/604800));
		$iD=sprintf("%2d",floor($lastseen/(60*60*24)));
		$iH=sprintf("%02d",floor((($lastseen%604800)%86400)/3600));
		$iM=sprintf("%02d",floor(((($lastseen%604800)%86400)%3600)/60));
		$iS=sprintf("%02d",floor((((($lastseen%604800)%86400)%3600)%60)));
		if ($lastseen < 60){
			$lastseen= $locale['lsup001'];
		} elseif ($lastseen < 360){
			$lastseen= $locale['lsup002'];
		} elseif ($iW > 0){
			if ($iW == 1) { $text = $locale['lsup003']; } else { $text = $locale['lsup004']; }
			$lastseen = $iW." ".$text;
		} elseif ($iD > 0){
			if ($iD == 1) { $text = $locale['lsup005']; } else { $text = $locale['lsup006']; }
			$lastseen = $iD." ".$text;
		} else {
			$lastseen = $iH.":".$iM.":".$iS;
		}
		echo "<tr>\n<td class='side-small' align='left'><img src='".THEME."images/bullet.gif' alt=''>\n";
		echo "<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."' title='".$data['user_name']."' class='side'>\n";
		echo trimlink($data['user_name'], 15)."</a></td><td class='side-small' align='right'>".$lastseen."</td>\n</tr>\n";
	}
}
echo "</table>";
closeside();
?>