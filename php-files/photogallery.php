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
include LOCALE.LOCALESET."photogallery.php";

define("SAFEMODE", @ini_get("safe_mode") ? true : false);
if (isset($photo_id) && !isNum($photo_id)) fallback(FUSION_SELF);
if (isset($album_id) && !isNum($album_id)) fallback(FUSION_SELF);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

if(isset($photo_id)){
	$result = dbquery(
		"SELECT tp.*, ta.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes
		FROM ".$db_prefix."photos tp
		LEFT JOIN ".$db_prefix."photo_albums ta USING (album_id)
		LEFT JOIN ".$db_prefix."users tu ON tp.photo_user=tu.user_id
		LEFT JOIN ".$db_prefix."ratings tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
		WHERE photo_id='$photo_id' GROUP BY tp.photo_id"
	);
	$data = dbarray($result);
	if (!checkgroup($data['album_access'])) {
		fallback(FUSION_SELF);
	} else {
		define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$data['album_id']."/" : ""));
		include INCLUDES."comments_include.php";
		include INCLUDES."ratings_include.php";
		$result=dbquery("UPDATE ".$db_prefix."photos SET photo_views=(photo_views+1) WHERE photo_id='".$photo_id."'");

		$pres = dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE photo_order='".($data['photo_order']-1)."' AND album_id='".$data['album_id']."'");
		$nres = dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE photo_order='".($data['photo_order']+1)."' AND album_id='".$data['album_id']."'");
		if (dbrows($pres)) $prev = dbarray($pres);
		if (dbrows($nres)) $next = dbarray($nres);

		opentable($locale['450']);
		if ($data['photo_thumb2']) { $photo_thumb = PHOTODIR.$data['photo_thumb2']; } else { $photo_thumb = ""; }
		$photo_file = PHOTODIR.$data['photo_filename'];
		$photo_size = @getimagesize($photo_file);

		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td class='tbl2'>\n";
		echo "<a href='".FUSION_SELF."'>".$locale['400']."</a> &gt;\n";
		echo "<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>".$data['album_title']."</a> &gt;\n";
		echo "<a href='".FUSION_SELF."?photo_id=$photo_id'>".$data['photo_title']."</a>\n</td>\n";		
		if ((isset($prev['photo_id']) && isNum($prev['photo_id'])) || (isset($next['photo_id']) && isNum($next['photo_id']))) {
			if (isset($prev)) echo "<td width='1%' class='tbl2'><a href='".FUSION_SELF."?photo_id=".$prev['photo_id']."' title='".$locale['451']."'>&lt;&lt;</a></td>\n";
			if (isset($next)) echo "<td width='1%' class='tbl2'><a href='".FUSION_SELF."?photo_id=".$next['photo_id']."' title='".$locale['452']."'>&gt;&gt;</a></td>\n";
		}
		echo "</tr>\n</table>\n";
		tablebreak();		
		echo "<div align='center' style='margin:5px;'>\n";
		echo "<a href=\"javascript:;\" onclick=\"window.open('showphoto.php?photo_id=".$data['photo_id']."','','scrollbars=yes,toolbar=no,status=no,resizable=yes,width=".($photo_size[0]+20).",height=".($photo_size[1]+20)."')\">";
		echo "<img src='".($photo_thumb ? $photo_thumb : $photo_file)."' alt='".$data['photo_filename']."' title='".$locale['453']."' border='0'></a>\n</div>\n";
		echo "<div align='center' style='margin:5px 0px 5px 0px'>\n";
		if ($data['photo_description']) echo nl2br(parseubb($data['photo_description']))."</b><br><br>\n";
		echo $locale['433'].showdate("shortdate", $data['photo_datestamp'])."<br>\n";
		echo $locale['434']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
		echo $locale['454']."$photo_size[0] x $photo_size[1] ".$locale['455']."<br>\n";
		echo $locale['456'].parsebytesize(filesize($photo_file))."<br>\n";
		echo $locale['436'].dbcount("(comment_id)", "comments", "comment_type='P' AND comment_item_id='".$data['photo_id']."'")."<br>\n";
		echo $locale['437'].($data['count_votes'] > 0 ? str_repeat("<img src='".IMAGES."star.gif' alt='*' style='vertical-align:middle'>", ceil($data['sum_rating'] / $data['count_votes'])) : $locale['438'])."<br>\n";
		echo $locale['457'].$data['photo_views']."\n";
		closetable();
		if ($data['photo_allow_comments'] == "1") showcomments("P","photos","photo_id",$photo_id,FUSION_SELF."?photo_id=$photo_id");
		if ($data['photo_allow_ratings']) showratings("P",$photo_id,FUSION_SELF."?photo_id=$photo_id");
	}
} elseif (isset($album_id)) {
	define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$album_id."/" : ""));
	$result = dbquery(
		"SELECT ta.* FROM ".$db_prefix."photo_albums ta WHERE album_id='$album_id'"
	);
	if (!dbrows($result)) {
		fallback(FUSION_SELF);
	} else {
		$data = dbarray($result);
		if (!checkgroup($data['album_access'])) {
			fallback(FUSION_SELF);
		} else {
			$rows = dbcount("(photo_id)", "photos", "album_id='$album_id'");
			opentable($locale['420']);
			tablebreak();
			echo "<table align='center' cellpadding='0' cellspacing='0' width='80%'>\n<tr>\n";
			echo "<td rowspan='2' align='center' class='tbl1'><img src='".PHOTOS.$data['album_thumb']."'></td>\n";
			echo "<td valign='top' width='100%'><div class='tbl2' style='font-weight:bold;vertical-align:top'>".$locale['421'].$data['album_title']."</div>\n";
			echo "<div class='tbl1' style='vertical-align:middle'>".nl2br(parseubb($data['album_description']))."</div>\n</td>\n</tr>\n";
			echo "<tr>\n<td valign='bottom' width='100%'>\n<div class='tbl2' style='vertical-align:bottom'>\n";
			if ($rows) {
				$pdata = dbarray(dbquery("SELECT tp.*, tu.user_id,user_name FROM ".$db_prefix."photos tp LEFT JOIN ".$db_prefix."users tu ON tp.photo_user=tu.user_id WHERE album_id='$album_id' ORDER BY photo_datestamp DESC LIMIT 1"));
				echo $locale['422']."$rows<br>\n";
				echo $locale['423']."<a href='".BASEDIR."profile.php?lookup=".$pdata['user_id']."'>".$pdata['user_name']."</a>".$locale['424'].showdate("longdate", $pdata['photo_datestamp'])."\n";
			} else {
				echo $locale['425']."\n";
			}
			echo "</div>\n</td>\n</tr>\n</table>";
			tablebreak();
			closetable();
			if ($rows) {
				tablebreak();
				opentable($locale['430']);
				$result = dbquery(
					"SELECT tp.*, tu.user_id,user_name, SUM(tr.rating_vote) AS sum_rating, COUNT(tr.rating_item_id) AS count_votes
					FROM ".$db_prefix."photos tp
					LEFT JOIN ".$db_prefix."users tu ON tp.photo_user=tu.user_id
					LEFT JOIN ".$db_prefix."ratings tr ON tr.rating_item_id = tp.photo_id AND tr.rating_type='P'
					WHERE album_id=$album_id GROUP BY photo_id ORDER BY photo_order LIMIT $rowstart,".$settings['thumbs_per_page']
				);
				$counter = 0;
				echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n<td class='tbl2'>\n";
				echo "<a href='".FUSION_SELF."'>".$locale['400']."</a> &gt;\n";
				echo "<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>".$data['album_title']."</a>\n";
				echo "</td>\n</tr>\n</table>\n";
				tablebreak();
				echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
				while ($data = dbarray($result)) {
					if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) echo "</tr>\n<tr>\n";
					echo "<td align='center' valign='top' class='tbl'>\n";
					echo "<b>".$data['photo_title']."</b><br><br>\n<a href='".FUSION_SELF."?photo_id=".$data['photo_id']."'>";
					if ($data['photo_thumb1'] && file_exists(PHOTODIR.$data['photo_thumb1'])){
						echo "<img src='".PHOTODIR.$data['photo_thumb1']."' alt='".$data['photo_thumb1']."' title='".$locale['431']."' border='0'>";
					} else {
						echo $locale['432'];
					}
					echo "</a><br><br>\n<span class='small'>\n";
					echo $locale['433'].showdate("shortdate", $data['photo_datestamp'])."<br>\n";
					echo $locale['434']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
					echo $locale['436'].dbcount("(comment_id)", "comments", "comment_type='P' AND comment_item_id='".$data['photo_id']."'")."<br>\n";
					echo $locale['437'].($data['count_votes'] > 0 ? str_repeat("<img src='".IMAGES."star.gif' alt='*' style='vertical-align:middle'>", ceil($data['sum_rating'] / $data['count_votes'])) : $locale['438'])."<br>\n";
					echo $locale['435'].$data['photo_views']."</span><br>\n";
					echo "</td>\n";
					$counter++;
				}
				echo "</tr>\n</table>\n";
				closetable();
			}
			if ($rows > $settings['thumbs_per_page']) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$settings['thumbs_per_page'],$rows,3,FUSION_SELF."?album_id=$album_id&amp;")."\n</div>\n";
		}
	}
} else {
	opentable($locale['400']);
	$rows = dbcount("(album_id)", "photo_albums", groupaccess('album_access'));
	if ($rows) {
		$result = dbquery(
			"SELECT ta.*, tu.user_id,user_name FROM ".$db_prefix."photo_albums ta
			LEFT JOIN ".$db_prefix."users tu ON ta.album_user=tu.user_id
			WHERE ".groupaccess('album_access')." ORDER BY album_order
			LIMIT $rowstart,".$settings['thumbs_per_page']
		);
		$counter = 0; $r = 0; $k = 1;
		echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
		while ($data = dbarray($result)) {
			if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) echo "</tr>\n<tr>\n";
			echo "<td align='center' valign='top' class='tbl'>\n";
			echo "<b>".$data['album_title']."</b><br><br>\n<a href='".FUSION_SELF."?album_id=".$data['album_id']."'>";
			if ($data['album_thumb'] && file_exists(PHOTOS.$data['album_thumb'])){
				echo "<img src='".PHOTOS.$data['album_thumb']."' alt='".$data['album_thumb']."' title='".$locale['401']."' border='0'>";
			} else {
				echo $locale['402'];
			}
			echo "</a><br><br>\n<span class='small'>\n";
			echo $locale['403'].showdate("shortdate", $data['album_datestamp'])."<br>\n";
			echo $locale['404']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
			echo $locale['405'].dbcount("(photo_id)", "photos", "album_id='".$data['album_id']."'")."</span><br>\n";
			echo "</td>\n";
			$counter++; $k++;
		}
		echo "</tr>\n</table>\n";
		closetable();
		if ($rows > $settings['thumbs_per_page']) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$settings['thumbs_per_page'],$rows,3)."\n</div>\n";
	}else{
		echo "<center><br>".$locale['406']."<br><br></center>\n";
		closetable();
	}
}

require "side_right.php";
require "footer.php";
?>