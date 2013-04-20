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
require_once INCLUDES."comments_include.php";
require_once INCLUDES."ratings_include.php";
include LOCALE.LOCALESET."custom_pages.php";

if (isset($page_id) && !isNum($page_id)) fallback("index.php");

$result = dbquery("SELECT * FROM ".$db_prefix."custom_pages WHERE page_id='$page_id'");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	opentable($data['page_title']);
	if (checkgroup($data['page_access'])) {
		eval("?>".stripslashes($data['page_content'])."<?php ");
	} else {
		echo "<center><br>\n".$locale['400']."\n<br><br></center>\n";
	}
} else {
	opentable($locale['401']);
	echo "<center><br>\n".$locale['402']."\n<br><br></center>\n";
}
closetable();
if (dbrows($result) && checkgroup($data['page_access'])) {
	if ($data['page_allow_comments']) showcomments("C","custom_pages","page_id",$page_id,FUSION_SELF."?page_id=$page_id");
	if ($data['page_allow_ratings']) showratings("C",$page_id,FUSION_SELF."?page_id=$page_id");
}

require_once "side_right.php";
require_once "footer.php";
?>