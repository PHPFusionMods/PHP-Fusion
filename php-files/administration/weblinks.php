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
include LOCALE.LOCALESET."admin/weblinks.php";

if (!checkrights("W") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($weblink_id) && !isNum($weblink_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($step)) $step = "";

$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats");
if (dbrows($result) != 0) {
	if ($step == "delete") {
		$result = dbquery("DELETE FROM ".$db_prefix."weblinks WHERE weblink_id='$weblink_id'");
		redirect(FUSION_SELF.$aidlink."&weblink_cat_id=$weblink_cat_id");
	}
	if (isset($_POST['save_link'])) {
		$weblink_name = stripinput($_POST['weblink_name']);
		$weblink_description = addslash($_POST['weblink_description']);
		$weblink_url = stripinput($_POST['weblink_url']);
		if ($step == "edit") {
			$weblink_datestamp = isset($_POST['update_datestamp']) ? ", weblink_datestamp='".time()."'" : "";
			$result = dbquery("UPDATE ".$db_prefix."weblinks SET weblink_name='$weblink_name', weblink_description='$weblink_description', weblink_url='$weblink_url', weblink_cat='$weblink_cat'".$weblink_datestamp." WHERE weblink_id='$weblink_id'");
			redirect(FUSION_SELF.$aidlink."&weblink_cat_id=$weblink_cat");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."weblinks (weblink_name, weblink_description, weblink_url, weblink_cat, weblink_datestamp, weblink_count) VALUES ('$weblink_name', '$weblink_description', '$weblink_url', '$weblink_cat', '".time()."', '0')");
			redirect(FUSION_SELF.$aidlink."&weblink_cat_id=$weblink_cat");
		}
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."weblinks WHERE weblink_id='$weblink_id'");
		$data = dbarray($result);
		$weblink_name = $data['weblink_name'];
		$weblink_description = stripslashes($data['weblink_description']);
		$weblink_url = $data['weblink_url'];
		$formaction = FUSION_SELF.$aidlink."&amp;step=edit&amp;weblink_cat_id=$weblink_cat_id&amp;weblink_id=$weblink_id";
		opentable($locale['470']);
	} else {
		$weblink_name = "";
		$weblink_description = "";
		$weblink_url = "http://";
		$formaction = FUSION_SELF.$aidlink;
		opentable($locale['471']);
	}
	$editlist = ""; $sel = "";
	$result2 = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
	if (dbrows($result2) != 0) {
		while ($data2 = dbarray($result2)) {
			if ($step == "edit") $sel = ($data['weblink_cat'] == $data2['weblink_cat_id'] ? " selected" : "");
			$editlist .= "<option value='".$data2['weblink_cat_id']."'$sel>".$data2['weblink_cat_name']."</option>\n";
		}
	}
	echo "<form name='inputform' method='post' action='$formaction'>
<table align='center' width='460' cellspacing='0' cellpadding='0'>
<tr>
<td width='80' class='tbl'>".$locale['480']."</td>
<td class='tbl'><input type='text' name='weblink_name' value='$weblink_name' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td valign='top' width='80' class='tbl'>".$locale['481']."</td>
<td class='tbl'><textarea name='weblink_description' rows='5' cols='70' class='textbox'>".$weblink_description."</textarea></td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('weblink_description', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('weblink_description', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('weblink_description', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px' onClick=\"addText('weblink_description', '<a href=\'', '\' target=\'_blank\'>Link</a>');\">
<input type='button' value='img' class='button' style='width:35px' onClick=\"addText('weblink_description', '<img src=\'', '\' style=\'margin:5px\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px' onClick=\"addText('weblink_description', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px' onClick=\"addText('weblink_description', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px' onClick=\"addText('weblink_description', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px' onClick=\"addText('weblink_description', '<span class=\'alt\'>', '</span>');\"><br>
</td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['482']."</td>
<td class='tbl'><input type='text' name='weblink_url' value='$weblink_url' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['483']."</td>
<td class='tbl'><select name='weblink_cat' class='textbox' style='width:150px;'>
$editlist</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>";
	if ($step == "edit") echo "<input type='checkbox' name='update_datestamp' value='1'> ".$locale['485']."<br><br>\n";
	echo "<input type='submit' name='save_link' value='".$locale['484']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['500']);
	echo "<table align='center' cellpadding='0' cellspacing='0' width='400'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
	if (dbrows($result) != 0) {
		echo "<tr>
<td class='tbl2'>".$locale['501']."</td>
<td align='right' class='tbl2'>".$locale['502']."</td>
</tr>
<tr>
<td colspan='2' height='1'></td>
</tr>\n";
		while ($data = dbarray($result)) {
			if (!isset($weblink_cat_id)) $weblink_cat_id = "";
			if ($data['weblink_cat_id'] == $weblink_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
			echo "<tr>
<td class='tbl2'>".$data['weblink_cat_name']."</td>
<td class='tbl2' align='right'><img onclick=\"javascript:flipBox('".$data['weblink_cat_id']."')\" src='".THEME."images/panel_".$p_img.".gif' name='b_".$data['weblink_cat_id']."' alt=''></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."weblinks WHERE weblink_cat='".$data['weblink_cat_id']."' ORDER BY weblink_name");
			if (dbrows($result2) != 0) {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['weblink_cat_id']."'$div>
<table cellpadding='0' cellspacing='0' width='100%'>\n";
				while ($data2 = dbarray($result2)) {
					echo "<tr>
<td class='tbl'><a href='$data2[weblink_url]' target='_blank'>".$data2['weblink_name']."</a></td>
<td width='75' class='tbl'><a href='".FUSION_SELF.$aidlink."&amp;step=edit&amp;weblink_cat_id=".$data['weblink_cat_id']."&amp;weblink_id=".$data2['weblink_id']."'>".$locale['503']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;step=delete&amp;weblink_cat_id=".$data['weblink_cat_id']."&amp;weblink_id=".$data2['weblink_id']."' onClick='return DeleteItem()'>".$locale['504']."</a></td>
</tr>\n";
				}
				echo "</table>
</div>
</td>
</tr>\n";
			} else {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['weblink_cat_id']."' style='display:none'>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['505']."</td>
</tr>
</table>
</div>
</td>
</tr>\n";
			}
		}
		echo "</table>\n";
		echo "<script type='text/javascript'>
function DeleteItem() {
	return confirm('".$locale['460']."');
}
</script>\n";
	} else {
		echo "<tr>
<td align='center' class='tbl'><br>
".$locale['506']."<br><br>
<a href='weblink_cats.php".$aidlink."'>".$locale['507']."<br><br></td>
</tr>
</table>\n";
	}
	closetable();
} else {
	opentable($locale['500']);
	echo "<center>".$locale['508']."<br>\n".$locale['509']."<br>\n<br>
<a href='weblink_cats.php".$aidlink."'>".$locale['510']."</a>".$locale['511']."</center>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>