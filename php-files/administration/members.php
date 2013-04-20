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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/members.php";
include LOCALE.LOCALESET."user_fields.php";

if (!checkrights("M") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($user_id) && !isNum($user_id)) fallback("index.php");
if (!isset($step)) $step = "";

if (isset($_POST['cancel_delete'])) fallback(FUSION_SELF.$aidlink."&sortby=$sortby&rowstart=$rowstart");

if ($step == "add") {
	if (isset($_POST['add_user'])) {
		$error = "";
		
		$username = trim(eregi_replace(" +", " ", $_POST['username']));
		
		if ($username == "" || $_POST['password1'] == "" || $_POST['email'] == "") $error .= $locale['451']."<br>\n";
		
		if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['452']."<br>\n";
		
		if (preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['password1'])) {
			if ($_POST['password1'] != $_POST['password2']) $error .= $locale['456']."<br>\n";
		} else {
			$error .= $locale['457']."<br>\n";
		}
	 
		if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['email'])) {
			$error .= $locale['454']."<br>\n";
		}
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error = $locale['453']."<br>\n";
		
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='".$_POST['email']."'");
		if (dbrows($result) != 0) $error = $locale['455']."<br>\n";
		
		if ($error == "") {
			$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', '".md5(md5($_POST['password1']))."', '$email', '$hide_email', '', '0000-00-00', '', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '".USER_IP."', '', '', '101', '0')");
			opentable($locale['480']);
			echo "<center><br>
".$locale['481']."<br><br>
<a href='members.php".$aidlink."'>".$locale['432']."</a><br><br>
<a href='index.php".$aidlink."'>".$locale['433']."</a><br><br>
</center>\n";
			closetable();
		} else {
			opentable($locale['480']);
			echo "<center><br>
".$locale['482']."<br><br>
$error<br>
<a href='members.php".$aidlink."'>".$locale['432']."</a><br><br>
<a href='index.php".$aidlink."'>".$locale['433']."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		opentable($locale['480']);
		echo "<form name='addform' method='post' action='".FUSION_SELF.$aidlink."&amp;step=add'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u004']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u006']."</td>
<td class='tbl'><input type='radio' name='hide_email' value='1'>".$locale['u007']."<input type='radio' name='hide_email' value='0' checked>".$locale['u008']."</td>
</tr>
<tr>
<td align='center' colspan='2'><br>
<input type='submit' name='add_user' value='".$locale['480']."' class='button'>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} elseif ($step == "view") {
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id'");
	if (dbrows($result)) { $data = dbarray($result); } else { fallback(FUSION_SELF.$aidlink); }
	
	opentable($locale['470']);
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
<td align='center' width='125' rowspan='5' class='tbl2'>\n";

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
		echo "[ <a href='mailto:".str_replace("@","&#64;",$data['user_email'])."' title='".str_replace("@","&#64;",$data['user_email'])."'>".$locale['u051']."</a> ]\n";
	}
	if ($data['user_web']) {
		$urlprefix = !strstr($data['user_web'], "http://") ? "http://" : "";
		echo "[ <a href='".$urlprefix.$data['user_web']."' title='".$urlprefix.$data['user_web']."' target='_blank'>".$locale['u052']."</a> ]\n";
	}
	if ($data['user_id'] != $userdata['user_id']) {
		echo "[ <a href='".BASEDIR."messages.php?msg_send=".$data['user_id']."' title='".$locale['u060']."'>".$locale['u053']."</a> ]\n";
	}
	echo "</td>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u013']."</b></td>
<td class='tbl2'>".($data['user_yahoo'] ? $data['user_yahoo'] : $locale['u048'])."</td>
</tr>
</table>\n";

	tablebreak();

	echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
