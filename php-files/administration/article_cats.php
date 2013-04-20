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
include LOCALE.LOCALESET."admin/news-articles.php";

if (!checkRights("AC")) fallback("../index.php");
if (isset($cat_id) && !isNum($cat_id)) fallback("index.php");

if (isset($status)) {
	if ($status == "deln") {
		$title = $locale['450'];
		$message = "<b>".$locale['451']."</b><br>\n".$locale['452'];
	} elseif ($status == "dely") {
		$title = $locale['450'];
		$message = "<b>".$locale['454']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($action) && $action == "delete") {
	$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_cat='$cat_id'");
	if (dbrows($result) != 0) {
		redirect(FUSION_SELF."?status=deln");
	} else {
		$result = dbquery("DELETE FROM ".$db_prefix."article_cats WHERE article_cat_id='$cat_id'");
		redirect(FUSION_SELF."?status=dely");
	}
} else {
	if (isset($_POST['save_cat'])) {
		$cat_name = stripinput($_POST['cat_name']);
		$cat_description = stripinput($_POST['cat_description']);
		$cat_access = isNum($_POST['cat_access']) ? $_POST['cat_access'] : "0";
		$cat_sorting = stripinput($_POST['cat_sort_by'])." ".stripinput($_POST['cat_sort_order']);
		if ($action == "edit") {
			$result = dbquery("UPDATE ".$db_prefix."article_cats SET article_cat_name='$cat_name', article_cat_description='$cat_description', article_cat_sorting='$cat_sorting', article_cat_access='$cat_access' WHERE article_cat_id='$cat_id'");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."article_cats (article_cat_name, article_cat_description, article_cat_sorting, article_cat_access) VALUES ('$cat_name', '$cat_description', '$cat_sorting', '$cat_access')");
		}
		redirect("article_cats.php");
	}
	if (isset($action) && $action == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."article_cats WHERE article_cat_id='$cat_id'");
		$data = dbarray($result);
		$cat_name = $data['article_cat_name'];
		$cat_description = $data['article_cat_description'];
		$cat_sorting = explode(" ", $data['article_cat_sorting']);
		$cat_access = $data['article_cat_access'];
		$formaction = FUSION_SELF."?action=edit&amp;cat_id=".$data['article_cat_id'];
		opentable($locale['455']);
	} else {
		$cat_name = "";
		$cat_description = "";
		$cat_sorting[0] = "article_subject";
		$cat_sorting[1] = "ASC";
		$cat_access = "";
		$formaction = FUSION_SELF;
		opentable($locale['456']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($cat_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='addcat' method='post' action='$formaction'>
<table align='center' cellpadding='0' cellspacing='0' width='400'>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['457']."</td>
<td class='tbl'><input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['458']."</td>
<td class='tbl'><input type='text' name='cat_description' value='$cat_description' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['467']."</td>
<td class='tbl'><select name='cat_sort_by' class='textbox'>
<option value='article_id'".($cat_sorting[0] == "article_id" ? " selected" : "").">".$locale['468']."</option>
<option value='article_subject'".($cat_sorting[0] == "article_subject" ? " selected" : "").">".$locale['469']."</option>
<option value='article_datestamp'".($cat_sorting[0] == "article_datestamp" ? " selected" : "").">".$locale['470']."</option>
</select> - 
<select name='cat_sort_order' class='textbox'>
<option value='ASC'".($cat_sorting[1] == "ASC" ? " selected" : "").">".$locale['471']."</option>
<option value='DESC'".($cat_sorting[1] == "DESC" ? " selected" : "").">".$locale['472']."</option>
</select></td>
</tr>
<tr>
<td width='1%' class='tbl' style='white-space:nowrap'>".$locale['465']."</td>
<td class='tbl'><select name='cat_access' class='textbox'>
$access_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_cat' value='".$locale['459']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['460']);
	echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."article_cats ORDER BY article_cat_name");
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<tr>
<td class='tbl2'>".$locale['461']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['466']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['462']."</td>
</tr>\n";
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>
<td class='$cell_color'><b>".$data['article_cat_name']."</b><br>
<span class='small'>".trimlink($data['article_cat_description'], 45)."</span></td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getgroupname($data['article_cat_access'])."</td>
<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'><a href='".FUSION_SELF."?action=edit&amp;cat_id=".$data['article_cat_id']."'>".$locale['509']."</a> -
<a href='".FUSION_SELF."?action=delete&amp;cat_id=".$data['article_cat_id']."'>".$locale['510']."</a></td>
</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<tr><td align='center' class='tbl1'>".$locale['518']."</td></tr>\n</table>\n";
	}
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>