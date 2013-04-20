<?php
/*--------------------------------------------+
| PHP-Fusion 5 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."articles.php";

if (!isset($cat_id)) {
	opentable($locale['400']);
	$result = dbquery("SELECT * FROM ".$db_prefix."article_cats WHERE ".groupaccess('article_cat_access')." ORDER BY article_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$counter = 0; $columns = 2;
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$num = dbcount("(article_cat)", "articles", "article_cat='".$data['article_cat_id']."'");
			echo "<td align='center' valign='top' width='50%'><a href='".FUSION_SELF."?cat_id=".$data['article_cat_id']."'>".$data['article_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['article_cat_description'] != "") echo "<br>\n<span class='small'>".$data['article_cat_description']."</span>";
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".$locale['401']."<br><br>\n</center>\n";
	}
	closetable();
} else {
	$res = 0;
	if (!isNum($cat_id)) fallback(FUSION_SELF);
	$result = dbquery("SELECT * FROM ".$db_prefix."article_cats WHERE article_cat_id='$cat_id'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['article_cat_access'])) {
			$res = 1;
			opentable($locale['400'].": ".$cdata['article_cat_name']);
			$rows = dbcount("(article_id)", "articles", "article_cat='$cat_id'");
			if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$result = dbquery("SELECT * FROM ".$db_prefix."articles WHERE article_cat='$cat_id' ORDER BY ".$cdata['article_cat_sorting']." LIMIT $rowstart,15");
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['article_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
						$new = "&nbsp;<span class='small'>[".$locale['402']."]</span>";
					} else {
						$new = "";
					}
					echo "<a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a>$new<br>\n".stripslashes($data['article_snippet']);
				echo ($i != $numrows ? "<br><br>\n" : "\n"); $i++;
				}
				closetable();
				if ($rows > 15) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,15,$rows,3,FUSION_SELF."?cat_id=$cat_id&amp;")."\n</div>\n";
			} else {
				echo "<center>".$locale['403']."</center>\n";
				closetable();
			}
		}
	}
	if ($res == 0) redirect(FUSION_SELF);
}

require_once "side_right.php";
require_once "footer.php";
?>