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
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
$step = (isset($_GET['step']) ? $_GET['step'] : "0");
$localeset = (isset($_GET['localeset']) ? $_GET['localeset'] : "English");
include "locale/".$localeset."/setup.php";

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$locale['title']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<style type=\"text/css\">
<!--
a { color:#003D71; text-decoration:none; }
a:hover { color:#027AC6; text-decoration:underline; }
.button {
	font-family:Tahoma,Arial,Verdana,Sans-Serif;
	font-size:11px;
	color:#000000; 	
	background-color:#E5E5E8;
	border:#7F98A7 1px solid;
	margin-top:2px;
}
.textbox {
	font-family:Verdana,Tahoma,Arial,Sans-Serif;
	font-size:11px;
	color:#000; 
	background-color:#FFFFFF;
	border:1px #7F98A7 solid;
}
td { font-family:Verdana,Tahoma,Arial,Sans-Serif; font-size:11px; }
.tbl-border { background-color:#D1D8DD; }
.tbl1 { font-size:11px; color:#000; background-color:#F1F1F1; padding:4px; }
.tbl2 {	font-size:11px; color:#000; background-color:#F6F6F6; padding:4px; }
-->
</style>
</head>
<body class='tbl2'>\n";

if ($step == "0") {
	$locale_files = makefilelist("locale/", ".|..", true, "folders");
	$counter = 0; $columns = 4;
	echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>\n";
	for ($i=0;$i < count($locale_files);$i++) {
		if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
		echo "<td align='center' width='25%' class='tbl1'>";
		if ($locale_files[$i] == $localeset) {
			echo "<b>".$locale_files[$i]."</b>";
		} else {
			echo "<a href='".FUSION_SELF."?step=0&localeset=".$locale_files[$i]."'>".$locale_files[$i]."</a>";
		}
		echo "</td>\n";
		$counter++;
	}
	echo "</tr>\n</table>\n<br>\n";
}

echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>
<tr>
<td align='center' class='tbl2'><img src='images/banner.gif'></td>
</tr>
</table>
<br>\n";

echo "<table align='center' width='450' cellpadding='0' cellspacing='1' class='tbl-border'>\n";

// mySQL database functions
function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	}
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

if ($step == "2") {
	require_once "config.php";
	$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
	$basedir = substr($_SERVER['PHP_SELF'], 0, -9);
}

if ($step == "0") {
	if (is_writable("images") && is_writable("images/articles") && is_writable("images/avatars")
	&& is_writable("images/news") && is_writable("forum/attachments") && is_writable("config.php"))
	{ $write_check = true; } else { $write_check = false; }
	echo "<form name='setup' method='post' action='".FUSION_SELF."?step=1&localeset=$localeset'>
<tr><td align='center' colspan='2' class='tbl1'><b>".$locale['410']."</b><br><br>
".($write_check ? $locale['411']."<br><br>\n".$locale['414'] : $locale['412']."<br><br>\n".$locale['413'])."</td></tr>
<tr><td align='center' colspan='2' class='tbl2'><b>".$locale['420']."</b></td></tr>
<tr><td align='right' class='tbl1'>".$locale['421']."</td><td class='tbl1'><input type='text' value='localhost' name='db_host' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['422']."</td><td class='tbl1'><input type='text' value='' name='db_user' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['423']."</td><td class='tbl1'><input type='password' value='' name='db_pass' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['424']."</td><td class='tbl1'><input type='text' value='' name='db_name' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['425']."</td><td class='tbl1'><input type='text' value='fusion_' name='db_prefix' class='textbox'></td></tr>
<tr><td align='center' colspan='2' class='tbl1'><input type='submit' name='next' value='".$locale['426']."' class='button'></td></tr>
</form>\n</td>\n</tr>";
}

if ($step == "1") {
	$db_host = stripinput($_POST['db_host']);
	$db_user = stripinput($_POST['db_user']);
	$db_pass = stripinput($_POST['db_pass']);
	$db_name = stripinput($_POST['db_name']);
	$db_prefix = stripinput($_POST['db_prefix']);
	$config = "<?php
// database settings
"."$"."db_host="."\"".$_POST['db_host']."\"".";
"."$"."db_user="."\"".$_POST['db_user']."\"".";
"."$"."db_pass="."\"".$_POST['db_pass']."\"".";
"."$"."db_name="."\"".$_POST['db_name']."\"".";
"."$"."db_prefix="."\"".$_POST['db_prefix']."\"".";
define("."\""."DB_PREFIX"."\"".", "."\"".$_POST['db_prefix']."\"".");
?>";
	$temp = fopen("config.php","w");
	if (!fwrite($temp, $config)) {
		echo $locale['430']."\n</td></tr>\n</table>\n";
		fclose($temp);
		exit;
	}
	fclose($temp);
	
	require_once "config.php";
	$link = dbconnect($db_host, $db_user, $db_pass, $db_name);
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin");
	$result = dbquery("CREATE TABLE ".$db_prefix."admin (
	admin_id TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
	admin_rights CHAR(2) NOT NULL DEFAULT '',
	admin_image VARCHAR(50) NOT NULL DEFAULT '',
	admin_title VARCHAR(50) NOT NULL DEFAULT '',
	admin_link VARCHAR(100) NOT NULL DEFAULT 'reserved',
	admin_page TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	PRIMARY KEY (admin_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."articles");
	$result = dbquery("CREATE TABLE ".$db_prefix."articles (
	article_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	article_cat SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	article_subject VARCHAR(200) NOT NULL DEFAULT '',
	article_snippet TEXT NOT NULL,
	article_article TEXT NOT NULL,
	article_breaks CHAR(1) NOT NULL DEFAULT '',
	article_name SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1',
	article_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	article_reads SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	article_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	article_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	PRIMARY KEY (article_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."article_cats");
	$result = dbquery("CREATE TABLE ".$db_prefix."article_cats (
	article_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	article_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	article_cat_description VARCHAR(200) NOT NULL DEFAULT '',
	article_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'article_subject ASC',
	article_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (article_cat_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."blacklist");
	$result = dbquery("CREATE TABLE ".$db_prefix."blacklist (
	blacklist_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	blacklist_ip VARCHAR(20) NOT NULL DEFAULT '',
	blacklist_email VARCHAR(100) NOT NULL DEFAULT '',
	blacklist_reason TEXT NOT NULL,
	PRIMARY KEY (blacklist_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."comments");
	$result = dbquery("CREATE TABLE ".$db_prefix."comments (
	comment_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	comment_item_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	comment_type CHAR(1) NOT NULL DEFAULT '',
	comment_name VARCHAR(50) NOT NULL DEFAULT '',
	comment_message TEXT NOT NULL,
	comment_smileys tinyint(1) unsigned NOT NULL default '1',
	comment_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	comment_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
	PRIMARY KEY (comment_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."custom_pages");
	$result = dbquery("CREATE TABLE ".$db_prefix."custom_pages (
	page_id SMALLINT(5) NOT NULL AUTO_INCREMENT,
	page_title VARCHAR(200) NOT NULL DEFAULT '',
	page_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	page_content TEXT NOT NULL,
	page_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	page_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (page_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."download_cats");
	$result = dbquery("CREATE TABLE ".$db_prefix."download_cats (
	download_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	download_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	download_cat_description TEXT NOT NULL,
	download_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'download_title ASC',
	download_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (download_cat_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."downloads");
	$result = dbquery("CREATE TABLE ".$db_prefix."downloads (
	download_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	download_title VARCHAR(100) NOT NULL DEFAULT '',
	download_description TEXT NOT NULL,
	download_url VARCHAR(200) NOT NULL DEFAULT '',
	download_cat SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	download_license VARCHAR(50) NOT NULL DEFAULT '',
	download_os VARCHAR(50) NOT NULL DEFAULT '',
	download_version VARCHAR(20) NOT NULL DEFAULT '',
	download_filesize VARCHAR(20) NOT NULL DEFAULT '',
	download_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	download_count SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (download_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faq_cats");
	$result = dbquery("CREATE TABLE ".$db_prefix."faq_cats (
	faq_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	faq_cat_name VARCHAR(200) NOT NULL DEFAULT '',
	faq_cat_description VARCHAR(250) NOT NULL DEFAULT '',
	PRIMARY KEY(faq_cat_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."faqs");
	$result = dbquery("CREATE TABLE ".$db_prefix."faqs (
	faq_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	faq_cat_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	faq_question VARCHAR(200) NOT NULL DEFAULT '',
	faq_answer TEXT NOT NULL,
	PRIMARY KEY(faq_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_attachments");
	$result = dbquery("CREATE TABLE ".$db_prefix."forum_attachments (
	attach_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	thread_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	post_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	attach_name VARCHAR(100) NOT NULL DEFAULT '',
	attach_ext VARCHAR(5) NOT NULL DEFAULT '',
	attach_size INT(20) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (attach_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forums");
	$result = dbquery("CREATE TABLE ".$db_prefix."forums (
	forum_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	forum_cat SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	forum_name VARCHAR(100) NOT NULL DEFAULT '',
	forum_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	forum_description TEXT NOT NULL,
	forum_moderators TEXT NOT NULL,
	forum_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	forum_posting TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	forum_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
	forum_lastuser SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (forum_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."infusions");
	$result = dbquery("CREATE TABLE ".$db_prefix."infusions (
	inf_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	inf_title VARCHAR(100) NOT NULL DEFAULT '',
	inf_folder VARCHAR(100) NOT NULL DEFAULT '',
	inf_version VARCHAR(10) NOT NULL DEFAULT '0',
	PRIMARY KEY (inf_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages");
	$result = dbquery("CREATE TABLE ".$db_prefix."messages (
	message_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	message_to SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	message_from SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	message_subject VARCHAR(100) NOT NULL DEFAULT '',
	message_message TEXT NOT NULL,
	message_smileys CHAR(1) NOT NULL DEFAULT '',
	message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
	PRIMARY KEY (message_id)
	) TYPE=MyISAM;");
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."messages_options");
	$result = dbquery("CREATE TABLE ".$db_prefix."messages_options (
	user_id smallint(5) UNSIGNED NOT NULL DEFAULT '0',
	pm_email_notify tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
	pm_save_sent tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
	pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	PRIMARY KEY (user_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news");
	$result = dbquery("CREATE TABLE ".$db_prefix."news (
	news_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	news_subject VARCHAR(200) NOT NULL DEFAULT '',
	news_cat SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	news_news TEXT NOT NULL,
	news_extended TEXT NOT NULL,
	news_breaks CHAR(1) NOT NULL DEFAULT '',
	news_name SMALLINT(5) UNSIGNED NOT NULL DEFAULT '1',
	news_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	news_start INT(10) UNSIGNED NOT NULL DEFAULT '0',
	news_end INT(10) UNSIGNED NOT NULL DEFAULT '0',
	news_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	news_reads SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	news_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	news_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	PRIMARY KEY (news_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news_cats");
	$result = dbquery("CREATE TABLE ".$db_prefix."news_cats (
	news_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	news_cat_image VARCHAR(100) NOT NULL DEFAULT '',
	PRIMARY KEY (news_cat_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."new_users");
	$result = dbquery("CREATE TABLE ".$db_prefix."new_users (
	user_code VARCHAR(32) NOT NULL,
	user_email VARCHAR(100) NOT NULL,
	user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	user_info TEXT NOT NULL
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."ratings");
	$result = dbquery("CREATE TABLE ".$db_prefix."ratings (
	rating_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	rating_item_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	rating_type CHAR(1) NOT NULL DEFAULT '',
	rating_user SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	rating_vote TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	rating_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	rating_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
	PRIMARY KEY (rating_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."online");
	$result = dbquery("CREATE TABLE ".$db_prefix."online (
	online_user VARCHAR(50) NOT NULL DEFAULT '',
	online_ip VARCHAR(20) NOT NULL DEFAULT '',
	online_lastactive INT(10) UNSIGNED NOT NULL DEFAULT '0'
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."panels");
	$result = dbquery("CREATE TABLE ".$db_prefix."panels (
	panel_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	panel_name VARCHAR(100) NOT NULL DEFAULT '',
	panel_filename VARCHAR(100) NOT NULL DEFAULT '',
	panel_content TEXT NOT NULL,
	panel_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	panel_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	panel_type VARCHAR(20) NOT NULL DEFAULT '',
	panel_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	panel_display TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	panel_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (panel_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
				
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photo_albums");
	$result = dbquery("CREATE TABLE ".$db_prefix."photo_albums (
	album_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	album_title VARCHAR(100) NOT NULL DEFAULT '',
	album_info VARCHAR(200) DEFAULT '',
	album_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (album_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
				
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."photos");
	$result = dbquery("CREATE TABLE ".$db_prefix."photos (
	photo_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	album_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	photo_title VARCHAR(100) NOT NULL DEFAULT '',
	photo_date INT(10) UNSIGNED NOT NULL DEFAULT '0',
	user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	photo_views SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	photo_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (photo_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."votes");
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."poll_votes");
	$result = dbquery("CREATE TABLE ".$db_prefix."poll_votes (
	vote_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	vote_user SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	vote_opt SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
	poll_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (vote_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."polls");
	$result = dbquery("CREATE TABLE ".$db_prefix."polls (
	poll_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	poll_title VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_0 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_1 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_2 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_3 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_4 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_5 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_6 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_7 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_8 VARCHAR(200) NOT NULL DEFAULT '',
	poll_opt_9 VARCHAR(200) NOT NULL DEFAULT '',
	poll_started INT(10) UNSIGNED NOT NULL DEFAULT '0',
	poll_ended INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (poll_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."posts");
	$result = dbquery("CREATE TABLE ".$db_prefix."posts (
	forum_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	thread_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	post_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	post_subject VARCHAR(100) NOT NULL DEFAULT '',
	post_message TEXT NOT NULL,
	post_showsig TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	post_smileys TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	post_author SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	post_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	post_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
	post_edituser SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	post_edittime INT(10) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (post_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings");
	$result = dbquery("CREATE TABLE ".$db_prefix."settings (
	sitename VARCHAR(200) NOT NULL DEFAULT '',
	siteurl VARCHAR(200) NOT NULL DEFAULT '',
	sitebanner VARCHAR(200) NOT NULL DEFAULT '',
	siteemail VARCHAR(100) NOT NULL DEFAULT '',
	siteusername VARCHAR(30) NOT NULL DEFAULT '',
	siteintro TEXT NOT NULL,
	description TEXT NOT NULL,
	keywords TEXT NOT NULL,
	footer TEXT NOT NULL,
	opening_page VARCHAR(100) NOT NULL DEFAULT '',
	news_style TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	locale VARCHAR(20) NOT NULL DEFAULT 'English',
	theme VARCHAR(100) NOT NULL DEFAULT '',
	shortdate VARCHAR(50) NOT NULL DEFAULT '',
	longdate VARCHAR(50) NOT NULL DEFAULT '',
	forumdate VARCHAR(50) NOT NULL DEFAULT '',
	subheaderdate VARCHAR(50) NOT NULL DEFAULT '',
	timeoffset VARCHAR(3) NOT NULL DEFAULT '0',
	numofthreads SMALLINT(2) UNSIGNED NOT NULL DEFAULT '5',
	attachments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	attachmax INT(12) UNSIGNED NOT NULL DEFAULT '150000',
	attachtypes VARCHAR(150) NOT NULL DEFAULT '.gif,.jpg,.png,.zip,.rar,.tar',
	enable_registration TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	email_verification TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	admin_activation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
	display_validation TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
	validation_method VARCHAR(5) DEFAULT 'image' NOT NULL,
	album_image_w SMALLINT(3) UNSIGNED NOT NULL DEFAULT '80',
	album_image_h SMALLINT(3) UNSIGNED NOT NULL DEFAULT '60',
	thumb_image_w SMALLINT(3) UNSIGNED NOT NULL DEFAULT '120',
	thumb_image_h SMALLINT(3) UNSIGNED NOT NULL DEFAULT '100',
	thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL,
	album_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	albums_per_row SMALLINT(2) UNSIGNED NOT NULL DEFAULT '4',
	albums_per_page SMALLINT(2) UNSIGNED NOT NULL DEFAULT '16',
	thumbs_per_row SMALLINT(2) UNSIGNED NOT NULL DEFAULT '4',
	thumbs_per_page SMALLINT(2) UNSIGNED NOT NULL DEFAULT '12',
	album_max_w SMALLINT(4) UNSIGNED NOT NULL DEFAULT '400',
	album_max_h SMALLINT(4) UNSIGNED NOT NULL DEFAULT '300',
	album_max_b INT(10) UNSIGNED NOT NULL DEFAULT '150000',
	db_backup_folder VARCHAR(200) NOT NULL DEFAULT 'db_backups/',
	tinymce_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	smtp_host VARCHAR(200) NOT NULL DEFAULT '' ,
	smtp_username VARCHAR(100) NOT NULL DEFAULT '',
	smtp_password VARCHAR(100) NOT NULL DEFAULT '',
	bad_words_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	bad_words TEXT NOT NULL,
	bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL,
	guestposts TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	numofshouts TINYINT(2) UNSIGNED NOT NULL DEFAULT '10',
	counter BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
	version VARCHAR(10) NOT NULL DEFAULT '6.00',
	maintenance TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	maintenance_message TEXT NOT NULL
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."shoutbox");
	$result = dbquery("CREATE TABLE ".$db_prefix."shoutbox (
	shout_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	shout_name VARCHAR(50) NOT NULL DEFAULT '',
	shout_message VARCHAR(200) NOT NULL DEFAULT '',
	shout_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	shout_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
	PRIMARY KEY (shout_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."site_links");
	$result = dbquery("CREATE TABLE ".$db_prefix."site_links (
	link_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	link_name VARCHAR(100) NOT NULL DEFAULT '',
	link_url VARCHAR(200) NOT NULL DEFAULT '',
	link_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	link_position TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	link_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	link_order SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (link_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."submissions");
	$result = dbquery("CREATE TABLE ".$db_prefix."submissions (
	submit_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	submit_type CHAR(1) NOT NULL,
	submit_user SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
	submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
	submit_criteria TEXT NOT NULL,
	PRIMARY KEY (submit_id) 
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."threads");
	$result = dbquery("CREATE TABLE ".$db_prefix."threads (
	forum_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	thread_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	thread_subject VARCHAR(100) NOT NULL DEFAULT '',
	thread_author SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	thread_views SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	thread_lastpost INT(10) UNSIGNED NOT NULL DEFAULT '0',
	thread_lastuser SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	thread_sticky TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	thread_locked TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (thread_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_groups");
	$result = dbquery("CREATE TABLE ".$db_prefix."user_groups (
	group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
	group_name VARCHAR(100) NOT NULL,
	group_description VARCHAR(200) NOT NULL,
	PRIMARY KEY (group_id) 
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."users");
	$result = dbquery("CREATE TABLE ".$db_prefix."users (
	user_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	user_name VARCHAR(30) NOT NULL DEFAULT '',
	user_password VARCHAR(32) NOT NULL DEFAULT '',
	user_email VARCHAR(100) NOT NULL DEFAULT '',
	user_hide_email TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
	user_location VARCHAR(50) NOT NULL DEFAULT '',
	user_birthdate DATE NOT NULL DEFAULT '0000-00-00',
	user_aim VARCHAR(16) NOT NULL DEFAULT '',
	user_icq VARCHAR(15) NOT NULL DEFAULT '',
	user_msn VARCHAR(100) NOT NULL DEFAULT '',
	user_yahoo VARCHAR(100) NOT NULL DEFAULT '',
	user_web VARCHAR(200) NOT NULL DEFAULT '',
	user_theme VARCHAR(100) NOT NULL DEFAULT 'Default',
	user_offset CHAR(3) NOT NULL DEFAULT '0',
	user_avatar VARCHAR(100) NOT NULL DEFAULT '',
	user_sig TEXT NOT NULL,
	user_posts SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	user_joined INT(10) UNSIGNED NOT NULL DEFAULT '0',
	user_lastvisit INT(10) UNSIGNED NOT NULL DEFAULT '0',
	user_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
	user_rights TEXT NOT NULL,
	user_groups TEXT NOT NULL,
	user_level TINYINT(3) UNSIGNED NOT NULL DEFAULT '101',
	user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY (user_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."vcode");
	$result = dbquery("CREATE TABLE ".$db_prefix."vcode (
	vcode_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	vcode_1 VARCHAR(5) NOT NULL DEFAULT '',
	vcode_2 VARCHAR(32) NOT NULL DEFAULT ''
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";

	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblink_cats");
	$result = dbquery("CREATE TABLE ".$db_prefix."weblink_cats (
	weblink_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	weblink_cat_name VARCHAR(100) NOT NULL DEFAULT '',
	weblink_cat_description TEXT NOT NULL,
	weblink_cat_sorting VARCHAR(50) NOT NULL DEFAULT 'weblink_name ASC',
	weblink_cat_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY(weblink_cat_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."weblinks");
	$result = dbquery("CREATE TABLE ".$db_prefix."weblinks (
	weblink_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
	weblink_name VARCHAR(100) NOT NULL DEFAULT '',
	weblink_description TEXT NOT NULL,
	weblink_url VARCHAR(200) NOT NULL DEFAULT '',
	weblink_cat SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	weblink_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
	weblink_count SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	PRIMARY KEY(weblink_id)
	) TYPE=MyISAM;");
	
	if (!$result) $fail = "1";
	
	if (isset($fail) && $fail == "1") {
		echo $locale['431']."</td></tr>\n</table>\n";
		exit;
	}else {
		echo "<form name='setup' method='post' action='".FUSION_SELF."?step=2&localeset=$localeset'>
<tr><td align='center' colspan='2' class='tbl1'>".$locale['432']."\n".$locale['433']."</td></tr>
<tr><td align='center' colspan='2' class='tbl2'><b>".$locale['440']."</b></td></tr>
<tr><td align='right' class='tbl1'>".$locale['441']."</td><td class='tbl1'><input type='text' name='username' maxlength='30' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['442']."</td><td class='tbl1'><input type='password' name='password1' maxlength='20' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['443']."</td><td class='tbl1'><input type='password' name='password2' maxlength='20' class='textbox'></td></tr>
<tr><td align='right' class='tbl1'>".$locale['444']."</td><td class='tbl1'><input type='text' name='email' maxlength='100' class='textbox'></td></tr>
<tr><td colspan='2' align='center' class='tbl1'><input type='submit' name='next' value='".$locale['426']."' class='button'></td></tr>
</form>\n</td></tr>\n";
	}
}

if ($step == "2") {
	$error = "";	
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$email = stripinput($_POST['email']);
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['450']."<br><br>\n";
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
		if ($password1 != $password2) $error .= $locale['451']."<br><br>\n";
	} else {
		$error .= $locale['452']."<br><br>\n";
	}
 	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= $locale['453']."<br><br>\n";
	}
	if ($error != "") {
		echo "<tr><td align='center'>\n".$locale['454']."<br><br>\n$error\n</td></tr>\n</table>\n";
		exit;
	} else {
		$result = dbquery("INSERT INTO ".$db_prefix."settings VALUES('PHP-Fusion Powered Website', 'http://www.yourdomain.com/',
		'images/banner.gif', 'you@yourdomain.com', '$username', '<center>Welcome to your site</center>', '', '', 
		'<center>Copyright &copy; 2005</center>', 'news.php', '0', '$localeset', 'Milestone',
		'%d/%m/%Y %H:%M', '%B %d %Y %H:%M:%S', '%d-%m-%Y %H:%M', '%B %d %Y %H:%M:%S', '0',
		'5', '0', '150000', '.gif,.jpg,.png,.zip,.rar,.tar',
		'1', '1', '0', '1', 'image',
		'80', '60', '120', '100', 'gd2', '1', '4', '16', '4', '12', '400', '300', '150000',
		'db_backups/', '0', '', '', '', '0', '', '****', '0', '10',
		'0', '6.00.307', '0', '')");
		
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AD', 'admins.gif', '".$locale['460']."', 'administrators.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('AC', 'article_cats.gif', '".$locale['461']."', 'article_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('A', 'articles.gif', '".$locale['462']."', 'articles.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('B', 'blacklist.gif', '".$locale['463']."', 'blacklist.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('C', '', '".$locale['464']."', 'reserved', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('CP', 'c-pages.gif', '".$locale['465']."', 'custom_pages.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DB', 'db_backup.gif', '".$locale['466']."', 'db_backup.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('DC', 'dl_cats.gif', '".$locale['467']."', 'download_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('D', 'dl.gif', '".$locale['468']."', 'downloads.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FQ', 'faq.gif', '".$locale['469']."', 'faq.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('F', 'forums.gif', '".$locale['470']."', 'forums.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IM', 'images.gif', '".$locale['471']."', 'images.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('I', 'infusions.gif', '".$locale['472']."', 'infusions.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('IP', '', '".$locale['473']."', 'reserved', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('M', 'members.gif', '".$locale['474']."', 'members.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('N', 'news.gif', '".$locale['475']."', 'news.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('NC', 'news_cats.gif', '".$locale['494']."', 'news_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('P', 'panels.gif', '".$locale['476']."', 'panels.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PH', 'photoalbums.gif', '".$locale['477']."', 'photoalbums.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PI', 'phpinfo.gif', '".$locale['478']."', 'phpinfo.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('PO', 'polls.gif', '".$locale['479']."', 'polls.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S', 'shout.gif', '".$locale['480']."', 'shoutbox.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SL', 'site_links.gif', '".$locale['481']."', 'site_links.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SU', 'submissions.gif', '".$locale['482']."', 'submissions.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('U', 'upgrade.gif', '".$locale['483']."', 'upgrade.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UG', 'user_groups.gif', '".$locale['484']."', 'user_groups.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('WC', 'wl_cats.gif', '".$locale['485']."', 'weblink_cats.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('W', 'wl.gif', '".$locale['486']."', 'weblinks.php', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S1', 'settings.gif', '".$locale['487']."', 'settings_main.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S2', 'settings_time.gif', '".$locale['488']."', 'settings_time.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S3', 'settings_forum.gif', '".$locale['489']."', 'settings_forum.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S4', 'registration.gif', '".$locale['490']."', 'settings_registration.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S5', 'photoalbums.gif', '".$locale['491']."', 'settings_photo.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S6', 'settings_misc.gif', '".$locale['492']."', 'settings_misc.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S7', 'settings_pm.gif', '".$locale['493']."', 'settings_messages.php', 3)");

		$result = dbquery("INSERT INTO ".$db_prefix."users (user_name, user_password, user_email, user_hide_email, user_location, user_birthdate, user_aim, user_icq, user_msn, user_yahoo, user_web, user_theme, user_offset, user_avatar, user_sig, user_posts, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status) VALUES ('$username', md5('$password1'), '$email', '1', '', '0000-00-00', '', '', '', '', '', 'Default', '0', '', '', '0', '".time()."', '0', '0.0.0.0', 'A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.NC.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC', '', '103', '0')");

		$result = dbquery("INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('0', '0', '1', '20', '20', '20')");
	
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['540']."', 'bugs.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['541']."', 'downloads.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['542']."', 'games.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['543']."', 'graphics.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['544']."', 'hardware.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['545']."', 'journal.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['546']."', 'members.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['547']."', 'mods.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['548']."', 'movies.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['549']."', 'network.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['550']."', 'news.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['551']."', 'php-fusion.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['552']."', 'security.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['553']."', 'software.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['554']."', 'themes.gif')");
		$result = dbquery("INSERT INTO ".$db_prefix."news_cats (news_cat_name, news_cat_image) VALUES ('".$locale['555']."', 'windows.gif')");

		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['520']."', 'navigation_panel', '', '1', '1', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['521']."', 'online_users_panel', '', '1', '2', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['522']."', 'forum_threads_panel', '', '1', '3', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['523']."', 'latest_articles_panel', '', '1', '4', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['524']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['525']."', 'forum_threads_list_panel', '', '2', '2', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['526']."', 'user_info_panel', '', '3', 1, 'file', '0', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['527']."', 'member_poll_panel', '', '3', '2', 'file', '0', '0', '0')");
		$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['528']."', 'shoutbox_panel', '', '3', '3', 'file', '0', '0', '1')");

		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['500']."', 'index.php', '0', '2', '0', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['501']."', 'articles.php', '0', '2', '0', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['502']."', 'downloads.php', '0', '2', '0', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['503']."', 'faq.php', '0', '1', '0', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['504']."', 'forum/index.php', '0', '2', '0', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['507']."', 'weblinks.php', '0', '2', '0', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['494']."', 'news_cats.php', '0', '2', '0', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['505']."', 'contact.php', '0', '1', '0', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['508']."', 'photogallery.php', '0', '1', '0', '9')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['509']."', 'search.php', '0', '1', '0', '10')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('---', '---', '101', '1', '0', '11')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['510']."', 'submit.php?stype=l', '101', '1', '0', '12')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['511']."', 'submit.php?stype=n', '101', '1', '0', '13')");
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['512']."', 'submit.php?stype=a', '101', '1', '0', '14')");
		
		echo "<tr>\n<td align='center' class='tbl1'>\n".$locale['580']."\n</td>\n</tr>\n";
	}
}

echo "</table>\n</body>\n</html>\n";
?>