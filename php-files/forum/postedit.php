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
if (!defined("IN_FUSION")) header("Location:../index.php");

if (isset($_POST['previewchanges'])) {
	$disable_smileys_check = isset($_POST['disable_smileys']) ? " checked" : "";
	$del_check = isset($_POST['delete']) ? " checked" : "";
	$del_attach_check = isset($_POST['delete_attach']) ? " checked" : "";
	opentable($locale['405']);
	$subject = trim(stripinput(censorwords($_POST['subject'])));
	$message = trim(stripinput(censorwords($_POST['message'])));
	if ($subject == "") $subject = $pdata['post_subject'];
	if ($message == "") {
		$previewmessage = $locale['421'];
	} else {
		$previewmessage = $message;
		if (!$disable_smileys_check) { $previewmessage = parsesmileys($previewmessage); }
		$previewmessage = parseubb($previewmessage);
		$previewmessage = nl2br($previewmessage);
	}
	$udata = dbarray(dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$pdata['post_author']."'"));
	$is_mod = in_array($udata['user_id'], $forum_mods) && $udata['user_level'] < "102" ? true : false;
	echo "<table cellspacing='0' cellpadding='0' border='0' width='100%' class='tbl-border'>
<tr>
<td>
<table cellpadding='0' cellspacing='1' width='100%'>
<tr>
<td width='145' class='tbl2'>".$locale['422']."</td>
<td class='tbl2'>$subject</td>
</tr>
<tr>
<td valign='top' rowspan='2' width='145' class='tbl1'>".$udata['user_name']."<br>
<span class='alt'>".($is_mod ? $locale['userf1'] : getuserlevel($udata['user_level']))."</span><br><br>\n";
	if ($udata['user_avatar']) {
		echo "<img src='".IMAGES."avatars/".$udata['user_avatar']."'><br><br>\n";
		$height = "200";
	} else {
		$height = "60";
	}
	echo "<span class='alt'>".$locale['423']."</span> ".$udata['user_posts']."<br>\n";
	if ($udata['user_location']) echo "<span class='alt'>".$locale['424']."</span> ".$udata['user_location']."<br>\n";
	echo "<span class='alt'>".$locale['425']."</span> ".showdate("%d.%m.%y", $udata['user_joined'])."</td>
<td class='tbl1'><span class='small'>".$locale['426'].showdate("forumdate", $pdata['post_datestamp'])."</span></td>
</tr>
<tr>
<td height='50' valign='top' class='tbl1'>$previewmessage<br>
<br>
<span class='small'>".$locale['427'].$userdata['user_name'].$locale['428'].showdate("forumdate", time())."</span>
</td>
</tr>
</table>
</tr>
</td>
</table>\n";
	closetable();
	tablebreak();
}
if (isset($_POST['savechanges'])) {
	if (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."posts WHERE post_id='$post_id' AND thread_id='$thread_id'");
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result) != 0) {
			$attach = dbarray($result);
			unlink(FORUM."attachments/".$attach['attach_name']."[".$attach['attach_id']."]".$attach['attach_ext']);
			$result2 = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		}
		$posts = dbcount("(post_id)", "posts", "thread_id='$thread_id'");
		if ($posts == 0) $result = dbquery("DELETE FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND forum_id='$forum_id'");
		// update forum_lastpost and forum_lastuser if post_datestamp matches
		$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id' AND forum_lastuser='".$pdata['post_author']."' AND forum_lastpost='".$pdata['post_datestamp']."'");
		if (dbrows($result)) {
			$result = dbquery("SELECT forum_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE forum_id='$forum_id' ORDER BY post_datestamp DESC LIMIT 1");
			if (dbrows($result)) { 
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='".$pdata2['post_datestamp']."', forum_lastuser='".$pdata2['post_author']."' WHERE forum_id='$forum_id'");
			} else {
				$result = dbquery("UPDATE ".$db_prefix."forums SET forum_lastpost='0', forum_lastuser='0' WHERE forum_id='$forum_id'");
			}
		}
		// update thread_lastpost and thread_lastuser if thread post > 0 and post_datestamp matches
		if ($posts > 0) {
			$result = dbquery("SELECT * FROM ".$db_prefix."threads WHERE thread_id='$thread_id' AND thread_lastpost='".$pdata['post_datestamp']."' AND thread_lastuser='".$pdata['post_author']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT thread_id,post_author,post_datestamp FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_datestamp DESC LIMIT 1");
				$pdata2 = dbarray($result);
				$result = dbquery("UPDATE ".$db_prefix."threads SET thread_lastpost='".$pdata2['post_datestamp']."', thread_lastuser='".$pdata2['post_author']."' WHERE thread_id='$thread_id'");
			}
		}
		opentable($locale['407']);
		echo "<center><br>\n".$locale['445']."<br><br>\n";
		if ($posts > 0) echo "<a href='viewthread.php?forum_id=$forum_id&thread_id=$thread_id'>".$locale['447']."</a> |\n";
		echo "<a href='viewforum.php?forum_id=$forum_id'>".$locale['448']."</a> |
