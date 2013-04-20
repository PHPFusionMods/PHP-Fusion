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
include LOCALE.LOCALESET."search.php";

$news_per_page = 11; $posts_per_page = 20;

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

if (isset($stype)) $stype = $stype;
if (isset($stext)) $stext = stripinput($stext);

if (!isset($stype)) $stype = isset($_POST['stype']) ? $_POST['stype'] : "a";
if (!isset($stext)) $stext = isset($_POST['stext']) ? stripinput($_POST['stext']) : "";

opentable($locale['400']);
echo "<center>
<form name='searchform' method='post' action='".FUSION_SELF."'>
".$locale['401']." <input type='text' name='stext' value='$stext' class='textbox' style='width:200px'>
<input type='submit' name='search' value='".$locale['408']."' class='button'><br>
<input type='radio' name='stype' value='a'".($stype == "a" ? " checked" : "")."> ".$locale['402']."
<input type='radio' name='stype' value='n'".($stype == "n" ? " checked" : "")."> ".$locale['403']."
<input type='radio' name='stype' value='f'".($stype == "f" ? " checked" : "")."> ".$locale['404']."
<input type='radio' name='stype' value='d'".($stype == "d" ? " checked" : "")."> ".$locale['405']."
<input type='radio' name='stype' value='w'".($stype == "w" ? " checked" : "")."> ".$locale['406']."
<input type='radio' name='stype' value='m'".($stype == "m" ? " checked" : "")."> ".$locale['407']."
</form>
</center>\n";
closetable();

