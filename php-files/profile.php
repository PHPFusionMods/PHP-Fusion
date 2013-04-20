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
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."members-profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (!isset($group_id)) {
	if (!isset($lookup) || !isNum($lookup)) fallback("index.php");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$lookup'");
	if (dbrows($result)) { $data = dbarray($result); } else { redirect("index.php"); }
	opentable($locale['420']);
	echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
<tr>
<td colspan='3'>
<table align='center' cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='tbl2'><b>".$data['user_name']."</b></td>
<td align='right' class='tbl2'>".getuserlevel($data['user_level'])."</td>
</tr>
</table>
</td>
<tr>
<td align='center' width='150' rowspan='5' class='tbl2'>\n";

	echo ($data['user_avatar'] ? "<img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$locale['u017']."'>" : $locale['u046'])."</td>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u009']."</b></td>
<td class='tbl1'>".($data['user_location'] ? $data['user_location'] : $locale['u048'])."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u010']."</b></td>
<td class='tbl2'>";
	if ($data['user_birthdate'] != "0000-00-00") {
		$months = explode("|", $locale['months']);
		$user_birthdate = explode("-", $data['user_birthdate']);
		echo $months[number_format($user_birthdate['1'])]." ".number_format($user_birthdate['2'])." ".$user_birthdate['0'];
	} else {
		echo $locale['u048'];
	}
	echo "</td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u021']."</b></td>
<td class='tbl1'>".($data['user_aim'] ? $data['user_aim'] : $locale['u048'])."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u011']."</b></td>
<td class='tbl2'>".($data['user_icq'] ? $data['user_icq'] : $locale['u048'])."</td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u012']."</b></td>
<td class='tbl1'>".($data['user_msn'] ? $data['user_msn'] : $locale['u048'])."</td>
</tr>
<tr>
<td align='center' class='tbl1'>\n";
	if ($data['user_hide_email'] != "1" || iADMIN) {
		echo "[<a href='mailto:".str_replace("@","&#64;",$data['user_email'])."' title='".str_replace("@","&#64;",$data['user_email'])."'>".$locale['u051']."</a>]\n";
	}
	if ($data['user_web']) {
		$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
		echo "[<a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".$locale['u052']."</a>]\n";
	}
	if (!isset($userdata['user_id']) || $data['user_id'] != $userdata['user_id']) {
		echo "[<a href='messages.php?msg_send=".$data['user_id']."' title='".$locale['u060']."'>".$locale['u053']."</a>]\n";
	}
	echo "</td>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u013']."</b></td>
<td class='tbl2'>".($data['user_yahoo'] ? $data['user_yahoo'] : $locale['u048'])."</td>
</tr>
</table>\n";

	tablebreak();
	
	echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
<tr>
<td class='tbl2' colspan='2'><b>".$locale['422']."</b></td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u040']."</b></td>
<td class='tbl1'>".showdate("longdate", $data['user_joined'])."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u044']."</b></td>
<td class='tbl2'>".($data['user_lastvisit'] != 0 ? showdate("longdate", $data['user_lastvisit']) : $locale['u049'])."</td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u041']."</b></td>
<td class='tbl1'>".number_format(dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'"))."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u042']."</b></td>
<td class='tbl2'>".number_format(dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'"))."</td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u043']."</b></td>
<td class='tbl1'>".number_format(dbcount("(post_id)", "posts", "post_author='".$data['user_id']."'"))."</td>
</tr>
</table>\n";
	if ($data['user_groups']) {
		tablebreak();
		echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n";
		echo "<tr>\n<td class='tbl2'><b>".$locale['423']."</b></td>\n\n</tr>\n<tr>\n<td class='tbl1'>\n";
		$user_groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
		for ($i = 0;$i < count($user_groups);$i++) {
			echo "<a href='".FUSION_SELF."?group_id=".$user_groups[$i]."'>".getgroupname($user_groups[$i])."</a>";
			if ($i != (count($user_groups)-1)) { echo ",\n"; } else { echo "\n"; }
		}
		echo "</td>\n</tr>\n</table>\n";
	}
} else {
	if (!isNum($group_id)) fallback("index.php");
	$result = dbquery("SELECT * FROM ".$db_prefix."user_groups WHERE group_id='$group_id'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_groups REGEXP('^\\\.{$group_id}$|\\\.{$group_id}\\\.|\\\.{$group_id}$') ORDER BY user_level DESC, user_name");
		opentable("View User Group");
		echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
<tr>
<td align='center' colspan='2' class='tbl1'><b>".$data['group_name']."</b> (".sprintf((dbrows($result)==1?$locale['411']:$locale['412']), dbrows($result)).")</td>
</tr>
<tr>
<td class='tbl2'><b>".$locale['401']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['402']."</b></td>
</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n<td class='$cell_color'>\n<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n</tr>";
		}
		echo "</table>\n";
	} else {
		fallback(BASEDIR."index.php");
	}
}
closetable();

require_once "side_right.php";
require_once "footer.php";
?>