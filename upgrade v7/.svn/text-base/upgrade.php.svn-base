<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: upgrade.php
| Author: Marcus Gottschalk (MarcusG)
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

if ((str_replace(".", "", $settings['version']) != "70200") || (str_replace(".", "", $settings['version']) < "70200")) {
	if (!isset($_POST['stage'])) {
		echo sprintf($locale['500'], $locale['503'])."<br />\n".$locale['501']."<br /><br />\n";
		echo "<input type='hidden' name='stage' value='2'>\n";
		echo "<input type='submit' name='upgrade' value='".$locale['400']."' class='button'><br /><br />\n";
	} elseif (isset($_POST['upgrade']) && isset($_POST['stage']) && $_POST['stage'] == 2) {

		$result = dbquery("CREATE TABLE ".$db_prefix."user_field_cats (
		field_cat_id MEDIUMINT( 8 ) UNSIGNED NOT NULL AUTO_INCREMENT ,
		field_cat_name VARCHAR(200) NOT NULL ,
		field_cat_order SMALLINT(5) UNSIGNED NOT NULL ,
		PRIMARY KEY (field_cat_id)
		) ENGINE=MYISAM;");

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

		// Settings table update!
		$settings = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

		// Create new settings table
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings2");
		$result = dbquery("CREATE TABLE ".$db_prefix."settings2 (
		settings_name VARCHAR(200) NOT NULL DEFAULT '',
		settings_value TEXT NOT NULL,
		PRIMARY KEY (settings_name)
		) ENGINE=MYISAM;");

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
		suspend_ip VARCHAR(45) NOT NULL default '',
		suspend_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
		suspend_date INT(10) NOT NULL DEFAULT '0',
		suspend_reason TEXT NOT NULL,
		suspend_type TINYINT(1) NOT NULL DEFAULT '0',
		reinstating_admin MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1',
		reinstate_reason TEXT NOT NULL,
		reinstate_date INT(10) NOT NULL DEFAULT '0',
		reinstate_ip VARCHAR(45) NOT NULL default '',
		reinstate_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
		PRIMARY KEY (suspend_id)
		) ENGINE=MYISAM;");

		// Create infusions settings table
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."settings_inf");
		$result = dbquery("CREATE TABLE ".$db_prefix."settings_inf (
		settings_name VARCHAR(200) NOT NULL DEFAULT '',
		settings_value TEXT NOT NULL,
		settings_inf VARCHAR(200) NOT NULL DEFAULT '',
		PRIMARY KEY (settings_name)
		) ENGINE=MYISAM;");

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

		// New settings
		$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('captcha', 'securimage2')");
		$siteurl = getCurrentURL();
		$url = parse_url($siteurl);
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('site_protocol', '".$url['scheme']."')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('site_host', '".$url['host']."')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('site_port', '".(isset($url['port']) ? $url['port'] : "")."')");
		$url['path'] = str_replace("administration/", "", $url['path']);
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('site_path', '".(isset($url['path']) ? $url['path'] : "/")."')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('password_algorithm', 'sha256')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('comments_per_page', '10')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('comments_sorting', 'ASC')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('rendertime_enabled', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('forum_last_posts_reply', '10')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('attachmax_count', '5')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_screen_max_b', '150000')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_screen_max_w', '1024')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_screen_max_h', '768')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('userNameChange', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('recaptcha_public', '')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('recaptcha_private', '')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('recaptcha_theme', 'red')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('forum_edit_timelimit', '0')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_screenshot', '1')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_thumb_max_w', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('download_thumb_max_h', '100')");
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('forum_editpost_to_lastpost', '1')");
		$result = dbquery("DELETE FROM ".DB_SETTINGS." WHERE settings_name='news_style'");

		// Add robots.txt Admin
		$result = dbquery("INSERT INTO ".DB_ADMIN." (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('ROB', 'robots.gif', '".$locale['442']."', 'robots.php', '3')");

		// Add User Log Admin
		$result = dbquery("INSERT INTO ".DB_ADMIN." SET admin_rights='UL', admin_image='user_fields.gif', admin_title='".$locale['443']."', admin_link='user_log.php', admin_page='2'");

		// Add link to Download Submission
		$result = dbquery("INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['440']."', 'submit.php?stype=d', '101', '1', '0', '16')");

		// Add Password Reset Admin
		$result = dbquery("DELETE FROM ".DB_ADMIN." WHERE admin_link='../infusions/admin_reset/index.php'");
		$reqult = dbquery("DELETE FROM ".DB_INFUSIONS." WHERE inf_folder='admin_reset'");

		$result = dbquery(
			"INSERT INTO ".DB_ADMIN."
				(admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES
				('APWR', 'admin_pass.gif', '".$locale['444']."', 'admin_reset.php', '2'),
				('ERRO', 'errors.gif', '".$locale['445']."', 'errors.php', '3')"
			);

		// Update all user_rights from above
		if ($result) {
			$result = dbquery("SELECT user_id, user_rights FROM ".DB_USERS." WHERE user_level='103'");
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".DB_USERS." SET user_rights='".$data['user_rights'].".APWR.ERRO.ROB.RS.UL' WHERE user_id='".$data['user_id']."'");
			}
		}

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."admin_resetlog");
		$result = dbquery("CREATE TABLE ".$db_prefix."admin_resetlog (
		reset_id mediumint(8) unsigned NOT NULL auto_increment,
		reset_admin_id mediumint(8) unsigned NOT NULL default '1',
		reset_timestamp int(10) unsigned NOT NULL default '0',
		reset_sucess text NOT NULL,
		reset_failed text NOT NULL,
		reset_admins varchar(8) NOT NULL default '0',
		reset_reason varchar(256) NOT NULL,
		PRIMARY KEY (reset_id)
		) ENGINE=MYISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."errors");
		$result = dbquery("CREATE TABLE ".$db_prefix."errors (
		error_id mediumint(8) unsigned NOT NULL auto_increment,
		error_level smallint(5) unsigned NOT NULL,
		error_message text NOT NULL,
		error_file varchar(300) NOT NULL,
		error_line smallint(5) NOT NULL,
		error_page varchar(200) NOT NULL,
		error_user_level smallint(3) NOT NULL,
		error_user_ip VARCHAR(45) NOT NULL default '',
		error_user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4',
		error_status tinyint(1) NOT NULL default '0',
		error_timestamp int(10) NOT NULL,
		PRIMARY KEY (error_id)
		) ENGINE=MYISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_log");
		$result = dbquery("CREATE TABLE ".$db_prefix."user_log (
		userlog_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		userlog_user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
		userlog_field VARCHAR(50) NOT NULL DEFAULT '',
		userlog_value_new TEXT NOT NULL DEFAULT '',
		userlog_value_old TEXT NOT NULL DEFAULT '',
		userlog_timestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (userlog_id),
		KEY userlog_user_id (userlog_user_id),
		KEY userlog_field (userlog_field)
		) ENGINE=MYISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."email_verify");
		$result = dbquery("CREATE TABLE ".$db_prefix."email_verify (
		user_id MEDIUMINT(8) NOT NULL,
		user_code VARCHAR(32) NOT NULL,
		user_email VARCHAR(100) NOT NULL,
		user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
		KEY user_datestamp (user_datestamp)
		) ENGINE=MYISAM;");

		// Add indexes
		$result = dbquery("ALTER TABLE ".DB_ARTICLES." ADD INDEX (article_cat)");
		$result = dbquery("ALTER TABLE ".DB_ARTICLE_CATS." ADD INDEX (article_cat_access)");

		// Add fields
		$result = dbquery("ALTER TABLE ".DB_USER_FIELDS." ADD field_required TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER field_cat");
		$result = dbquery("ALTER TABLE ".DB_NEW_USERS." ADD user_name VARCHAR(30) NOT NULL AFTER user_code");
		$result = dbquery("ALTER TABLE ".DB_FORUM_RANKS." ADD rank_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER rank_posts");
		$result = dbquery("UPDATE ".DB_FORUM_RANKS." SET rank_type='1' WHERE rank_posts='0' and rank_apply>101");
		$result = dbquery("ALTER TABLE ".DB_FORUM_ATTACHMENTS." ADD attach_count INT(10) UNSIGNED NOT NULL DEFAULT '0' AFTER attach_size");
		$result = dbquery("ALTER TABLE ".DB_PANELS." ADD (panel_url_list TEXT NOT NULL DEFAULT '', panel_restriction TINYINT(1) UNSIGNED NOT NULL DEFAULT '0')");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_user MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '1' AFTER download_id");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_homepage VARCHAR(100) NOT NULL DEFAULT '' AFTER download_user");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_description_short VARCHAR(500) NOT NULL AFTER download_title");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_image VARCHAR(100) NOT NULL DEFAULT '' AFTER download_description");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_image_thumb VARCHAR(100) NOT NULL DEFAULT '' AFTER download_image");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_copyright VARCHAR(250) NOT NULL DEFAULT '' AFTER download_license");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_allow_comments TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER download_count");
		$result = dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD download_allow_ratings TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER download_allow_comments");
		$result = dbquery("ALTER TABLE ".DB_USER_FIELDS." ADD field_log TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_POSTS." ADD post_editreason TEXT NOT NULL AFTER post_edittime");
		$result = dbquery("ALTER TABLE ".DB_POSTS." ADD post_locked TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_FORUMS." ADD forum_merge TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".DB_FORUMS." ADD forum_attach_download SMALLINT(3) UNSIGNED NOT NULL DEFAULT '0' AFTER forum_attach");

		// New user password fields (algo, and salt)
		$result = dbquery("ALTER TABLE ".DB_USERS." ADD user_algo VARCHAR( 10 ) NOT NULL DEFAULT 'md5' AFTER user_name ,
			ADD user_salt VARCHAR(40) NOT NULL AFTER user_algo");
		$result = dbquery("ALTER TABLE ".DB_USERS." ADD user_admin_algo VARCHAR( 10 ) NOT NULL DEFAULT 'md5' AFTER user_password ,
			ADD user_admin_salt VARCHAR(40) NOT NULL AFTER user_admin_algo");
		$result = dbquery("ALTER TABLE ".DB_USERS." CHANGE user_password user_password VARCHAR(64) NOT NULL");
		$result = dbquery("ALTER TABLE ".DB_USERS." CHANGE user_admin_password user_admin_password VARCHAR(64) NOT NULL");
		$result = dbquery("ALTER TABLE ".DB_NEW_USERS." CHANGE user_code user_code VARCHAR(40) NOT NULL");

		// IPv6 support
		$result = dbquery("ALTER TABLE ".DB_BLACKLIST." CHANGE blacklist_ip blacklist_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_CAPTCHA." CHANGE captcha_ip captcha_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_COMMENTS." CHANGE comment_ip comment_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_FLOOD_CONTROL." CHANGE flood_ip flood_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_FORUM_POLL_VOTERS." CHANGE forum_vote_user_ip forum_vote_user_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_RATINGS." CHANGE rating_ip rating_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_ONLINE." CHANGE online_ip online_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_POSTS." CHANGE post_ip post_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".DB_USERS." CHANGE user_ip user_ip VARCHAR(45) NOT NULL default '0.0.0.0'");
		$result = dbquery("ALTER TABLE ".DB_BLACKLIST." ADD blacklist_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER blacklist_ip, ADD KEY (blacklist_ip_type)");
		$result = dbquery("ALTER TABLE ".DB_CAPTCHA." ADD captcha_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER captcha_ip");
		$result = dbquery("ALTER TABLE ".DB_COMMENTS." ADD comment_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER comment_ip");
		$result = dbquery("ALTER TABLE ".DB_FLOOD_CONTROL." ADD flood_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER flood_ip");
		$result = dbquery("ALTER TABLE ".DB_FORUM_POLL_VOTERS." ADD forum_vote_user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER forum_vote_user_ip");
		$result = dbquery("ALTER TABLE ".DB_RATINGS." ADD rating_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER rating_ip");
		$result = dbquery("ALTER TABLE ".DB_ONLINE." ADD online_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER online_ip");
		$result = dbquery("ALTER TABLE ".DB_POSTS." ADD post_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER post_ip");
		$result = dbquery("ALTER TABLE ".DB_USERS." ADD user_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER user_ip");

		// Drop Shoutbox from the core, add it as an in fusion!
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox CHANGE shout_ip shout_ip VARCHAR(45) NOT NULL default ''");
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox ADD shout_ip_type TINYINT(1) UNSIGNED NOT NULL DEFAULT '4' AFTER shout_ip");
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox ADD shout_hidden TINYINT(1) UNSIGNED NOT NULL DEFAULT '0'");
		$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS." WHERE settings_name='numofshouts' OR settings_name='guestposts'");
		while ($data = dbarray($result)) {
			$settings2[$data['settings_name']] = $data['settings_value'];
		}
		$result = dbquery("INSERT INTO ".$db_prefix."settings_inf (settings_name, settings_value, settings_inf) VALUES ('visible_shouts', '".$settings2['numofshouts']."', 'shoutbox_panel')");
		$result = dbquery("INSERT INTO ".$db_prefix."settings_inf (settings_name, settings_value, settings_inf) VALUES ('guest_shouts', '".$settings2['guestposts']."', 'shoutbox_panel')");
		$folder = INFUSIONS."shoutbox_panel/shoutbox_admin.php";
		$result = dbquery("INSERT INTO ".DB_INFUSIONS." (inf_title, inf_folder, inf_version) VALUES ('".$locale['441']."', '".$folder."', '1.00')");
		$result = dbquery("UPDATE ".DB_ADMIN." SET admin_link='".$folder."', admin_page='5' WHERE admin_rights='S'");

		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='7.02.01' WHERE settings_name='version'");
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='.gif,.jpg,.png,.zip,.rar,.tar' WHERE settings_name='attachtypes'");

		echo $locale['502']."\n";
		echo "<div style='width:400px; margin:20px auto;' class='tbl'>\n";
		echo "Please replace these lines with the lines in your config.php!<br />\n";
		echo "<div class='tbl-border' style='margin-top:10px; padding: 5px; text-align:left;'>";
		echo "&lt;?php<br />\n";
		echo "// database settings<br />\n";
		echo '$db_host = "'.$db_host.'";<br />';
		echo '$db_user = "'.$db_user.'";<br />';
		echo '$db_pass = "'.$db_pass.'";<br />';
		echo '$db_name = "'.$db_name.'";<br />';
		echo '$db_prefix = "'.DB_PREFIX.'";<br />';
		echo 'define("DB_PREFIX", "'.DB_PREFIX.'");<br />';
		echo 'define("COOKIE_PREFIX", "fusion'.createRandomPrefix().'_");<br />';
		echo "?>";
		echo "</div></div>";
	}
} else {
	echo $locale['401']."<br /><br />\n";
}

echo "</form>\n</div>\n";
closetable();

// Get Current URL
function getCurrentURL() {
	$s = empty($_SERVER["HTTPS"]) ? "" : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.(str_replace(basename(cleanurl($_SERVER['PHP_SELF'])), "", $_SERVER['REQUEST_URI']));
}

function strleft($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}

function createRandomPrefix ($length = 5) {
	$chars = array("abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ", "123456789");
	$count = array((strlen($chars[0]) - 1), (strlen($chars[1]) - 1));
	$prefix = "";
	for ($i = 0; $i < $length; $i++) {
		$type = mt_rand(0, 1);
		$prefix .= substr($chars[$type], mt_rand(0, $count[$type]), 1);
	}
	return $prefix;
}

require_once THEMES."templates/footer.php";
?>