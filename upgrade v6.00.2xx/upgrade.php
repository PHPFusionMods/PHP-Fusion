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
if (str_replace(".","",$settings['version']) < "601019") {
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
			$result = dbquery("ALTER TABLE ".$db_prefix."panels ADD panel_display TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER panel_access");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD news_style TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER opening_page");
			$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."vcode");
			$result = dbquery("CREATE TABLE ".$db_prefix."captcha (
			captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
		  captcha_ip varchar(20) NOT NULL,
			captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
			captcha_string VARCHAR(15) NOT NULL DEFAULT ''
			) TYPE=MyISAM;");
			// Create new flood_control table
			$result = dbquery("CREATE TABLE ".$db_prefix."flood_control (
			flood_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			flood_timestamp INT(5) UNSIGNED NOT NULL DEFAULT '0'
			) TYPE=MyISAM;");
			// Create new thread notify table
			$result = dbquery("CREATE TABLE ".$db_prefix."thread_notify (
			thread_id smallint(5) unsigned NOT NULL default '0',
			notify_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			notify_user smallint(5) unsigned NOT NULL default '0',
			notify_status tinyint(1) unsigned NOT NULL default '1'
			) TYPE=MyISAM;");
			// Rename photo_albums and photos tables
			$result = dbquery("ALTER TABLE ".$db_prefix."photo_albums RENAME ".$db_prefix."old_photo_albums");
			$result = dbquery("ALTER TABLE ".$db_prefix."photos RENAME ".$db_prefix."old_photos");
			// Create new photo_albums table
			$result = dbquery("CREATE TABLE ".$db_prefix."photo_albums (
			album_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
			album_title VARCHAR(100) NOT NULL DEFAULT '',
			album_description TEXT NOT NULL,
			album_thumb VARCHAR(100) NOT NULL DEFAULT '',
			album_user SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			album_access SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			album_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			album_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (album_id)
			) TYPE=MyISAM;");
			// Create new photos table			
			$result = dbquery("CREATE TABLE ".$db_prefix."photos (
			photo_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
			album_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			photo_title VARCHAR(100) NOT NULL DEFAULT '',
			photo_description TEXT NOT NULL,
			photo_filename VARCHAR(100) NOT NULL DEFAULT '',
			photo_thumb1 VARCHAR(100) NOT NULL DEFAULT '',
			photo_thumb2 VARCHAR(100) NOT NULL DEFAULT '',
			photo_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			photo_user SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			photo_views SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			photo_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			photo_allow_comments tinyint(1) unsigned NOT NULL default '1',
			photo_allow_ratings tinyint(1) unsigned NOT NULL default '1',
			PRIMARY KEY (photo_id)
			) TYPE=MyISAM;");
			// Drop old photo gallery settings
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_image_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_image_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP thumb_image_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP thumb_image_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP thumb_compression");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_comments");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP albums_per_row");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP albums_per_page");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP thumbs_per_row");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP thumbs_per_page");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_max_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_max_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP album_max_b");
			// Create new photo gallery settings
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thumb_w SMALLINT(3) UNSIGNED DEFAULT '100' NOT NULL AFTER validation_method");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thumb_h SMALLINT(3) UNSIGNED DEFAULT '100' NOT NULL AFTER thumb_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_w SMALLINT(4) UNSIGNED DEFAULT '400' NOT NULL AFTER thumb_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_h SMALLINT(4) UNSIGNED DEFAULT '300' NOT NULL AFTER photo_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_max_w SMALLINT(4) UNSIGNED DEFAULT '1800' NOT NULL AFTER photo_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_max_h SMALLINT(4) UNSIGNED DEFAULT '1600' NOT NULL AFTER photo_max_w");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_max_b INT(10) UNSIGNED DEFAULT '150000' NOT NULL AFTER photo_max_h");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL AFTER photo_max_b");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thumbs_per_row SMALLINT(2) UNSIGNED DEFAULT '4' NOT NULL AFTER thumb_compression");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thumbs_per_page SMALLINT(2) UNSIGNED DEFAULT '12' NOT NULL AFTER thumbs_per_row");
			// Change lower center panel value to 3, right panel value to 4
			$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='5' WHERE panel_side='4'");
			$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='4' WHERE panel_side='3'");
			$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3' WHERE panel_side='5'");
			// Reformat forum attachment's attach_name
			$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments");
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					$attachname = $data['attach_name']."[".$data['attach_id']."]".$data['attach_ext'];
					$result2 = dbquery("UPDATE ".$db_prefix."forum_attachments SET attach_name='$attachname' WHERE attach_id='".$data['attach_id']."'");
				}
			}
			// Various updates
			$result = dbquery("UPDATE ".$db_prefix."comments SET comment_type='X' WHERE comment_type='P'");
			$result = dbquery("ALTER TABLE ".$db_prefix."news ADD news_sticky TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER news_reads");
			$result = dbquery("ALTER TABLE ".$db_prefix."posts ADD INDEX (thread_id)");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD thread_notify TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER attachtypes");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings DROP db_backup_folder");
			$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD flood_interval TINYINT(2) UNSIGNED DEFAULT '15' NOT NULL AFTER numofshouts");
			// Set version number
			$result = dbquery("UPDATE ".$db_prefix."settings SET version='6.01.19'");
			echo "<div align='center'><br>\nDatabase upgrade complete.<br><br>\n</div>\n";
		}
	}
} else {
	echo "<div align='center'><br>\nThere is no database upgrade available.<br><br>\n</div>\n";
}
closetable();

require_once BASEDIR."footer.php";
?>