<?php

require_once "maincore.php";

if(iADMIN){
	
$start = microtime();

if(!isset($_GET['continue'])){
	if(dbquery("ALTER TABLE ".DB_FORUMS." ADD forum_postcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER forum_lastpost")){
		echo "Updating DB structure ...";
		dbquery("ALTER TABLE ".DB_FORUMS." ADD forum_threadcount MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER forum_postcount");
		dbquery("ALTER TABLE ".DB_FORUMS." ADD INDEX (forum_order)");
		dbquery("ALTER TABLE ".DB_FORUMS." ADD INDEX (forum_lastpost)");
		dbquery("ALTER TABLE ".DB_FORUMS." ADD INDEX (forum_postcount)");
		dbquery("ALTER TABLE ".DB_FORUMS." ADD INDEX (forum_threadcount)");
		
		dbquery("ALTER TABLE ".DB_THREADS." ADD thread_lastpostid MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER thread_lastpost");
		dbquery("ALTER TABLE ".DB_THREADS." ADD thread_postcount SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0' AFTER thread_lastpostid");
		dbquery("ALTER TABLE ".DB_THREADS." ADD INDEX (thread_postcount)");
		dbquery("ALTER TABLE ".DB_THREADS." ADD INDEX (thread_lastpost)");
		dbquery("ALTER TABLE ".DB_THREADS." ADD INDEX (thread_views)");
		
		dbquery("ALTER TABLE ".DB_ARTICLES." ADD INDEX (article_datestamp)");
		dbquery("ALTER TABLE ".DB_ARTICLES." ADD INDEX (article_reads)");
		dbquery("ALTER TABLE ".DB_BBCODES." ADD INDEX (bbcode_order)");
		dbquery("ALTER TABLE ".DB_CAPTCHA." ADD INDEX (captcha_datestamp)");
		dbquery("ALTER TABLE ".DB_COMMENTS." ADD INDEX (comment_datestamp)");
		dbquery("ALTER TABLE ".DB_DOWNLOADS." ADD INDEX (download_datestamp)");
		dbquery("ALTER TABLE ".DB_FLOOD_CONTROL." ADD INDEX (flood_timestamp)");
		dbquery("ALTER TABLE ".DB_MESSAGES." ADD INDEX (message_datestamp)");
		dbquery("ALTER TABLE ".DB_NEWS." ADD INDEX (news_datestamp)");
		dbquery("ALTER TABLE ".DB_NEWS." ADD INDEX (news_reads)");
		dbquery("ALTER TABLE ".DB_NEW_USERS." ADD INDEX (user_datestamp)");
		dbquery("ALTER TABLE ".DB_PANELS." ADD INDEX (panel_order)");
		dbquery("ALTER TABLE ".DB_PHOTO_ALBUMS." ADD INDEX (album_order)");
		dbquery("ALTER TABLE ".DB_PHOTO_ALBUMS." ADD INDEX (album_datestamp)");
		dbquery("ALTER TABLE ".DB_PHOTOS." ADD INDEX (photo_order)");
		dbquery("ALTER TABLE ".DB_PHOTOS." ADD INDEX (photo_datestamp)");
		dbquery("ALTER TABLE ".DB_POSTS." ADD INDEX (post_datestamp)");
		dbquery("ALTER TABLE ".DB_SHOUTBOX." ADD INDEX (shout_datestamp)");
		dbquery("ALTER TABLE ".DB_THREAD_NOTIFY." ADD INDEX (notify_datestamp)");
		dbquery("ALTER TABLE ".DB_USER_FIELDS." ADD INDEX (field_order)");
		dbquery("ALTER TABLE ".DB_USERS." ADD INDEX (user_name)");
		dbquery("ALTER TABLE ".DB_USERS." ADD INDEX (user_joined)");
		dbquery("ALTER TABLE ".DB_USERS." ADD INDEX (user_lastvisit)");
		dbquery("ALTER TABLE ".DB_WEBLINKS." ADD INDEX (weblink_datestamp)");
		dbquery("ALTER TABLE ".DB_WEBLINKS." ADD INDEX (weblink_count)");
		dbquery("ALTER TABLE ".DB_POSTS." ADD INDEX (post_datestamp)");
		echo "done <br/>\n";
	}
}


$res = dbquery("SELECT COUNT(post_id) AS count, ".DB_POSTS.".thread_id, ".DB_THREADS.".thread_postcount
FROM ".DB_POSTS."
JOIN ".DB_THREADS." ON ".DB_THREADS.".thread_id = ".DB_POSTS.".thread_id
WHERE thread_postcount = 0
GROUP BY thread_id");

if(dbrows($res) > 0){
	echo "Counting posts per thread <br/>\n";
	while($data = dbarray($res)){
		echo "...";
		if(abs(microtime() - $start) < 20){
			dbquery("UPDATE ".DB_THREADS." SET thread_postcount='".$data['count']."' WHERE thread_id='".$data['thread_id']."'");
		}else{
			header("Location: ".FUSION_SELF."?continue=1");
		}
	}
	echo "done <br/>\n";
}


$res = dbquery("SELECT SUM(thread_postcount) AS postcount, ".DB_THREADS.".forum_id, ".DB_FORUMS.".forum_postcount
FROM ".DB_THREADS."
JOIN ".DB_FORUMS." ON ".DB_FORUMS.".forum_id = ".DB_THREADS.".forum_id
WHERE forum_postcount = 0
GROUP BY forum_id");

if(dbrows($res) > 0){
	echo "Counting posts per forum <br/>\n";
	while($data = dbarray($res)){
		echo "...";
		if(abs(microtime() - $start) < 20){
			dbquery("UPDATE ".DB_FORUMS." SET forum_postcount='".$data['postcount']."' WHERE forum_id='".$data['forum_id']."'");
		}else{
			header("Location: ".FUSION_SELF."?continue=1");
		}
	}
	echo "done <br/>\n";
}


$res = dbquery("SELECT COUNT(thread_id) AS threadcount, ".DB_THREADS.".forum_id, ".DB_FORUMS.".forum_threadcount
FROM ".DB_THREADS."
JOIN ".DB_FORUMS." ON ".DB_FORUMS.".forum_id = ".DB_THREADS.".forum_id
WHERE forum_threadcount = 0
GROUP BY forum_id");

if(dbrows($res) > 0){
	echo "Counting threads per forum <br/>\n";
	while($data = dbarray($res)){
		echo "...";
		if(abs(microtime() - $start) < 20){
			dbquery("UPDATE ".DB_FORUMS." SET forum_threadcount='".$data['threadcount']."' WHERE forum_id='".$data['forum_id']."'");
		}else{
			header("Location: ".FUSION_SELF."?continue=1");
		}
	}
	echo "done <br/>\n";
}


$res = dbquery("SELECT MAX(post_id) AS lastpid, ".DB_POSTS.".thread_id, ".DB_THREADS.".thread_lastpostid
FROM ".DB_POSTS."
JOIN ".DB_THREADS." ON ".DB_THREADS.".thread_id = ".DB_POSTS.".thread_id
WHERE thread_lastpostid = 0
GROUP BY thread_id");

if(dbrows($res) > 0){
	echo "Calculating IDs of latest posts <br/>\n";
	while($data = dbarray($res)){
		echo "... ";
		if(abs(microtime() - $start) < 20){
		dbquery("UPDATE ".DB_THREADS." SET thread_lastpostid='".$data['lastpid']."' WHERE thread_id='".$data['thread_id']."'");
		}else{
			header("Location: ".FUSION_SELF."?continue=1");
		}
	}
	echo "done <br/>\n";
}

echo "Ok";
}

?>