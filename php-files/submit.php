<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."submit.php";

if (!iMEMBER) fallback("index.php");

if ($stype == "l") {
	if (isset($_POST['submit_link'])) {
		if ($_POST['link_name'] != "" && $_POST['link_url'] != "" && $_POST['link_description'] != "") {
			$submit_info['link_category'] = stripinput($_POST['link_category']);
			$submit_info['link_name'] = stripinput($_POST['link_name']);
			$submit_info['link_url'] = stripinput($_POST['link_url']);
			$submit_info['link_description'] = stripinput($_POST['link_description']);
			$result = dbquery("INSERT INTO ".$db_prefix."submissions (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('l', '".$userdata['user_id']."', '".time()."', '".serialize($submit_info)."')");
			opentable($locale['400']);
			echo "<center><br>\n".$locale['410']."<br><br>
<a href='submit.php?stype=l'>".$locale['411']."</a><br><br>
<a href='index.php'>".$locale['412']."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		$opts = "";
		opentable($locale['400']);
		$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats ORDER BY weblink_cat_name");
		if (dbrows($result)) {
			while ($data = dbarray($result)) $opts .= "<option>".$data['weblink_cat_name']."</option>\n";
			echo $locale['420']."<br><br>
<form name='submit_form' method='post' action='".FUSION_SELF."?stype=l' onSubmit='return validateLink(this);'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['421']."</td>
<td class='tbl'><select name='link_category' class='textbox'>
$opts</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['422']."</td>
<td class='tbl'><input type='text' name='link_name' maxlength='100' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['423']."</td>
<td class='tbl'><input type='text' name='link_url' value='http://' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td class='tbl'>".$locale['424']."</td>
<td class='tbl'><input type='text' name='link_description' maxlength='200' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='submit_link' value='".$locale['425']."' class='button'>
</td>
</tr>
</table>
</form>\n";
		} else {
			echo "<center><br>\n".$locale['551']."<br><br>\n</center>\n";
		}
		closetable();
	}
} elseif ($stype == "n") {
	if (isset($_POST['submit_news'])) {
		if ($_POST['news_subject'] != "" && $_POST['news_body'] != "") {
			$submit_info['news_subject'] = stripinput($_POST['news_subject']);
			$submit_info['news_cat'] = isNum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
			$submit_info['news_body'] = descript($_POST['news_body']);
			$submit_info['news_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$result = dbquery("INSERT INTO ".$db_prefix."submissions (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES('n', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			opentable($locale['400']);
			echo "<center><br>\n".$locale['460']."<br><br>
<a href='submit.php?stype=n'>".$locale['461']."</a><br><br>
<a href='index.php'>".$locale['412']."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_news'])) {
			$news_subject = stripinput($_POST['news_subject']);
			$news_cat = isNum($_POST['news_cat']) ? $_POST['news_cat'] : "0";
			$news_body = phpentities(descript(stripslash($_POST['news_body'])));
			$breaks = (isset($_POST['line_breaks']) ? " checked" : "");
			opentable($news_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($news_body) : $news_body);
			closetable();
			tablebreak();
		}
		if (!isset($_POST['preview_news'])) {
			$news_subject = "";
			$news_body = "";
			$breaks = " checked";
		}
		$news_cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_name");
		if (dbrows($result2)) {
			while ($data2 = dbarray($result2)) {
				if (isset($news_cat)) $sel = ($news_cat == $data2['news_cat_id'] ? " selected" : "");
				$news_cat_opts .= "<option value='".$data2['news_cat_id']."'$sel>".$data2['news_cat_name']."</option>\n";
			}
		}	
		opentable($locale['450']);
		echo $locale['470']."<br><br>
<form name='submit_form' method='post' action='".FUSION_SELF."?stype=n' onSubmit='return validateNews(this);'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['471']."</td>
<td class='tbl'><input type='text' name='news_subject' value='$news_subject' maxlength='64' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td width='100' class='tbl'>".$locale['476']."</td>
<td width='80%' class='tbl'><select name='news_cat' class='textbox'>
<option value='0'>".$locale['477']."</option>
$news_cat_opts</select>
</td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['472']."</td>
<td class='tbl'><textarea class='textbox' name='news_body' rows='8' style='width:300px;'>$news_body</textarea></td>
</tr>
<tr>
<td colspan='2' class='tbl'><br><center>
<input type='checkbox' name='line_breaks' value='yes'$breaks>".$locale['473']."<br><br>
<input type='submit' name='preview_news' value='".$locale['474']."' class='button'>
<input type='submit' name='submit_news' value='".$locale['475']."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
} elseif ($stype == "a") {
	if (isset($_POST['submit_article'])) {
		if ($_POST['article_subject'] != "" && $_POST['article_body'] != "") {
			$submit_info['article_cat'] = $_POST['article_cat'];
			$submit_info['article_subject'] = stripinput($_POST['article_subject']);
			$submit_info['article_snippet'] = descript($_POST['article_snippet']);
			$submit_info['article_body'] = descript($_POST['article_body']);
			$submit_info['article_breaks'] = (isset($_POST['line_breaks']) ? "y" : "n");
			$result = dbquery("INSERT INTO ".$db_prefix."submissions (submit_type, submit_user, submit_datestamp, submit_criteria) VALUES ('a', '".$userdata['user_id']."', '".time()."', '".addslashes(serialize($submit_info))."')");
			opentable($locale['400']);
			echo "<center><br>\n".$locale['510']."<br><br>
<a href='submit.php?stype=a'>".$locale['511']."</a><br><br>
<a href='index.php'>".$locale['412']."</a><br><br>\n</center>\n";
			closetable();
		}
	} else {
		if (isset($_POST['preview_article'])) {
			$article_cat = $_POST['article_cat'];
			$article_subject = stripinput($_POST['article_subject']);
			$article_snippet = phpentities(descript(stripslash($_POST['article_snippet'])));
			$article_body = phpentities(descript(stripslash($_POST['article_body'])));
			$breaks = (isset($_POST['line_breaks']) ? " checked" : "");
			opentable($article_subject);
			echo (isset($_POST['line_breaks']) ? nl2br($article_body) : $article_body);
			closetable();
			tablebreak();
		}
		if (!isset($_POST['preview_article'])) {
			$article_category = "";
			$article_subject = "";
			$article_snippet = "";
			$article_body = "";
			$breaks = " checked";
		}
		$cat_list = ""; $sel = "";
		opentable($locale['500']);
		$result = dbquery("SELECT * FROM ".$db_prefix."article_cats ORDER BY article_cat_name DESC");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				if (isset($_POST['preview_article'])) $sel = ($article_cat == $data['article_cat_id'] ? " selected" : "");
				$cat_list .= "<option value='".$data['article_cat_id']."'$sel>".$data['article_cat_name']."</option>\n";
			}
			echo $locale['520']."<br><br>
<form name='submit_form' method='post' action='".FUSION_SELF."?stype=a' onSubmit='return validateArticle(this);'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td width='100' class='tbl'>".$locale['521']."</td>
<td class='tbl'><select name='article_cat' class='textbox'>
$cat_list</select></td>
</tr>
<tr>
<td class='tbl'>".$locale['522']."</td>
<td class='tbl'><input type='text' name='article_subject' value='$article_subject' maxlength='64' class='textbox' style='width:300px;'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['523']."</td>
<td class='tbl'><textarea class='textbox' name='article_snippet' rows='3' style='width:300px;'>$article_snippet</textarea></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['524']."</td>
<td class='tbl'><textarea class='textbox' name='article_body' rows='8' style='width:300px;'>$article_body</textarea></td>
</tr>
<tr>
<td colspan='2' class='tbl'><br><center>
<input type='checkbox' name='line_breaks' value='yes'$breaks>".$locale['525']."<br><br>
<input type='submit' name='preview_article' value='".$locale['526']."' class='button'>
<input type='submit' name='submit_article' value='".$locale['527']."' class='button'></center>
</td>
</tr>
</table>
</form>\n";
		} else {
			echo "<center><br>\n".$locale['551']."<br><br>\n</center>\n";
		}
		closetable();
	}
} else {
	redirect("index.php");
}
echo "<script type='text/javascript'>
function validateLink(frm) {
	if (frm.link_name.value==\"\" || frm.link_name.value==\"\" || frm.link_description.value==\"\") {
		alert(\"".$locale['550']."\"); return false;
	}
}
function validateNews(frm) {
	if (frm.news_subject.value==\"\" || frm.news_body.value==\"\") {
		alert(\"".$locale['550']."\"); return false;
	}
}
function validateArticle(frm) {
	if (frm.article_subject.value==\"\" || frm.article_snippet.value==\"\" || frm.article_body.value==\"\") {
		alert(\"".$locale['550']."\");
		return false;
	}
}
</script>\n";

require_once "side_right.php";
require_once "footer.php";
?>