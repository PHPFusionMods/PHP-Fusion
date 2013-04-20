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
require_once INCLUDES."forum_functions_include.php";
require_once BASEDIR."subheader.php";
require_once BASEDIR."side_left.php";
include LOCALE.LOCALESET."forum/main.php";

$posts_per_page = 20;

if (!FUSION_QUERY || !isset($forum_id) || !isNum($forum_id) || !isset($thread_id) || !isNum($thread_id)) fallback("index.php");

$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	LEFT JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$forum_id."'"
);
if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) fallback("index.php");
} else {
	fallback("index.php");
}
$can_post = checkgroup($fdata['forum_posting']);

$forum_mods = explode(".", $fdata['forum_moderators']);
if (iMEMBER && in_array($userdata['user_id'], $forum_mods)) { define("iMOD", true); } else { define("iMOD", false); }

$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='".$thread_id."' AND forum_id='".$fdata['forum_id']."'");
if (dbrows($result)) { $tdata = dbarray($result); } else { fallback("index.php"); }

$caption = $fdata['forum_cat_name']." | <a href='viewforum.php?forum_id=".$fdata['forum_id']."'>".$fdata['forum_name']."</a>";
$result = dbquery("UPDATE ".$db_prefix."threads SET thread_views=thread_views+1 WHERE thread_id='$thread_id'");

if (iMEMBER && $can_post && !$tdata['thread_locked'] && isset($_POST['postquickreply'])) {
	$flood = false;
	$message = stripinput(censorwords($_POST['message']));
	if ($message != "") {
		$result = dbquery("SELECT MAX(post_datestamp) AS last_post FROM ".$db_prefix."posts WHERE post_author='".$userdata['user_id']."'");
		if (dbrows($result) > 0) {
			$data = dbarray($result);
			if ((time() - $data['last_post']) < $settings['flood_interval']) {
				$flood = true;
				$result = dbquery("INSERT INTO ".$db_prefix."flood_control (flood_ip, flood_timestamp) VALUES ('".USER_IP."', '".time()."')");
				if (dbcount("(flood_ip)", "flood_control", "flood_ip='".USER_IP."'") > 4) {
					$result = dbquery("UPDATE ".$db_prefix."users SET user_status='1' WHERE user_id='".$userdata['user_id']."'");
				}
				fallback("viewforum.php?forum_id=$forum_id");
			}
		}
		if (!$flood) {
			$sig = ($userdata['user_sig'] ? '1' :'0');
			$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
			$subject = "RE: ".$tdata['thread_subject'];
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".time()."', forum_lastuser='".$userdata['user_id']."' WHERE forum_id='$forum_id'");
			$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".time()."', thread_lastuser='".$userdata['user_id']."' WHERE thread_id='$thread_id'");
			$result = dbquery("INSERT INTO ".$db_prefix."posts (forum_id, thread_id, post_subject, post_message, post_showsig, post_smileys, post_author, post_datestamp, post_ip, post_edituser, post_edittime) VALUES ('$forum_id', '$thread_id', '$subject', '$message', '$sig', '$smileys', '".$userdata['user_id']."', '".time()."', '".USER_IP."', '0', '0')");
			$newpost_id = mysql_insert_id();
			$result = dbquery("UPDATE ".$db_prefix."users SET user_posts=user_posts+1 WHERE user_id='".$userdata['user_id']."'");
			redirect("postify.php?post=reply&error=0&forum_id=$forum_id&thread_id=$thread_id&post_id=$newpost_id");
		}
	}
}

opentable($locale['500']);
echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='smallalt'><a href='index.php'>".$settings['sitename']."</a> | $caption</td>\n";
if (iMEMBER && $can_post) {
	echo "<td align='right'>\n";
	if (!$tdata['thread_locked']) {
		echo "<a href='post.php?action=reply&amp;forum_id=$forum_id&amp;thread_id=$thread_id'><img src='".THEME."forum/reply.gif' alt='".$locale['565']."' style='border:0px;'></a>\n";
	}
	echo "<a href='post.php?action=newthread&amp;forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' alt='".$locale['566']."' style='border:0px;'></a></td>\n";
}
echo "</tr>
</table>\n";

