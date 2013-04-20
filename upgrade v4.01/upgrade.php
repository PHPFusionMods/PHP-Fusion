<?
/*
---------------------------------------------------
	PHP-Fusion - Content Management System
	-------------------------------------------
	© Nick Jones (Digitanium) 2002-2004
	http://www.php-fusion.co.uk
	nick@php-fusion.co.uk
	-------------------------------------------
	Released under the terms and conditions of
	the GNU General Public License (Version 2)
---------------------------------------------------
*/
@include "../fusion_config.php";
include "../header.php";
include "../subheader.php";
echo "<td valign='top' class='bodybg'>\n";

$error = "0";

opentable("Upgrade");
if (str_replace(".","",$settings['version']) < 600300) {
	if (!isset($_POST['stage'])) {
		echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
A database upgrade is available for this installation of PHP-Fusion.<br>
It is highly recommended that you back-up your database prior to<br>
completing this process.<br><br>\n";

if (!file_exists("../config.php")) {
	echo "config.php does not exist, please upload it to your root folder.<br><br>\n";
	$error = "1";
} elseif (!is_writable("../config.php")) {
	echo "config.php is not writable, please chmod it to 777 and try again.<br><br>\n";
	$error = "1";
}

if ($error == "0") { echo "<input type='hidden' name='stage' value='2'>
<input type='submit' name='upgrade' value='Upgrade' class='button'>\n"; }

echo "</center>
</td>
</tr>
</form>
</table>\n";
	}
	if (isset($_POST['stage']) && $_POST['stage'] == 2) {
		if (isset($_POST['upgrade'])) {
			$config = "<?php
// database settings
"."$"."db_host="."\"".$dbhost."\"".";
"."$"."db_user="."\"".$dbusername."\"".";
"."$"."db_pass="."\"".$dbpassword."\"".";
"."$"."db_name="."\"".$dbname."\"".";
"."$"."db_prefix="."\"".$fusion_prefix."\"".";
define("."\""."DB_PREFIX"."\"".", "."\"".$fusion_prefix."\"".");
?>";
			$temp = fopen("../config.php","w");
			if (!fwrite($temp, $config)) {
				echo "<center><br>\nUnable to write new config.<br><br>\n</center>\n";
				fclose($temp);
				exit;
			} else {
				fclose($temp);
				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."new_users");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."new_users (
				user_code VARCHAR(32) NOT NULL,
				user_email VARCHAR(100) NOT NULL,
				user_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				user_info TEXT NOT NULL
				) TYPE=MyISAM;");

				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."infusions");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."infusions (
				inf_id smallint(5) unsigned NOT NULL auto_increment,
				inf_title varchar(100) NOT NULL default '',
				inf_folder varchar(100) NOT NULL default '',
				inf_version varchar(5) NOT NULL default '0',
				PRIMARY KEY (inf_id)
				) TYPE=MyISAM;");
				
				$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."ratings");
				$result = dbquery("CREATE TABLE ".$fusion_prefix."ratings (
				rating_id smallint(5) unsigned NOT NULL auto_increment,
				rating_item_id smallint(5) unsigned NOT NULL default '0',
				rating_type char(1) NOT NULL default '',
				rating_user smallint(5) unsigned NOT NULL default '0',
				rating_vote tinyint(1) unsigned NOT NULL default '0',
				rating_datestamp int(10) unsigned NOT NULL default '0',
				rating_ip varchar(20) NOT NULL default '0.0.0.0',
				PRIMARY KEY (rating_id)
				) TYPE=MyISAM;");
				
				$result = dbquery("CREATE TABLE ".$fusion_prefix."submissions (
				submit_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
				submit_type CHAR(1) NOT NULL ,
				submit_user SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
				submit_datestamp INT(10) UNSIGNED DEFAULT '0' NOT NULL,
				submit_criteria TEXT NOT NULL,
				PRIMARY KEY (submit_id)
				) TYPE=MyISAM;");
				
				$result = dbquery("DELETE FROM ".$fusion_prefix."panels WHERE panel_type='file'");
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='1' WHERE panel_side='l'");
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_side='3' WHERE panel_side='r'");
				$result = dbquery("ALTER TABLE ".$fusion_prefix."panels CHANGE panel_side panel_side TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
				// Refresh remaining panels
				$i = 1;
				$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
				while ($data = dbarray($result)) {
					$result2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
					$i++;
				}
				$i = 1;
				$result = dbquery("SELECT * FROM ".$fusion_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
				while ($data = dbarray($result)) {
					$result2 = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
					$i++;
				}
				
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+4 WHERE panel_order>='1' AND panel_side='1'");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES('', 'Navigation', 'navigation_panel', '', '1', '1', 'file', '0', '1')");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Online Users', 'online_users_panel', '', '1', 2, 'file', 0, 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Forum Threads', 'forum_threads_panel', '', '1', 3, 'file', 0, 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Latest Articles', 'latest_articles_panel', '', '1', 4, 'file', 0, 1)");

				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Welcome Message', 'welcome_message_panel', '', '2', 1, 'file', 0, 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Active Forum Threads', 'forum_threads_list_panel', '', '2', 2, 'file', 0, 1)");
		
				$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_order=panel_order+3 WHERE panel_order>='1' AND panel_side='1'");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'User Info', 'user_info_panel', '', '3', 1, 'file', 0, 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Member Poll', 'member_poll_panel', '', '3', 2, 'file', 0, 1)");
				$result = dbquery("INSERT INTO ".$fusion_prefix."panels VALUES ('', 'Shoutbox', 'shoutbox_panel', '', '3', 3, 'file', 0, 1)");
				
				$result = dbquery("ALTER TABLE ".$fusion_prefix."site_links ADD link_position TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER link_visibility");
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=n', link_visibility='1' WHERE link_url='submit_news.php'");
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=a', link_visibility='1' WHERE link_url='submit_article.php'");
				$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='submit.php?stype=l', link_visibility='1' WHERE link_url='submit_link.php'");

				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_showsig='0' WHERE post_showsig='n'");
				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_showsig='1' WHERE post_showsig='y'");
				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_smileys='0' WHERE post_smileys='n'");
				$result = dbquery("UPDATE ".$fusion_prefix."posts SET post_smileys='1' WHERE post_smileys='y'");
			
				$result=dbquery("ALTER TABLE ".$fusion_prefix."posts CHANGE post_showsig post_showsig TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."posts CHANGE post_smileys post_smileys TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='0' WHERE thread_sticky='n'");
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_sticky='1' WHERE thread_sticky='y'");
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='0' WHERE thread_locked='n'");
				$result = dbquery("UPDATE ".$fusion_prefix."threads SET thread_locked='1' WHERE thread_locked='y'");
			
				$result=dbquery("ALTER TABLE ".$fusion_prefix."threads CHANGE thread_sticky thread_sticky TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."threads CHANGE thread_locked thread_locked TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

				$result=dbquery("ALTER TABLE ".$fusion_prefix."forums DROP forum_threads");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."forums DROP forum_posts");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_start INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER news_datestamp");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_end INT(10) UNSIGNED DEFAULT '0' NOT NULL AFTER news_start");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."polls DROP poll_optcount");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."polls DROP poll_votes");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD start_page VARCHAR(50) DEFAULT 'news.php' NOT NULL AFTER footer");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD other_page VARCHAR(100) DEFAULT '' NOT NULL AFTER start_page");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD enable_registration TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER attachtypes");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD email_verification TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER enable_registration");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD display_validation TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER email_verification");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD validation_method VARCHAR(5) DEFAULT 'image' NOT NULL AFTER display_validation");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD thumb_compression CHAR(3) DEFAULT 'gd2' NOT NULL AFTER thumb_image_h");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_words TEXT NOT NULL AFTER album_max_b");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL AFTER bad_words");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."settings DROP forumpanel");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."threads DROP thread_replies");
				$result=dbquery("ALTER TABLE ".$fusion_prefix."articles DROP article_comments"); 
				$result=dbquery("ALTER TABLE ".$fusion_prefix."news DROP news_comments"); 
				
				echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
