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
include LOCALE.LOCALESET."news_cats.php";

if (isset($cat_id) && !isNum($cat_id)) fallback(FUSION_SELF);

opentable($locale['400']);
if (isset($cat_id)) {
	$res = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='$cat_id'");
	if (dbrows($result) || $cat_id == 0) {
		$data = dbarray($result);
		$rows = dbcount("(news_id)", "news", "news_cat='$cat_id' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
		if ($rows) {
			$res = 1;
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
			if ($cat_id != 0) {
				echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'><img src='".IMAGES_NC.$data['news_cat_image']."'><br><br>\n";
				echo "<b>".$locale['401']."</b> ".$data['news_cat_name']."<br>\n<b>".$locale['402']."</b> $rows</td>\n";
				echo "<td class='tbl1' style='vertical-align:top'>\n";
			} else {
				echo "</td>\n</tr>\n<tr>\n<td width='150' class='tbl1' style='vertical-align:top'>None-Categorised News<br>\n";
				echo "<b>".$locale['401']."</b> $rows</td>\n<td class='tbl1' style='vertical-align:top'>\n";
			}
			$result2 = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='$cat_id' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC");
			while ($data2 = dbarray($result2)) {
				echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='news.php?readmore=".$data2['news_id']."'>".$data2['news_subject']."</a><br>\n";
			}
			echo "</td>\n</tr>\n<tr>\n<td colspan='2' class='tbl1' style='text-align:center'><img src='".THEME."images/bullet.gif' alt=''> <a href='".FUSION_SELF."'>".$locale['406']."</a> <img src='".THEME."images/bulletb.gif' alt=''>";
			echo "</td>\n</tr>\n</table>\n";
		}
	}
	if (!$res) redirect(FUSION_SELF);
} else {
	$res = 0;
	$result = dbquery("SELECT * FROM ".$db_prefix."news_cats ORDER BY news_cat_id");
	if (dbrows($result)) {
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			$rows = dbcount("(news_id)", "news", "news_cat='".$data['news_cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
			echo "<tr>\n<td width='150' class='tbl1' style='vertical-align:top'><img src='".IMAGES_NC.$data['news_cat_image']."'><br><br>\n";
			echo "<b>".$locale['401']."</b> ".$data['news_cat_name']."<br>\n<b>".$locale['402']."</b> $rows</td>\n";
			echo "<td class='tbl1' style='vertical-align:top'>\n";
			if ($rows) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='".$data['news_cat_id']."' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC LIMIT 10");
				while ($data2 = dbarray($result2)) {
					echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='news.php?readmore=".$data2['news_id']."'>".$data2['news_subject']."</a><br>\n";
				}
				if ($rows > 10) echo "<div style='text-align:right'><img src='".THEME."images/bullet.gif' alt=''> <a href='".FUSION_SELF."?cat_id=".$data['news_cat_id']."'>".$locale['405']."</a></div>\n";
			} else {
				echo "<img src='".THEME."images/bullet.gif' alt=''> ".$locale['404']."\n";
			}
		}
		$res = 1;
	}
	$result = dbquery("SELECT * FROM ".$db_prefix."news WHERE news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().") ORDER BY news_datestamp DESC LIMIT 10");
	if (dbrows($result)) {
		if ($res == 0) echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		$nrows = dbcount("(news_id)", "news", "news_cat='0' AND ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
		echo "</td>\n</tr>\n<tr>\n<td width='150' class='tbl1' style='vertical-align:top'>".$locale['403']."<br>\n";
		echo "<b>".$locale['402']."</b> $nrows</td>\n<td class='tbl1' style='vertical-align:top'>\n";
		while ($data = dbarray($result)) {
			echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='news.php?readmore=".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
		}
		$res = 1;
		if ($nrows > 10) echo "<div style='text-align:right'><img src='".THEME."images/bullet.gif' alt=''> <a href='".FUSION_SELF."?cat_id=0'>".$locale['405']."</a></div>\n";
	}
	if ($res == 1) {
		echo "</td>\n</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".$locale['407']."<br><br>\n</center>\n";
	}
}
closetable();

require_once "side_right.php";
require_once "footer.php";
?>