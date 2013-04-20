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

if (iMEMBER) {
	header("Location:index.php");
} else {
	opentable($locale['060']);
	echo "<div align='center'>
<form name='loginform' method='post' action='".FUSION_SELF."'>
".$locale['061']."<br>
<input type='text' name='user_name' class='textbox' style='width:100px'><br>
".$locale['062']."<br>
<input type='password' name='user_pass' class='textbox' style='width:100px'><br>
<input type='checkbox' name='remember_me' value='y'>".$locale['063']."<br><br>
<input type='submit' name='login' value='Login' class='button'><br>
</form>
<br>
</div>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>