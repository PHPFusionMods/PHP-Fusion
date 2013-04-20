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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

render_footer(false);

echo "</body>\n</html>\n";

if (iADMIN) {
	$result = dbquery("DELETE FROM ".$db_prefix."flood_control WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."captcha WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_datestamp < '".(time()-86400)."'");
}

mysql_close();

ob_end_flush();
?>