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
include LOCALE.LOCALESET."downloads.php";

if (isset($download_id) && !isNum($download_id)) fallback("index.php");

if (isset($download_id)) {
	$res = 0;
	if ($data = dbarray(dbquery("SELECT download_url,download_cat FROM ".$db_prefix."downloads WHERE download_id='$download_id'"))) {
		$cdata = dbarray(dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id='".$data['download_cat']."'"));
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			$result = dbquery("UPDATE ".$db_prefix."downloads SET download_count=download_count+1 WHERE download_id='$download_id'");
			redirect($data['download_url']);
		}
	}
	if ($res == 0) redirect("downloads.php");
}

if (!isset($cat_id)) {
	opentable($locale['400']);
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE ".groupaccess('download_cat_access')." ORDER BY download_cat_name");
	$rows = dbrows($result);
	if ($rows != 0) {
		$counter = 0; $columns = 2; 
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			$num = dbcount("(download_cat)", "downloads", "download_cat='".$data['download_cat_id']."'");
			echo "<td align='left' valign='top' width='50%' class='tbl'><a href='".FUSION_SELF."?cat_id=".$data['download_cat_id']."'>".$data['download_cat_name']."</a> <span class='small2'>($num)</span>";
			if ($data['download_cat_description'] != "") echo "<br>\n<span class='small'>".$data['download_cat_description']."</span>";
			echo "</td>\n" ;
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
	$result = dbquery("SELECT * FROM ".$db_prefix."download_cats WHERE download_cat_id='$cat_id'");
	if (dbrows($result) != 0) {
		$cdata = dbarray($result);
		if (checkgroup($cdata['download_cat_access'])) {
			$res = 1;
			opentable($locale['400'].": ".$cdata['download_cat_name']);
			$rows = dbcount("(*)", "downloads", "download_cat='$cat_id'");
			if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
			if ($rows != 0) {
				$result = dbquery("SELECT * FROM ".$db_prefix."downloads WHERE download_cat='$cat_id' ORDER BY ".$cdata['download_cat_sorting']." LIMIT $rowstart,15");
				$numrows = dbrows($result); $i = 1;
				while ($data = dbarray($result)) {
					if ($data['download_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
						$new = " <span class='small'>".$locale['410']."</span>";
					} else {
						$new = "";
					}
					echo "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n";
					echo "<tr>\n<td colspan='3' class='forum-caption'><b>".$data['download_title']."</b> $new</td>\n</tr>\n";
					if ($data['download_description'] != "") echo "<tr>\n<td colspan='3' class='tbl1'>".nl2br(stripslashes($data['download_description']))."</td>\n</tr>\n";
					echo "<tr>\n<td width='30%' class='tbl2'><b>".$locale['411']."</b> ".$data['download_license']."</td>\n<td width='30%' class='tbl1'><b>".$locale['412']."</b> ".$data['download_os']."</td>\n";
					echo "<td width='40%' class='tbl2'><b>".$locale['413']."</b> ".$data['download_version']."</td>\n</tr>\n<tr>\n<td width='30%' class='tbl2'><b>".$locale['414']."</b> ".showdate("%d.%m.%y", $data['download_datestamp'])."</td>\n";
					echo "<td width='30%' class='tbl1'><b>".$locale['415']."</b> ".$data['download_count']."</td>\n<td width='40%' class='tbl2'><a href='".FUSION_SELF."?cat_id=$cat_id&amp;download_id=".$data['download_id']."' target='_blank'>".$locale['416']."</a> (".$data['download_filesize'].")</td>\n</tr>\n";
					echo "</table>\n";
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