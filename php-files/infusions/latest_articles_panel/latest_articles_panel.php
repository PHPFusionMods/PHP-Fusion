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
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }

openside($locale['023']);
$result = dbquery(
	"SELECT ta.*,tac.* FROM ".$db_prefix."articles ta
	INNER JOIN ".$db_prefix."article_cats tac ON ta.article_cat=tac.article_cat_id
	WHERE ".groupaccess('article_cat_access')." ORDER BY article_datestamp DESC LIMIT 0,5"
);
if (dbrows($result) != 0) {
	while($data = dbarray($result)) {
		$itemsubject = trimlink($data['article_subject'], 23);
		echo "<img src='".THEME."images/bullet.gif' alt=''> <a href='".BASEDIR."readarticle.php?article_id=".$data['article_id']."' title='".$data['article_subject']."' class='side'>$itemsubject</a><br>\n";
	}
} else {
	echo "<center>".$locale['004']."</center>\n";
}
closeside();
?>