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

if (isset($readmore) && !isNum($readmore)) fallback(FUSION_SELF);

// Predefined variables, do not edit these values
if ($settings['news_style'] == "1") { $i = 0; $rc = 0; $ncount = 1; $ncolumn = 1; $news_[0] = ""; $news_[1] = ""; $news_[2] = ""; } else { $i = 1; }

// This number should be an odd number to keep layout tidy
$items_per_page = 11;

if (!isset($readmore)) {
	$rows = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")");
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$result = dbquery(
			"SELECT tn.*, tc.*, user_id, user_name FROM ".$db_prefix."news tn
			LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
			LEFT JOIN ".$db_prefix."news_cats tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().") AND (news_end='0'||news_end>=".time().")
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT $rowstart,$items_per_page"
		);		
		$numrows = dbrows($result);
		if ($settings['news_style'] == "1") $nrows = round((dbrows($result) - 1) / 2);
		while ($data = dbarray($result)) {
			$news_cat_image = "";
			$news_subject = "<a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			if ($data['news_cat_image']) {
				$news_cat_image = "<a href='news_cats.php?cat_id=".$data['news_cat_id']."'><img src='".IMAGES_NC.$data['news_cat_image']."' alt='".$data['news_cat_name']."' align='left' style='border:0px;margin-top:3px;margin-right:5px'></a>";
			} else {
				$news_cat_image = "";
			}
			$news_news = $data['news_breaks'] == "y" ? nl2br(stripslashes($data['news_news'])) : stripslashes($data['news_news']);
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'], 
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			if ($settings['news_style'] == "1") {
				if ($rows <= 2 || $ncount == 1) {
					$news_[0] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
					$news_[0] .= "<tr>\n<td class='tbl2'><b>$news_subject</b></td>\n</tr>\n";
					$news_[0] .= "<tr>\n<td class='tbl1' style='text-align:justify'>$news_news</td>\n</tr>\n";
					$news_[0] .= "<tr>\n<td align='center' class='tbl2'>\n";
					if (iSUPERADMIN && checkrights("N")) $news_[0] .= "<form name='editnews".$news_info['news_id']."' method='post' action='".ADMIN."news.php".$aidlink."&amp;news_id=".$news_info['news_id']."'>\n";
					$news_[0] .= "<span class='small2'><img src='".THEME."images/bullet.gif' alt=''> <a href='profile.php?lookup=".$news_info['user_id']."'>".$news_info['user_name']."</a> ".$locale['041'].showdate("longdate", $news_info['news_date'])." &middot;\n";
					if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
						$news_[0] .= $news_info['news_ext'] == "y" ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$locale['042']."</a> &middot;\n" : "";
						$news_[0] .= $news_info['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$news_info['news_comments'].$locale['043']."</a> &middot;\n" : "";
						$news_[0] .= $news_info['news_reads'].$locale['044']." &middot;\n";
					}
					$news_[0] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='border:0px;vertical-align:middle;'></a>";
					if (iSUPERADMIN && checkrights("N")) { $news_[0] .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$news_info['news_id'].".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a></span>\n</form>\n"; } else { $news_[0] .= "</span>\n"; }
					$news_[0] .= "</td>\n</tr>\n</table>\n";
					if ($ncount != $rows) $news_[0] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='8'></div>\n";
				} else {
					if ($i == $nrows && $ncolumn != 2) { $ncolumn = 2; $i = 0; }
					$row_color = ($rc % 2 == 0 ? "tbl2" : "tbl1");
					$news_[$ncolumn] .= "<table width='100%' cellpadding='0' cellspacing='0'>\n";
					$news_[$ncolumn] .= "<tr>\n<td class='tbl2'><b>$news_subject</b></td>\n</tr>\n";
					$news_[$ncolumn] .= "<tr>\n<td class='tbl1' style='text-align:justify'>$news_news</td>\n</tr>\n";
					$news_[$ncolumn] .= "<tr>\n<td align='center' class='tbl2'>\n";
					if (iSUPERADMIN && checkrights("N")) $news_[$ncolumn] .= "<form name='editnews".$news_info['news_id']."' method='post' action='".ADMIN."news.php".$aidlink."&amp;news_id=".$news_info['news_id']."'>\n";
					$news_[$ncolumn] .= "<span class='small2'><img src='".THEME."images/bullet.gif' alt=''> <a href='profile.php?lookup=".$news_info['user_id']."'>".$news_info['user_name']."</a> ".$locale['041'].showdate("longdate", $news_info['news_date']);
					if ($news_info['news_ext'] == "y" || $news_info['news_allow_comments']) {
						$news_[$ncolumn] .= "<br>\n";
						$news_[$ncolumn] .= $news_info['news_ext'] == "y" ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$locale['042']."</a> &middot;\n" : "";
						$news_[$ncolumn] .= $news_info['news_allow_comments'] ? "<a href='".FUSION_SELF."?readmore=".$news_info['news_id']."'>".$news_info['news_comments'].$locale['043']."</a> &middot;\n" : "";
						$news_[$ncolumn] .= $news_info['news_reads'].$locale['044']." &middot;\n";
					} else {
						$news_[$ncolumn] .= " &middot;\n";
					}
					$news_[$ncolumn] .= "<a href='print.php?type=N&amp;item_id=".$news_info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='border:0px;vertical-align:middle;'></a>\n";
					if (iSUPERADMIN && checkrights("N")) { $news_[$ncolumn] .= " &middot; <input type='hidden' name='edit' value='edit'><a href='javascript:document.editnews".$news_info['news_id'].".submit();'><img src='".IMAGES."edit.gif' alt='".$locale['048']."' title='".$locale['048']."' style='vertical-align:middle;border:0px;'></a></span>\n</form>\n"; } else { $news_[$ncolumn] .= "</span>\n"; }
					$news_[$ncolumn] .= "</td>\n</tr>\n</table>\n";
					if ($ncolumn == 1 && $i < ($nrows - 1)) $news_[$ncolumn] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='8'></div>\n";
					if ($ncolumn == 2 && $i < (dbrows($result) - $nrows - 2)) $news_[$ncolumn] .= "<div><img src='".THEME."images/blank.gif' alt='' width='1' height='8'></div>\n";
					$i++; $rc++;
				}
				$ncount++;
			} else {
				render_news($news_subject, $news_news, $news_info);
				if ($i != $numrows) { tablebreak(); } $i++;
			}
		}
		if ($settings['news_style'] == "1") {
			opentable($locale['046']);
			echo "<table cellpadding='0' cellspacing='0' style='width:100%'>\n<tr>\n<td colspan='3' style='width:100%'>\n";
			echo $news_[0];
			echo "</td>\n</tr>\n<tr>\n<td style='width:50%;vertical-align:top;'>\n";
			echo $news_[1];
			echo "</td>\n<td style='width:10px'><img src='".THEME."images/blank.gif' alt='' width='10' height='1'></td>\n<td style='width:50%;vertical-align:top;'>\n";
			echo $news_[2];
			echo "</td>\n</tr>\n</table>\n";
			closetable();
		}
		if ($rows > $items_per_page) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$items_per_page,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['046']);
		echo "<center><br>\n".$locale['047']."<br><br>\n</center>\n";
		closetable();
	}
} else {
	include INCLUDES."comments_include.php";
	include INCLUDES."ratings_include.php";
	$result = dbquery(
		"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
		LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
		WHERE news_id='$readmore'"
	);
	if (dbrows($result)!=0) {
		$data = dbarray($result);
		if (checkgroup($data['news_visibility'])) {
			$news_cat_image = "";
			if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
				 $result2 = dbquery("UPDATE ".$db_prefix."news SET news_reads=news_reads+1 WHERE news_id='$readmore'");
				 $data['news_reads']++;
			}
			$news_subject = $data['news_subject'];
			if ($data['news_cat'] != 0) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."news_cats WHERE news_cat_id='".$data['news_cat']."'");
				if (dbrows($result2)) {
					$data2 = dbarray($result2);
					$news_cat_image = "<a href='news_cats.php?cat_id=".$data2['news_cat_id']."'><img src='".IMAGES_NC.$data2['news_cat_image']."' alt='".$data2['news_cat_name']."' align='left' style='border:0px;margin-top:3px;margin-right:5px'></a>";
				}
			}
			$news_news = stripslashes($data['news_extended'] ? $data['news_extended'] : $data['news_news']);
			if ($data['news_breaks'] == "y") { $news_news = nl2br($news_news); }
			if ($news_cat_image != "") $news_news = $news_cat_image.$news_news;
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"news_date" => $data['news_datestamp'],
				"news_ext" => "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => dbcount("(comment_id)", "comments", "comment_type='N' AND comment_item_id='".$data['news_id']."'"),
				"news_allow_comments" => $data['news_allow_comments']
			);
			render_news($news_subject, $news_news, $news_info);
			if ($data['news_allow_comments']) showcomments("N","news","news_id",$readmore,FUSION_SELF."?readmore=$readmore");
			if ($data['news_allow_ratings']) showratings("N",$readmore,FUSION_SELF."?readmore=$readmore");
		} else {
			redirect(FUSION_SELF);
		}
	} else {
		redirect(FUSION_SELF);
	}
}

require_once "side_right.php";
require_once "footer.php";
?>