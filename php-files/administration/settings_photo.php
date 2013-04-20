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

if (!checkrights("S5") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		thumb_w='".(isNum($_POST['thumb_w']) ? $_POST['thumb_w'] : "100")."',
		thumb_h='".(isNum($_POST['thumb_h']) ? $_POST['thumb_h'] : "100")."',
		photo_w='".(isNum($_POST['photo_w']) ? $_POST['photo_w'] : "400")."',
		photo_h='".(isNum($_POST['photo_h']) ? $_POST['photo_h'] : "300")."',
		photo_max_w='".(isNum($_POST['photo_max_w']) ? $_POST['photo_max_w'] : "1800")."',
		photo_max_h='".(isNum($_POST['photo_max_h']) ? $_POST['photo_max_h'] : "1600")."',
		photo_max_b='".(isNum($_POST['photo_max_b']) ? $_POST['photo_max_b'] : "150000")."',
		thumb_compression='".$_POST['thumb_compression']."',
		thumbs_per_row='".(isNum($_POST['thumbs_per_row']) ? $_POST['thumbs_per_row'] : "4")."',
		thumbs_per_page='".(isNum($_POST['thumbs_per_page']) ? $_POST['thumbs_per_page'] : "12")."'
	");
	redirect(FUSION_SELF.$aidlink);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['601']."<br>
<span class='small2'>".$locale['604']."</span></td>
<td width='50%' class='tbl'><input type='text' name='thumb_w' value='".$settings2['thumb_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='thumb_h' value='".$settings2['thumb_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['602']."<br>
<span class='small2'>".$locale['604']."</span></td>
<td width='50%' class='tbl'><input type='text' name='photo_w' value='".$settings2['photo_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='photo_h' value='".$settings2['photo_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['603']."<br>
<span class='small2'>".$locale['604']."</span></td>
<td width='50%' class='tbl'><input type='text' name='photo_max_w' value='".$settings2['photo_max_w']."' maxlength='4' class='textbox' style='width:40px;'> x
<input type='text' name='photo_max_h' value='".$settings2['photo_max_h']."' maxlength='4' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['605']."</td>
<td width='50%' class='tbl'><input type='text' name='photo_max_b' value='".$settings2['photo_max_b']."' maxlength='10' class='textbox' style='width:100px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['606']."</td>
<td width='50%' class='tbl'><select name='thumb_compression' class='textbox'>
<option value='gd1'".($settings2['thumb_compression'] == "gd1" ? " selected" : "").">".$locale['607']."</option>
<option value='gd2'".($settings2['thumb_compression'] == "gd2" ? " selected" : "").">".$locale['608']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['609']."</td>
<td width='50%' class='tbl'><input type='text' name='thumbs_per_row' value='".$settings2['thumbs_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['610']."</td>
<td width='50%' class='tbl'><input type='text' name='thumbs_per_page' value='".$settings2['thumbs_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
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