<tr>
<td class='tbl2' colspan='2'><b>".$locale['472']."</b></td>
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
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u050']."</b></td>
<td class='tbl1'>".$data['user_ip']."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u041']."</b></td>
<td class='tbl2'>".number_format(dbcount("(shout_id)", "shoutbox", "shout_name='".$data['user_id']."'"))."</td>
</tr>
<tr>
<td width='1%' class='tbl1' style='white-space:nowrap'><b>".$locale['u042']."</b></td>
<td class='tbl1'>".number_format(dbcount("(comment_id)", "comments", "comment_name='".$data['user_id']."'"))."</td>
</tr>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['u043']."</b></td>
<td class='tbl2'>".number_format($data['user_posts'])."</td>
</tr>
</table>\n";

	if ($data['user_groups']) {
		tablebreak();
		echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n";
		echo "<tr>\n<td class='tbl2'><b>".$locale['473']."</b></td>\n\n</tr>\n<tr>\n<td class='tbl1'>\n";
		$user_groups = (strpos($data['user_groups'], ".") == 0 ? explode(".", substr($data['user_groups'], 1)) : explode(".", $data['user_groups']));
		for ($i = 0;$i < count($user_groups);$i++) {
			echo getgroupname($user_groups[$i]);
			if ($i != (count($user_groups)-1)) echo ", ";
		}
		echo "</td>\n</tr>\n</table>\n";
	}
	closetable();
} elseif ($step == "edit") {
	if (isset($_POST['savechanges'])) {
		require_once "updateuser.php";
		if ($error == "") {
			opentable($locale['430']);
			echo "<center><br>
".$locale['431']."<br><br>
<a href='members.php".$aidlink."'>".$locale['432']."</a><br><br>
<a href='index.php".$aidlink."'>".$locale['433']."</a><br><br>
</center>\n";
			closetable();
		} else {
			opentable($locale['430']);
			echo "<center><br>
".$locale['434']."<br><br>
$error<br>
<a href='members.php".$aidlink."'>".$locale['432']."</a><br><br>
<a href='index.php".$aidlink."'>".$locale['433']."</a><br><br>
</center>\n";
			closetable();
		}
	} else {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$user_id'");
		if (dbrows($result)) { $data = dbarray($result); } else { fallback(FUSION_SELF.$aidlink); }

		if ($data['user_birthdate']!="0000-00-00") {
			$user_birthdate = explode("-", $data['user_birthdate']);
			$user_month = number_format($user_birthdate['1']);
			$user_day = number_format($user_birthdate['2']);
			$user_year = $user_birthdate['0'];
		} else {
			$user_month = 0; $user_day = 0; $user_year = 0;
		}
		$theme_files = makefilelist(THEMES, ".|..", true, "folders");
		array_unshift($theme_files, "Default");
		$offset_list = "";
		for ($i=-13;$i<17;$i++) {
			if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
			$offset_list .= "<option".($offset == $data['user_offset'] ? " selected" : "").">$offset</option>\n";
		}
		opentable($locale['430']);
		echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."&amp;step=edit&amp;user_id=$user_id' enctype='multipart/form-data'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['u001']."<font color='red'>*&nbsp;</font></td>
<td class='tbl'><input type='text' name='user_name' value='".$data['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u003']."</td>
<td class='tbl'><input type='password' name='user_newpassword' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u004']."</td>
<td class='tbl'><input type='password' name='user_newpassword2' maxlength='20' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u005']."<font color='red'>*&nbsp;</font></td>
<td class='tbl'><input type='text' name='user_email' value='".$data['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u006']."</td>
<td class='tbl'><input type='radio' name='user_hide_email' value='1'".($data['user_hide_email'] == "1" ? " checked" : "").">".$locale['u007']."
<input type='radio' name='user_hide_email' value='0'".($data['user_hide_email'] == "0" ? " checked" : "").">".$locale['u008']."</td>
</tr>
<tr>
<td class='tbl'>".$locale['u009']."</td>
<td class='tbl'><input type='text' name='user_location' value='".$data['user_location']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u010']." <span class='small2'>(mm/dd/yyyy)</span></td>
<td class='tbl'><select name='user_month' class='textbox'>\n<option>--</option>\n";
for ($i=1;$i<=12;$i++) echo "<option".($user_month == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_day' class='textbox'>\n<option>--</option>\n";
for ($i=1;$i<=31;$i++) echo "<option".($user_day == $i ? " selected" : "").">$i</option>\n";
echo "</select>
<select name='user_year' class='textbox'>\n<option>----</option>\n";
for ($i=1900;$i<=2004;$i++) echo "<option".($user_year == $i ? " selected" : "").">$i</option>\n";
echo "</select>
</td>
</tr>
<tr>
<td class='tbl'>".$locale['u021']."</td>
<td class='tbl'><input type='text' name='user_aim' value='".$data['user_aim']."' maxlength='16' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u011']."</td>
<td class='tbl'><input type='text' name='user_icq' value='".$data['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u012']."</td>
<td class='tbl'><input type='text' name='user_msn' value='".$data['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u013']."</td>
<td class='tbl'><input type='text' name='user_yahoo' value='".$data['user_yahoo']."' maxlength='50' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u014']."</td>
<td class='tbl'><input type='text' name='user_web' value='".$data['user_web']."' maxlength='200' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u015']."</td>
<td class='tbl'><select name='user_theme' class='textbox' style='width:100px;'>
".makefileopts($theme_files, $data['user_theme'])."</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u016']."</td>
<td class='tbl'><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>\n";
if (!$data['user_avatar']) {
echo "<tr>
<td class='tbl'>".$locale['u017']."</td>
<td class='tbl'>
<input type='file' name='user_avatar' class='textbox' style='width:200px;'><br>
<span class='small2'>".$locale['u018']."</span><br>
<span class='small2'>".sprintf($locale['u022'], parsebytesize(30720), 100, 100)."</span>
</td>
</tr>\n";
}
echo "<tr>
<td valign='top' class='tbl'>".$locale['u020']."</td>
<td class='tbl'><textarea name='user_sig' rows='5' cols='53' class='textbox'>".$data['user_sig']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('user_sig', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('user_sig', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('user_sig', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('user_sig', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('user_sig', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('user_sig', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('user_sig', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('user_sig', '[small]', '[/small]');\">
</tr>
<tr>
<td colspan='2' class='tbl'><br><div align='center'>\n";
if ($data['user_avatar']) {
echo $locale['u017']."<br>\n<img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$locale['u017']."'><br>
<input type='checkbox' name='del_avatar' value='y'> ".$locale['u019']."
<input type='hidden' name='user_avatar' value='".$data['user_avatar']."'><br><br>\n";
}
echo "<input type='hidden' name='user_hash' value='".$data['user_password']."'>
<input type='submit' name='savechanges' value='".$locale['440']."' class='button'></div>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} else {
	opentable($locale['400']);
	if ($step == "ban") {
		if ($act == "on") {
			if ($user_id != 1) {
				$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='$user_id'");
				echo "<center>".$locale['420']."<br><br></center>\n";
			}
		} elseif ($act == "off") {
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0' WHERE user_id='$user_id'");
			echo "<center>".$locale['421']."<br><br></center>\n";
		}
	} elseif ($step == "activate") {
		$result = dbquery("SELECT user_name,user_email FROM ".$db_prefix."users WHERE user_id='$user_id'");
		if (dbrows($result) != 0) {
			$udata = dbarray($result);
			$result = dbquery("UPDATE ".$db_prefix."users SET user_status='0' WHERE user_id='$user_id'");
			if ($settings['email_verification'] == "1") {
				require_once INCLUDES."sendmail_include.php";
				sendemail($udata['user_name'],$udata['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['425'].$settings['sitename'],str_replace("[USER_NAME]", $udata['user_name'], $locale['426']));
			}
			echo "<center>".$locale['424']."<br><br></center>\n";
		}
	} elseif ($step == "delete") {
		if ($user_id != 1) {
			$result = dbquery("DELETE FROM ".$db_prefix."users WHERE user_id='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."articles WHERE article_name='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_name='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_to='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_from='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."news WHERE news_name='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."poll_votes WHERE vote_user='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_user='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."shoutbox WHERE shout_name='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_author='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_author='$user_id'");
			$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_user='$user_id'");
			echo "<center>".$locale['422']."<br><br></center>\n";
		}
	}
	if (!isset($sortby) || !preg_match("/^[0-9A-Z]$/", $sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '".stripinput($sortby)."%'");
	$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby."");
	$rows = dbrows($result);
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0; 
	$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby." ORDER BY user_status DESC, user_level DESC, user_name LIMIT $rowstart,20");
	if ($rows != 0) {
		$i = 0;
		echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>
<tr>
<td class='tbl2'><b>".$locale['401']." [<a href='".FUSION_SELF.$aidlink."&amp;step=add'>".$locale['402']."</a>]</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['403']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['404']."</b></td>
</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n<td class='$cell_color'><a href='".FUSION_SELF.$aidlink."&amp;step=view&amp;user_id=".$data['user_id']."'>".$data['user_name']."</a></td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>";
			if (iUSER >= $data['user_level'] && $data['user_id'] != 1) {
				echo "<a href='".FUSION_SELF.$aidlink."&amp;step=edit&amp;user_id=".$data['user_id']."'>".$locale['406']."</a>\n";
				if ($data['user_status'] == "2") {
					echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=activate&amp;sortby=$sortby&amp;rowstart=$rowstart&amp;user_id=".$data['user_id']."'>".$locale['412']."</a>\n";
				} elseif ($data['user_status'] == "1") {
					echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=ban&amp;act=off&amp;sortby=$sortby&amp;rowstart=$rowstart&amp;user_id=".$data['user_id']."'>".$locale['407']."</a>\n";
				} else {
					echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=ban&amp;act=on&amp;sortby=$sortby&amp;rowstart=$rowstart&amp;user_id=".$data['user_id']."'>".$locale['408']."</a>\n";
				}
				echo "- <a href='".FUSION_SELF.$aidlink."&amp;step=delete&amp;sortby=$sortby&amp;rowstart=$rowstart&amp;user_id=".$data['user_id']."' onClick='return DeleteMember();'>".$locale['409']."</a>";
			}
			echo "</td>\n</tr>\n"; $i++;
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".$locale['410']."$sortby<br><br>\n</center>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr>\n<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;sortby=all'>".$locale['411']."</a></td>";
	for ($i=0;$i < 36;$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF.$aidlink."&amp;sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;sortby=all'>".$locale['411']."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
	closetable();
	if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,FUSION_SELF.$aidlink."&amp;sortby=$sortby&amp;")."\n</div>\n";
	echo "<script type='text/javascript'>
function DeleteMember(username) {
	return confirm('".$locale['423']."');
}
</script>\n";
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>