The first part of the upgrade process is complete.<br>
Click Next to procede to the next step.<br><br>
<input type='hidden' name='stage' value='3'>
<input type='submit' name='upgrade' value='Next' class='button'>
</center>
</td>
</tr>
</form>
</table>\n";
			}
		}
	} elseif (isset($_POST['stage']) && $_POST['stage'] == 3) {
		if (isset($_POST['upgrade'])) {
			$forum_mods == ""; $i = 1;
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_mod user_level TINYINT(3) UNSIGNED DEFAULT '250' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums ADD forum_moderators TEXT NOT NULL AFTER forum_description");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums ADD forum_posting TINYINT(3) UNSIGNED DEFAULT '250' NOT NULL AFTER forum_access");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."infusions CHANGE inf_version inf_version VARCHAR(10) DEFAULT '1' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."messages CHANGE message_subject message_subject VARCHAR(200) DEFAULT '' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_visibility TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER news_end");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."site_links ADD link_window TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER link_position");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0' AFTER user_lastvisit");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_rights TEXT NOT NULL AFTER user_ip");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_groups TEXT NOT NULL AFTER user_rights");
			$sql = dbquery("CREATE TABLE ".$fusion_prefix."blacklist (
				blacklist_id smallint(5) unsigned NOT NULL auto_increment,
				blacklist_ip varchar(20) NOT NULL default '',
				blacklist_email varchar(100) NOT NULL default '',
				blacklist_reason text NOT NULL,
				PRIMARY KEY (blacklist_id)
			) TYPE=MyISAM;");
			$sql = dbquery("CREATE TABLE ".$fusion_prefix."user_groups (
				group_id TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT,
				group_name VARCHAR(100) NOT NULL,
				group_description VARCHAR(200) NOT NULL,
				PRIMARY KEY (group_id) 
			) TYPE=MyISAM;");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages CHANGE page_access page_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."forums CHANGE forum_access forum_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."panels CHANGE panel_access panel_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."site_links CHANGE link_visibility link_visibility TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL");
			$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='251' WHERE page_access>='2'");
			$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='250' WHERE page_access='1'");
			$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='251' WHERE forum_access>='2'");
			$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='250' WHERE forum_access='1'");
			$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='251' WHERE panel_access>='2'");
			$sql = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='250' WHERE panel_access='1'");
			$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='251' WHERE link_visibility>='2'");
			$sql = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='250' WHERE link_visibility='1'");
			$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='252', user_rights='1.2.3.4.5.6.7.8.9.A.B.C.D.E.F.G.H.I.J.K.L.M.N.O.P.Q.R.S' WHERE user_level='4'");
			$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='251', user_rights='1.4.6.9.B.D.E.G.I.J.M.N' WHERE user_level='3'");
			$sql = dbquery("UPDATE ".$fusion_prefix."users SET user_level='250' WHERE user_level<'251'");
			$sql = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level>'250'");
			while ($data = dbarray($sql)) {
				$forum_mods .= $data['user_id'];
				if ($i < dbrows($sql)) $forum_mods .= ".";
				$i++;
			}
			$sql = dbquery("UPDATE ".$fusion_prefix."forums SET forum_moderators='$forum_mods'");
			$sql = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_content TEXT NOT NULL AFTER page_access");
			$result = dbquery("SELECT * FROM ".$fusion_prefix."custom_pages");
			if (dbrows($result) != 0) {
				while ($data = dbarray($result)) {
					$file = fopen("../fusion_pages/".$data['page_id'].".php","rb");
					$content = fread($file, filesize("../fusion_pages/".$data['page_id'].".php"));
					fclose($file);
					$content = addslashes(addslashes($content));
					$sql = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_content='$content' WHERE page_id='".$data['page_id']."'");
				}
			}
			$sql= dbquery("SELECT * FROM ".$fusion_prefix."site_links WHERE link_url LIKE '%fusion_pages/index.php%'");
			while ($data = dbarray($sql)) {
				$new_url = str_replace("fusion_pages/index.php", "viewpage.php", $data['link_url']);
				$sql2 = dbquery("UPDATE ".$fusion_prefix."site_links SET link_url='$new_url' WHERE link_id='".$data['link_id']."'");
			}
			echo "<center><br>\nCustom pages successfully imported into database.<br><br>\n</center>\n";
			echo "<table align='center' cellpadding='0' cellspacing='0' class='body'>
