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

if (!checkrights("S2")) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		shortdate='".$_POST['shortdate']."',
		longdate='".$_POST['longdate']."',
		forumdate='".$_POST['forumdate']."',
		subheaderdate='".$_POST['subheaderdate']."',
		timeoffset='".$_POST['timeoffset']."'
	");
	redirect(FUSION_SELF);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$offsetlist = "";
for ($i=-13;$i<17;$i++) {
	if ($i > 0) { $offset="+".$i; } else { $offset=$i; }
	if ($offset == $settings2['timeoffset']) { $sel = " selected"; } else { $sel = ""; }
	$offsetlist .= "<option$sel>$offset</option>\n";
}

$timestamp = time()+($settings2['timeoffset']*3600);

$date_opts = "<option value=''>".$locale['455']."</option>
<option value='%m/%d/%Y'>".strftime("%m/%d/%Y", $timestamp)."</option>
<option value='%d/%m/%Y'>".strftime("%d/%m/%Y", $timestamp)."</option>
<option value='%d-%m-%Y'>".strftime("%d-%m-%Y", $timestamp)."</option>
<option value='%d.%m.%Y'>".strftime("%d.%m.%Y", $timestamp)."</option>
<option value='%m/%d/%Y %H:%M'>".strftime("%m/%d/%Y %H:%M", $timestamp)."</option>
<option value='%d/%m/%Y %H:%M'>".strftime("%d/%m/%Y %H:%M", $timestamp)."</option>
<option value='%d-%m-%Y %H:%M'>".strftime("%d-%m-%Y %H:%M", $timestamp)."</option>
<option value='%d.%m.%Y %H:%M'>".strftime("%d.%m.%Y %H:%M", $timestamp)."</option>
<option value='%m/%d/%Y %H:%M:%S'>".strftime("%m/%d/%Y %H:%M:%S", $timestamp)."</option>
<option value='%d/%m/%Y %H:%M:%S'>".strftime("%d/%m/%Y %H:%M:%S", $timestamp)."</option>
<option value='%d-%m-%Y %H:%M:%S'>".strftime("%d-%m-%Y %H:%M:%S", $timestamp)."</option>
<option value='%d.%m.%Y %H:%M:%S'>".strftime("%d.%m.%Y %H:%M:%S", $timestamp)."</option>
<option value='%B %d %Y'>".strftime("%B %d %Y", $timestamp)."</option>
<option value='%d. %B %Y'>".strftime("%d. %B %Y", $timestamp)."</option>
<option value='%d %B %Y'>".strftime("%d %B %Y", $timestamp)."</option>
<option value='%B %d %Y %H:%M'>".strftime("%B %d %Y %H:%M", $timestamp)."</option>
<option value='%d. %B %Y %H:%M'>".strftime("%d. %B %Y %H:%M", $timestamp)."</option>
<option value='%d %B %Y %H:%M'>".strftime("%d %B %Y %H:%M", $timestamp)."</option>
<option value='%B %d %Y %H:%M:%S'>".strftime("%B %d %Y %H:%M:%S", $timestamp)."</option>
<option value='%d. %B %Y %H:%M:%S'>".strftime("%d. %B %Y %H:%M:%S", $timestamp)."</option>
<option value='%d %B %Y %H:%M:%S'>".strftime("%d %B %Y %H:%M:%S", $timestamp)."</option>\n";

opentable($locale['450']);

	echo "<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_main.php' title='".$locale['401']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['450']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_forum.php' title='".$locale['500']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['451']."</td>
<td width='50%' class='tbl'>
<select name='shortdatetext' class='textbox' style='width:201px;'>
".$date_opts."</select><br>
<input type='button' name='setshortdate' value='>>' onclick=\"shortdate.value=shortdatetext.options[shortdatetext.selectedIndex].value;shortdatetext.selectedIndex=0;\" class='button'>
<input type='text' name='shortdate' value='".$settings2['shortdate']."' maxlength='50' class='textbox' style='width:180px;'>
</td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['452']."</td>
<td width='50%' class='tbl'>
<select name='longdatetext' class='textbox' style='width:201px;'>
".$date_opts."</select><br>
<input type='button' name='setlongdate' value='>>' onclick=\"longdate.value=longdatetext.options[longdatetext.selectedIndex].value;longdatetext.selectedIndex=0;\" class='button'>
<input type='text' name='longdate' value='".$settings2['longdate']."' maxlength='50' class='textbox' style='width:180px;'>
</td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['453']."</td>
<td width='50%' class='tbl'>
<select name='forumdatetext' class='textbox' style='width:201px;'>
".$date_opts."</select><br>
<input type='button' name='setforumdate' value='>>' onclick=\"forumdate.value=forumdatetext.options[forumdatetext.selectedIndex].value;forumdatetext.selectedIndex=0;\" class='button'>
<input type='text' name='forumdate' value='".$settings2['forumdate']."' maxlength='50' class='textbox' style='width:180px;'>
</td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['454']."</td>
<td width='50%' class='tbl'>
<select name='subheaderdatetext' class='textbox' style='width:201px;'>
".$date_opts."</select><br>
<input type='button' name='setsubheaderdate' value='>>' onclick=\"subheaderdate.value=subheaderdatetext.options[subheaderdatetext.selectedIndex].value;subheaderdatetext.selectedIndex=0;\" class='button'>
<input type='text' name='subheaderdate' value='".$settings2['subheaderdate']."' maxlength='50' class='textbox' style='width:180px;'>
</td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['456']."</td>
<td width='50%' class='tbl'><select name='timeoffset' class='textbox' style='width:75px;'>
$offsetlist</select></td>
</tr>
<tr><td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='savesettings' value='".$locale['750']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>