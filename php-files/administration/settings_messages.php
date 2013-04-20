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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkRights("S7")) fallback("../index.php");
$count = 0;

if (isset($saveoptions)) {
	dbquery("UPDATE ".DB_PREFIX."messages_options SET 
		pm_email_notify = '".$_POST['pm_email_notify']."',
		pm_save_sent = '".$_POST['pm_save_sent']."',
		pm_inbox = '".$_POST['pm_inbox']."',
		pm_sentbox = '".$_POST['pm_sentbox']."',
		pm_savebox = '".$_POST['pm_savebox']."'
		WHERE user_id='0'"
	);
}

$options = dbarray(dbquery("SELECT * FROM ".DB_PREFIX."messages_options WHERE user_id='0'"),0);
$pm_inbox = $options['pm_inbox'];
$pm_sentbox = $options['pm_sentbox'];
$pm_savebox = $options['pm_savebox'];

// Options Table
opentable($locale['700']);

	echo "<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_misc.php' title='".$locale['650']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['700']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_main.php' title='".$locale['401']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='optionsform' method='post' action='".FUSION_SELF."?folder=options'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td class='tbl2' align='center' colspan='2'>".$locale['707']."</td>
</tr>
<tr>
<td class='tbl' width='50%'>".$locale['701']."<br><span class='small2'>".$locale['704']."</span></td>
<td class='tbl' width='50%'><input type='text' name='pm_inbox' maxlength='4' class='textbox' style='width:40px;' value='".$pm_inbox."'></td>
</tr>
<tr>
<td class='tbl' width='50%'>".$locale['702']."<br><span class='small2'>".$locale['704']."</span></td>
<td class='tbl' width='50%'><input type='text' name='pm_sentbox' maxlength='4' class='textbox' style='width:40px;' value='".$pm_sentbox."'></td>
</tr>
<tr>
<td class='tbl' width='50%'>".$locale['703']."<br><span class='small2'>".$locale['704']."</span></td>
<td class='tbl' width='50%'><input type='text' name='pm_savebox' maxlength='4' class='textbox' style='width:40px;' value='".$pm_savebox."'></td>
</tr>
<tr><td class='tbl' align='center' colspan='2'><br></td></tr>	
<tr>
<td class='tbl2' align='center' colspan='2'>".$locale['708']."</td>
</tr>
<tr>
<td class='tbl' width='50%'>".$locale['709']."</td>
<td class='tbl' width='50%'>
<select name='pm_email_notify' class='textbox'>
<option value='0'".($options['pm_email_notify'] == "0" ? " selected" : "").">".$locale['509']."</option>
<option value='1'".($options['pm_email_notify'] == "1" ? " selected" : "").">".$locale['508']."</option>
</select>
</td>
</tr>
<tr>
<td class='tbl' width='50%'>".$locale['710']."</td>
<td class='tbl' width='50%'>
<select name='pm_save_sent' class='textbox'>
<option value='0'".($options['pm_save_sent'] == "0" ? " selected" : "").">".$locale['509']."</option>
<option value='1'".($options['pm_save_sent'] == "1" ? " selected" : "").">".$locale['508']."</option>
</select>
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><span class='small2'>".$locale['711']."</span></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='saveoptions' value='".$locale['750']."' class='button'>
</td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>