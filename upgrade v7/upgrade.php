<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: upgrade.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";

if (file_exists(LOCALE.LOCALESET."admin/upgrade.php")) {
	include LOCALE.LOCALESET."admin/upgrade.php";
} else {
	include LOCALE."English/admin/upgrade.php";
}

if (!checkrights("U") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

opentable($locale['400']);
echo "<div style='text-align:center'><br />\n";
echo "<form name='upgradeform' method='post' action='".FUSION_SELF.$aidlink."'>\n";

if ((str_replace(".", "", $settings['version']) != "70100") || (str_replace(".", "", $settings['version']) < "70100")) {
	if (!isset($_POST['stage'])) {
		echo sprintf($locale['500'], $locale['503'])."<br />\n".$locale['501']."<br /><br />\n";
		echo "<input type='hidden' name='stage' value='2'>\n";
		echo "<input type='submit' name='upgrade' value='".$locale['400']."' class='button'><br /><br />\n";
	} elseif (isset($_POST['upgrade']) && isset($_POST['stage']) && $_POST['stage'] == 2) {
		$result = dbquery("CREATE TABLE ".$db_prefix."sessions (
		session_id varchar(32) NOT NULL,
		session_started int(10) unsigned NOT NULL default '0',
		session_expire int(10) unsigned NOT NULL default '0',
		session_ip varchar(20) NOT NULL,
		session_data text NOT NULL
		) ENGINE=MyISAM;");

		$result = dbquery("CREATE TABLE ".$db_prefix."user_field_cats (
		field_cat_id MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		field_cat_name VARCHAR(200) NOT NULL ,
		field_cat_order SMALLINT(5) UNSIGNED NOT NULL ,
		PRIMARY KEY (field_cat_id) 
		) ENGINE=MyISAM;");
				
		$result = dbquery("ALTER TABLE ".DB_BLACKLIST." ADD blacklist_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER blacklist_id");
		$result = dbquery("ALTER TABLE ".DB_BLACKLIST." ADD blacklist_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER blacklist_reason");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_file VARCHAR(100) NOT NULL DEFAULT '' AFTER download_url");
		$result = dbquery("ALTER TABLE ".DB_NEWS." ADD news_image VARCHAR(100) NOT NULL DEFAULT '' AFTER news_cat");
		$result = dbquery("ALTER TABLE ".DB_NEWS." ADD news_image_t1 VARCHAR(100) NOT NULL DEFAULT '' AFTER news_image");
		$result = dbquery("ALTER TABLE ".DB_NEWS." ADD news_image_t2 VARCHAR(100) NOT NULL DEFAULT '' AFTER news_image_t1");
		$result = dbquery("ALTER TABLE ".DB_USER_FIELDS." CHANGE field_group field_cat MEDIUMINT(8) UNSIGNED DEFAULT '1'");
		$result = dbquery("ALTER TABLE ".DB_USERS." ADD user_actiontime INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER user_status");
		$result = dbquery("ALTER TABLE ".DB_USERS." CHANGE user_offset user_offset CHAR(5) DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".DB_COMMENTS." ADD comment_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_SHOUTBOX." ADD shout_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_THREADS." ADD thread_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_POSTS." ADD post_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");

		$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (1, '".$locale['420']."', 1)");
		$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (2, '".$locale['421']."', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (3, '".$locale['422']."', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."user_field_cats (field_cat_id, field_cat_name, field_cat_order) VALUES (4, '".$locale['423']."', 4)");
		
		$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_level='103'");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".DB_USERS." SET user_rights='".$data['user_rights'].".UFC.S8.S9.S10.S11.S12' WHERE user_id='".$data['user_id']."'");
		}
		
		// Move infusions to admin page 5 and settings to page 4
		$result = dbquery("UPDATE ".DB_ADMIN." SET admin_page='5' WHERE admin_page='4'");
		for ($i = 1; $i < 13; $i++) {
			$result = dbquery("UPDATE ".DB_ADMIN." SET admin_page='4' WHERE admin_rights='S$i'");
		}

		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UFC', 'user_fields.gif', '".$locale['430']."', 'user_field_cats.php', 2)");
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S8', 'settings_news.gif', '".$locale['436']."', 'settings_news.php', '4')");
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S9', 'members.gif', '".$locale['432']."', 'settings_users.php', '4')");
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S10', 'settings_ipp.gif', '".$locale['434']."', 'settings_ipp.php', '4')");
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S11', 'settings_dl.gif', '".$locale['437']."', 'settings_dl.php', '4')");
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('S12', 'security.gif', '".$locale['435']."', 'settings_security.php', '4')");
		$result = dbquery("UPDATE ".DB_ADMIN." SET admin_image='settings_users.gif' WHERE admin_link='settings_users.php'");
		$result = dbquery("UPDATE ".DB_ADMIN." SET admin_image='user_fields_cats.gif' WHERE admin_link='user_field_cats.php'");

		$result = dbquery("UPDATE ".DB_SETTINGS." SET version='7.01.06'");
		$result = dbquery("UPDATE ".DB_SETTINGS." SET attachtypes='.gif,.jpg,.png,.zip,.rar,.tar'");

		// Settings table update!
		$settings = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));
		
		// Create new settings table
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings2");
		$result = dbquery("CREATE TABLE ".$db_prefix."settings2 (
		settings_name VARCHAR(200) NOT NULL DEFAULT '',
		settings_value TEXT NOT NULL,
		PRIMARY KEY (settings_name)
		) ENGINE=MyISAM");
		
		// Insert settings as rows
		foreach ($settings as $key => $value) {
			$result = dbquery("INSERT INTO ".$db_prefix."settings2 (settings_name, settings_value) VALUES ('$key', '$value')");
		}
		
		// Drop old settings table
		$result = dbquery("DROP TABLE ".DB_SETTINGS);
		
		// Rename new settings table
		$result = dbquery("RENAME TABLE ".$db_prefix."settings2 TO ".DB_SETTINGS);
		
		// Create suspends table for suspension log
		$result = dbquery("CREATE TABLE ".$db_prefix."suspends (
		suspend_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		suspended_user MEDIUMINT(8) UNSIGNED NOT NULL,
		suspending_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
		suspend_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
		suspend_date INT(10) NOT NULL DEFAULT '0',
		suspend_reason TEXT NOT NULL,
		suspend_type TINYINT(1) NOT NULL DEFAULT '0',
		reinstating_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
		reinstate_reason TEXT NOT NULL,
		reinstate_date INT(10) NOT NULL DEFAULT '0',
		reinstate_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
		PRIMARY KEY (suspend_id)
		) ENGINE=MyISAM");
		
		// Create infusions settings table
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings_inf");
		$result = dbquery("CREATE TABLE ".$db_prefix."settings_inf (
		settings_name VARCHAR(200) NOT NULL DEFAULT '',
		settings_value TEXT NOT NULL,
		settings_inf VARCHAR(200) NOT NULL DEFAULT '',
		PRIMARY KEY (settings_name)
		) ENGINE=MyISAM");

		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('login_method', 'cookies')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('deactivation_response', '14')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('deactivation_period', '365')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('enable_deactivation', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('comments_enabled', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('ratings_enabled', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('hide_userprofiles', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_thumb_ratio', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_image_link', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_thumb_w', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_thumb_h', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_photo_max_w', '1800')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_photo_max_h', '1600')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_photo_max_b', '150000')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('newsdate', '%B %d %Y')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('download_max_b', '15000')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('download_types', '.zip,.rar,.tar,.bz2')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('articles_per_page', '15')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('downloads_per_page', '15')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('links_per_page', '15')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('avatar_filesize', '15000')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('avatar_width', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('avatar_height', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('avatar_ratio', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('smtp_port', '25')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('cronjob_day', '".time()."')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('cronjob_hour', '".time()."')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('flood_autoban', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('visitorcounter_enabled', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('popular_threads_timeframe', '604800')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('maintenance_level', '102')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_photo_w', '400')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_photo_h', '300')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_image_frontpage', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('news_image_readmore', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('deactivation_action', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('serveroffset', '0.0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('default_timezone', 'Europe/London')");
		$result = dbquery("DELETE FROM ".DB_SETTINGS." WHERE settings_name='validation_method'");

		echo $locale['502']."<br /><br />\n";
	}
} else {
	echo $locale['401']."<br /><br />\n";
}
	
echo "</form>\n</div>\n";
closetable();

require_once THEMES."templates/footer.php";
?>