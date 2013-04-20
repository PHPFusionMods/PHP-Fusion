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
$threads_per_page = 20;

if (!FUSION_QUERY || !$forum_id || !isNum($forum_id)) fallback("index.php");

$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);
if (dbrows($result)) {
	$data = dbarray($result);
	if (!checkgroup($data['forum_access']) || !$data['forum_cat']) fallback("index.php");
} else {
	fallback("index.php");
}
$can_post = checkgroup($data['forum_posting']);

$caption = $data['forum_cat_name']." | ".$data['forum_name'];

opentable($locale['450']);	
echo "<table cellspacing='0' cellpadding='0' width='100%'>
<tr>
<td class='smallalt'>
<a href='index.php'>".$settings['sitename']."</a> | $caption</td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>
<a href='post.php?action=newthread&amp;forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' alt='".$locale['566']."' style='border:0px;'></a>
</td>\n";
}
echo "</tr>
</table>\n";

$rows = dbrows(dbquery("SELECT * FROM ".$db_prefix."threads WHERE forum_id='$forum_id' AND thread_sticky='0'"));
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

if ($rows > $threads_per_page) {
	echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?forum_id=$forum_id&amp;")."
</div>\n";
}

echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table cellspacing='1' cellpadding='0' width='100%'>
<tr>
<td width='20' class='tbl2'>&nbsp;</td>
<td class='tbl2'>".$locale['451']."</td>
<td width='100' class='tbl2'>".$locale['452']."</td>
<td align='center' width='50' class='tbl2'>".$locale['453']."</td>
<td align='center' width='50' class='tbl2'>".$locale['454']."</td>
<td width='120' class='tbl2'>".$locale['404']."</td>
</tr>\n";

if ($rowstart == 0) {	
	$result = dbquery(
		"SELECT t.*, COUNT(p.post_id) AS thread_replies, tu1.user_name AS user_author, tu2.user_name AS user_lastuser FROM ".$db_prefix."threads t
		LEFT JOIN ".$db_prefix."posts p USING(thread_id)
		LEFT JOIN ".$db_prefix."users tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id='$forum_id' AND thread_sticky='1' GROUP BY thread_id ORDER BY thread_lastpost DESC"
	);
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			if ($data['thread_locked']) {
				echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".THEME."forum/folderlock.gif' alt='".$locale['564']."'></td>";
			} else  {
				if ($data['thread_lastpost'] > $lastvisited) {
					$folder = "<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."'>";
				} else {
					$folder = "<img src='".THEME."forum/folder.gif' alt='".$locale['561']."'>";
				}
				echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
			}
			$reps = ceil($data['thread_replies'] / $threads_per_page);
			$threadsubject = "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a>";
			if ($reps > 1) {
				$ctr = 0; $ctr2 = 1; $pages = "";
				while ($ctr2 <= $reps) {
					$pnum = "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=".$data['thread_id']."&amp;rowstart=$ctr'>$ctr2</a> ";
					$pages = $pages.$pnum; $ctr = $ctr + $threads_per_page; $ctr2++;
				}
				$threadsubject .= " - (".$locale['412'].trim($pages).")";
			}
			echo "<td class='tbl1'><img src='".THEME."forum/stickythread.gif' alt='".$locale['560']."' style='vertical-align:middle;'>
$threadsubject</td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".($data['thread_replies']-1)."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
		}
		$threadcount = dbrows($result);
	} else {
		$threadcount = 0;
	}
}

