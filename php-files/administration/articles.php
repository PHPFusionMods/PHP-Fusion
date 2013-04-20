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
require_once "subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/news-articles.php";

if (!checkrights("A") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($article_id) && !isNum($article_id)) fallback("index.php");

if ($settings['tinymce_enabled'] == 1) echo "<script type='text/javascript'>advanced();</script>\n";

if (isset($status)) {
	if ($status == "su") {
		$title = $locale['500'];
		$message = "<b>".$locale['501']."</b>";
	} elseif ($status == "sn") {
		$title = $locale['504'];
		$message = "<b>".$locale['505']."</b>";
	} elseif ($status == "del") {
		$title = $locale['506'];
		$message = "<b>".$locale['507']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

$result = dbquery("SELECT * FROM ".$db_prefix."article_cats");
if (dbrows($result) != 0) {
	if (isset($_POST['save'])) {
		$subject = stripinput($_POST['subject']);
		$body = addslash($_POST['body']);
		$body2 = addslash($_POST['body2']);
		if ($settings['tinymce_enabled'] != 1) { $breaks = isset($_POST['line_breaks']) ? "y" : "n"; } else { $breaks = "n"; }
		$comments = isset($_POST['article_comments']) ? "1" : "0";
		$ratings = isset($_POST['article_ratings']) ? "1" : "0";
		if (isset($article_id)) {
			$result = dbquery("UPDATE ".$db_prefix."articles SET article_cat='".$_POST['article_cat']."', article_subject='$subject', article_snippet='$body', article_article='$body2', article_breaks='$breaks', article_allow_comments='$comments', article_allow_ratings='$ratings' WHERE article_id='$article_id'");
			redirect(FUSION_SELF.$aidlink."&status=su");
		} else {
			$result = dbquery("INSERT INTO ".$db_prefix."articles (article_cat, article_subject, article_snippet, article_article, article_breaks, article_name, article_datestamp, article_reads, article_allow_comments, article_allow_ratings) VALUES ('".$_POST['article_cat']."', '$subject', '$body', '$body2', '$breaks', '".$userdata['user_id']."', '".time()."', '0', '$comments', '$ratings')");
			redirect(FUSION_SELF.$aidlink."&status=sn");
		}
	} else if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."articles WHERE article_id='$article_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id='$article_id' and comment_type='A'");
		$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id='$article_id' and rating_type='A'");
		redirect(FUSION_SELF.$aidlink."&status=del");
	} else {
		if (isset($_POST['preview'])) {
			$article_cat = $_POST['article_cat'];
			$subject = stripinput($_POST['subject']);
			$body = phpentities(stripslash($_POST['body']));
			$body2 = phpentities(stripslash($_POST['body2']));
			$bodypreview = str_replace("src='".str_replace("../", "", IMAGES_A), "src='".IMAGES_A, stripslash($_POST['body']));
			$body2preview = str_replace("src='".str_replace("../", "", IMAGES_A), "src='".IMAGES_A, stripslash($_POST['body2']));
			if (isset($_POST['line_breaks'])) {
				$breaks = " checked";
				$bodypreview = nl2br($bodypreview);
				$body2preview = nl2br($body2preview);
			}
			$comments = isset($_POST['article_comments']) ? " checked" : "";
			$ratings = isset($_POST['article_ratings']) ? " checked" : "";
			opentable($subject);
			echo "$bodypreview\n";
			closetable();
			tablebreak();
			opentable($subject);
			echo "$body2preview\n";
			closetable();
			tablebreak();
		}
		opentable($locale['508']);
		$editlist = ""; $sel = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."articles ORDER BY article_datestamp DESC");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				if (isset($article_id)) $sel = ($article_id == $data['article_id'] ? " selected" : "");
				$editlist .= "<option value='".$data['article_id']."'$sel>".$data['article_subject']."</option>\n";
			}
		} else { $editlist .= "<option value='---' disabled>---</option>\n"; }
		echo "<form name='selectform' method='post' action='".FUSION_SELF.$aidlink."'>
<center>
<select name='article_id' class='textbox' style='width:250px;'>
$editlist</select>
<input type='submit' name='edit' value='".$locale['509']."' class='button'>
<input type='submit' name='delete' value='".$locale['510']."' onclick='return DeleteArticle();' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
		if (isset($_POST['edit'])) {
			$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_id='$article_id'");
			if (dbrows($result) != 0) {
				$data = dbarray($result);
				$article_cat = $data['article_cat'];
				$subject = $data['article_subject'];
				$body = phpentities(stripslashes($data['article_snippet']));
				$body2 = phpentities(stripslashes($data['article_article']));
				$breaks = ($data['article_breaks'] == "y" ? " checked" : "");
				$comments = ($data['article_allow_comments'] == "1" ? " checked" : "");
				$ratings = ($data['article_allow_ratings'] == "1" ? " checked" : "");
			}
		}
		if (isset($article_id)) {
			$action = FUSION_SELF.$aidlink."&amp;article_id=$article_id";
			opentable($locale['500']);
		} else {
			if (!isset($_POST['preview'])) {
				$subject = "";
				$body = "";
				$body2 = "";
				$breaks = " checked";
				$comments = " checked";
				$ratings = " checked";
			}
			$action = FUSION_SELF.$aidlink;
			opentable($locale['504']);
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."article_cats ORDER BY article_cat_name DESC");
		$catlist = ""; $sel = "";
		while ($data = dbarray($result)) {
			if (isset($article_cat)) $sel = ($article_cat == $data['article_cat_id'] ? " selected" : "");
			$catlist .= "<option value='".$data['article_cat_id']."'$sel>".$data['article_cat_name']."</option>\n";
		}
		$image_files = makefilelist(IMAGES_A, ".|..|index.php", true);
		$image_list = makefileopts($image_files);
		echo "<form name='inputform' method='post' action='$action' onSubmit='return ValidateForm(this)'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='100' class='tbl'>".$locale['511']."</td>
