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
if (!defined("IN_FUSION")) { die("Access Denied"); }

echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
echo "<td class='".(eregi("settings_main.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_main.php".$aidlink."'>".$locale['401']."</a></span></td>\n";
echo "<td class='".(eregi("settings_time.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_time.php".$aidlink."'>".$locale['450']."</a></span></td>\n";
echo "<td class='".(eregi("settings_forum.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_forum.php".$aidlink."'>".$locale['500']."</a></span></td>\n";
echo "<td class='".(eregi("settings_registration.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_registration.php".$aidlink."'>".$locale['550']."</a></span></td>\n";
echo "<td class='".(eregi("settings_photo.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_photo.php".$aidlink."'>".$locale['600']."</a></span></td>\n";
echo "<td class='".(eregi("settings_misc.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_misc.php".$aidlink."'>".$locale['650']."</a></span></td>\n";
echo "<td class='".(eregi("settings_messages.php", FUSION_SELF) ? "tbl1" : "tbl2")."' style='padding-left:10px;padding-right:10px;'><span class='small'><a href='settings_messages.php".$aidlink."'>".$locale['700']."</a></span></td>\n";
echo "</tr>\n</table>\n<br>\n";
?>