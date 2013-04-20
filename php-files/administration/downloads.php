<?php
/*---------------------------------------------------+
|	PHP-Fusion 6 Content Management System
+---------------------------------------------------------+
|	Copyright (c) 2005 Nick Jones
|	http://www.php-fusion.co.uk/
+---------------------------------------------------------+
|	Released under the terms & conditions of v2 of the
|	GNU General Public License. For details refer to
|	the included gpl.txt file or visit http://gnu.org
+---------------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/downloads.php";

if (!checkrights("D")) fallback("../index.php");
if (isset($download_id) && !isNum($download_id)) fallback(FUSION_SELF);
if (!isset($step)) $step = "";

$result = dbquery("SELECT * FROM ".$db_prefix."download_cats");
if (dbrows($result) != 0) {
	if ($step == "delete") {
		$result = dbquery("DELETE FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		redirect("downloads.php?download_cat_id=$download_cat_id");
	}
	if (isset($_POST['save_download'])) {
		$download_title = stripinput($_POST['download_title']);
		$download_description = addslash($_POST['download_description']);
		$download_url = stripinput($_POST['download_url']);
		$download_license = stripinput($_POST['download_license']);
		$download_os = stripinput($_POST['download_os']);
		$download_version = stripinput($_POST['download_version']);
		$download_filesize = stripinput($_POST['download_filesize']);
		if ($step == "edit") {
			$download_datestamp = isset($_POST['update_datestamp']) ? ", download_datestamp='".time()."'" : "";
			$result = dbquery("UPDATE ".$db_prefix."downloads SET download_title='$download_title', download_description='$download_description', download_url='$download_url', download_cat='$download_cat', download_license='$download_license', download_os='$download_os', download_version='$download_version', download_filesize='$download_filesize'".$download_datestamp." WHERE download_id='$download_id'");
			redirect("downloads.php?download_cat_id=$download_cat");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."downloads (download_title, download_description, download_url, download_cat, download_license, download_os, download_version, download_filesize, download_datestamp, download_count) VALUES ('$download_title', '$download_description', '$download_url', '$download_cat', '$download_license', '$download_os', '$download_version', '$download_filesize', '".time()."', '0')");
			redirect("downloads.php?download_cat_id=$download_cat");
		}
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_id='$download_id'");
		$data = dbarray($result);
		$download_title = $data['download_title'];
		$download_description = stripslashes($data['download_description']);
		$download_url = $data['download_url'];
		$download_license = $data['download_license'];
		$download_os = $data['download_os'];
		$download_version = $data['download_version'];
		$download_filesize = $data['download_filesize'];
		$formaction = FUSION_SELF."?step=edit&amp;download_cat_id=$download_cat_id&amp;download_id=$download_id";
		opentable($locale['470']);
	} else {
		$download_title = "";
		$download_description = "";
		$download_url = "";
		$download_license = "";
		$download_os = "";
		$download_version = "";
		$download_filesize = "";
		$formaction = FUSION_SELF;
		opentable($locale['471']);
	}
	$editlist = ""; $sel = "";
	$result2 = dbquery("SELECT * FROM ".$db_prefix."download_cats ORDER BY download_cat_name");
	if (dbrows($result2) != 0) {
		while ($data2 = dbarray($result2)) {
			if ($step == "edit") $sel = ($data['download_cat'] == $data2['download_cat_id'] ? " selected" : "");
			$editlist .= "<option value='".$data2['download_cat_id']."'$sel>".$data2['download_cat_name']."</option>\n";
		}
	}
	echo "<form name='inputform' method='post' action='$formaction'>
<table align='center' cellpadding='0' cellspacing='0' width='460'>
<tr>
<td width='80' class='tbl'>".$locale['480']."</td>
<td class='tbl'><input type='text' name='download_title' value='$download_title' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td valign='top' width='80' class='tbl'>".$locale['481']."</td>
<td class='tbl'><textarea name='download_description' rows='5' class='textbox' style='width:380px;'>".$download_description."</textarea></td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('download_description', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('download_description', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('download_description', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px' onClick=\"addText('download_description', '<a href=\'', '\' target=\'_blank\'>Link</a>');\">
<input type='button' value='img' class='button' style='width:35px' onClick=\"addText('download_description', '<img src=\'', '\' style=\'margin:5px\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px' onClick=\"addText('download_description', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px' onClick=\"addText('download_description', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px' onClick=\"addText('download_description', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px' onClick=\"addText('download_description', '<span class=\'alt\'>', '</span>');\"><br>
</td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['482']."</td>
<td class='tbl'><input type='text' name='download_url' value='$download_url' class='textbox' style='width:380px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['483']."</td>
<td class='tbl'><select name='download_cat' class='textbox'>
$editlist</select></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['484']."</td>
<td class='tbl'><input type='text' name='download_license' value='$download_license' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['485']."</td>
<td class='tbl'><input type='text' name='download_os' value='$download_os' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['486']."</td>
<td class='tbl'><input type='text' name='download_version' value='$download_version' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td width='80' class='tbl'>".$locale['487']."</td>
<td class='tbl'><input type='text' name='download_filesize' value='$download_filesize' class='textbox' style='width:150px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>";
	if ($step == "edit") echo "<input type='checkbox' name='update_datestamp' value='1'> ".$locale['489']."<br><br>\n";
	echo "<input type='submit' name='save_download' value='".$locale['488']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['500']);
	echo "<table align='center' cellpadding='0' cellspacing='0' width='400'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats ORDER BY download_cat_name");
	if (dbrows($result) != 0) {
		echo "<tr>
<td class='tbl2'>".$locale['501']."</td>
<td align='right' class='tbl2'>".$locale['502']."</td>
</tr>
<tr>
<td colspan='2' height='1'></td>
</tr>\n";
		while ($data = dbarray($result)) {
			if (!isset($download_cat_id)) $download_cat_id = "";
			if ($data['download_cat_id'] == $download_cat_id) { $p_img = "off"; $div = ""; } else { $p_img = "on"; $div = "style='display:none'"; }
			echo "<tr>
<td class='tbl2'>".$data['download_cat_name']."</td>
<td class='tbl2' align='right'><img onclick=\"javascript:flipBox('".$data['download_cat_id']."')\" src='".THEME."images/panel_".$p_img.".gif' name='b_".$data['download_cat_id']."'></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='".$data['download_cat_id']."' ORDER BY download_title");
			if (dbrows($result2) != 0) {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['download_cat_id']."'".$div.">
<table cellpadding='0' cellspacing='0' width='100%'>\n";
				while ($data2 = dbarray($result2)) {
					if (!strstr($data2['download_url'],"http://") && !strstr($data2['download_url'],"../")) {
						$download_url = BASEDIR.$data2['download_url'];
					} else {
						$download_url = $data2['download_url'];
					}
					echo "<tr>\n<td class='tbl'><a href='$download_url' target='_blank'>".$data2['download_title']."</a></td>
<td align='right' width='100' class='tbl'><a href='".FUSION_SELF."?step=edit&amp;download_cat_id=".$data['download_cat_id']."&amp;download_id=".$data2['download_id']."'>".$locale['503']."</a> -
<a href='".FUSION_SELF."?step=delete&amp;download_cat_id=".$data['download_cat_id']."&amp;download_id=".$data2['download_id']."' onClick='return DeleteItem()'>".$locale['504']."</a></td>
</tr>\n";
				}
				echo "</table>
</div>
</td>
</tr>\n";
			} else {
				echo "<tr>
<td colspan='2'>
<div id='box_".$data['download_cat_id']."' style='display:none'>
<table cellpadding='0' cellspacing='0' width='100%'>
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
function DeleteItem()
{
return confirm('".$locale['460']."');
}
</script>\n";
	} else {
		echo "<tr>
<td align='center'><br>
".$locale['506']."<br><br>
<a href='download_cats.php'>".$locale['507']."<br><br></td>
</tr>
</table>\n";
	}
	closetable();
} else {
	opentable($locale['500']);
	echo "<center>".$locale['508']."<br>
".$locale['509']."<br><br>
<a href='download_cats.php'>".$locale['510']."</a>".$locale['511']."</center>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>