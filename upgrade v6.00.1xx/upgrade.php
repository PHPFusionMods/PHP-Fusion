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
if (str_replace(".","",$settings['version']) < 600300) {
	if (!isset($_POST['stage'])) {
		echo "<form name='upgradeform' method='post' action='upgrade.php'>
<div align='center'>
A minor database upgrade is available for this installation of PHP-Fusion.<br>
Simply click Upgrade to update your system.<br><br>
<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>
</div>
</form>\n";
	}
	if (isset($_POST['stage']) && $_POST['stage'] == 2) {
		if (isset($_POST['upgrade'])) {
			if (str_replace(".","",$settings['version']) < 600200) {
				$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."news_cats");
				$result = dbquery("CREATE TABLE ".$db_prefix."news_cats (
				news_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
				news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
				news_cat_image VARCHAR(100) NOT NULL DEFAULT '',
				PRIMARY KEY (news_cat_id)
				) TYPE=MyISAM;");
				$result = dbquery("ALTER TABLE ".$db_prefix."news DROP news_cat");
				$result = dbquery("ALTER TABLE ".$db_prefix."faq_cats ADD faq_cat_description VARCHAR(250) DEFAULT '' NOT NULL AFTER faq_cat_name");
				$result = dbquery("ALTER TABLE ".$db_prefix."news ADD news_cat SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL AFTER news_subject");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD news_style TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER opening_page");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD db_backup_folder VARCHAR(200) DEFAULT 'db_backups/' NOT NULL AFTER album_max_b");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD tinymce_enabled TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER db_backup_folder");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD smtp_host VARCHAR(200) DEFAULT '' NOT NULL AFTER tinymce_enabled");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD smtp_username VARCHAR(100) DEFAULT '' NOT NULL AFTER smtp_host");
				$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD smtp_password VARCHAR(100) DEFAULT '' NOT NULL AFTER smtp_username");
				$result = dbquery("ALTER TABLE ".$db_prefix."article_cats ADD article_cat_sorting VARCHAR(50) DEFAULT 'article_subject ASC' NOT NULL AFTER article_cat_description");
				$result = dbquery("ALTER TABLE ".$db_prefix."download_cats ADD download_cat_sorting VARCHAR(50) DEFAULT 'download_title ASC' NOT NULL AFTER download_cat_description");
				$result = dbquery("ALTER TABLE ".$db_prefix."weblink_cats ADD weblink_cat_sorting VARCHAR(50) DEFAULT 'weblink_name ASC' NOT NULL AFTER weblink_cat_description");
				$result = dbquery("INSERT INTO ".$db_prefix."admin VALUES('', 'NC', 'news_cats.gif', 'News Categories', 'news_cats.php', '1')");
				$result = dbquery("ALTER TABLE ".$db_prefix."downloads CHANGE download_description download_description TEXT NOT NULL");
				$result = dbquery("ALTER TABLE ".$db_prefix."weblinks CHANGE weblink_description weblink_description TEXT NOT NULL");
				$result = dbquery("ALTER TABLE ".$db_prefix."panels ADD panel_display TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER panel_access");
				$result = dbquery("ALTER TABLE ".$db_prefix."photos CHANGE photo_title photo_title VARCHAR(100) DEFAULT '' NOT NULL");
				$result = dbquery("ALTER TABLE ".$db_prefix."photo_albums CHANGE album_title album_title VARCHAR(100) DEFAULT '' NOT NULL");
				$result = dbquery("SELECT weblink_id,weblink_description FROM ".$db_prefix."weblinks");
				while ($data = dbarray($result)) {
					$weblink_description = parseubb(stripslashes($data['weblink_description']));
					$weblink_description = addslash($weblink_description);
					$result2 = dbquery("UPDATE ".$db_prefix."weblinks SET weblink_description='$weblink_description' WHERE weblink_id='".$data['weblink_id']."'");
				}
				$result = dbquery("SELECT download_id,download_description FROM ".$db_prefix."downloads");
				while ($data = dbarray($result)) {
					$download_description = parseubb(stripslashes($data['download_description']));
					$download_description = addslash($download_description);
					$result2 = dbquery("UPDATE ".$db_prefix."downloads SET download_description='$download_description' WHERE download_id='".$data['download_id']."'");
				}
				$result = dbquery("SELECT faq_id,faq_answer FROM ".$db_prefix."faqs");
				while ($data = dbarray($result)) {
					$faq_answer = parseubb(stripslashes($data['faq_answer']));
					$faq_answer = addslash($faq_answer);
					$result2 = dbquery("UPDATE ".$db_prefix."faqs SET faq_answer='$faq_answer' WHERE faq_id='".$data['faq_id']."'");
				}
				$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level='103'");
				while ($data = dbarray($result)) {
					$user_rights = $data['user_rights'].".NC";
					$result2 = dbquery("UPDATE ".$db_prefix."users SET user_rights='$user_rights' WHERE user_id='".$data['user_id']."'");
				}
				$result = dbquery("UPDATE ".$db_prefix."settings SET version='6.00.307'");
			}
			echo "<div align='center'><br>\nDatabase upgrade complete.<br><br>\n</div>\n";
		}
	}
} else {
	echo "<div align='center'><br>\nThere is no database upgrade available.<br><br>\n</div>\n";
}
closetable();

require_once BASEDIR."footer.php";
?>