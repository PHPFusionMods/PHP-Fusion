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

if (!checkrights("S1")) fallback("../index.php");

if (isset($_POST['savesettings'])) {
	$siteintro = descript(stripslash($_POST['intro']));
	$sitefooter = descript(stripslash($_POST['footer']));
	$localeset = stripinput($_POST['localeset']);
	$old_localeset = stripinput($_POST['old_localeset']);
	$result = dbquery("UPDATE ".$db_prefix."settings SET
		sitename='".stripinput($_POST['sitename'])."',
		siteurl='".stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : "")."',
		sitebanner='".stripinput($_POST['sitebanner'])."',
		siteemail='".stripinput($_POST['siteemail'])."',
		siteusername='".stripinput($_POST['username'])."',
		siteintro='".addslashes(addslashes($siteintro))."',
		description='".stripinput($_POST['description'])."',
		keywords='".stripinput($_POST['keywords'])."',
		footer='".addslashes(addslashes($sitefooter))."',
		opening_page='".stripinput($_POST['opening_page'])."',
		news_style='".(isNum($_POST['news_style']) ? $_POST['news_style'] : "0")."',
		locale='$localeset',
		theme='".stripinput($_POST['theme'])."'
	");
	if ($localeset != $old_localeset) {
		include LOCALE.$localeset."/admin/main.php";
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['201']."' WHERE admin_link='administrators.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['202']."' WHERE admin_link='article_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['203']."' WHERE admin_link='articles.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['204']."' WHERE admin_link='blacklist.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['206']."' WHERE admin_link='custom_pages.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['207']."' WHERE admin_link='db_backup.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['208']."' WHERE admin_link='download_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['209']."' WHERE admin_link='downloads.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['210']."' WHERE admin_link='faq.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['211']."' WHERE admin_link='forums.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['212']."' WHERE admin_link='images.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['213']."' WHERE admin_link='infusions.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['215']."' WHERE admin_link='members.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['216']."' WHERE admin_link='news.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['235']."' WHERE admin_link='news_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['217']."' WHERE admin_link='panels.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['218']."' WHERE admin_link='photoalbums.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['219']."' WHERE admin_link='phpinfo.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['220']."' WHERE admin_link='polls.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['221']."' WHERE admin_link='shoutbox.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['222']."' WHERE admin_link='site_links.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['223']."' WHERE admin_link='submissions.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['224']."' WHERE admin_link='upgrade.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['225']."' WHERE admin_link='user_groups.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['226']."' WHERE admin_link='weblink_cats.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['227']."' WHERE admin_link='weblinks.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['228']."' WHERE admin_link='settings_main.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['229']."' WHERE admin_link='settings_time.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['230']."' WHERE admin_link='settings_forum.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['231']."' WHERE admin_link='settings_registration.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['232']."' WHERE admin_link='settings_photo.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['233']."' WHERE admin_link='settings_misc.php'");
		$result = dbquery("UPDATE ".$db_prefix."admin SET admin_title='".$locale['234']."' WHERE admin_link='settings_messages.php'");
	}
	redirect(FUSION_SELF);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));
$theme_files = makefilelist(THEMES, ".|..", true, "folders");
$locale_files = makefilelist(LOCALE, ".|..", true, "folders");

opentable($locale['401']);

	echo "<table align='center' cellpadding='0' cellspacing='1' width='500' class='tbl-border'>\n<tr>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_messages.php' title='".$locale['700']."'>".$locale['751']."</a></span></td>
<td width='50%' align='center' class='tbl1'><span class='small'><b>".$locale['401']."</b></span></td>
<td width='25%' align='center' class='tbl2'><span class='small'><a href='settings_time.php' title='".$locale['450']."'>".$locale['752']."</a></span></td>
</tr>\n</table><br>\n";

	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['402']."</td>
<td width='50%' class='tbl'><input type='text' name='sitename' value='".phpentities($settings2['sitename'])."' maxlength='255' class='textbox' style='width:230px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['403']."</td>
<td width='50%' class='tbl'><input type='text' name='siteurl' value='".$settings2['siteurl']."' maxlength='255' class='textbox' style='width:230px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['404']."</td>
<td width='50%' class='tbl'><input type='text' name='sitebanner' value='".$settings2['sitebanner']."' maxlength='255' class='textbox' style='width:230px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['405']."</td>
<td width='50%' class='tbl'><input type='text' name='siteemail' value='".$settings2['siteemail']."' maxlength='128' class='textbox' style='width:230px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['406']."</td>
<td width='50%' class='tbl'><input type='text' name='username' value='".$settings2['siteusername']."' maxlength='32' class='textbox' style='width:230px;'></td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['407']."<br>
<span class='small2'>".$locale['408']."</span></td>
<td width='50%' class='tbl'><textarea name='intro' rows='6' class='textbox' style='width:230px;'>".phpentities(stripslashes($settings2['siteintro']))."</textarea></td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['409']."</td>
<td width='50%' class='tbl'><textarea name='description' rows='6' class='textbox' style='width:230px;'>".$settings2['description']."</textarea></td>
</tr>
<tr>
<td valign='top' width='50%' class='tbl'>".$locale['410']."<br>
<span class='small2'>".$locale['411']."</span></td>
<td width='50%' class='tbl'><textarea name='keywords' rows='6' class='textbox' style='width:230px;'>".$settings2['keywords']."</textarea></td>
</tr>
<tr><td valign='top' width='50%' class='tbl'>".$locale['412']."</td>
<td width='50%' class='tbl'><textarea name='footer' rows='6' class='textbox' style='width:230px;'>".phpentities(stripslashes($settings2['footer']))."</textarea></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['413']."</td>
<td width='50%' class='tbl'><input type='text' name='opening_page' value='".$settings2['opening_page']."' maxlength='100' class='textbox' style='width:200px;'></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['416']."</td>
<td width='50%' class='tbl'>
<select name='news_style' class='textbox'>
<option value='0'".($settings2['news_style'] == 0 ? " selected" : "").">".$locale['417']."</option>
<option value='1'".($settings2['news_style'] == 1 ? " selected" : "").">".$locale['418']."</option>
</select>
</td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['414']."</td>
<td width='50%' class='tbl'><select name='localeset' class='textbox'>
".makefileopts($locale_files, $settings2['locale'])."
</select></td>
</tr>
<tr>
<td width='50%' class='tbl'>".$locale['415']."</td>
<td width='50%' class='tbl'><select name='theme' class='textbox'>
".makefileopts($theme_files, $settings2['theme'])."
</select></td>
</tr>
<tr><td align='center' colspan='2' class='tbl'><br>
<input type='hidden' name='old_localeset' value='".$settings2['locale']."'>
<input type='submit' name='savesettings' value='".$locale['750']."' class='button'></td>
</tr>
</table>
</form>\n";
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>