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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

render_footer(false);

echo "</body>
</html>\n";

$result = dbquery("DELETE FROM ".$db_prefix."vcode WHERE vcode_datestamp < '".(time()-360)."'");
$result = dbquery("DELETE FROM ".$db_prefix."new_users WHERE user_datestamp < '".(time()-86400)."'");

ob_end_flush();
?>