$rows = dbcount("(thread_id)", "posts", "thread_id='$thread_id'");

if (isset($pid) && isNum($pid)) {
	$reply_count = dbcount("(post_id)", "posts", "thread_id='".$tdata['thread_id']."' AND post_id<='".$pid."'");
	if ($reply_count > $posts_per_page) $rowstart = ((ceil($reply_count / $posts_per_page)-1)*$posts_per_page);
}

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;

if ($rows > $posts_per_page) {
	echo "<div align='center' style='margin-top:5px;margin-bottom:5px;'>
".makePageNav($rowstart,$posts_per_page,$rows,3,FUSION_SELF."?forum_id=$forum_id&amp;thread_id=$thread_id&amp;")."
</div>\n";
}

echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";

if ($rows != 0) {
	$result = dbquery(
		"SELECT p.*, fa.attach_id, fa.attach_name, fa.attach_ext, fa.attach_size, u.*, u2.user_name AS edit_name FROM ".$db_prefix."posts p
		LEFT JOIN ".$db_prefix."forum_attachments fa USING(post_id)
		LEFT JOIN ".$db_prefix."users u ON p.post_author = u.user_id
		LEFT JOIN ".$db_prefix."users u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
		WHERE p.thread_id='$thread_id' ORDER BY post_datestamp LIMIT $rowstart,$posts_per_page"
	);
	$numrows = dbrows($result);
	while ($data = dbarray($result)) {
		$message = $data['post_message'];
		if ($data['post_showsig']) { $message = $message."\n\n<hr>".$data['user_sig']; }
		if ($data['post_smileys']) { $message = parsesmileys($message); }
		$message = parseubb($message);
		$message = nl2br($message);
		if ($data['post_edittime'] != "0") {
			$edituser = "<a href='../profile.php?lookup=".$data['post_edituser']."'>".$data['edit_name']."</a>";
			$edittime = showdate("forumdate", $data['post_edittime']);
		}
		$is_mod = in_array($data['user_id'], $forum_mods) && $data['user_level'] < "102" ? true : false;
		echo "<tr>
<td width='145' class='tbl2'>".$locale['501']."</td>
<td class='tbl2'><a name='post_".$data['post_id']."' id='post_".$data['post_id']."'></a>".$data['post_subject']."</td>
</tr>
<tr>
<td valign='top' rowspan='3' width='145' class='tbl1'>
<a href='../profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($data['user_level']))."</span><br><br>\n";
		if ($data['user_avatar'] != "") {
			echo "<img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$locale['567']."'><br><br>\n";
			$height = "185";
		} else {
			$height = "70";
		}
		echo "<span class='alt'>".$locale['502']."</span> ".$data['user_posts']."<br>\n";
		if ($data['user_location']) echo "<span class='alt'>".$locale['503']."</span> ".$data['user_location']."<br>\n";
		echo "<span class='alt'>".$locale['504']."</span> ".showdate("%d.%m.%y", $data['user_joined'])."</td>
<td>
<table cellspacing='0' cellpadding='0' width='100%'>
<tr>
<td class='tbl1'>".$locale['505'].showdate("forumdate", $data['post_datestamp'])."</td>
<td align='right' class='tbl1'>\n";
		if (iMEMBER && $can_post) {
			if (!$tdata['thread_locked']) {
				if ($userdata['user_id'] == $data['post_author'] || iMOD || iSUPERADMIN) {
					echo "<a href='post.php?action=edit&amp;forum_id=$forum_id&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id']."'><img src='".THEME."forum/edit.gif' alt='".$locale['568']."' style='border:0px;'></a>\n";
				}
				echo "<a href='post.php?action=reply&amp;forum_id=$forum_id&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id']."&amp;quote=".$data['post_id']."'><img src='".THEME."forum/quote.gif' alt='".$locale['569']."' style='border:0px;'></a>\n";
				if (iMOD || iSUPERADMIN && $data['post_ip'] != "0.0.0.0" && file_exists(THEME."forum/ip.gif")) echo "<img src='".THEME."forum/ip.gif' alt='".$locale['570']."' title='".$data['post_ip']."' style='border:0px;'>\n";
			} else {
				if (iMOD || iSUPERADMIN) {
					echo "<a href='post.php?action=edit&amp;forum_id=$forum_id&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id']."'><img src='".THEME."forum/edit.gif' alt='".$locale['568']."' style='border:0px;'></a>\n";
					if ($data['post_ip'] != "0.0.0.0" && file_exists(THEME."forum/ip.gif")) echo "<img src='".THEME."forum/ip.gif' alt='".$locale['570']."' title='".$data['post_ip']."' style='border:0px;'>\n";
				}
			}
		}
		echo "</td>
</tr>
</table>
</td>
</tr>
<tr>
<td valign='top' height='$height' class='tbl1'>
$message";
		if ($data['attach_id']) {
			if (in_array($data['attach_ext'], $imagetypes) && @getimagesize(FORUM."attachments/".$data['attach_name'])) {
				echo "<hr>\n".$data['user_name'].$locale['506']."<br><br>\n<img src='".FORUM."attachments/".$data['attach_name']."'>";
			} else {
				echo "<hr>\n".$data['user_name'].$locale['507']."<br>\n<a href='".FUSION_SELF."?forum_id=$forum_id&amp;thread_id=$thread_id&amp;getfile=".$data['post_id']."'>".$data['attach_name']."</a>";
			}
		}
		if ($data['post_edittime'] != "0") {
			echo "<hr>\n".$locale['508'].$edituser.$locale['509']."$edittime";
		}
echo "</td>
</tr>
<tr>
<td class='tbl1'>\n";
		if ($data['user_aim']) {
			echo "<a href='aim:goim?screenname=".str_replace(" ", "+", $data['user_aim'])."' target='_blank'><img src='".THEME."forum/aim.gif' alt='".$data['user_aim']."' style='border:0px;'></a> ";
		}
		if ($data['user_icq']) {
			echo "<a href='http://icq.com/people/about_me.php?uin=".$data['user_icq']."' target='_blank'><img src='".THEME."forum/icq.gif' alt='".$data['user_icq']."' style='border:0px;'></a> ";
		}
		if ($data['user_msn']) {
			echo "<a href='mailto:$data[user_msn]'><img src='".THEME."forum/msn.gif' alt='".$data['user_msn']."' style='border:0px;'></a> ";
		}
		if ($data['user_yahoo']) {
			echo "<a href='http://uk.profiles.yahoo.com/$data[user_yahoo]' target='_blank'><img src='".THEME."forum/yahoo.gif' alt='".$data['user_yahoo']."' style='border:0px;'></a> ";
		}
		if ($data['user_web']) {
			if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
			echo "<a href='".$urlprefix."".$data['user_web']."' target='_blank'><img src='".THEME."forum/web.gif' alt='".$data['user_web']."' style='border:0px;'></a> ";
		}
		if (iMEMBER && $data['user_id'] != $userdata['user_id']) echo "<a href='".BASEDIR."messages.php?msg_send=".$data['user_id']."'><img src='".THEME."forum/pm.gif' alt='".$locale['571']."' style='border:0px;'></a>\n";
echo "</td>
</tr>\n";
	}
}

