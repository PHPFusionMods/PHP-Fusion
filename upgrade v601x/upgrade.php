<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: upgrade.php
| CVS Version: 1.03
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
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";

if (!checkrights("U")) { fallback("../index.php"); }

opentable("Upgrade");
if (version_compare("7.00", $settings['version'], ">")) {
	if (!isset($_GET['stage'])) {
		echo "<div align='center'>\n<form name='upgradeform' method='post' action='".FUSION_SELF."?stage=1'>\n";
		echo "A database upgrade is available for this installation of PHP-Fusion.<br>\n";
		echo "This upgrade is performed in four stages. You must complete<br>\n";
		echo "all of the stages, failure to fully complete this upgrade may cause<br>";
		echo "PHP-Fuion to malfunction.<br><br>Click Upgrade to begin the upgrade.<br><br>\n";
		echo "<input type='hidden' name='stage' value='2'>\n";
		echo "<input type='submit' name='upgrade' value='Upgrade' class='button'>\n";
		echo "</form>\n</div>\n";
	}
	if (isset($_GET['stage']) && $_GET['stage'] == 1) {
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."bbcodes");
		$result = dbquery("CREATE TABLE ".$db_prefix."bbcodes (
		bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		bbcode_name VARCHAR(20) NOT NULL DEFAULT '',
		bbcode_order SMALLINT(5) UNSIGNED NOT NULL,
		PRIMARY KEY (bbcode_id)
		) TYPE=MyISAM;");
		
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."smileys");
		$result = dbquery("CREATE TABLE ".$db_prefix."smileys (
		smiley_id MEDIUMINT(8) unsigned NOT NULL AUTO_INCREMENT,
		smiley_code VARCHAR(50) NOT NULL,
		smiley_image VARCHAR(100) NOT NULL,
		smiley_text VARCHAR(100) NOT NULL,
		PRIMARY KEY (smiley_id)
		) TYPE=MyISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_options");
		$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_options (
		thread_id MEDIUMINT(8) unsigned NOT NULL,
		forum_poll_option_id SMALLINT(5) UNSIGNED NOT NULL,
		forum_poll_option_text VARCHAR(150) NOT NULL,
		forum_poll_option_votes SMALLINT(5) UNSIGNED NOT NULL,
		KEY thread_id (thread_id)
		) TYPE=MyISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_poll_voters");
		$result = dbquery("CREATE TABLE ".$db_prefix."forum_poll_voters (
		thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
		forum_vote_user_id MEDIUMINT(8) UNSIGNED NOT NULL,
		forum_vote_user_ip VARCHAR(20) NOT NULL,
		KEY thread_id (thread_id,forum_vote_user_id)
		) TYPE=MyISAM;");

		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_polls");
		$result = dbquery("CREATE TABLE ".$db_prefix."forum_polls (
		thread_id MEDIUMINT(8) UNSIGNED NOT NULL,
		forum_poll_title VARCHAR(250) NOT NULL,
		forum_poll_start INT(10) UNSIGNED DEFAULT NULL,
		forum_poll_length iNT(10) UNSIGNED NOT NULL,
		forum_poll_votes SMALLINT(5) UNSIGNED NOT NULL,
		KEY thread_id (thread_id)
		) TYPE=MyISAM;");
		
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."forum_ranks");
		$result = dbquery("CREATE TABLE ".$db_prefix."forum_ranks (
		rank_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		rank_title VARCHAR(100) NOT NULL default '',
		rank_image VARCHAR(100) NOT NULL default '',
		rank_posts iNT(10) UNSIGNED NOT NULL DEFAULT '0',
		rank_apply SMALLINT(5) UNSIGNED NOT NULL DEFAULT '101',
		PRIMARY KEY (rank_id)
		) TYPE=MyISAM;");
		
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."captcha");
		$result = dbquery("CREATE TABLE ".$db_prefix."captcha (
		captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
		captcha_ip varchar(20) NOT NULL,
		captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
		captcha_string VARCHAR(15) NOT NULL DEFAULT ''
		) TYPE=MyISAM;");
		
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."user_fields");
		$result = dbquery("CREATE TABLE ".$db_prefix."user_fields (
		field_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
		field_name VARCHAR(50) NOT NULL,
		field_group SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
		field_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
		PRIMARY KEY (field_id)
		) TYPE=MyISAM;");
 			
		echo "<div align='center'>\n<form name='upgradeform' method='post' action='".FUSION_SELF."?stage=2'>\n";
		echo "Stage 1 of 4 Complete.<br><br>\n";
		echo "Click Next to continue.<br><br>\n";
		echo "<input type='submit' name='next' value='Next' class='button'>\n";
		echo "</form>\n</div>\n";
	} elseif (isset($_GET['stage']) && $_GET['stage'] == 2) {
		$result = dbquery("ALTER TABLE ".$db_prefix."comments DROP comment_smileys");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums CHANGE forum_posting forum_post SMALLINT(3) UNSIGNED DEFAULT '101'");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_reply SMALLINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER forum_post");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_attach SMALLINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER forum_reply");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_poll SMALLINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER forum_post");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_vote SMALLINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER forum_poll");
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD thread_poll TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER thread_lastuser");
		$result = dbquery("ALTER TABLE ".$db_prefix."posts DROP post_subject");
		
		$result = dbquery("ALTER TABLE ".$db_prefix."articles ADD article_draft TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER article_article");
		$result = dbquery("ALTER TABLE ".$db_prefix."news ADD news_draft TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER news_reads");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD sitebanner1 TEXT NOT NULL AFTER sitebanner");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD sitebanner2 TEXT NOT NULL AFTER sitebanner1");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings CHANGE attachments forum_ips TINYINT(1) UNSIGNED DEFAULT '0'");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD default_search VARCHAR(100) NOT NULL DEFAULT 'forums' AFTER theme");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD exclude_left TEXT NOT NULL AFTER default_search");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD exclude_upper TEXT NOT NULL AFTER exclude_left");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD exclude_lower TEXT NOT NULL AFTER exclude_upper");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD exclude_right TEXT NOT NULL AFTER exclude_lower");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD enable_terms TINYINT(1) UNSIGNED DEFAULT '0' AFTER validation_method");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD license_agreement TEXT NOT NULL AFTER enable_terms");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD license_lastupdate INT(10) UNSIGNED DEFAULT '0' AFTER license_agreement");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD forum_ranks TINYINT(1) UNSIGNED DEFAULT '0' AFTER thread_notify");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD forum_edit_lock TINYINT(1) UNSIGNED DEFAULT '0' AFTER forum_ranks");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER thumbs_per_page");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_save SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER photo_watermark");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_image VARCHAR(255) NOT NULL DEFAULT 'images/watermark.png' AFTER photo_watermark");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_text SMALLINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER photo_watermark_image");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_text_color1 VARCHAR(6) NOT NULL DEFAULT 'FF6600' AFTER photo_watermark_text");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_text_color2 VARCHAR(6) NOT NULL DEFAULT 'FFFF00' AFTER photo_watermark_text_color1");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD photo_watermark_text_color3 VARCHAR(6) NOT NULL DEFAULT 'FFFFFF' AFTER photo_watermark_text_color2");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD userthemes TINYINT(1) UNSIGNED NOT NULL DEFAULT '1' AFTER guestposts");
		$result = dbquery("ALTER TABLE ".$db_prefix."settings ADD newsperpage TINYINT(2) UNSIGNED NOT NULL DEFAULT '11' AFTER userthemes");
		$result = dbquery("ALTER TABLE ".$db_prefix."users ADD user_admin_password VARCHAR(32) NOT NULL AFTER user_password");
		$result = dbquery("ALTER TABLE ".$db_prefix."users ADD user_threads TEXT NOT NULL AFTER user_posts");

		$result = dbquery("ALTER TABLE ".$db_prefix."admin CHANGE admin_id admin_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT");
		$result = dbquery("ALTER TABLE ".$db_prefix."admin CHANGE admin_rights admin_rights CHAR(4) NOT NULL DEFAULT ''");
		$result = dbquery("ALTER TABLE ".$db_prefix."article_cats CHANGE article_cat_id article_cat_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."articles CHANGE article_id article_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."articles CHANGE article_cat article_cat MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."articles CHANGE article_reads article_reads MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."comments CHANGE comment_id comment_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."custom_pages CHANGE page_id page_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."download_cats CHANGE download_cat_id download_cat_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."downloads CHANGE download_id download_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."downloads CHANGE download_cat download_cat MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."faq_cats CHANGE faq_cat_id faq_cat_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."faqs CHANGE faq_id faq_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."faqs CHANGE faq_cat_id faq_cat_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."forum_attachments CHANGE attach_id attach_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."forum_attachments CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."forum_attachments CHANGE post_id post_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."forums CHANGE forum_id forum_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."forums CHANGE forum_cat forum_cat MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."infusions CHANGE inf_id inf_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."messages CHANGE message_id message_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."messages CHANGE message_to message_to MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."messages CHANGE message_from message_from MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."messages_options CHANGE user_id user_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."news_cats CHANGE news_cat_id news_cat_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."news CHANGE news_id news_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."news CHANGE news_cat news_cat MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."news CHANGE news_reads news_reads MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."panels CHANGE panel_id panel_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."photo_albums CHANGE album_id album_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."photos CHANGE photo_id photo_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."photos CHANGE album_id album_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."poll_votes CHANGE vote_id vote_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."poll_votes CHANGE poll_id poll_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."polls CHANGE poll_id poll_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."posts CHANGE forum_id forum_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."posts CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."posts CHANGE post_id post_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."ratings CHANGE rating_id rating_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."ratings CHANGE rating_item_id rating_item_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox CHANGE shout_id shout_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."site_links CHANGE link_id link_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."submissions CHANGE submit_id submit_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."submissions CHANGE submit_user submit_user MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."thread_notify CHANGE thread_id thread_id MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."thread_notify CHANGE notify_user notify_user MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."users CHANGE user_id user_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."weblink_cats CHANGE weblink_cat_id weblink_cat_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."weblinks CHANGE weblink_id weblink_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."weblinks CHANGE weblink_cat weblink_cat MEDIUMINT(8) UNSIGNED DEFAULT '0'"); 
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox CHANGE shout_id shout_id MEDIUMINT(8) UNSIGNED DEFAULT NULL AUTO_INCREMENT"); 

		echo "<div align='center'>\n<form name='upgradeform' method='post' action='".FUSION_SELF."?stage=3'>\n";
		echo "Stage 2 of 4 Complete.<br><br>\n";
		echo "Click Next to continue.<br><br>\n";
		echo "<input type='submit' name='next' value='Next' class='button'>\n";
		echo "</form>\n</div>\n";	
	} elseif (isset($_GET['stage']) && $_GET['stage'] == 3) {
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_moderators='102'");

		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('smiley', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('b', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('i', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('u', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('center', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('small', '9')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('url', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('mail', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('img', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('code', '10')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('quote', '11')");
		$result = dbquery("INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('color', '12')");

		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':)', 'smile.gif', 'Smile')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (';)', 'wink.gif', 'Wink')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':(', 'sad.gif', 'Sad')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':|', 'frown.gif', 'Frown')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':o', 'shock.gif', 'Shock')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':P', 'pfft.gif', 'Pfft')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES ('B)', 'cool.gif', 'Cool')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':D', 'grin.gif', 'Grin')");
		$result = dbquery("INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':@', 'angry.gif', 'Angry')");

		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_location', '2', '1')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_birthdate', '2', '2')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_aim', '1', '3')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_icq', '1', '4')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_msn', '1', '5')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_yahoo', '1', '6')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_web', '1', '7')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_theme', '3', '8')");
		$result = dbquery("INSERT INTO ".$db_prefix."user_fields (field_name, field_group, field_order) VALUES ('user_sig', '3', '9')");

		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level='103'");
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".$db_prefix."users SET user_rights='".$data['user_rights'].".BB.FR.SB.SM.UF' WHERE user_id='".$data['user_id']."'");
		}

		$result = dbquery("DELETE FROM ".$db_prefix."admin WHERE admin_rights='BB'");
		$result = dbquery("DELETE FROM ".$db_prefix."admin WHERE admin_rights='SB'");
		$result = dbquery("DELETE FROM ".$db_prefix."admin WHERE admin_rights='SM'");
		
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SB', 'infusion.gif', 'Banners', 'banners.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('BB', 'bbcodes.gif', 'BB Codes', 'bbcodes.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('FR', 'forum_ranks.gif', 'Forum Ranks', 'forum_ranks.php', 2)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('SM', 'smileys.gif', 'Smileys', 'smileys.php', 3)");
		$result = dbquery("INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('UF', 'user_fields.gif', 'User Fields', 'user_fields.php', 2)");

		echo "<div align='center'>\n<form name='upgradeform' method='post' action='".FUSION_SELF."?stage=4'>\n";
		echo "Stage 3 of 4 Complete.<br><br>\n";
		echo "Click Next to continue.<br><br>\n";
		echo "<input type='submit' name='next' value='Next' class='button'>\n";
		echo "</form>\n</div>\n";
	} elseif (isset($_GET['stage']) && $_GET['stage'] == 4) {
		$result = dbquery("SELECT forum_id, forum_post from ".$db_prefix."forums WHERE forum_cat!='0'");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".$db_prefix."forums SET forum_reply='".$data['forum_post']."' WHERE forum_id='".$data['forum_id']."'");
			}
		}
		
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_postcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER forum_lastpost");
		
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD forum_threadcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER forum_postcount");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD INDEX (forum_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD INDEX (forum_lastpost)");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD INDEX (forum_postcount)");
		$result = dbquery("ALTER TABLE ".$db_prefix."forums ADD INDEX (forum_threadcount)");
				
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD thread_lastpostid MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER thread_lastpost");
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD thread_postcount SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER thread_lastpostid");
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD INDEX (thread_postcount)");
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD INDEX (thread_lastpost)");
		$result = dbquery("ALTER TABLE ".$db_prefix."threads ADD INDEX (thread_views)");
				
		$result = dbquery("ALTER TABLE ".$db_prefix."articles ADD INDEX (article_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."articles ADD INDEX (article_reads)");
		$result = dbquery("ALTER TABLE ".$db_prefix."bbcodes ADD INDEX (bbcode_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."captcha ADD INDEX (captcha_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."comments ADD INDEX (comment_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."downloads ADD INDEX (download_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."flood_control ADD INDEX (flood_timestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."messages ADD INDEX (message_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."news ADD INDEX (news_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."news ADD INDEX (news_reads)");
		$result = dbquery("ALTER TABLE ".$db_prefix."new_users ADD INDEX (user_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."panels ADD INDEX (panel_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."photo_albums ADD INDEX (album_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."photo_albums ADD INDEX (album_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."photos ADD INDEX (photo_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."photos ADD INDEX (photo_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."posts ADD INDEX (post_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."shoutbox ADD INDEX (shout_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."thread_notify ADD INDEX (notify_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."user_fields ADD INDEX (field_order)");
		$result = dbquery("ALTER TABLE ".$db_prefix."users ADD INDEX (user_name)");
		$result = dbquery("ALTER TABLE ".$db_prefix."users ADD INDEX (user_joined)");
		$result = dbquery("ALTER TABLE ".$db_prefix."users ADD INDEX (user_lastvisit)");
		$result = dbquery("ALTER TABLE ".$db_prefix."weblinks ADD INDEX (weblink_datestamp)");
		$result = dbquery("ALTER TABLE ".$db_prefix."weblinks ADD INDEX (weblink_count)");
		$result = dbquery("ALTER TABLE ".$db_prefix."posts ADD INDEX (post_datestamp)");
		
		$result = dbquery("SELECT COUNT(post_id) AS postcount, thread_id FROM ".$db_prefix."posts GROUP BY thread_id");
		
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".$db_prefix."threads SET thread_postcount='".$data['postcount']."' WHERE thread_id='".$data['thread_id']."'");
			}
		}
		
		$result = dbquery("SELECT SUM(thread_postcount) AS postcount, forum_id FROM ".$db_prefix."threads GROUP BY forum_id");
		
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".$db_prefix."forums SET forum_postcount='".$data['postcount']."' WHERE forum_id='".$data['forum_id']."'");
			}
		}
		
		$result = dbquery("SELECT COUNT(thread_id) AS threadcount, forum_id FROM ".$db_prefix."threads GROUP BY forum_id");
		
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".$db_prefix."forums SET forum_threadcount='".$data['threadcount']."' WHERE forum_id='".$data['forum_id']."'");
			}
		}
		
		$result = dbquery("SELECT MAX(post_id) AS lastpid, ".$db_prefix."posts.thread_id FROM ".$db_prefix."posts GROUP BY thread_id");
		
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpostid='".$data['lastpid']."' WHERE thread_id='".$data['thread_id']."'");
			}
		}
	
		$result = dbquery("DROP TABLE IF EXISTS ".$db_prefix."vcode");
		$result = dbquery("UPDATE ".$db_prefix."settings SET theme='Gillette', version='7.00.05'");

		echo "<div style='text-align:center'><br>\nDatabase upgrade complete.<br><br>\n</div>\n";			
	}
} else {
	echo "<div style='text-align:center'><br>\nThere is no database upgrade available.<br><br>\n</div>\n";
}
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>