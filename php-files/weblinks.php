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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."weblinks.php";

if (isset($weblink_id) && !isNum($weblink_id)) fallback("index.php");

if (isset($weblink_id)) {
	$res = 0;
	if ($data = dbarray(dbquery("SELECT weblink_url,weblink_cat FROM ".$db_prefix."weblinks WHERE weblink_id='$weblink_id'"))) {
		$cdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='".$data['weblink_cat']."'"));
		if (checkgroup($cdata['weblink_cat_access'])) {
			$res = 1;
			$result = dbquery("UPDATE ".$db_prefix."weblinks SET weblink_count=weblink_count+1 WHERE weblink_id='$weblink_id'");
			redirect($data['weblink_url']);
		}
	}
	if ($res == 0) redirect("downloads.php");
}

if (!isset($cat_id)) {
	opentable($locale['400']);
	$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats WHERE ".groupaccess('weblink_cat_access')." ORDER BY weblink_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$counter = 0; $columns = 2; 
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$num = dbcount("(weblink_cat)", "weblinks", "weblink_cat='".$data['weblink_cat_id']."'");
			echo "<td align='left' valign='top' width='50%' class='tbl'><a href='".FUSION_SELF."?cat_id=".$data['weblink_cat_id']."'>".$data['weblink_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['weblink_cat_description'] != "") echo "<br>\n<span class='small'>".$data['weblink_cat_description']."</span>";
			echo "</td>\n";
			$counter++;
		}
		echo "</tr>\n</table>\n";
	} else {
		echo "<center><br>\n".$locale['430']."<br><br>\n</center>\n";
	}
	closetable();
} else {
	$res = 0;
	if (!isNum($cat_id)) fallback(FUSION_SELF);
	$result = dbquery("SELECT * FROM ".$db_prefix."weblink_cats WHERE weblink_cat_id='$cat_id'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['weblink_cat_access'])) {
			$res = 1;
			opentable($locale['400'].": ".$cdata['weblink_cat_name']);
			$rows = dbcount("(weblink_id)", "weblinks", "weblink_cat='$cat_id'");
			if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$result = dbquery("SELECT * FROM ".$db_prefix."weblinks WHERE weblink_cat='$cat_id' ORDER BY ".$cdata['weblink_cat_sorting']." LIMIT $rowstart,15");
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['weblink_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
						$new = " <span class='small'>".$locale['410']."</span>";
					} else {
						$new = "";
					}
					echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
					echo "<tr>\n<td colspan='2' class='tbl2'><a href='".FUSION_SELF."?cat_id=$cat_id&weblink_id=".$data['weblink_id']."' target='_blank'>".$data['weblink_name']."</a>$new</td>\n</tr>\n";
					if ($data['weblink_description'] != "") echo "<tr>\n<td colspan='2' class='tbl1'>".nl2br(stripslashes($data['weblink_description']))."</td>\n</tr>\n";
					echo "<tr>\n<td width='30%' class='tbl2'><b>".$locale['411']."</b> ".showdate("%d.%m.%y", $data['weblink_datestamp'])."</td>\n";
					echo "<td width='70%' class='tbl1'><b>".$locale['412']."</b> ".$data['weblink_count']."</td>\n</tr>\n</table>\n";
					if ($i != $numrows) { echo "<div align='center'><img src='".THEME."images/blank.gif' alt='' height='15' width='1'></div>\n"; $i++; }
				}
				closetable();
				if ($rows > 15) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,15,$rows,3,FUSION_SELF."?cat_id=$cat_id&amp;")."\n</div>\n";
			} else {
				echo $locale['431']."\n";
				closetable();
			}
		}
	}
	if ($res == 0) redirect(FUSION_SELF);
}

require_once "side_right.php";
require_once "footer.php";
?>