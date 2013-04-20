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
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/options.php";

if (iMEMBER) {
	if (!isset($forum_id) || !isNum($forum_id)) fallback("../index.php");
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='".$forum_id."'"));
	if (!checkgroup($data['forum_access'])) fallback("index.php");
	$forum_mods = explode(".", $data['forum_moderators']);
	if (in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }
} else {
	define("iMOD", false);
}

if ((!iMOD && !iSUPERADMIN) || !checkgroup($data['forum_posting'])) fallback("index.php");
if (!isset($thread_id) || !isNum($thread_id)) fallback("../index.php");
if (!isset($step) || $step == "") redirect("viewthread.php?forum_id=$forum_id&thread_id=$thread_id");

if (isset($_POST['canceldelete'])) fallback("viewthread.php?forum_id=$forum_id&thread_id=$thread_id");

if ($step == "renew") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."' WHERE thread_id='$thread_id'");
	opentable($locale['458']);
	echo "<div align='center'><br>
".$locale['459']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br></div>\n";
	closetable();
}
if ($step == "delete") {
	opentable($locale['400']);
	echo "<div align='center'><br>\n";
	if (!isset($_POST['deletethread'])) {
		echo "<form name='delform' method='post' action='".FUSION_SELF."?step=delete&amp;forum_id=$forum_id&amp;thread_id=$thread_id'>
".$locale['404']."<br><br>
<input type='submit' name='deletethread' value='".$locale['405']."' class='button' style='width:75px'>
<input type='submit' name='canceldelete' value='".$locale['406']."' class='button' style='width:75px'><br><br>
</form>\n";
	} else {
		$tdata2 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));

		$threads_count = dbcount("(forum_id)", "threads", "forum_id='$forum_id'") - 1;
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE thread_id='$thread_id'");
		$result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE thread_id='$thread_id'");
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(FORUM."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
			}
		}
		$result = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE thread_id='$thread_id'");
		
	       	if ($threads_count > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastpost='".$tdata2['thread_lastpost']."' AND forum_lastuser='".$tdata2['thread_lastuser']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
		}
		echo $locale['401']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>\n";
	}
	echo "</div>\n";
	closetable();
}
if ($step == "lock") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_locked='1' WHERE thread_id='$thread_id'");
	opentable($locale['410']);
	echo "<div align='center'><br>
".$locale['411']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>\n</div>\n";
	closetable();
}
if ($step == "unlock") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_locked='0' WHERE thread_id='$thread_id'");
	opentable($locale['420']);
	echo "<div align='center'><br>
".$locale['421']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>\n</div>\n";
	closetable();
}
if ($step == "sticky") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_sticky='1' WHERE thread_id='$thread_id'");
	opentable($locale['430']);
	echo "<div align='center'><br>
".$locale['431']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br>\n</div>\n";
	closetable();
}
if ($step == "nonsticky") {
	$result = dbquery("UPDATE ".$db_prefix."threads SET thread_sticky='0' WHERE thread_id='$thread_id'");
	opentable($locale['440']);
	echo "<div align='center'><br>
".$locale['441']."<br><br>
<a href='viewforum.php?forum_id=$forum_id'>".$locale['402']."</a><br><br>
<a href='index.php'>".$locale['403']."</a><br><br></div>\n";
	closetable();
}
if ($step == "move") {
	opentable($locale['450']);
	if (isset($_POST['move_thread'])) {
		$new_forum_id = $_POST['new_forum_id'];
		if (!isset($new_forum_id) || !isNum($new_forum_id)) fallback("../index.php");
		$tdata2 = dbarray(dbquery("SELECT thread_id,thread_lastpost,thread_lastuser FROM ".$db_prefix."threads WHERE thread_id='$thread_id'"));

		$threads_count_old = dbcount("(forum_id)", "threads", "forum_id='$forum_id'") - 1;
		$threads_count_new = dbcount("(forum_id)", "threads", "forum_id='$new_forum_id'") + 1;
		
		$result = dbquery("UPDATE ".$db_prefix."threads SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		$result = dbquery("UPDATE ".$db_prefix."posts SET forum_id='$new_forum_id' WHERE thread_id='$thread_id'");
		
	       	if ($threads_count_old > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastpost='".$tdata2['thread_lastpost']."' AND forum_lastuser='".$tdata2['thread_lastuser']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
		}
		
	       	if ($threads_count_old > 1) {
			$result = dbquery("SELECT forum_lastpost FROM ".$db_prefix."forums WHERE forum_id='$new_forum_id'");
			$fdata2 = dbarray($result);
			if ($tdata2['thread_lastpost'] > $fdata['forum_lastpost']) {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$tdata2['thread_lastpost']."', forum_lastuser='".$tdata2['thread_lastuser']."' WHERE forum_id='$new_forum_id'");
			}
		} else {
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$tdata2['thread_lastpost']."', forum_lastuser='".$tdata2['thread_lastuser']."' WHERE forum_id='$new_forum_id'");
		}
		echo "<div align='center'><br>
".$locale['452']."<br><br>
<a href='index.php'>".$locale['403']."</a><br><br>\n</div>\n";
	} else {
		$move_list = ""; $sel = "";
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
				if (dbrows($result2) != 0) {
					$move_list .= "<optgroup label='".$data['forum_name']."'>\n";
					while ($data2 = dbarray($result2)) {
						if ($forum_id == $data2['forum_id']) { $sel = " selected"; } else { $sel = ""; }
						$move_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
					}
					$move_list .= "</optgroup>\n";
				}
			}
		}
		echo "<form name='moveform' method='post' action='".FUSION_SELF."?step=move&forum_id=$forum_id&amp;thread_id=$thread_id'>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td class='tbl2' width='150'>".$locale['451']."</td>
<td class='tbl1'><select name='new_forum_id' class='textbox' style='width:250px;'>
$move_list</select></td>
</tr>
<tr>
<td colspan='2' class='tbl2' style='text-align:center;'><input type='submit' name='move_thread' value='".$locale['450']."' class='button'></td>
</tr>
</table>
</td>
</tr>
</table>
</form>\n";
	}
	closetable();
}

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>