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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."members-profile.php";

opentable($locale['400']);
if (iMEMBER) {
	if (!isset($sortby) || !preg_match("/^[A-Z]$/", $sortby)) $sortby = "all";
	$orderby = ($sortby == "all" ? "" : " WHERE user_name LIKE '".stripinput($sortby)."%'");
	$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby."");
	$rows = dbrows($result);
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	if ($rows != 0) {
		$i = 0;
		echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>
<tr>
<td class='tbl2'><b>".$locale['401']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['402']."</b></td>
</tr>\n";
		$result = dbquery("SELECT * FROM ".$db_prefix."users".$orderby." ORDER BY user_level DESC, user_name LIMIT $rowstart,20");
		while ($data = dbarray($result)) {
			$cell_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
			echo "<tr>\n<td class='$cell_color'>\n<a href='profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			echo "<td align='center' width='1%' class='$cell_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>\n</tr>";
		}
		echo "</table>\n"; 
	} else {
		echo "<center><br>\n".$locale['403']."$sortby<br><br>\n</center>\n";
	}
	$search = array(
		"A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R",
		"S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9"
	);
	echo "<hr>\n<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
	echo "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['404']."</a></td>";
	for ($i=0;$i < 36!="";$i++) {
		echo "<td align='center' class='tbl1'><div class='small'><a href='".FUSION_SELF."?sortby=".$search[$i]."'>".$search[$i]."</a></div></td>";
		echo ($i==17 ? "<td rowspan='2' class='tbl2'><a href='".FUSION_SELF."?sortby=all'>".$locale['404']."</a></td>\n</tr>\n<tr>\n" : "\n");
	}
	echo "</tr>\n</table>\n";
} else {
	echo "<center><br>\n".$locale['003']."<br><br>\n</center>\n";
}
closetable();
if ($rows > 20) echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,20,$rows,3,FUSION_SELF."?sortby=$sortby&amp;")."\n</div>\n";

require_once "side_right.php";
require_once "footer.php";
?>