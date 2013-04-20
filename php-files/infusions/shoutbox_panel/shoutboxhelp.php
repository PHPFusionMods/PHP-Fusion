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
require_once "../../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."shoutboxhelp.php";

opentable($locale['400']);
echo $locale['401']."<br><br>
<table align='center'>
<tr>
<td width='80'>".$locale['402']."</td>
<td>:)</td>
<td>;)</td>
<td>:|</td>
<td>:(</td>
<td>:o</td>
<td>:p</td>
<td>b)</td>
<td>:D</td>
<td>:@</td>
</tr>
<tr>
<td>".$locale['403']."</td>
<td><img src='".IMAGES."smiley/smile.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/wink.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/frown.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/sad.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/shock.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/pfft.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/cool.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/grin.gif' alt='smiley'></td>
<td><img src='".IMAGES."smiley/angry.gif' alt='smiley'></td>
</tr>
</table>
<br>
".$locale['404']."\n";
closetable();

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>