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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."members-profile.php";
include LOCALE.LOCALESET."user_fields.php";

if (isset($_POST['update_profile'])) require_once INCLUDES."update_profile_include.php";

opentable($locale['440']);
if (iMEMBER) {
	if ($userdata['user_birthdate']!="0000-00-00") {
		$user_birthdate = explode("-", $userdata['user_birthdate']);
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
		if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
		$offset_list .= "<option".($offset == $userdata['user_offset'] ? " selected" : "").">$offset</option>\n";
	}
	echo "<form name='inputform' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
	echo "<table align='center' cellpadding='0' cellspacing='0'>\n";
	if (isset($update_profile)) {
		echo "<tr>\n<td colspan='2' class='tbl'>".$locale['441']."<br><br>\n</td>\n</tr>\n";
	}
	echo "<tr>
<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='user_name' value='".$userdata['user_name']."' maxlength='30' class='textbox' style='width:200px;'></td>
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
<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>
<td class='tbl'><input type='text' name='user_email' value='".$userdata['user_email']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u006']."</td>
<td class='tbl'><input type='radio' name='user_hide_email' value='1'".($userdata['user_hide_email'] == "1" ? " checked" : "").">".$locale['u007']."
<input type='radio' name='user_hide_email' value='0'".($userdata['user_hide_email'] == "0" ? " checked" : "").">".$locale['u008']."</td>
</tr>
<tr>
<td class='tbl'>".$locale['u009']."</td>
<td class='tbl'><input type='text' name='user_location' value='".$userdata['user_location']."' maxlength='50' class='textbox' style='width:200px;'></td>
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
<td class='tbl'><input type='text' name='user_aim' value='".$userdata['user_aim']."' maxlength='16' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u011']."</td>
<td class='tbl'><input type='text' name='user_icq' value='".$userdata['user_icq']."' maxlength='15' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u012']."</td>
<td class='tbl'><input type='text' name='user_msn' value='".$userdata['user_msn']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u013']."</td>
<td class='tbl'><input type='text' name='user_yahoo' value='".$userdata['user_yahoo']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u014']."</td>
<td class='tbl'><input type='text' name='user_web' value='".$userdata['user_web']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['u015']."</td>
<td class='tbl'><select name='user_theme' class='textbox' style='width:100px;'>
".makefileopts($theme_files, $userdata['user_theme'])."
</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['u016']."</td>
<td class='tbl'><select name='user_offset' class='textbox' style='width:100px;'>
$offset_list</select></td>
</tr>\n";
	if (!$userdata['user_avatar']) {
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
<td class='tbl'>
<textarea name='user_sig' rows='5' cols='53' class='textbox'>".$userdata['user_sig']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('user_sig', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('user_sig', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('user_sig', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('user_sig', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('user_sig', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('user_sig', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('user_sig', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('user_sig', '[small]', '[/small]');\">
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>\n";
	if ($userdata['user_avatar']) {
		echo $locale['u017']."<br>\n<img src='".IMAGES."avatars/".$userdata['user_avatar']."' alt='".$locale['u017']."'><br>
<input type='checkbox' name='del_avatar' value='y'> ".$locale['u019']."
<input type='hidden' name='user_avatar' value='".$userdata['user_avatar']."'><br><br>\n";
	}
	echo "<input type='hidden' name='user_hash' value='".$userdata['user_password']."'>
<input type='submit' name='update_profile' value='".$locale['460']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
} else {
	echo "<center><br>\n".$locale['003']."<br>\n<br></center>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>