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
require_once ADMIN."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/submissions.php";

if (!checkrights("SU")) fallback("../index.php");
if (isset($submit_id) && !isNum($submit_id)) fallback(FUSION_SELF);
if (!isset($stage)) $stage = "";
$links = ""; $news = ""; $articles = "";

if ($stage == "" || $stage == "1") {
	if (isset($delete)) {
		opentable($locale['400']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$delete'");
		echo "<br><div align='center'>".$locale['401']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='l' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$links .= "<tr>\n<td class='tbl1'>".$submit_criteria['link_name']."</td>
<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF."?stage=2&amp;t=l&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$links = "<tr>\n<td colspan='2' class='tbl1'>".$locale['414']."</td>\n</tr>\n";
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='n' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$news .= "<tr>\n<td class='tbl1'>".$submit_criteria['news_subject']."</td>
<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF."?stage=2&amp;t=n&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$news = "<tr>\n<td colspan='2' class='tbl1'>".$locale['415']."</td>\n</tr>\n";
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."submissions WHERE submit_type='a' ORDER BY submit_datestamp DESC");
		if (dbrows($result) != "0") {
			while ($data = dbarray($result)) {
				$submit_criteria = unserialize($data['submit_criteria']);
				$articles .= "<tr>\n<td class='tbl1'>".$submit_criteria['article_subject']."</td>
<td align='right' width='1%' class='tbl1' style='white-space:nowrap'><span class='small'><a href='".FUSION_SELF."?stage=2&amp;t=a&amp;submit_id=".$data['submit_id']."'>".$locale['417']."</a></span> |
<span class='small'><a href='".FUSION_SELF."?delete=".$data['submit_id']."'>".$locale['418']."</a></span></td>\n</tr>\n";
			}
		} else {
			$articles = "<tr>\n<td colspan='2' class='tbl1'>".$locale['416']."</td>\n</tr>\n";
		}
		opentable($locale['410']);
		echo "<table align='center' width='400' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td colspan='2' class='tbl2'>".$locale['411']."</td>
</tr>
$links<tr>
<td colspan='2' class='tbl2'>".$locale['412']."</td>
</tr>
$news<tr>
<td colspan='2' class='tbl2'>".$locale['413']."</td>
</tr>
$articles</table>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "l") {
	if (isset($_POST['add'])) {
		$link_name = stripinput($_POST['link_name']);
		$link_url = stripinput($_POST['link_url']);
		$link_description = stripinput($_POST['link_description']);
		$result = dbquery("INSERT INTO ".$db_prefix."weblinks (weblink_name, weblink_description, weblink_url, weblink_cat, weblink_datestamp, weblink_count) VALUES ('$link_name', '$link_description', '$link_url', '".$_POST['link_category']."', '".time()."', '0')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['430']);
		echo "<br><div align='center'>".$locale['431']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['432']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['433']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {
		$opts = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result) != 0) {
			while($data = dbarray($result)) $opts .= "<option value='".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</option>\n";
		} else {
			$opts .= "<option value='0'>".$locale['434']."</option>\n";
		}
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$posted = showdate("longdate", $data['submit_datestamp']);
		opentable($locale['440']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?stage=2&amp;t=l&amp;submit_id=$submit_id'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td style='text-align:center;' class='tbl'>".$locale['441']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>".$locale['442']."$posted</td>
</tr>
<tr>
<td style='text-align:center;' class='tbl'><a href='".$submit_criteria['link_url']."' target='_blank'>".$submit_criteria['link_name']."</a> - ".$submit_criteria['link_url']."</td>
</tr>
<tr>
<td style='text-align:center;' class='tbl'><span class='alt'>".$locale['443']."</span> ".$submit_criteria['link_category']."</td>
</tr>
</table>
<table align='center'>
<tr>
<td>".$locale['443']."</td>
<td><select name='link_category' class='textbox'>
$opts</select></td>
</tr>
<tr>
<td>".$locale['444']."</td>
<td><input type='text' name='link_name' value='".$submit_criteria['link_name']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".$locale['445']."</td>
<td><input type='text' name='link_url' value='".$submit_criteria['link_url']."' class='textbox' style='width:300px'></td>
</tr>
<tr>
<td>".$locale['446']."</td>
<td><input type='text' name='link_description' value='".$submit_criteria['link_description']."' class='textbox' style='width:300px'></td>
</tr>
</table>
<center><br>
".$locale['447']."<br>
<input type='submit' name='add' value='".$locale['448']."' class='button'>
<input type='submit' name='delete' value='".$locale['449']."' class='button'></center>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "n") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$news_subject = stripinput($_POST['news_subject']);
		$news_cat = isNum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
		$news_body = addslash($_POST['news_body']);
		$news_breaks = ($_POST['news_breaks'] == "y") ? "y" : "n";
		$result = dbquery("INSERT INTO ".$db_prefix."news (news_subject, news_cat, news_news, news_extended, news_breaks, news_name, news_datestamp, news_start, news_end, news_visibility, news_reads, news_allow_comments, news_allow_ratings) VALUES ('$news_subject', '$news_cat', '$news_body', '', '$news_breaks', '".$data['user_id']."', '".time()."', '0', '0', '0', '0', '1', '1')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['490']);
		echo "<br><div align='center'>".$locale['491']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['492']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['493']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {	
		if ($settings['tinymce_enabled'] == 1) echo "<script type='text/javascript'>advanced();</script>\n";
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$news_subject = $submit_criteria['news_subject'];
		$news_cat = $submit_criteria['news_cat'];
		$news_body = phpentities(stripslashes($submit_criteria['news_body']));
		$news_breaks = $submit_criteria['news_breaks'];
		$news_cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_name");
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if (isset($news_cat)) $sel = ($news_cat == $data2['news_cat_id'] ? " selected" : "");
				$news_cat_opts .= "<option value='".$data2['news_cat_id']."'$sel>".$data2['news_cat_name']."</option>\n";
			}
		}	
		opentable($locale['500']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&amp;stage=2&amp;t=n&amp;submit_id=$submit_id'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='100' class='tbl'>".$locale['505']."</td>
<td width='80%' class='tbl'><input type='text' name='news_subject' value='$news_subject' class='textbox' style='width: 250px'></td>
</tr>
<tr>
<td width='100' class='tbl'>".$locale['506']."</td>
<td width='80%' class='tbl'><select name='news_cat' class='textbox'>
<option value='0'>".$locale['507']."</option>
$news_cat_opts</select>
</td>
</tr>
<tr>
<td valign='top' width='100' class='tbl'>".$locale['508']."</td>
<td width='80%' class='tbl'><textarea name='news_body' cols='65' rows='10' class='textbox'>$news_body</textarea></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl1'><br>
".$locale['501']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".$locale['502']."<br>
<input type='hidden' name='news_breaks' value='$news_breaks'>
<input type='submit' name='publish' value='".$locale['503']."' class='button'>
<input type='submit' name='delete' value='".$locale['504']."' class='button'>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
}
if ($stage == "2" && $t == "a") {
	if (isset($_POST['publish'])) {
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$article_cat = $_POST['article_cat'];
		$article_subject = $_POST['article_subject'];
		$article_snippet = addslash($_POST['article_snippet']);
		$article_body = addslash($_POST['article_body']);
		$article_breaks = ($_POST['article_breaks'] == "y") ? "y" : "n";
		$result = dbquery("INSERT INTO ".$db_prefix."articles (article_cat, article_subject, article_snippet, article_article, article_breaks, article_name, article_datestamp, article_reads, article_allow_comments, article_allow_ratings) VALUES ('$article_cat', '$article_subject', '$article_snippet', '$article_body', '$article_breaks', '".$data['user_id']."', '".time()."', '0', '1', '1')");
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		opentable($locale['530']);
		echo "<br><div align='center'>".$locale['531']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else if (isset($_POST['delete'])) {
		opentable($locale['532']);
		$result = dbquery("DELETE FROM ".$db_prefix."submissions WHERE submit_id='$submit_id'");
		echo "<br><div align='center'>".$locale['533']."<br><br>
<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a></div><br>\n";
		closetable();
	} else {	
		if ($settings['tinymce_enabled'] == 1) echo "<script type='text/javascript'>advanced();</script>\n";
		$data = dbarray(dbquery(
			"SELECT ts.*, user_id,user_name FROM ".$db_prefix."submissions ts
			LEFT JOIN ".$db_prefix."users tu ON ts.submit_user=tu.user_id
			WHERE submit_id='$submit_id'"
		));
		$submit_criteria = unserialize($data['submit_criteria']);
		$article_cat = $submit_criteria['article_cat'];
		$article_subject = $submit_criteria['article_subject'];
		$article_snippet = phpentities(stripslashes($submit_criteria['article_snippet']));
		$article_body = phpentities(stripslashes($submit_criteria['article_body']));
		$article_breaks = $submit_criteria['article_breaks'];
		$result2 = dbquery("SELECT * FROM ".$db_prefix."article_cats ORDER BY article_cat_name DESC");
		$article_cat_opts = ""; $sel = "";
		while ($data2 = dbarray($result2)) {
			if (isset($article_cat)) $sel = ($article_cat == $data2['article_cat_id'] ? " selected" : "");
			$article_cat_opts .= "<option value='".$data2['article_cat_id']."'$sel>".$data2['article_cat_name']."</option>\n";
		}
		opentable($locale['540']);
		echo "<form name='publish' method='post' action='".FUSION_SELF."?sub=submissions&amp;stage=2&amp;t=a&amp;submit_id=$submit_id'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='100' class='tbl'>".$locale['506']."</td>
<td width='80%' class='tbl'><select name='article_cat' class='textbox'>
$article_cat_opts</select></td>
</tr>
<tr>
<td width='100' class='tbl'>".$locale['505']."</td>
<td width='80%' class='tbl'><input type='text' name='article_subject' value='$article_subject' class='textbox' style='width: 250px'></td>
</tr>
<tr>
<td valign='top' width='100' class='tbl'>".$locale['547']."</td>
<td width='80%' class='tbl'><textarea name='article_snippet' cols='65' rows='5' class='textbox'>$article_snippet</textarea></td>
</tr>
<tr>
<td valign='top' width='100' class='tbl'>".$locale['548']."</td>
<td width='80%' class='tbl'><textarea name='article_body' cols='65' rows='10' class='textbox'>$article_body</textarea></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl1'><br>
".$locale['541']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br><br>
".$locale['542']."<br>
<input type='hidden' name='article_breaks' value='$article_breaks'>
<input type='submit' name='publish' value='".$locale['543']."' class='button'>
<input type='submit' name='delete' value='".$locale['544']."' class='button'>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>