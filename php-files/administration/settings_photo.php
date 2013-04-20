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

if (!checkrights("S5")) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		album_image_w='".(isNum($_POST['album_image_w']) ? $_POST['album_image_w'] : "80")."',
		album_image_h='".(isNum($_POST['album_image_h']) ? $_POST['album_image_h'] : "60")."',
		thumb_image_w='".(isNum($_POST['thumb_image_w']) ? $_POST['thumb_image_w'] : "120")."',
		thumb_image_h='".(isNum($_POST['thumb_image_h']) ? $_POST['thumb_image_h'] : "100")."',
		thumb_compression='".$_POST['thumb_compression']."',
		album_comments='".(isNum($_POST['album_comments']) ? $_POST['album_comments'] : "1")."',
		albums_per_row='".(isNum($_POST['albums_per_row']) ? $_POST['albums_per_row'] : "4")."',
		albums_per_page='".(isNum($_POST['albums_per_page']) ? $_POST['albums_per_page'] : "16")."',
		thumbs_per_row='".(isNum($_POST['thumbs_per_row']) ? $_POST['thumbs_per_row'] : "4")."',
		thumbs_per_page='".(isNum($_POST['thumbs_per_page']) ? $_POST['thumbs_per_page'] : "12")."',
		album_max_w='".(isNum($_POST['album_max_w']) ? $_POST['album_max_w'] : "400")."',
		album_max_h='".(isNum($_POST['album_max_h']) ? $_POST['album_max_h'] : "300")."',
		album_max_b='".(isNum($_POST['album_max_b']) ? $_POST['album_max_b'] : "150000")."'
	");
	redirect(FUSION_SELF);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

opentable($locale['600']);

	echo "<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_registration.php' title='".$locale['550']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['600']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_misc.php' title='".$locale['650']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['601']."<br>
<span class='small2'>".$locale['613']."</span></td>
<td width='50%' class='tbl'><input type='text' name='album_image_w' value='".$settings2['album_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_image_h' value='".$settings2['album_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['602']."<br>
<span class='small2'>".$locale['613']."</span></td>
<td width='50%' class='tbl'><input type='text' name='thumb_image_w' value='".$settings2['thumb_image_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='thumb_image_h' value='".$settings2['thumb_image_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['603']."</td>
<td width='50%' class='tbl'><select name='thumb_compression' class='textbox'>
<option value='gd1'".($settings2['thumb_compression'] == "gd1" ? " selected" : "").">".$locale['604']."</option>
<option value='gd2'".($settings2['thumb_compression'] == "gd2" ? " selected" : "").">".$locale['605']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['606']."</td>
<td width='50%' class='tbl'><select name='album_comments' class='textbox'>
<option value='1'".($settings2['album_comments'] == "1" ? " selected" : "").">".$locale['508']."</option>
<option value='0'".($settings2['album_comments'] == "0" ? " selected" : "").">".$locale['509']."</option>
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['607']."</td>
<td width='50%' class='tbl'><input type='text' name='albums_per_row' value='".$settings2['albums_per_row']."' maxlength='2' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['608']."</td>
<td width='50%' class='tbl'><input type='text' name='albums_per_page' value='".$settings2['albums_per_page']."' maxlength='2' class='textbox' style='width:40px;'></td>
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
<td width='50%' class='tbl'>".$locale['611']."<br>
<span class='small2'>".$locale['613']."</span></td>
<td width='50%' class='tbl'><input type='text' name='album_max_w' value='".$settings2['album_max_w']."' maxlength='3' class='textbox' style='width:40px;'> x
<input type='text' name='album_max_h' value='".$settings2['album_max_h']."' maxlength='3' class='textbox' style='width:40px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['612']."</td>
<td width='50%' class='tbl'><input type='text' name='album_max_b' value='".$settings2['album_max_b']."' maxlength='10' class='textbox' style='width:100px;'></td>
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