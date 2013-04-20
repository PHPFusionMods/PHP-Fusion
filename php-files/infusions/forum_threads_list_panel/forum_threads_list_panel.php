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

$result = dbquery(
	"SELECT tf.*, tt.*, tu.user_id,user_name, MAX(tp.post_id) as last_id, COUNT(tp.post_id) as count_posts FROM ".$db_prefix."forums tf
	INNER JOIN ".$db_prefix."threads tt USING(forum_id)
	INNER JOIN ".$db_prefix."posts tp USING(thread_id)
	INNER JOIN ".$db_prefix."users tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('forum_access')." GROUP BY thread_id ORDER BY thread_lastpost DESC LIMIT 0,".$settings['numofthreads']
);
if (dbrows($result) != 0) {
	$i = 0;
	opentable($locale['025']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	if ($theme_width == "100%") echo "<td class='tbl2'><span class='small'><b>".$locale['030']."</b></span></td>\n";
	echo "<td class='tbl2'><span class='small'><b>".$locale['031']."</b></span></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><span class='small'><b>".$locale['033']."</b></span></td>
<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><span class='small'><b>".$locale['034']."</b></span></td>
</tr>\n";
	while ($data = dbarray($result)) {
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		echo "<tr>\n";
		if ($theme_width == "100%") {
			echo "<td width='45%' class='$row_color'><span class='small'>".$data['forum_name']."</span></td>
<td width='55%' class='$row_color'><span class='small'><a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['last_id']."#post_".$data['last_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a></span></td>\n";
		} else {
			echo "<td width='100%' class='$row_color'><span class='small'><a href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['last_id']."#post_".$data['last_id']."' title='".$data['thread_subject']." (".$data['forum_name'].")'>".trimlink($data['thread_subject'], 30)."</a></span></td>\n";
		}
		echo "<td align='center' width='1%' class='$row_color' style='white-space:nowrap'><span class='small'>".($data['count_posts']-1)."</span></td>
<td align='center' width='1%' class='$row_color' style='white-space:nowrap'><span class='small'><a href='".BASEDIR."profile.php?lookup=".$data['thread_lastuser']."'>".$data['user_name']."</a></span></td>
<td align='center' width='1%' class='$row_color' style='white-space:nowrap'><span class='small'>".showdate("forumdate", $data['thread_lastpost'])."</span></td>
</tr>\n";
		$i++;
	}
	if (iMEMBER) {
		echo "<tr>\n<td align='center' colspan='5' class='tbl1'><span class='small'><a href='".INFUSIONS."forum_threads_list_panel/my_threads.php'>".$locale['026']."</a> |
<a href='".INFUSIONS."forum_threads_list_panel/my_posts.php'>".$locale['027']."</a> |
<a href='".INFUSIONS."forum_threads_list_panel/new_posts.php'>".$locale['028']."</a></span></td>\n</tr>\n";
	}
	echo "</table>\n";
	closetable();
}
?>