echo "</table>\n";

if ($rows > $posts_per_page) {
	echo "<div align='center' style='margin-top:5px;'>
".makePageNav($rowstart,$posts_per_page,$rows,3,FUSION_SELF."?forum_id=$forum_id&amp;thread_id=$thread_id&amp;")."
</div>\n";
}

echo "<table cellpadding='0' cellspacing='0' width='100%' style='margin-top:5px;'>\n<tr>\n";
$forum_list = ""; $current_cat = "";
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM ".$db_prefix."forums f
	INNER JOIN ".$db_prefix."forums f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
while ($data = dbarray($result)) {
	if ($data['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") $forum_list .= "</optgroup>\n";
		$current_cat = $data['forum_cat_name'];
		$forum_list .= "<optgroup label='".$data['forum_cat_name']."'>\n";
	}
	$sel = ($data['forum_id'] == $fdata['forum_id'] ? " selected" : "");
	$forum_list .= "<option value='".$data['forum_id']."'$sel>".$data['forum_name']."</option>\n";
}
$forum_list .= "</optgroup>\n";
echo "<td align='left' class='tbl'>".$locale['540']."<br>
<select name='jump_id' class='textbox' onChange=\"jumpForum(this.options[this.selectedIndex].value);\">
$forum_list</select></td>\n";

if (iMEMBER && $can_post) {
	if (iMOD || iSUPERADMIN) {
		echo "<td align='right' class='tbl'>
<form name='modopts' method='post' action='options.php?forum_id=$forum_id&amp;thread_id=$thread_id'>
".$locale['520']."<br>
<select name='step' class='textbox'>
<option value='none'>&nbsp;</option>
<option value='renew'>".$locale['527']."</option>
<option value='delete'>".$locale['521']."</option>\n";
		if (!$tdata['thread_locked']) { 
			echo "<option value='lock'>".$locale['522']."</option>\n";
		} else {
			echo "<option value='unlock'>".$locale['523']."</option>\n";
		}
		if (!$tdata['thread_sticky']) {
			echo "<option value='sticky'>".$locale['524']."</option>\n";
		} else {
			echo "<option value='nonsticky'>".$locale['525']."</option>\n";
		}
		echo "<option value='move'>".$locale['526']."</option>\n";
		echo "</select>
<input type='submit' name='go' value='".$locale['528']."' class='button'>
</form>
</td>
</tr>\n";
	}
}
echo "</table>\n";
if (iMEMBER) {
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	if ($settings['thread_notify']) {
		echo "<td valign='top' class='tbl'>";
		if (dbcount("(thread_id)", "thread_notify", "thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'")) {
			$result = dbquery("UPDATE ".$db_prefix."thread_notify SET notify_datestamp='".time()."', notify_status='1' WHERE thread_id='$thread_id' AND notify_user='".$userdata['user_id']."'");
			$notify_link = "<a href='postify.php?post=off&amp;forum_id=$forum_id&amp;thread_id=$thread_id'>".$locale['515']."</a>";
		} else {
			$notify_link = "<a href='postify.php?post=on&amp;forum_id=$forum_id&amp;thread_id=$thread_id'>".$locale['516']."</a>";
		}
		echo "$notify_link</td>\n";
	}
	if ($can_post) {
		echo "<td align='right' class='tbl'>";
		if (!$tdata['thread_locked']) {
			echo "<a href='post.php?action=reply&amp;forum_id=$forum_id&amp;thread_id=$thread_id'><img src='".THEME."forum/reply.gif' alt='".$locale['565']."' style='border:0px;'></a>\n";
		}
		echo "<a href='post.php?action=newthread&amp;forum_id=$forum_id'><img src='".THEME."forum/newthread.gif' alt='".$locale['566']."' style='border:0px;'></a>\n";
		echo "</td>\n";
	}
	echo "</tr>\n</table>\n";
}
closetable();
tablebreak();

if (iMEMBER && $can_post && !$tdata['thread_locked']) {
	opentable($locale['512']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?forum_id=$forum_id&amp;thread_id=$thread_id'>
<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td align='center' class='tbl1'><textarea name='message' cols='80' rows='7' class='textbox'></textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\"></td>
</tr>
<tr>
<td align='center' class='tbl2'>".displaysmileys("message")."<br>
<input type='checkbox' name='disable_smileys' value='1'>".$locale['513']."</td>
</tr>
<tr>
<td align='center' class='tbl1'><input type='submit' name='postquickreply' value='".$locale['514']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
}

echo "<script type='text/javascript'>
function jumpForum(forumid) {
	document.location.href='".FORUM."viewforum.php?forum_id='+forumid;
}
</script>\n";

require_once BASEDIR."side_right.php";
require_once BASEDIR."footer.php";
?>