<form name='upgradeform' method='post' action='upgrade.php'>
<tr>
<td>
<center>
The second part of the upgrade process is complete.<br>
Click Next to procede to the final step.<br><br>
<input type='hidden' name='stage' value='4'>
<input type='submit' name='upgrade' value='Next' class='button'>
</center>
</td>
</tr>
</form>
</table>\n";
		}
	} elseif (isset($_POST['stage']) && $_POST['stage'] == 4) {
		if (isset($_POST['upgrade'])) {
			$result = dbquery("DROP TABLE ".$fusion_prefix."temp");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."vcode (
			vcode_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			vcode_1 VARCHAR(5) NOT NULL DEFAULT '',
			vcode_2 VARCHAR(32) NOT NULL DEFAULT ''
			) TYPE=MyISAM;");

			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."admin");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."admin (
			admin_id TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			admin_rights CHAR(2) NOT NULL default '',
			admin_image VARCHAR(50) NOT NULL default '',
			admin_title VARCHAR(50) NOT NULL default '',
			admin_link VARCHAR(100) NOT NULL default 'reserved',
			admin_page TINYINT(1) UNSIGNED NOT NULL default '1',
			PRIMARY KEY (admin_id)
			) TYPE=MyISAM;");
			
			$result = dbquery("ALTER TABLE ".$fusion_prefix."messagesv5 RENAME ".$fusion_prefix."messages_old");
			if (!$result) {
				echo " Don't worry this is an expected error.<br>";
				$result = dbquery("ALTER TABLE ".$fusion_prefix."messages RENAME ".$fusion_prefix."messages_old");
			}
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."messages (
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
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."messages_options");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."messages_options (
			user_id SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			pm_email_notify TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_save_sent TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY (user_id)
			) TYPE=MyISAM;");
			
			$result = dbquery("DROP TABLE IF EXISTS ".$fusion_prefix."news_cats");
			$result = dbquery("CREATE TABLE ".$fusion_prefix."news_cats (
			news_cat_id SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
			news_cat_name VARCHAR(100) NOT NULL DEFAULT '',
			news_cat_image VARCHAR(100) NOT NULL DEFAULT '',
			PRIMARY KEY (news_cat_id)
			) TYPE=MyISAM;");

			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'AD', 'admins.gif', 'Administrators', 'administrators.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'AC', 'article_cats.gif', 'Article Categories', 'article_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'A', 'articles.gif', 'Articles', 'articles.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'B', 'blacklist.gif', 'Blacklist', 'blacklist.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'C', '', 'Comments', 'reserved', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'CP', 'c-pages.gif', 'Custom Pages', 'custom_pages.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'DB', 'db_backup.gif', 'Database Backup', 'db_backup.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'DC', 'dl_cats.gif', 'Download Categories', 'download_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'D', 'dl.gif', 'Downloads', 'downloads.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'FQ', 'faq.gif', 'FAQs', 'faq.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'F', 'forums.gif', 'Forums', 'forums.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'IM', 'images.gif', 'Images', 'images.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'I', 'infusions.gif', 'Infusions', 'infusions.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'IP', '', 'Infusion Panels', 'reserved', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'M', 'members.gif', 'Members', 'members.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'N', 'news.gif', 'News', 'news.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'NC', 'news_cats.gif', 'News Categories', 'news_cats.php', '1')");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'P', 'panels.gif', 'Panels', 'panels.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'PH', 'photoalbums.gif', 'Photo Albums', 'photoalbums.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'PI', 'phpinfo.gif', 'PHP Info', 'phpinfo.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'PO', 'polls.gif', 'Polls', 'polls.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S', 'shout.gif', 'Shoutbox', 'shoutbox.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'SL', 'site_links.gif', 'Site Links', 'site_links.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'SU', 'submissions.gif', 'Submissions', 'submissions.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'U', 'upgrade.gif', 'Upgrade', 'upgrade.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'UG', 'user_groups.gif', 'User Groups', 'user_groups.php', 2)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'WC', 'wl_cats.gif', 'Web Link Categories', 'weblink_cats.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'W', 'wl.gif', 'Web Links', 'weblinks.php', 1)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S1', 'settings.gif', 'Main Settings', 'settings_main.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S2', 'settings_time.gif', 'Time & Date Settings', 'settings_time.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S3', 'settings_forum.gif', 'Forum Settings', 'settings_forum.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S4', 'registration.gif', 'Registration Settings', 'settings_registration.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S5', 'photoalbums.gif', 'Photo Gallery Settings', 'settings_photo.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S6', 'settings_misc.gif', 'Miscellaneous Settings', 'settings_misc.php', 3)");
			$result = dbquery("INSERT INTO ".$fusion_prefix."admin VALUES ('', 'S7', 'settings_pm.gif', 'Private Message Settings', 'settings_messages.php', 3)");
			
			$result = dbquery("INSERT INTO ".$fusion_prefix."messages_options VALUES ('0', '0', '1', '20', '20', '20')");

			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings DROP start_page");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings DROP forumdate");

			$result = dbquery("ALTER TABLE ".$fusion_prefix."article_cats ADD article_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER article_cat_description");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."articles ADD article_allow_comments TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."articles ADD article_allow_ratings TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."comments ADD comment_smileys TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL AFTER comment_message");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_allow_comments TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."custom_pages ADD page_allow_ratings TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."downloads CHANGE download_description download_description TEXT NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."download_cats ADD download_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER download_cat_description");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_allow_comments TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_allow_ratings TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."posts ADD post_ip VARCHAR(20) DEFAULT '0.0.0.0' NOT NULL AFTER post_datestamp");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD forumdate VARCHAR(50) DEFAULT '%d-%m-%Y %H:%M' NOT NULL AFTER longdate");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD subheaderdate VARCHAR(50) DEFAULT '%B %d %Y %H:%M:%S' NOT NULL AFTER forumdate");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD admin_activation TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER email_verification");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD bad_words_enabled TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER album_max_b");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."users ADD user_aim VARCHAR(16) DEFAULT '' NOT NULL AFTER user_birthdate");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."weblinks CHANGE weblink_description weblink_description TEXT NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."weblink_cats ADD weblink_cat_access TINYINT(3) UNSIGNED DEFAULT '0' NOT NULL AFTER weblink_cat_description");

			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE language locale VARCHAR(20) DEFAULT 'English' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE other_page opening_page VARCHAR(100) DEFAULT '0' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings CHANGE version version VARCHAR(10) DEFAULT '0' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_level user_level TINYINT(3) UNSIGNED DEFAULT '101' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."users CHANGE user_ban user_status TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL");

			$result = dbquery("ALTER TABLE ".$fusion_prefix."faq_cats ADD faq_cat_description VARCHAR(250) DEFAULT '' NOT NULL AFTER faq_cat_name");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."news ADD news_cat SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL AFTER news_subject");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD news_style TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER opening_page");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD db_backup_folder VARCHAR(200) DEFAULT 'db_backups/' NOT NULL AFTER album_max_b");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD tinymce_enabled TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER db_backup_folder");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD smtp_host VARCHAR(200) DEFAULT '' NOT NULL AFTER tinymce_enabled");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD smtp_username VARCHAR(100) DEFAULT '' NOT NULL AFTER smtp_host");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."settings ADD smtp_password VARCHAR(100) DEFAULT '' NOT NULL AFTER smtp_username");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."article_cats ADD article_cat_sorting VARCHAR(50) DEFAULT 'article_subject ASC' NOT NULL AFTER article_cat_description");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."download_cats ADD download_cat_sorting VARCHAR(50) DEFAULT 'download_title ASC' NOT NULL AFTER download_cat_description");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."weblink_cats ADD weblink_cat_sorting VARCHAR(50) DEFAULT 'weblink_name ASC' NOT NULL AFTER weblink_cat_description");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."downloads CHANGE download_description download_description TEXT NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."weblinks CHANGE weblink_description weblink_description TEXT NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."panels ADD panel_display TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL AFTER panel_access");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."photos CHANGE photo_title photo_title VARCHAR(100) DEFAULT '' NOT NULL");
			$result = dbquery("ALTER TABLE ".$fusion_prefix."photo_albums CHANGE album_title album_title VARCHAR(100) DEFAULT '' NOT NULL");

			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='101' WHERE page_access='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='102' WHERE page_access='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."custom_pages SET page_access='103' WHERE page_access='252'");

			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='101' WHERE forum_access='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='102' WHERE forum_access='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_access='103' WHERE forum_access='252'");

			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='101' WHERE forum_posting='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='102' WHERE forum_posting='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."forums SET forum_posting='103' WHERE forum_posting='252'");

			$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='101' WHERE panel_access='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='102' WHERE panel_access='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."panels SET panel_access='103' WHERE panel_access='252'");

			$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='101' WHERE link_visibility='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='102' WHERE link_visibility='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."site_links SET link_visibility='103' WHERE link_visibility='252'");

			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='101' WHERE user_level='250'");
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='102', user_rights='' WHERE user_level='251'");
			$result = dbquery("UPDATE ".$fusion_prefix."users SET user_level='103', user_rights='A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.NC.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC' WHERE user_level='252'");

			$result = dbquery("SELECT weblink_id,weblink_description FROM ".$fusion_prefix."weblinks");
			while ($data = dbarray($result)) {
				$weblink_description = parseubb(stripslashes($data['weblink_description']));
				$weblink_description = addslashes($weblink_description);
				$result2 = dbquery("UPDATE ".$fusion_prefix."weblinks SET weblink_description='$weblink_description' WHERE weblink_id='".$data['weblink_id']."'");
			}
			$result = dbquery("SELECT download_id,download_description FROM ".$fusion_prefix."downloads");
			while ($data = dbarray($result)) {
				$download_description = parseubb(stripslashes($data['download_description']));
				$download_description = addslashes($download_description);
				$result2 = dbquery("UPDATE ".$fusion_prefix."downloads SET download_description='$download_description' WHERE download_id='".$data['download_id']."'");
			}
			$result = dbquery("SELECT faq_id,faq_answer FROM ".$fusion_prefix."faqs");
			while ($data = dbarray($result)) {
				$faq_answer = parseubb(stripslashes($data['faq_answer']));
				$faq_answer = addslashes($faq_answer);
				$result2 = dbquery("UPDATE ".$fusion_prefix."faqs SET faq_answer='$faq_answer' WHERE faq_id='".$data['faq_id']."'");
			}
			$result = dbquery("SELECT * FROM ".$fusion_prefix."users WHERE user_level='103'");
			while ($data = dbarray($result)) {
				$user_rights = $data['user_rights'].".NC";
				$result2 = dbquery("UPDATE ".$fusion_prefix."users SET user_rights='$user_rights' WHERE user_id='".$data['user_id']."'");
			}

			$result = dbquery("UPDATE ".$fusion_prefix."settings SET opening_page='news.php', theme='Milestone', version='6.00.307'");
			echo "<center><br>\nDatabase upgrade complete.<br><br>\n</center>\n";
		}
	}
} else {
	echo "<center><br>\nThere is no database upgrade available.<br><br>\n</center>\n";
}
closetable();

echo "</td>
</tr>
</table>\n";

include "../footer.php";
?>