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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";

opentable($locale['126']);
$result = dbquery("SELECT count(shout_id) FROM ".$db_prefix."shoutbox");
$rows = dbresult($result, 0);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if ($rows != 0) {
	$i = 0;
	$result = dbquery(
		"SELECT * FROM ".$db_prefix."shoutbox LEFT JOIN ".$db_prefix."users
		ON ".$db_prefix."shoutbox.shout_name=".$db_prefix."users.user_id
		ORDER BY shout_datestamp DESC LIMIT $rowstart,20"
	);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	while ($data = dbarray($result)) {
		echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a>";
		} else {
			echo $data['shout_name'];
		}
		echo "</span>\n<span class='small'>".$locale['041'].showdate("longdate", $data['shout_datestamp'])."</span>";
		if (iADMIN && checkrights("S")) {
			echo "\n[<a href='".ADMIN."shoutbox.php?action=edit&amp;shout_id=".$data['shout_id']."'>".$locale['048']."</a>]";
		}
		echo "<br>\n".str_replace("<br>", "", parsesmileys($data['shout_message']))."</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
} else {
	echo "<center><br>\n".$locale['127']."<br><br>\n</center>\n";
}
closetable();

echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,"$PHP_SELF?")."\n</div>\n";

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>