<a href='index.php'>".$locale['449']."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$error = "0";
		$smileys = isset($_POST['disable_smileys']) ? "0" : "1";
		$subject = trim(stripinput(censorwords($_POST['subject'])));
		$message = trim(stripinput(censorwords($_POST['message'])));
		if (iMEMBER) {
			if ($subject != "" && $message != "") {
				$result = dbquery("UPDATE ".$db_prefix."posts SET post_subject='$subject', post_message='$message', post_smileys='$smileys', post_edituser='".$userdata['user_id']."', post_edittime='".time()."' WHERE post_id='$post_id'");
				$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."posts WHERE thread_id='$thread_id' ORDER BY post_id ASC LIMIT 1"));
				if ($data['post_id'] == $post_id) {
					$result = dbquery("UPDATE ".$db_prefix."threads SET thread_subject='$subject' WHERE thread_id='$thread_id'");
				}
				if (isset($_POST['delete_attach'])) {
					$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
					if (dbrows($result) != 0) {
						$adata = dbarray($result);
						unlink(FORUM."attachments/".$adata['attach_name']."[".$adata['attach_id']."]".$adata['attach_ext']);
						$result = dbquery("DELETE FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
					}
				}
				$attach = $_FILES['attach'];
				if ($attach['name'] != "" && !empty($attach['name']) && is_uploaded_file($attach['tmp_name'])) {
					if (preg_match("/^[-0-9A-Z_\.\[\]]+$/i", $attach['name']) && $attach['size'] <= $settings['attachmax']) {
						$attachext = strtolower(strrchr($attach['name'],"."));
						$attachtypes = explode(",", $settings['attachtypes']);
						if (in_array($attachext, $attachtypes)) {
							$result = dbarray(dbquery("SHOW TABLE STATUS LIKE '".$db_prefix."forum_attachments'"));
							$file_id = $result['Auto_increment'];
							$attachname = substr($attach['name'], 0, strrpos($attach['name'], "."));
							move_uploaded_file($attach['tmp_name'], FORUM."attachments/".$attachname."[".$file_id."]".$attachext);
							chmod(FORUM."attachments/".$attachname."[".$file_id."]".$attachext,0644);
							if (in_array($attachext, $imagetypes) && !@getimagesize(FORUM."attachments/".$attachname."[".$file_id."]".$attachext)) {
								unlink(FORUM."attachments/".$attachname."[".$file_id."]".$attachext);
								$error = "1";
							}
							if (!$error) $result = dbquery("INSERT INTO ".$db_prefix."forum_attachments (thread_id, post_id, attach_name, attach_ext, attach_size) VALUES ('$thread_id', '$post_id', '$attachname', '$attachext', '".$attach['size']."')");
						} else {
							@unlink($attach['tmp_name']);
							$error = "1";
						}
					} else {
						@unlink($attach['tmp_name']);
						$error = "1";
					}
				}
				$reply_count = dbcount("(thread_id)", "posts", "post_id<='$post_id' AND thread_id='$thread_id'");
				$rstart = ($reply_count > 20 ? ((ceil($reply_count / 20)-1)*20) : "0");
			} else {
				$error = "2";
			}
		} else {
			$error = "3";
		}
		redirect("postify.php?post=edit&error=$error&forum_id=$forum_id&thread_id=$thread_id&rstart=$rstart&post_id=$post_id");
	}
} else {
	if (!isset($_POST['previewchanges'])) {
		$subject = $pdata['post_subject'];
		$message = $pdata['post_message'];
		$disable_smileys_check = ($pdata['post_smileys'] == "0" ? " checked" : "");
		$del_check = "";
	}
	opentable($locale['408']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?action=edit&amp;forum_id=$forum_id&amp;thread_id=$thread_id&amp;post_id=$post_id' enctype='multipart/form-data'>
<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>
<tr>
<td>
<table width='100%' border='0' cellspacing='1' cellpadding='0'>
<tr>
<td width='145' class='tbl2'>".$locale['460']."</td>
<td class='tbl2'><input type='text' name='subject' value='$subject' class='textbox' maxlength='255' style='width:250px'></td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".$locale['461']."</td>
<td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox'>$message</textarea></td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\">
</td>
</tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl1'>
".$locale['462']."<select name='bbcolor' class='textbox' style='width:90px;' onChange=\"addText('message', '[color=' + this.options[this.selectedIndex].value + ']', '[/color]');this.selectedIndex=0;\">
<option value=''>Default</option>
<option value='maroon' style='color:maroon;'>Maroon</option>
<option value='red' style='color:red;'>Red</option>
<option value='orange' style='color:orange;'>Orange</option>
<option value='brown' style='color:brown;'>Brown</option>
<option value='yellow' style='color:yellow;'>Yellow</option>
<option value='green' style='color:green;'>Green</option>
<option value='lime' style='color:lime;'>Lime</option>
<option value='olive' style='color:olive;'>Olive</option>
<option value='cyan' style='color:cyan;'>Cyan</option>
<option value='blue' style='color:blue;'>Blue</option>
<option value='navy' style='color:navy;'>Navy Blue</option>
<option value='purple' style='color:purple;'>Purple</option>
<option value='violet' style='color:violet;'>Violet</option>
<option value='black' style='color:black;'>Black</option>
<option value='gray' style='color:gray;'>Gray</option>
<option value='silver' style='color:silver;'>Silver</option>
<option value='white' style='color:white;'>White</option>
</select>
</td>
</tr>
<tr>
<td width='145' class='tbl2'>&nbsp;</td>
<td class='tbl2'>
".displaysmileys("message")."
</td>
</tr>
<tr>
<td valign='top' width='145' class='tbl2'>".$locale['463']."</td>
<td class='tbl1'>
<input type='checkbox' name='disable_smileys' value='1'$disable_smileys_check>".$locale['483']."<br>
<input type='checkbox' name='delete' value='1'$del_check>".$locale['482']."
</td>
</tr>\n";
	if ($settings['attachments'] == "1") {
		echo "<tr>\n<td valign='top' width='145' class='tbl2'>".$locale['464']."</td>\n<td class='tbl1'>\n";
		$result = dbquery("SELECT * FROM ".$db_prefix."forum_attachments WHERE post_id='$post_id'");
		if (dbrows($result)) {
			$adata = dbarray($result);
			echo "<input type='checkbox' name='delete_attach' value='1'$del_attach_check>".$locale['484']."\n";
			echo "<a href='".FORUM."attachments/".$adata['attach_name']."[".$adata['attach_id']."]".$adata['attach_ext']."'>".$adata['attach_name']."[".$adata['attach_id']."]".$adata['attach_ext']."</a>\n";
		} else {
			echo "<input type='file' name='attach' enctype='multipart/form-data' class='textbox' style='width:200px;'><br>\n";
			echo "<span class='small2'>".sprintf($locale['466'], parsebytesize($settings['attachmax']), str_replace(',', ' ', $settings['attachtypes']))."</span>";
	
		}
		echo "</td>\n</tr>\n";
	}
	echo "</table>
</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='previewchanges' value='".$locale['405']."' class='button'>
<input type='submit' name='savechanges' value='".$locale['409']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}
?>