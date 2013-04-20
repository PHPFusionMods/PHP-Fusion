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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/main.php";

if (!isset($lastvisited) || !isNum($lastvisited)) $lastvisited = time();

opentable($locale['400']);
echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table border='0' cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td colspan='2' class='tbl2'>".$locale['401']."</td>
<td align='center' width='50' class='tbl2'>".$locale['402']."</td>
<td align='center' width='50' class='tbl2'>".$locale['403']."</td>
<td width='120' class='tbl2'>".$locale['404']."</td>
</tr>\n";

$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		$forums = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if (checkgroup($data2['forum_access'])) {
					$moderators = "";
					$forum_mods = ($data2['forum_moderators'] ? explode(".", $data2['forum_moderators']) : "");
					if (is_array($forum_mods)) {
						sort($forum_mods);
						for ($i=0;$i < count($forum_mods);$i++) {
							$data3 = dbarray(dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE user_id='".$forum_mods[$i]."'"));
							$moderators .= "<a href='".BASEDIR."profile.php?lookup=".$data3['user_id']."'>".$data3['user_name']."</a>".($i != (count($forum_mods)-1) ? ", " : "");
						}
					}
					$new_posts = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."' AND post_datestamp>'$lastvisited'");
					$thread_count = dbcount("(*)", "threads", "forum_id='".$data2['forum_id']."'");
					$posts_count = dbcount("(*)", "posts", "forum_id='".$data2['forum_id']."'");
					if ($new_posts > 0) {
						$fim = "<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."'>";
					} else {
						$fim = "<img src='".THEME."forum/folder.gif' alt='".$locale['561']."'>";
					}
	        			$forums .= "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data2['forum_id']."'>".$data2['forum_name']."</a><br>
<span class='small'>".$data2['forum_description'].($moderators ? "<br>\n".$locale['411'].$moderators."</span></td>\n" : "</span></td>\n")."
<td align='center' class='tbl2'>".$thread_count."</td>
<td align='center' class='tbl1'>".$posts_count."</td>
<td class='tbl2'>";
					if ($data2['forum_lastpost'] == 0) {
						$forums .=  $locale['405']."</td>\n</tr>\n";
					} else {
						$data3 = dbarray(dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_id='".$data2['forum_lastuser']."'"));
						$forums .= showdate("forumdate", $data2['forum_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='".BASEDIR."profile.php?lookup=".$data2['forum_lastuser']."'>".$data3['user_name']."</a></span></td>
</tr>\n";
					}
				}
			}
			if ($forums != "") {
				echo "<tr>\n<td colspan='5' class='forum-caption'>".$data['forum_name']."</td>\n</tr>\n".$forums;
				unset($forums);
			}
		}
	}
}

echo "</table>
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='forum'><br>
<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."' style='vertical-align:middle;'> - ".$locale['409']."<br>
<img src='".THEME."forum/folder.gif' alt='".$locale['561']."' style='vertical-align:middle;'> - ".$locale['410']."
</td><td align='right' valign='bottom' class='forum'>
<form name='searchform' method='post' action='".BASEDIR."search.php?stype=f'>
<input type='text' name='stext' class='textbox' style='width:150px'>
<input type='submit' name='search' value='".$locale['550']."' class='button'>
</form>
</td>
</tr>
</table>\n";
closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>