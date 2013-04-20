<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/post.php";

if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id)) fallback("index.php");

if (!isset($error) || !isNum($error) || $error == 0 || $error > 4) { $errorb = ""; }
else if ($error == 1) { $errorb = $locale['440a']; }
else if ($error == 2) { $errorb = $locale['440b']; }
else if ($error == 3) { $errorb = $locale['441']; }
else if ($error == 4) { $errorb = $locale['450']; }

if (($post == "on" || $post == "off") && $settings['thread_notify']) {
	$output = false;
	if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");
	$result = dbquery(
		"SELECT tt.*, tf.* FROM ".$db_prefix."threads tt
		INNER JOIN ".$db_prefix."forums tf ON tt.forum_id=tf.forum_id
		WHERE tt.thread_id='$thread_id'"
	);
	if (dbrows($result)) {
		$data = dbarray($result);
		if (checkgroup($data['forum_access'])) {
			$output = true;
			opentable($locale['451']);
			echo "<center><br>\n";
			if ($post == "on" && !dbcount("(thread_id)", "thread_notify", "thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'")) {
				$result = dbquery("INSERT INTO ".$db_prefix."thread_notify (thread_id, notify_datestamp, notify_user, notify_status) VALUES('$thread_id', '".time()."', '".$userdata['user_id']."', '1')");
				echo $locale['452']."<br><br>\n";
			} else if ($post == "off") {
				$result = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'");
				echo $locale['453']."<br><br>\n";
			}
			echo "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id'>".$locale['447']."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>
</center>\n";
			closetable();
		}
	}
	if (!$output) fallback("index.php");
} else if ($post == "new") {
	opentable($locale['401']);
	echo "<div align='center'>\n";
	if ($errorb) {
		echo "<br>$errorb<br><br>\n";
	} else {
		echo "<br>".$locale['442']."<br><br>\n";
	}
	if ($error < 3) {
		if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");
		echo "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id'>".$locale['447']."</a> |\n";
	}
	echo "<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br></div>\n";
	closetable();
} else if ($post == "reply") {
	if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");
	opentable($locale['403']);
	echo "<center><br>\n";
	if ($errorb) {
		echo "$errorb<br><br>\n";
	} else {
		echo $locale['443']."<br><br>\n";
	}
	if ($error < "2") {
		if (!isset($post_id) || !isNum($post_id)) fallback("index.php");
		if ($settings['thread_notify']) {
			$result = dbquery(
				"SELECT tn.*, tu.user_id,user_name,user_email FROM ".$db_prefix."thread_notify tn
				LEFT JOIN ".$db_prefix."users tu ON tn.notify_user=tu.user_id
				WHERE thread_id='$thread_id' AND notify_user!='".$userdata['user_id']."' AND notify_status='1'
			");
			if (dbrows($result)) {
				require_once INCLUDES."sendmail_include.php";
				$data2 = dbarray(dbquery("SELECT thread_subject FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));
				$link = $settings['siteurl']."forum/viewthread.php?forum_id=$forum_id&thread_id=$thread_id&pid=$post_id#post_$post_id";
				while ($data = dbarray($result)) {
					$message_el1 = array("{USERNAME}", "{THREAD_SUBJECT}", "{THREAD_URL}");
					$message_el2 = array($data['user_name'], $data2['thread_subject'], $link);
					$message_subject = str_replace("{THREAD_SUBJECT}", $data2['thread_subject'], $locale['550']);
					$message_content = str_replace($message_el1, $message_el2, $locale['551']);
					sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],$settings['siteemail'],$message_subject,$message_content);
				}
				$result = dbquery("UPDATE ".$db_prefix."thread_notify SET notify_status='0' WHERE thread_id='$thread_id' AND notify_user!='".$userdata['user_id']."'");
			}
		}
		echo "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id&amp;pid=$post_id#post_$post_id'>".$locale['447']."</a> |\n";
	} else {
		$data = dbarray(dbquery("SELECT post_id FROM ".$db_prefix."posts WHERE thread_id='".$data['thread_id']."' ORDER BY post_id DESC"));
		echo "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id&amp;pid=".$data['post_id']."#post_".$data['post_id']."'>".$locale['447']."</a> |\n";
	}
	echo "<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>
</center>\n";
	closetable();
} else if ($post == "edit") {
	if (!isset($thread_id) || !isNum($thread_id)) fallback("index.php");
	opentable($locale['409']);
	echo "<center><br>\n";
	if ($errorb) {
		echo "$errorb<br><br>\n";
	} else {
		echo $locale['446']."<br><br>\n";
	}
	echo "<a href='viewthread.php?forum_id=$forum_id&amp;thread_id=$thread_id&amp;pid=$post_id#post_$post_id'>".$locale['447']."</a> |
<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>
</center>\n";
		closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>