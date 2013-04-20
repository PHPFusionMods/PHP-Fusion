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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S4")) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		enable_registration='".(isNum($_POST['enable_registration']) ? $_POST['enable_registration'] : "1")."',
		email_verification='".(isNum($_POST['email_verification']) ? $_POST['email_verification'] : "1")."',
		admin_activation='".(isNum($_POST['admin_activation']) ? $_POST['admin_activation'] : "0")."',
		display_validation='".(isNum($_POST['display_validation']) ? $_POST['display_validation'] : "1")."',
		validation_method='".$_POST['validation_method']."'
	");
	redirect(FUSION_SELF);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

opentable($locale['550']);

	echo "<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_forum.php' title='".$locale['500']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['550']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_photo.php' title='".$locale['600']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['551']."</td>
<td width='50%' class='tbl'><select name='enable_registration' class='textbox'>
<option value='1'".($settings2['enable_registration'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['enable_registration'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['552']."</td>
<td width='50%' class='tbl'><select name='email_verification' class='textbox'>
<option value='1'".($settings2['email_verification'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['email_verification'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['557']."</td>
<td width='50%' class='tbl'><select name='admin_activation' class='textbox'>
<option value='1'".($settings2['admin_activation'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['admin_activation'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['553']."</td>
<td width='50%' class='tbl'><select name='display_validation' class='textbox'>
<option value='1'".($settings2['display_validation'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['display_validation'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['554']."</td>
<td width='50%' class='tbl'><select name='validation_method' class='textbox'>
<option value='image'".($settings2['validation_method'] == "image" ? " selected" : "").">".$locale['555']."</option>
<option value='text'".($settings2['validation_method'] == "text" ? " selected" : "").">".$locale['556']."</option>
</select></td>
</tr>
<tr><td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='savesettings' value='".$locale['750']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>