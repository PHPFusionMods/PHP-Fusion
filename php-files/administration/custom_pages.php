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
include LOCALE.LOCALESET."admin/custom_pages.php";

if (!checkrights("CP")) fallback("../index.php");
if (isset($page_id) && !isNum($page_id)) fallback("index.php");

if (isset($status)) {
	if ($status == "su") {
		$title = $locale['400'];
		$message = "<b>".$locale['401']."</b><br>\n".$locale['402']."\n<a href='".BASEDIR."viewpage.php?page_id=$pid'>viewpage.php?page_id=$pid</a>\n";
	} elseif ($status == "sn") {
		$title = $locale['405'];
		$message = "<b>".$locale['406']."</b><br>\n".$locale['402']."\n<a href='".BASEDIR."viewpage.php?page_id=$pid'>viewpage.php?page_id=$pid</a>\n";
	} elseif ($status == "del") {
		$title = $locale['407'];
		$message = "<b>".$locale['408']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($_POST['save'])) {
	$page_title = stripinput($_POST['page_title']);
	$page_access = isNum($_POST['page_access']) ? $_POST['page_access'] : "0";
	$page_content = addslash($_POST['page_content']);
	$comments = isset($_POST['page_comments']) ? "1" : "0";
	$ratings = isset($_POST['page_ratings']) ? "1" : "0";
	if (isset($page_id)) {
		$result = dbquery("UPDATE ".$db_prefix."custom_pages SET page_title='$page_title', page_access='$page_access', page_content='$page_content', page_allow_comments='$comments', page_allow_ratings='$ratings' WHERE page_id='$page_id'");
		redirect(FUSION_SELF."?status=su&pid=$page_id");
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."custom_pages (page_title, page_access, page_content, page_allow_comments, page_allow_ratings) VALUES ('$page_title', '$page_access', '$page_content', '$comments', '$ratings')");
		$page_id = mysql_insert_id();
		if (isset($_POST['add_link'])) {
			$result = dbquery("SELECT * FROM ".$db_prefix."site_links ORDER BY link_order DESC LIMIT 1");
			$data = dbarray($result);
			$link_order = $data['link_order'] + 1;
			$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('$page_title', 'viewpage.php?page_id=$page_id', '$page_access', '1', '0', '$link_order')");
		}
		redirect(FUSION_SELF."?status=sn&pid=$page_id");
	}
} else if (isset($_POST['delete'])) {
	$result = dbquery("DELETE FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
	$result = dbquery("DELETE FROM ".$db_prefix."site_links WHERE link_url='viewpage.php?page_id=$page_id'");
	redirect(FUSION_SELF."?status=del");
} else {
	if (isset($_POST['preview'])) {
		$addlink = isset($_POST['add_link']) ? " checked" : "";
		$page_title = stripinput($_POST['page_title']);
		$page_access = $_POST['page_access'];
		$page_content = $_POST['page_content'];
		$page_content = stripslash($page_content);
		$comments = isset($_POST['page_comments']) ? " checked" : "";
		$ratings = ($_POST['page_ratings']) ? " checked" : "";
		opentable($page_title);
		eval("?>".$page_content."<?php ");
		closetable();
		tablebreak();
		//$page_content = stripinput((QUOTES_GPC ? addslashes($page_content) : $page_content));
		$page_content = phpentities($page_content);
	}
	$editlist = ""; $sel = "";
	$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages ORDER BY page_title DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if (isset($page_id)) $sel = ($page_id == $data['page_id'] ? " selected" : "");
			$editlist .= "<option value='".$data['page_id']."'$sel>".$data['page_title']."</option>\n";
		}
	}
	opentable($locale['420']);
	echo "<form name='selectform' method='post' action='".FUSION_SELF."'>
<center>
<select name='page_id' class='textbox' style='width:200px;'>
$editlist</select>
<input type='submit' name='edit' value='".$locale['421']."' class='button'>
<input type='submit' name='delete' value='".$locale['422']."' onclick='return DeletePage();' class='button'>
</center>
</form>\n";
	closetable();
	tablebreak();
	if (isset($_POST['edit'])) {
		$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$page_title = $data['page_title'];
			$page_access = $data['page_access'];
			//$page_content = stripinput((QUOTES_GPC ? $data['page_content'] : stripslashes($data['page_content'])));
			$page_content = phpentities(stripslashes($data['page_content']));
			$comments = ($data['page_allow_comments'] == "1" ? " checked" : "");
			$ratings = ($data['page_allow_ratings'] == "1" ? " checked" : "");
			$addlink = "";
		}
	}
	if (isset($page_id)) {
		$action = FUSION_SELF."?page_id=$page_id";
		opentable($locale['400']);
	} else {
		if (!isset($_POST['preview'])) {
			$page_title = "";
			$page_access = "";
			$page_content = "";
			$comments = " checked";
			$ratings = " checked";
			$addlink = "";
		}
		$action = FUSION_SELF;
		opentable($locale['405']);
	}
	$user_groups = getusergroups(); $access_opts = ""; $sel = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($page_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='inputform' method='post' action='$action' onSubmit='return ValidateForm(this);'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='100' class='tbl'>".$locale['430']."</td>
<td width='80%' class='tbl'><input type='textbox' name='page_title' value='$page_title' class='textbox' style='width: 250px;'>
&nbsp;".$locale['431']."<select name='page_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td valign='top' width='100' class='tbl'>".$locale['432']."</td>
<td width='80%' class='tbl'><textarea name='page_content' cols='95' rows='15' class='textbox'>$page_content</textarea></td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'>
<input type='button' value='<?php?>' class='button' style='width:60px;' onClick=\"addText('page_content', '<?php\\n', '\\n?>');\">
<input type='button' value='<p>' class='button' style='width:35px;' onClick=\"insertText('page_content', '<p>');\">
<input type='button' value='<br>' class='button' style='width:40px;' onClick=\"insertText('page_content', '<br>');\">
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('page_content', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('page_content', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('page_content', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('page_content', '<a href=\'', '\' target=\'_blank\'>Link</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"insertText('page_content', '<img src=\'IMAGES/\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('page_content', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('page_content', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('page_content', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('page_content', '<span class=\'alt\'>', '</span>');\">
</td>
</tr>
<tr>
<td class='tbl'></td><td class='tbl'><br>\n";
	if (!isset($page_id)) echo "<input type='checkbox' name='add_link' value='1'$addlink>  ".$locale['433']."<br>\n";
	echo "<input type='checkbox' name='page_comments' value='1'$comments> ".$locale['434']."<br>
<input type='checkbox' name='page_ratings' value='1'$ratings> ".$locale['435']."
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='preview' value='".$locale['436']."' class='button'>
<input type='submit' name='save' value='".$locale['437']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	echo "<script type='text/javascript'>
function DeletePage() {
	return confirm('".$locale['409']."');
}
function ValidateForm(frm) {
	if(frm.page_title.value=='') {
		alert('".$locale['410']."');
		return false;
	}
}
</script>\n";
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>