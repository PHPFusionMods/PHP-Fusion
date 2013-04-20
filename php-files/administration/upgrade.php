<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";

if (!checkrights("U")) fallback("../index.php");

opentable("Upgrade");
echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>