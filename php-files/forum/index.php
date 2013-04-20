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

$forum_list = ""; $current_cat = "";
$result = dbquery(
	"SELECT f.*, COUNT(t.thread_id) AS thread_count, MAX(t.thread_lastpost) AS last_post, f2.forum_name AS forum_cat_name, u.user_id, u.user_name FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."threads t USING(forum_id)
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat = f2.forum_id
	LEFT JOIN ".$db_prefix."users u ON f.forum_lastuser = u.user_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' GROUP BY forum_id ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		if ($data['forum_cat_name'] != $current_cat) {
			$current_cat = $data['forum_cat_name'];
			echo "<tr>\n<td colspan='5' class='forum-caption'>".$data['forum_cat_name']."</td>\n</tr>\n";
		}
		$moderators = "";
		if ($data['forum_moderators']) {
			$res = "user_id='".str_replace(".", "' OR user_id='", $data['forum_moderators'])."'";
			$result2 = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users WHERE (".$res.")");
			while ($data2 = dbarray($result2)) {
				if ($moderators) $moderators .= ", ";
				$moderators .= "<a href='".BASEDIR."profile.php?lookup=".$data2['user_id']."'>".$data2['user_name']."</a>";
			}
		}
		if ($data['last_post'] > $lastvisited) {
			$fim = "<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."'>";
		} else {
			$fim = "<img src='".THEME."forum/folder.gif' alt='".$locale['561']."'>";
		}
		echo "<tr>
<td align='center' class='tbl2'>$fim</td>
<td class='tbl1'><a href='viewforum.php?forum_id=".$data['forum_id']."'>".$data['forum_name']."</a><br>
<span class='small'>".$data['forum_description'].($moderators ? "<br>\n".$locale['411'].$moderators."</span></td>\n" : "</span></td>\n")."
<td align='center' class='tbl2'>".$data['thread_count']."</td>
<td align='center' class='tbl1'>".dbcount("(post_id)", "posts", "forum_id='".$data['forum_id']."'")."</td>
<td class='tbl2'>";
		if ($data['forum_lastpost'] == 0) {
			echo $locale['405']."</td>\n</tr>\n";
		} else {
			echo showdate("forumdate", $data['forum_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='".BASEDIR."profile.php?lookup=".$data['forum_lastuser']."'>".$data['user_name']."</a></span></td>
</tr>\n";
		}
	}
} else {
	echo "<tr>\n<td colspan='5' class='tbl1'>".$locale['407']."</td>\n</tr>\n";
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