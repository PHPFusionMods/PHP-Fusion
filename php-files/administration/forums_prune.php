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
if (!defined("IN_FUSION")) { header("location: ../index.php"); exit; }

if (!checkrights("S3") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

opentable($locale['800']);
$expired = time()-(86400 * $_POST['prune_days']);
// Check number of posts & threads older than expired date and delete them
$result = dbquery("SELECT post_id,post_datestamp FROM ".$db_prefix."posts WHERE post_datestamp < $expired");
$delposts = dbrows($result);
if ($delposts != 0) {
	$delattach = 0;
	while ($data = dbarray($result)) {
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		if (dbrows($result2) != 0) {
			$delattach++;
			$attach = dbarray($result2);
			@unlink(FORUM."attachments/".$attach['attach_name']);
			$result3 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='".$data['post_id']."'");
		}
	}
}
$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_datestamp < $expired");
$result = dbquery("SELECT thread_id,thread_lastpost FROM ".$db_prefix."threads WHERE thread_lastpost < $expired");
$delthreads = dbrows($result);
if ($delthreads != 0) {
	while ($data = dbarray($result)) {
		$result2 = dbquery("DELETE FROM ".$db_prefix."thread_notify WHERE thread_id='".$data['thread_id']."'");
	}
}
$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_lastpost < $expired");
echo $locale['801'].$delposts."<br>\n".$locale['802'].$delthreads."<br>\n".$locale['803'].$delattach."<br>\n";
closetable();
tablebreak();
?>