<td class='tbl'><select name='article_cat' class='textbox' style='width:250px;'>
$catlist</select></td>
</tr>
<tr>
<td width='100' class='tbl'>".$locale['512']."</td>
<td class='tbl'><input type='text' name='subject' value='$subject' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td valign='top' width='100' class='tbl'>".$locale['513']."</td>
<td class='tbl'><textarea name='body' cols='95' rows='5' class='textbox'>$body</textarea></td>
</tr>\n";
		if ($settings['tinymce_enabled'] != 1) {
			echo "<tr>\n<td class='tbl'></td>\n<td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('body', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('body', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('body', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('body', '<a href=\'', '\' target=\'_blank\'>Link</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"addText('body', '<img src=\'".str_replace("../","",IMAGES_A)."', '\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('body', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('body', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('body', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('body', '<span class=\'alt\'>', '</span>');\"><br>
<select name='setcolor' class='textbox' style='margin-top:5px;' onChange=\"addText('body', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;\">
<option value=''>".$locale['420']."</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
<select name='insertimage' class='textbox' style='margin-top:5px;' onChange=\"insertText('body', '<img src=\'".str_replace("../","",IMAGES_A)."' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;\">
<option value=''>".$locale['421']."</option>
$image_list</select>
</td>
</tr>\n";
		}
		echo "<tr>\n<td valign='top' width='100' class='tbl'>".$locale['514']."</td>
<td class='tbl'><textarea name='body2' cols='95' rows='10' class='textbox'>$body2</textarea></td>
</tr>\n";
		if ($settings['tinymce_enabled'] != 1) {
			echo "<tr>\n<td class='tbl'></td><td class='tbl'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('body2', '<b>', '</b>');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('body2', '<i>', '</i>');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('body2', '<u>', '</u>');\">
<input type='button' value='link' class='button' style='width:35px;' onClick=\"addText('body2', '<a href=\'', '\' target=\'_blank\'>Link</a>');\">
<input type='button' value='img' class='button' style='width:35px;' onClick=\"addText('body2', '<img src=\'".str_replace("../","",IMAGES_A)."', '\' style=\'margin:5px;\' align=\'left\'>');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('body2', '<center>', '</center>');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('body2', '<span class=\'small\'>', '</span>');\">
<input type='button' value='small2' class='button' style='width:45px;' onClick=\"addText('body2', '<span class=\'small2\'>', '</span>');\">
<input type='button' value='alt' class='button' style='width:25px;' onClick=\"addText('body2', '<span class=\'alt\'>', '</span>');\">
<input type='button' value='new page' class='button' style='width:60px;' onClick=\"insertText('body2', '<--PAGEBREAK-->');\"><br>
<select name='setcolor' class='textbox' style='margin-top:5px;' onChange=\"addText('body2', '<span style=\'color:' + this.options[this.selectedIndex].value + ';\'>', '</span>');this.selectedIndex=0;\">
<option value=''>".$locale['420']."</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
<select name='insertimage' class='textbox' style='margin-top:5px;' onChange=\"insertText('body2', '<img src=\'".str_replace("../","",IMAGES_A)."' + this.options[this.selectedIndex].value + '\' style=\'margin:5px;\' align=\'left\'>');this.selectedIndex=0;\">
<option value=''>".$locale['421']."</option>
$image_list</select>
</td>
</tr>\n";
		}
		echo "<tr>
<td class='tbl'></td><td class='tbl'>";
if ($settings['tinymce_enabled'] != 1) echo "<input type='checkbox' name='line_breaks' value='yes'$breaks> ".$locale['417']."<br>\n";
echo "<input type='checkbox' name='article_comments' value='yes'$comments> ".$locale['423']."<br>
<input type='checkbox' name='article_ratings' value='yes'$ratings> ".$locale['424']."</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='preview' value='".$locale['515']."' class='button'>
<input type='submit' name='save' value='".$locale['516']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		echo "<script type='text/javascript'>
function DeleteArticle() {
	return confirm('".$locale['552']."');
}
function ValidateForm(frm) {
	if(frm.subject.value=='') {
		alert('".$locale['550']."');
		return false;
	}
}
</script>\n";
	}
} else {
	opentable($locale['517']);
	echo "<center>".$locale['518']."<br>\n".$locale['519']."<br>
<a href='article_cats.php".$aidlink."'>".$locale['520']."</a>".$locale['521']."</center>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>