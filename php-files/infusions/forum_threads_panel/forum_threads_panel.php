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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }

openside($locale['020']);
echo "<div class='side-label'><b>".$locale['021']."</b></div>\n";
$result = dbquery("
	SELECT * FROM ".$db_prefix."threads
	INNER JOIN ".$db_prefix."forums ON ".$db_prefix."threads.forum_id=".$db_prefix."forums.forum_id
	WHERE ".groupaccess('forum_access')." ORDER BY thread_lastpost DESC LIMIT 5
");
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['thread_subject'], 23);
		echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."' title='".$data['thread_subject']."' class='side'>$itemsubject</a><br>\n";
	}
} else {
	echo "<center>".$locale['004']."</center>\n";
}
echo "<div class='side-label'><b>".$locale['022']."</b></div>\n";
$result = dbquery("
	SELECT tf.forum_id, tt.thread_id, tt.thread_subject, COUNT(tp.post_id) as count_posts 
	FROM ".$db_prefix."forums tf
	INNER JOIN ".$db_prefix."threads tt USING(forum_id)
	INNER JOIN ".$db_prefix."posts tp USING(thread_id)
	WHERE ".groupaccess('forum_access')." GROUP BY thread_id ORDER BY count_posts DESC, thread_lastpost DESC LIMIT 5
");
if (dbrows($result) != 0) {
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['thread_subject'], 20);
		echo "<tr>\n<td class='side-small'><img src='".THEME."images/bullet.gif' alt=''> <a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."' title='".$data['thread_subject']."' class='side'>$itemsubject</a></td>
<td align='right' class='side-small'>[".($data['count_posts']-1)."]</td>\n</tr>\n";
	}
	echo "</table>\n";
} else {
	echo "<center>".$locale['004']."</center>\n";
}
closeside();
?>