if ($rows != 0) {
	$result = dbquery(
		"SELECT t.*, COUNT(p.post_id) AS thread_replies, tu1.user_name AS user_author, tu2.user_name AS user_lastuser FROM ".$db_prefix."threads t
		LEFT JOIN ".$db_prefix."posts p USING(thread_id)
		LEFT JOIN ".$db_prefix."users tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".$db_prefix."users tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id='$forum_id' AND thread_sticky='0' GROUP BY thread_id ORDER BY thread_lastpost DESC LIMIT $rowstart,20"
	);
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		if ($data['thread_locked']) {
			echo "<tr>\n<td align='center' width='25' class='tbl2'><img src='".THEME."forum/folderlock.gif' alt='".$locale['564']."'></td>";
		} else  {
			if ($data['thread_lastpost'] > $lastvisited) {
				$folder = "<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."'>";
			} else {
				$folder = "<img src='".THEME."forum/folder.gif' alt='".$locale['561']."'>";
			}
			echo "<tr>\n<td align='center' width='25' class='tbl2'>$folder</td>";
		}
		$reps = ceil($data['thread_replies'] / $threads_per_page);
		$threadsubject = "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=".$data['thread_id']."'>".$data['thread_subject']."</a>";
		if ($reps > 1) {
			$ctr = 0; $ctr2 = 1; $pages = "";
			while ($ctr2 <= $reps) {
				$pnum = "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=".$data['thread_id']."&amp;rowstart=$ctr'>$ctr2</a> ";
				$pages = $pages.$pnum; $ctr = $ctr + $threads_per_page; $ctr2++;
			}
			$threadsubject .= " - (".$locale['412'].trim($pages).")";
		}
		echo "<td class='tbl1'>$threadsubject</td>
<td class='tbl2'><a href='../profile.php?lookup=".$data['thread_author']."'>".$data['user_author']."</a></td>
<td align='center' class='tbl1'>".$data['thread_views']."</td>
<td align='center' class='tbl2'>".($data['thread_replies']-1)."</td>
<td class='tbl1'>".showdate("forumdate", $data['thread_lastpost'])."<br>
<span class='small'>".$locale['406']."<a href='../profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_lastuser']."</a></span></td>
</tr>\n";
	}
} else {
	if ($threadcount == 0) {
		echo "<tr>\n<td colspan='6' class='tbl1'>".$locale['455']."</td>\n</tr>\n";
	}
}

echo "</table>
</td>
</tr>
</table>\n";

if ($rows > $threads_per_page) {
	echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?forum_id=$forum_id&amp;")."
</div>\n";
}

$forum_list = ""; $current_cat = "";
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") $forum_list .= "</optgroup>\n";
		$current_cat = $data2['forum_cat_name'];
		$forum_list .= "<optgroup label='".$data2['forum_cat_name']."'>\n";
	}
	$sel = ($data2['forum_id'] == $data['forum_id'] ? " selected" : "");
	$forum_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
}
$forum_list .= "</optgroup>\n";
echo "<table width='100%' cellpadding='0' cellspacing='0' style='margin-top:5px;'>
<tr>
<td align='left' class='tbl'>".$locale['540']."<br>
<select name='jump_id' class='textbox' onChange=\"jumpForum(this.options[this.selectedIndex].value);\">
$forum_list</select></td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>
<a href='post.php?action=newthread&amp;forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' alt='".$locale['566']."' style='border:0px;'></a>
</td>\n";
}
echo "</tr>
</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='tbl1'>
<img src='".THEME."forum/foldernew.gif' alt='".$locale['560']."' style='vertical-align:middle;'> - ".$locale['456']."(
<img src='".THEME."forum/folderhot.gif' alt='".$locale['562']."' style='vertical-align:middle;'> - ".$locale['457']." )<br>
<img src='".THEME."forum/folder.gif' alt='".$locale['561']."' style='vertical-align:middle;'> - ".$locale['458']."<br>
<img src='".THEME."forum/folderlock.gif' alt='".$locale['564']."' style='vertical-align:middle;'> - ".$locale['459']."<br>
<img src='".THEME."forum/stickythread.gif' alt='".$locale['563']."' style='vertical-align:middle;'> - ".$locale['460']."</td>
</tr>
</table>\n";
closetable();

echo "<script type='text/javascript'>
function DeleteItem() {
	return confirm('Delete this thread?');
}
function jumpForum(forumid) {
	document.location.href='".FORUM."viewforum.php?forum_id='+forumid;
}
</script>\n";

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>