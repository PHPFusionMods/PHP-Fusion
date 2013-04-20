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

include LOCALE.LOCALESET."admin/weblinks.php";

if (!checkrights("WC")) fallback("../index.php");
if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF);
if (!isset($step)) $step = "";

if (isset($status)) {
	if ($status == "deln") {
		$title = $locale['400'];
		$message = "<b>".$locale['401']."</b><br>\n".$locale['402'];
	} elseif ($status == "dely") {
		$title = $locale['400'];
		$message = "<b>".$locale['405']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if ($step == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."weblinks WHERE weblink_cat='$cat_id'");
	if (dbrows($result) != 0) {
		redirect(FUSION_SELF."?status=deln");
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
		redirect(FUSION_SELF."?status=dely");
	}
} else {
	if (isset($_POST['save_cat'])) {
		$cat_name = stripinput($_POST['cat_name']);
		$cat_description = stripinput($_POST['cat_description']);
		$cat_sorting = stripinput($_POST['cat_sort_by'])." ".stripinput($_POST['cat_sort_order']);
		$cat_access = isNum($_POST['cat_access']) ? $_POST['cat_access'] : "0";
		if ($step == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."weblink_cats SET weblink_cat_name='$cat_name', weblink_cat_description='$cat_description', weblink_cat_sorting='$cat_sorting', weblink_cat_access='$cat_access' WHERE weblink_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."weblink_cats (weblink_cat_name, weblink_cat_description, weblink_cat_sorting, weblink_cat_access) VALUES ('$cat_name', '$cat_description', '$cat_sorting', '$cat_access')");
		}
		redirect(FUSION_SELF);
	}
	if ($step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
		$data = dbarray($result);
		$cat_name = $data['weblink_cat_name'];
		$cat_description = $data['weblink_cat_description'];
		$cat_sorting = explode(" ", $data['weblink_cat_sorting']);
		$cat_access = $data['weblink_cat_access'];
		$formaction = FUSION_SELF."?step=edit&amp;cat_id=".$data['weblink_cat_id'];
		opentable($locale['420']);
	} else {
		$cat_name = "";
		$cat_description = "";
		$cat_sorting[0] = "article_subject";
		$cat_sorting[1] = "ASC";
		$cat_access = "";
		$formaction = FUSION_SELF;
		opentable($locale['421']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($cat_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='addcat' method='post' action='$formaction'>
<table align='center' cellpadding='0' cellspacing='0' width='400'>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['430']."</td>
<td class='tbl'><input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['431']."</td>
<td class='tbl'><input type='text' name='cat_description' value='$cat_description' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['434']."</td>
<td class='tbl'><select name='cat_sort_by' class='textbox'>
<option value='weblink_id'".($cat_sorting[0] == "weblink_id" ? " selected" : "").">".$locale['435']."</option>
<option value='weblink_name'".($cat_sorting[0] == "weblink_name" ? " selected" : "").">".$locale['436']."</option>
<option value='weblink_datestamp'".($cat_sorting[0] == "weblink_datestamp" ? " selected" : "").">".$locale['437']."</option>
</select> - 
<select name='cat_sort_order' class='textbox'>
<option value='ASC'".($cat_sorting[1] == "ASC" ? " selected" : "").">".$locale['438']."</option>
<option value='DESC'".($cat_sorting[1] == "DESC" ? " selected" : "").">".$locale['439']."</option>
</select></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['433']."</td>
<td class='tbl'><select name='cat_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_cat' value='".$locale['432']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['440']);
	echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<tr>
<td class='tbl2'>".$locale['441']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['445']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['502']."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>
<td class='$cell_color'><b>".$data['weblink_cat_name']."</b>"
.($data['weblink_cat_description'] ? "<br>\n<span class='small'>".trimlink($data['weblink_cat_description'], 45)."</span>" : "")."</td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getgroupname($data['weblink_cat_access'])."</td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'><a href='".FUSION_SELF."?step=edit&amp;cat_id=".$data['weblink_cat_id']."'>".$locale['503']."</a> -
<a href='".FUSION_SELF."?step=delete&amp;cat_id=".$data['weblink_cat_id']."'>".$locale['504']."</a></td>
</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<tr><td align='center' class='tbl1'>".$locale['508']."</td></tr>\n</table>\n";
	}
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>