if ($stext != "" && strlen($stext) >= "3") {
	tablebreak();
	opentable($locale['409']);
	if ($stype == "a") {
		$result = dbquery(
			"SELECT ta.*,tac.* FROM ".$db_prefix."articles ta
			INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
			WHERE ".groupaccess('article_cat_access')." AND (article_subject LIKE '%$stext%' || article_article LIKE '%$stext%')"
		);
		$rows = dbrows($result);
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['410'] : $locale['411']).$locale['422'].":<br><br>\n";
			$result = dbquery(
				"SELECT ta.*,tac.*, tu.user_id,user_name FROM ".$db_prefix."articles ta
				INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
				LEFT JOIN ".$db_prefix."users tu ON ta.article_name=tu.user_id
				WHERE ".groupaccess('article_cat_access')." AND (article_subject LIKE '%$stext%' || article_article LIKE '%$stext%')
				ORDER BY article_datestamp DESC LIMIT $rowstart,10"
			);
			while ($data = dbarray($result)) {
				if (eregi($stext, $data['article_subject']) && eregi($stext, $data['article_article'])) {
					$subj_c = substr_count(strtolower($data['article_subject']), strtolower($stext));
					$text_c = substr_count(strtolower($data['article_article']), strtolower($stext));
					echo "<a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['article_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['433'].", ";
					echo $text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['434']."</span><br><br>";
				} elseif (eregi($stext, $data['article_article'])) {
					$text_c = substr_count(strtolower($data['article_article']), strtolower($stext));
					echo "<a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['article_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['434']."</span><br><br>";
				} elseif (eregi($stext, $data['article_subject'])) {
					$subj_c = substr_count(strtolower($data['article_subject']), strtolower($stext));
					echo "<a href='readarticle.php?article_id=".$data['article_id']."'>".$data['article_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['article_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['433']."</span><br>";
				}
			}
		} else {
			echo "<center>0 ".$locale['411'].$locale['422'].".</center>\n";
		}
		closetable();
		if ($rows > 10) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?stype=a&amp;stext=$stext&amp;")."\n</div>\n";
	} else	if ($stype == "n") {
		$rows = dbcount("(news_id)", "news", groupaccess('news_visibility')."AND (news_subject LIKE '%$stext%' || news_news LIKE '%$stext%' || news_extended LIKE '%$stext%')");
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['412'] : $locale['413']).$locale['422'].":<br><br>\n";
			$result = dbquery(
				"SELECT tn.*, user_id, user_name FROM ".$db_prefix."news tn
				LEFT JOIN ".$db_prefix."users tu ON tn.news_name=tu.user_id
				WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().")
				AND (news_end='0'||news_end>=".time().") AND (news_subject LIKE '%$stext%' || news_news LIKE '%$stext%' || news_extended LIKE '%$stext%')
				ORDER BY news_datestamp DESC LIMIT $rowstart,10"
			);
			while ($data = dbarray($result)) {
				$numrows = dbcount("(news_id)", "news", groupaccess('news_visibility')." AND news_id>='".$data['news_id']."'");
				if ($numrows > $news_per_page) {
					$rstart = ceil($numrows / $news_per_page);
					$rstart = "?rowstart=".(($rstart-1)*$news_per_page);
				} else {
					$rstart = "";
				}
				if (eregi($stext, $data['news_subject']) && eregi($stext, $data['news_news']) && eregi($stext, $data['news_extended'])) {
					$subj_c = substr_count(strtolower($data['news_subject']), strtolower($stext));
					$text_c = substr_count(strtolower($data['news_news']), strtolower($stext));
					$text_c2 = substr_count(strtolower($data['news_extended']), strtolower($stext));
					echo "<a href='news.php".$rstart."#news_".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['435'].", ";
					echo $text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['436'].", ";
					echo $text_c2." ".($text_c2 == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['437']."</span><br><br>";
				} elseif (eregi($stext, $data['news_subject']) && eregi($stext, $data['news_news'])) {
					$subj_c = substr_count(strtolower($data['news_subject']), strtolower($stext));
					$text_c = substr_count(strtolower($data['news_news']), strtolower($stext));
					echo "<a href='news.php".$rstart."#news_".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['435'].", ";
					echo $text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['436']."</span><br><br>";
				} elseif (eregi($stext, $data['news_subject']) && eregi($stext, $data['news_extended'])) {
					$subj_c = substr_count(strtolower($data['news_subject']), strtolower($stext));
					$text_c = substr_count(strtolower($data['news_extended']), strtolower($stext));
					echo "<a href='news.php?readmore=".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['435'].", ";
					echo $text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['437']."</span><br><br>";
				} elseif (eregi($stext, $data['news_news']) && eregi($stext, $data['news_extended'])) {
					$text_c = substr_count(strtolower($data['news_news']), strtolower($stext));
					$text_c2 = substr_count(strtolower($data['news_extended']), strtolower($stext));
					echo "<a href='news.php".$rstart."#news_".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['436'].", ";
					echo $text_c2." ".($text_c2 == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['437']."</span><br><br>";
				} elseif (eregi($stext, $data['news_news'])) {
					$text_c = substr_count(strtolower($data['news_news']), strtolower($stext));
					echo "<a href='news.php".$rstart."#news_".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['436']."</span><br><br>";
				} elseif (eregi($stext, $data['news_extended'])) {
					$text_c = substr_count(strtolower($data['news_extended']), strtolower($stext));
					echo "<a href='news.php?readmore=".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['437']."</span><br><br>";
				} elseif (eregi($stext, $data['news_subject'])) {
					$subj_c = substr_count(strtolower($data['news_subject']), strtolower($stext));
					echo "<a href='news.php".$rstart."#news_".$data['news_id']."'>".$data['news_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['news_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['437']."</span><br><br>";
				}
			}
		} else {
			echo "<center>0 ".$locale['413'].$locale['422'].".</center>\n";
		}
		closetable();
		if ($rows > 10) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?stype=n&amp;stext=$stext&amp;")."\n</div>\n";
	} elseif ($stype == "f") {
		$result = dbquery(
			"SELECT tp.*, tf.* FROM ".$db_prefix."posts tp
			INNER JOIN ".$db_prefix."forums tf USING(forum_id)
			WHERE ".groupaccess('forum_access')." AND (post_subject LIKE '%$stext%' || post_message LIKE '%$stext%')"
		);
		$rows = dbrows($result);
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['414'] : $locale['415']).$locale['422'].":<br><br>\n";
			$result = dbquery(
				"SELECT tp.*, tf.*, tu.user_id,user_name FROM ".$db_prefix."posts tp
				INNER JOIN ".$db_prefix."forums tf USING(forum_id)
				INNER JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
				WHERE ".groupaccess('forum_access')." AND (post_subject LIKE '%$stext%' || post_message LIKE '%$stext%')
				ORDER BY post_datestamp DESC LIMIT $rowstart,10"
			);
			while ($data = dbarray($result)) {
				if (eregi($stext, $data['post_subject']) && eregi($stext, $data['post_message'])) {
					$subj_c = substr_count(strtolower($data['post_subject']), strtolower($stext));
					$text_c = substr_count(strtolower($data['post_message']), strtolower($stext));
					echo "<a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".$data['post_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['post_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['438'].", ";
					echo $text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['439']."</span><br><br>";
				} elseif (eregi($stext, $data['post_message'])) {
					$text_c = substr_count(strtolower($data['post_message']), strtolower($stext));
					echo "<a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".$data['post_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['post_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$text_c." ".($text_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['439']."</span><br><br>";
				} elseif (eregi($stext, $data['post_subject'])) {
					$subj_c = substr_count(strtolower($data['post_subject']), strtolower($stext));
					echo "<a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".$data['post_subject']."</a><br>\n";
					echo "<span class='small2'>".$locale['040']."<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a>\n";
					echo $locale['041'].showdate("longdate", $data['post_datestamp'])."</span><br>\n";
					echo "<span class='small'>".$subj_c." ".($subj_c == 1 ? $locale['430'] : $locale['431']).$locale['432'].$locale['438']."</span><br><br>";
				}
			}
		} else {
			echo "<center>0 ".$locale['415'].$locale['422'].".</center>\n";
		}
		closetable();
		if ($rows > 10) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?stype=f&amp;stext=$stext&amp;")."\n</div>\n";
	} elseif ($stype == "d") {
		$result = dbquery(
			"SELECT td.*,tdc.* FROM ".$db_prefix."downloads td
			INNER JOIN ".$db_prefix."download_cats tdc ON td.download_cat=tdc.download_cat_id
			WHERE ".groupaccess('download_cat_access')." AND (download_title LIKE '%$stext%' || download_description LIKE '%$stext%')"
		);
		$rows = dbrows($result);
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['416'] : $locale['417']).$locale['422'].":<br><br>\n";
			$result = dbquery(
				"SELECT td.*,tdc.* FROM ".$db_prefix."downloads td
				INNER JOIN ".$db_prefix."download_cats tdc ON td.download_cat=tdc.download_cat_id
				WHERE ".groupaccess('download_cat_access')." AND (download_title LIKE '%$stext%' || download_description LIKE '%$stext%')
				ORDER BY download_title LIMIT $rowstart,10"
			);
			$i = 1;
			while ($data = dbarray($result)) {
				if ($data['download_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
					$new = " <span class='small'>".$locale['450']."</span>";
				} else {
					$new = "";
				}
				echo "<a href='downloads.php?cat_id=".$data['download_cat']."&amp;download_id=".$data['download_id']."' target='_blank'>".$data['download_title']."</a> - ".$data['download_filesize']." $new<br>\n";
				if ($data['download_description'] != "") echo stripslashes($data['download_description'])."<br>\n";
				echo "<span class='small'><font class='alt'>".$locale['451']."</font> ".$data['download_license']." |
<font class='alt'>".$locale['452']."</font> ".$data['download_os']." |
<font class='alt'>".$locale['453']."</font> ".$data['download_version']."<br>
<font class='alt'>".$locale['454']."</font> ".showdate("%d.%m.%y", $data['download_datestamp'])." |
<font class='alt'>".$locale['455']."</font> ".$data['download_count']."</span>\n";
				if ($i != $numrows) { echo "<br><br>\n"; } else { echo "\n"; }
				$i++;
			}
		} else {
			echo "<center>0 ".$locale['417'].$locale['422'].".</center>\n";
		}
		closetable();
		if ($rows > 10) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?stype=d&amp;stext=$stext&amp;")."\n</div>\n";
	} elseif ($stype == "w") {
		$result = dbquery(
			"SELECT tw.*,twc.* FROM ".$db_prefix."weblinks tw
			INNER JOIN ".$db_prefix."weblink_cats twc ON tw.weblink_cat=twc.weblink_cat_id
			WHERE ".groupaccess('weblink_cat_access')." AND (weblink_name LIKE '%$stext%' || weblink_description LIKE '%$stext%')"
		);
		$rows = dbrows($result);
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['418'] : $locale['419']).$locale['422'].":<br><br>\n";
			$result = dbquery(
				"SELECT tw.*,twc.* FROM ".$db_prefix."weblinks tw
				INNER JOIN ".$db_prefix."weblink_cats twc ON tw.weblink_cat=twc.weblink_cat_id
				WHERE ".groupaccess('weblink_cat_access')." AND (weblink_name LIKE '%$stext%' || weblink_description LIKE '%$stext%')
				ORDER BY weblink_name LIMIT $rowstart,10"
			);
			$i = 1;
			while ($data = dbarray($result)) {
				if ($data['weblink_datestamp']+604800 > time()+($settings['timeoffset']*3600)) {
					$new = " <span class='small'>".$locale['450']."</span>";
				} else {
					$new = "";
				}
				echo "<a href='weblinks.php?cat_id=".$data['weblink_cat']."&amp;weblink_id=".$data['weblink_id']."' target='_blank'>".$data['weblink_name']."</a>$new<br>\n";
				if ($data['weblink_description'] != "") echo $data['weblink_description']."<br>\n";
				echo "<span class='small'><font class='alt'>".$locale['454']."</font> ".showdate("%d.%m.%y", $data['weblink_datestamp'])." |
<span class='alt'>".$locale['456']."</span> ".$data['weblink_count']."</span>";
				echo ($i != $numrows ? "<br><br>\n" : "\n"); $i++;
			}
		} else {
			echo "<center>0 ".$locale['419'].$locale['422'].".</center>\n";
		}
		closetable();
		if ($rows > 10) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,10,$rows,3,FUSION_SELF."?stype=w&amp;stext=$stext&amp;")."\n</div>\n";
	} elseif ($stype == "m") {
		$rows = dbcount("(user_id)", "users", "user_name LIKE '%$stext%'");
		if ($rows != 0) {
			echo $rows." ".($rows == 1 ? $locale['420'] : $locale['421']).$locale['422'].":<br><br>\n";
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name LIKE '%$stext%' ORDER BY user_name");
			while ($data = dbarray($result)) {
				echo "<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
			}
		} else {
			echo "<center>0 ".$locale['421'].$locale['422'].".</center>\n";
		}
		closetable();
	}
} 

require_once "side_right.php";
require_once "footer.php";
?>