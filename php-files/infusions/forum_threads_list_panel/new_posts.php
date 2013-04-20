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
| Updated to v6.0 by janmol / janm@janm.dk
| English language by Helmuth Mikkelsen helmuthm@gmail.com
+----------------------------------------------------*/
include "../../maincore.php";
include BASEDIR."subheader.php";
include BASEDIR."side_left.php";

if (!iMEMBER) fallback("../../index.php");

if (!isset($lastvisited) || !isNum($lastvisited)) $lastvisited = time();

opentable($locale['028']);
$result = dbquery(
	"SELECT tp.*, tf.* FROM ".$db_prefix."posts tp
	INNER JOIN ".$db_prefix."forums tf USING(forum_id)
	WHERE ".groupaccess('forum_access')." AND tp.post_datestamp>'$lastvisited'"
);
$rows = dbrows($result);
if ($rows != 0) {
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$result = dbquery(
		"SELECT tp.*, tf.*, tu.user_id,user_name FROM ".$db_prefix."posts tp
		INNER JOIN ".$db_prefix."forums tf USING(forum_id)
		LEFT JOIN ".$db_prefix."users tu ON tp.post_author=tu.user_id
		WHERE ".groupaccess('forum_access')." AND tp.post_datestamp>'$lastvisited' ORDER BY post_datestamp DESC LIMIT $rowstart,20"
	);
	echo "<table cellspacing='1' cellpadding='0' width='100%' class='tbl-border'>
<tr>
<td class='tbl2'><b>".$locale['030']."</b></td>
<td class='tbl2'><b>".$locale['035']."</b></td>
<td align='center' class='tbl2' width='100'><b>Author</b></td>
<td align='center' class='tbl2' width='120'><b>Date</b></td>\n";
	while ($data = dbarray($result)) {
		echo "<tr>
<td class='tbl1'><a href='".BASEDIR."forum/viewforum.php?forum_id=".$data['forum_id']."&forum_cat=".$data['forum_cat']."'>".$data['forum_name']."</a></td>
<td class='tbl1'><a href='".BASEDIR."forum/viewthread.php?forum_id=".$data['forum_id']."&thread_id=".$data['thread_id']."&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".$data['post_subject']."</a></td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['post_author']."'>".$data['user_name']."</a></td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".showdate("forumdate",$data['post_datestamp'])."</td>
</tr>\n";
	}
	echo "<tr>\n<td align='center' colspan='4' class='tbl1'>".sprintf($locale['039'], $rows)."</td>\n</tr>\n</table>\n";
} else {
	echo "<center><br>".sprintf($locale['039'], $rows)."<br><br></center>\n";
}
closetable();
if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3)."\n</div>\n";
echo "</td>\n";

include BASEDIR."side_right.php";
include BASEDIR."footer.php";
?>