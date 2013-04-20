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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S6") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		tinymce_enabled='".(isNum($_POST['tinymce_enabled']) ? $_POST['tinymce_enabled'] : "0")."',
		smtp_host='".stripinput($_POST['smtp_host'])."',
		smtp_username='".stripinput($_POST['smtp_username'])."',
		smtp_password='".stripinput($_POST['smtp_password'])."',
		bad_words_enabled='".(isNum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")."',
		bad_words='".addslash($_POST['bad_words'])."',
		bad_word_replace='".stripinput($_POST['bad_word_replace'])."',
		guestposts='".(isNum($_POST['guestposts']) ? $_POST['guestposts'] : "0")."',
		numofshouts='".(isNum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")."',
		flood_interval='".(isNum($_POST['flood_interval']) ? $_POST['flood_interval'] : "15")."',
		maintenance='".(isNum($_POST['maintenance']) ? $_POST['maintenance'] : "0")."',
		maintenance_message='".addslash(descript($_POST['maintenance_message']))."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['662']."<br>
<span class='small2'>".$locale['663']."</span></td>
<td width='50%' class='tbl'><select name='tinymce_enabled' class='textbox'>
<option value='1'".($settings2['tinymce_enabled'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['tinymce_enabled'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['664']."<br>
<span class='small2'>".$locale['665']."</span></td>
<td width='50%' class='tbl'><input type='text' name='smtp_host' value='".$settings2['smtp_host']."' maxlength='200' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['666']."</td>
<td width='50%' class='tbl'><input type='text' name='smtp_username' value='".$settings2['smtp_username']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['667']."</td>
<td width='50%' class='tbl'><input type='password' name='smtp_password' value='".$settings2['smtp_password']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['659']."</td>
<td width='50%' class='tbl'><select name='bad_words_enabled' class='textbox'>
<option value='1'".($settings2['bad_words_enabled'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['bad_words_enabled'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr><td valign='top' width='50%' class='tbl'>".$locale['651']."<br>
<span class='small2'>".$locale['652']."<br>
".$locale['653']."</span></td>
<td width='50%' class='tbl'><textarea name='bad_words' rows='5' cols='44' class='textbox'>".$settings2['bad_words']."</textarea></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['654']."</td>
<td width='50%' class='tbl'><input type='text' name='bad_word_replace' value='".$settings2['bad_word_replace']."' maxlength='128' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['655']."</td>
<td width='50%' class='tbl'><select name='guestposts' class='textbox'>
<option value='1'".($settings2['guestposts'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['guestposts'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['656']."</td>
<td width='50%' class='tbl'>
<select name='numofshouts' class='textbox' style='width:50px;'>
<option".($settings2['numofshouts'] == 5 ? " selected" : "").">5</option>
<option".($settings2['numofshouts'] == 10 ? " selected" : "").">10</option>
<option".($settings2['numofshouts'] == 15 ? " selected" : "").">15</option>
<option".($settings2['numofshouts'] == 20 ? " selected" : "").">20</option>
</select>
</td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['660']."</td>
<td width='50%' class='tbl'><input type='text' name='flood_interval' value='".$settings2['flood_interval']."' maxlength='2' class='textbox' style='width:50px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['657']."</td>
<td width='50%' class='tbl'><select name='maintenance' class='textbox' style='width:50px;'>
<option value='1'".($settings2['maintenance'] == "1" ? " selected" : "").">".$locale['502']."</option>
<option value='0'".($settings2['maintenance'] == "0" ? " selected" : "").">".$locale['503']."</option>
</select></td>
</tr>
<tr><td valign='top' width='50%' class='tbl'>".$locale['658']."</td>
<td width='50%' class='tbl'><textarea name='maintenance_message' rows='5' cols='44' class='textbox'>".stripslashes($settings2['maintenance_message'])."</textarea></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='savesettings' value='".$locale['750']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>