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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."messages.php";

if (!iMEMBER) fallback("index.php");

$msg_settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='0'"));

if (!isset($folder) || !preg_match("/^(inbox|outbox|archive|options)$/", $folder)) $folder = "inbox";
if (isset($_POST['msg_send']) && isNum($_POST['msg_send'])) $msg_send = $_POST['msg_send'];
if (isset($_POST['msg_to_group']) && isNum($_POST['msg_to_group'])) $msg_to_group = $_POST['msg_to_group'];

$msg_ids = ""; $check_count = 0;

if (isset($_POST['check_mark'])) {
	if (is_array($_POST['check_mark']) && count($_POST['check_mark']) > 1) {
		foreach ($_POST['check_mark'] as $thisnum) {
			if (isNum($thisnum)) $msg_ids .= ($msg_ids ? "," : "").$thisnum;
			$check_count++;
		}
	} else {
		if (isNum($_POST['check_mark'][0])) $msg_ids = $_POST['check_mark'][0];
		$check_count = 1;
	}
}

if (isset($_POST['save_options'])) {
	$pm_email_notify = isNum($_POST['pm_email_notify']) ? $_POST['pm_email_notify'] : "0";
	$pm_save_sent = isNum($_POST['pm_save_sent']) ? $_POST['pm_save_sent'] : "0";
	if ($_POST['update_type'] == "insert") {
		$result = dbquery("INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('".$userdata['user_id']."', '$pm_email_notify', '$pm_save_sent', '0', '0', '0')");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."messages_options SET pm_email_notify='$pm_email_notify', pm_save_sent='$pm_save_sent' WHERE user_id='".$userdata['user_id']."'");
	}
	redirect(FUSION_SELF."?folder=options");
}

if (isset($msg_id) && isNum($msg_id)) {
	if (isset($_POST['save'])) {
		$archive_total = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_folder='2'");
		if ($msg_settings['pm_savebox'] == "0" || ($archive_total + 1) <= $msg_settings['pm_savebox']) {
			$result = dbquery("UPDATE ".$db_prefix."messages SET message_folder='2' WHERE message_id='$msg_id' AND message_to='".$userdata['user_id']."'");
		} else {
			$error = "1";
		}
		redirect(FUSION_SELF."?folder=archive".($error ? "&error=$error" : ""));
	} elseif (isset($_POST['unsave'])) {
		$inbox_total = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_folder='0'");
		if ($msg_settings['pm_inbox'] == "0" || ($inbox_total + 1) <= $msg_settings['pm_inbox']) {
			$result = dbquery("UPDATE ".$db_prefix."messages SET message_folder='0' WHERE message_id='$msg_id' AND message_to='".$userdata['user_id']."'");
		} else {
			$error = "1";
		}
		redirect(FUSION_SELF."?folder=archive".($error ? "&error=$error" : ""));
	} elseif (isset($_POST['delete'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_id='$msg_id' AND message_to='".$userdata['user_id']."'");
		redirect(FUSION_SELF."?folder=$folder");
	}
}

if ($msg_ids && $check_count > 0) {
	if (isset($_POST['save_msg'])) {
		$archive_total = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_folder='2'");
		if ($msg_settings['pm_savebox'] == "0" || ($archive_total + $check_count) <= $msg_settings['pm_savebox']) {
			$result = dbquery("UPDATE ".$db_prefix."messages SET message_folder='2' WHERE message_id IN(".$msg_ids.") AND message_to='".$userdata['user_id']."'");
		} else {
			$error = "1";
		}
	} elseif (isset($_POST['unsave_msg'])) {
		$inbox_total = dbcount("(message_id)", "messages", "message_to='".$userdata['user_id']."' AND message_folder='0'");
		if ($msg_settings['pm_inbox'] == "0" || ($inbox_total + $check_count) <= $msg_settings['pm_inbox']) {
			$result = dbquery("UPDATE ".$db_prefix."messages SET message_folder='0' WHERE message_id IN(".$msg_ids.") AND message_to='".$userdata['user_id']."'");
		} else {
			$error = "1";
		}
	} elseif (isset($_POST['read_msg'])) {
		$result = dbquery("UPDATE ".$db_prefix."messages SET message_read='1' WHERE message_id IN(".$msg_ids.") AND message_to='".$userdata['user_id']."'");
	} elseif (isset($_POST['unread_msg'])) {
		$result = dbquery("UPDATE ".$db_prefix."messages SET message_read='0' WHERE message_id IN(".$msg_ids.") AND message_to='".$userdata['user_id']."'");
	} elseif (isset($_POST['delete_msg'])) {
		$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_id IN(".$msg_ids.") AND message_to='".$userdata['user_id']."'");
	}
	redirect(FUSION_SELF."?folder=$folder".($error ? "&error=$error" : ""));
}

if (isset($_POST['send_message'])) {
	$error = "";
	$result = dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result)) {
		$my_settings = dbarray($result);
	} else {
		$my_settings['pm_save_sent'] = $msg_settings['pm_save_sent'];
		$my_settings['pm_email_notify'] = $msg_settings['pm_email_notify'];
	}
	$subject = stripinput(trim($_POST['subject']));
	$message = stripinput(trim($_POST['message']));
	if ($subject == "" || $message == "") fallback(FUSION_SELF."?folder=inbox");
	$smileys = isset($_POST['chk_disablesmileys']) ? "n" : "y";
	require_once INCLUDES."sendmail_include.php";
	if (iADMIN && isset($_POST['chk_sendtoall']) && isNum($_POST['msg_to_group'])) {
		$msg_to_group = $_POST['msg_to_group'];
		if ($msg_to_group == "101" || $msg_to_group == "102" || $msg_to_group == "103") {
			$result = dbquery(
				"SELECT u.user_id, u.user_name, u.user_email, mo.pm_email_notify FROM ".$db_prefix."users u
				LEFT JOIN ".$db_prefix."messages_options mo USING(user_id)
				WHERE user_level>='".$msg_to_group."'"
			);
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					if ($data['user_id'] != $userdata['user_id']) {
						$result2 = dbquery("INSERT INTO ".$db_prefix."messages (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('".$data['user_id']."','".$userdata['user_id']."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
						$send_email = isset($data['pm_email_notify']) ? $data['pm_email_notify'] : $msg_settings['pm_email_notify'];
						if ($send_email == "1") sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$data['user_name'].$locale['626']);
					}
				}
			} else {
				fallback(FUSION_SELF."?folder=inbox");
			}
		} else {
			$result = dbquery(
				"SELECT u.user_id, u.user_name, u.user_email, mo.pm_email_notify FROM ".$db_prefix."users u
				LEFT JOIN ".$db_prefix."messages_options mo USING(user_id)
				WHERE user_groups REGEXP('^\\\.{$msg_to_group}$|\\\.{$msg_to_group}\\\.|\\\.{$msg_to_group}$')"
			);
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					if ($data['user_id'] != $userdata['user_id']) {
						$result2 = dbquery("INSERT INTO ".$db_prefix."messages (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('".$data['user_id']."','".$userdata['user_id']."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
						$send_email = isset($data['pm_email_notify']) ? $data['pm_email_notify'] : $msg_settings['pm_email_notify'];
						if ($send_email == "1") sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$data['user_name'].$locale['626']);
					}
				}
			} else {
				fallback(FUSION_SELF."?folder=inbox");
			}
		}
	} elseif (isNum($msg_send)) {
		$result = dbquery(
			"SELECT u.user_id, u.user_name, u.user_email, mo.pm_email_notify, COUNT(message_id) as message_count FROM ".$db_prefix."users u
			LEFT JOIN ".$db_prefix."messages_options mo USING(user_id)
			LEFT JOIN ".$db_prefix."messages ON message_to=u.user_id AND message_folder='0'
			WHERE u.user_id='".$msg_send."' GROUP BY u.user_id"
		);
		if (dbrows($result)) {
			$data = dbarray($result);
			if ($data['user_id'] != $userdata['user_id']) {
				if ($msg_settings['pm_inbox'] == "0" || ($data['message_count'] + 1) <= $msg_settings['pm_inbox']) {
					$result = dbquery("INSERT INTO ".$db_prefix."messages (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('".$data['user_id']."','".$userdata['user_id']."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
					$send_email = isset($data['pm_email_notify']) ? $data['pm_email_notify'] : $msg_settings['pm_email_notify'];
					if ($send_email == "1") sendemail($data['user_name'],$data['user_email'],$settings['siteusername'],$settings['siteemail'],$locale['625'],$data['user_name'].$locale['626']);
				} else {
					$error = "2";
				}
			}
		} else {
			fallback(FUSION_SELF."?folder=inbox");
		}
	}
	if (!$error) {
		$result =dbquery(
			"SELECT COUNT(message_id) AS outbox_count, MIN(message_id) AS last_message FROM ".$db_prefix."messages
			WHERE message_to='".$userdata['user_id']."' AND message_folder='1' GROUP BY message_to"
		);
		$cdata = dbarray($result);
		if ($my_settings['pm_save_sent']) {
			if ($msg_settings['pm_sentbox'] != "0" && ($cdata['outbox_count'] + 1) > $msg_settings['pm_sentbox']) {
				$result = dbquery("DELETE FROM ".$db_prefix."messages WHERE message_id='".$cdata['last_message']."' AND message_to='".$userdata['user_id']."'");
			}
			if (isset($_POST['chk_sendtoall']) && isNum($_POST['msg_to_group'])) {
				$outbox_user = $userdata['user_id'];
			} elseif (isset($msg_send) && isNum($msg_send)) {
				$outbox_user = $msg_send;
			} else {
				$msg_send = "";
			}
			if ($msg_send) { $result = dbquery("INSERT INTO ".$db_prefix."messages (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('".$userdata['user_id']."','".$outbox_user."','".$subject."','".$message."','".$smileys."','1','".time()."','1')"); }
		}
	}
	redirect(FUSION_SELF."?folder=inbox".($error ? "&error=$error" : ""));
}

if (isset($error)) {
	if ($error == "1") {
		$message = $locale['629'];
	} elseif ($error == "2") {
		$message = $locale['628'];
	} else {
		$message = "";
	}
	opentable($locale['627']);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (!isset($msg_send) && !isset($msg_read) && $folder != "options") {
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	$bdata = dbarray(dbquery(
		"SELECT COUNT(IF(message_folder=0, 1, null)) inbox_total,
		COUNT(IF(message_folder=1, 1, null)) outbox_total, COUNT(IF(message_folder=2, 1, null)) archive_total
		FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' GROUP BY message_to"
	));
	$bdata['inbox_total'] = isset($bdata['inbox_total']) ? $bdata['inbox_total'] : "0";
	$bdata['outbox_total'] = isset($bdata['outbox_total']) ? $bdata['outbox_total'] : "0";
	$bdata['archive_total'] = isset($bdata['archive_total']) ? $bdata['archive_total'] : "0";
	if ($folder == "inbox") {
		$total_rows = $bdata['inbox_total'];
		$result = dbquery(
			"SELECT m.*, u.user_id, u.user_name FROM ".$db_prefix."messages m
			LEFT JOIN ".$db_prefix."users u ON m.message_from=u.user_id
			WHERE message_to='".$userdata['user_id']."' AND message_folder='0'
			ORDER BY message_datestamp DESC LIMIT $rowstart,20"
		);
	} elseif ($folder == "outbox") {
		$total_rows = $bdata['outbox_total'];
		$result = dbquery(
			"SELECT m.*, u.user_id, u.user_name FROM ".$db_prefix."messages m
			LEFT JOIN ".$db_prefix."users u ON m.message_from=u.user_id
			WHERE message_to='".$userdata['user_id']."' AND message_folder='1'
			ORDER BY message_datestamp DESC LIMIT $rowstart,20"
		);
	} elseif ($folder == "archive") {
		$total_rows = $bdata['archive_total'];
		$result = dbquery(
			"SELECT m.*, u.user_id, u.user_name FROM ".$db_prefix."messages m
			LEFT JOIN ".$db_prefix."users u ON m.message_from=u.user_id
			WHERE message_to='".$userdata['user_id']."' AND message_folder='2'
			ORDER BY message_datestamp DESC LIMIT $rowstart,20"
		);
	}
	opentable($locale['400']);
	if ($total_rows) echo "<form name='pm_form' method='post' action='".FUSION_SELF."?folder=$folder'>\n";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr>\n<td align='left' width='100%' class='tbl'><a href='".FUSION_SELF."?msg_send=0'>".$locale['401']."</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="inbox"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=inbox'>".$locale['402']." [".$bdata['inbox_total']."/".$msg_settings['pm_inbox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="outbox"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=outbox'>".$locale['403']." [".$bdata['outbox_total']."/".$msg_settings['pm_sentbox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="archive"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=archive'>".$locale['404']." [".$bdata['archive_total']."/".$msg_settings['pm_savebox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="options"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=options'>".$locale['425']."</a></td>\n";
	echo "</tr>\n</table>\n";
	if ($total_rows) {
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		echo "<tr>\n<td class='tbl2'>".$locale['405']."</td>\n";
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".($folder != "outbox" ? $locale['406'] : $locale['421'])."</td>\n";
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".$locale['407']."</td>\n</tr>\n";
		while ($data = dbarray($result)) {
			$message_subject = $data['message_subject'];
			if (!$data['message_read']) $message_subject = "<b>".$message_subject."</b>";
			echo "<tr>\n<td class='tbl1'><input type='checkbox' name='check_mark[]' value='".$data['message_id']."'>\n";
			echo "<a href='".FUSION_SELF."?folder=$folder&amp;msg_read=".$data['message_id']."'>".$message_subject."</a></td>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n";
			echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".showdate("shortdate", $data['message_datestamp'])."</td>\n</tr>\n";
		}
		echo "</table>\n";
		
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n<td class='tbl'><a href='#' onClick=\"javascript:setChecked('pm_form','check_mark[]',1);return false;\">".$locale['410']."</a> |\n";
		echo "<a href='#' onClick=\"javascript:setChecked('pm_form','check_mark[]',0);return false;\">".$locale['411']."</a></td>\n";
		echo "<td align='right' class='tbl'>".$locale['409']."\n";
		if ($folder == "inbox") echo "<input type='submit' name='save_msg' value='".$locale['412']."' class='button'>\n";
		if ($folder == "archive") echo "<input type='submit' name='unsave_msg' value='".$locale['413']."' class='button'>\n";
		echo "<input type='submit' name='read_msg' value='".$locale['414']."' class='button'>\n";
		echo "<input type='submit' name='unread_msg' value='".$locale['415']."' class='button'>\n";
		echo "<input type='submit' name='delete_msg' value='".$locale['416']."' class='button'>\n";
		echo "</td>\n</tr>\n</table>\n</form>\n";
	} else {
		echo "<div align='center'><br>".$locale['461']."<br><br></div>";
	}
	echo "<script type='text/javascript'>"."\n"."function setChecked(frmName,chkName,val) {"."\n";
	echo "dml=document.forms[frmName];"."\n"."len=dml.elements.length;"."\n"."for(i=0;i < len;i++) {"."\n";
	echo "if(dml.elements[i].name == chkName) {"."\n"."dml.elements[i].checked = val;"."\n";
	echo "}\n}\n}\n</script>\n";
	closetable();
	if ($total_rows > 20) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$total_rows,3,FUSION_SELF."?folder=$folder&amp;")."\n</div>\n";
} elseif ($folder == "options") {
	$result = dbquery("SELECT * FROM ".$db_prefix."messages_options WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result)) {
		$my_settings = dbarray($result);
		$update_type = "update";
	} else {
		$my_settings['pm_save_sent'] = 0;
		$my_settings['pm_email_notify'] = 0;
		$update_type = "insert";
	}
	$bdata = dbarray(dbquery(
		"SELECT COUNT(IF(message_folder=0, 1, null)) inbox_total,
		COUNT(IF(message_folder=1, 1, null)) outbox_total, COUNT(IF(message_folder=2, 1, null)) archive_total
		FROM ".$db_prefix."messages WHERE message_to='".$userdata['user_id']."' GROUP BY message_to"
	));
	$bdata['inbox_total'] = isset($bdata['inbox_total']) ? $bdata['inbox_total'] : "0";
	$bdata['outbox_total'] = isset($bdata['outbox_total']) ? $bdata['outbox_total'] : "0";
	$bdata['archive_total'] = isset($bdata['archive_total']) ? $bdata['archive_total'] : "0";
	opentable($locale['400']);
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr>\n<td align='left' width='100%' class='tbl'><a href='".FUSION_SELF."?msg_send=0'>".$locale['401']."</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="inbox"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=inbox'>".$locale['402']." [".$bdata['inbox_total']."/".$msg_settings['pm_inbox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="outbox"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=outbox'>".$locale['403']." [".$bdata['outbox_total']."/".$msg_settings['pm_sentbox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="archive"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=archive'>".$locale['404']." [".$bdata['archive_total']."/".$msg_settings['pm_savebox']."]</a></td>\n";
	echo "<td width='1%' class='tbl' style='white-space:nowrap;font-weight:".($folder=="options"?"bold":"normal")."'><a href='".FUSION_SELF."?folder=options'>".$locale['425']."</a></td>\n";
	echo "</tr>\n</table>\n";
	tablebreak();
	echo "<form name='options_form' method='post' action='".FUSION_SELF."?folder=options'>\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='500'>\n";
	echo "<tr><td class='tbl1' width='60%'>".$locale['621']."</td>\n";
	echo "<td class='tbl1' width='40%'><select name='pm_email_notify' class='textbox'>\n";
	echo "<option value='1'".($my_settings['pm_email_notify'] ? " selected" : "").">".$locale['631']."</option>\n";
	echo "<option value='0'".(!$my_settings['pm_email_notify'] ? " selected" : "").">".$locale['632']."</option>\n";
	echo "</select></td></tr>\n";
	echo "<tr><td class='tbl1' width='60%'>".$locale['622']."</td>\n";
	echo "<td class='tbl1' width='40%'><select name='pm_save_sent' class='textbox'>\n";
	echo "<option value='1'".($my_settings['pm_save_sent'] ? " selected" : "").">".$locale['631']."</option>\n";
	echo "<option value='0'".(!$my_settings['pm_save_sent'] ? " selected" : "").">".$locale['632']."</option>\n";
	echo "</select></td></tr>\n";
	echo "<tr><td align='center' colspan='2' class='tbl1'>\n";
	echo "<br><input type='hidden' name='update_type' value='$update_type'>\n";
	echo "<input type='submit' name='save_options' value='".$locale['623']."' class='button'></td>\n</tr>\n";
	echo "</table></form>\n";
	closetable();
} elseif ((isset($msg_read) && isNum($msg_read)) && ($folder == "inbox" || $folder == "archive" || $folder == "outbox")) {
	$result = dbquery(
		"SELECT m.*, u.user_id, u.user_name FROM ".$db_prefix."messages m
		LEFT JOIN ".$db_prefix."users u ON m.message_from=u.user_id
		WHERE message_to='".$userdata['user_id']."' AND message_id='$msg_read'"
	);
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("UPDATE ".$db_prefix."messages SET message_read='1' WHERE message_id='".$data['message_id']."'");
		$message_message = $data['message_message'];
		if ($data['message_smileys'] == "y") $message_message = parsesmileys($message_message);
		opentable($locale['431']);
		echo "<form name='pm_form' method='post' action='".FUSION_SELF."?folder=$folder&amp;msg_send=".$data['user_id']."&amp;msg_id=".$data['message_id']."'>\n";
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
		echo "<td align='right' width='1%' class='tbl2' style='white-space:nowrap'>".($folder != "outbox" ? $locale['406'] : $locale['421'])."</td>\n";
		echo "<td class='tbl1'><a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a></td>\n</tr>\n";
		echo "<tr>\n<td align='right' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['407']."</td>\n";
		echo "<td class='tbl1'>".showdate("longdate", $data['message_datestamp'])."</td>\n</tr>\n";
		echo "<tr>\n<td align='right' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['405']."</td>\n";
		echo "<td class='tbl1'>".$data['message_subject']."</td>\n</tr>\n";
		echo "<tr>\n<td colspan='2' class='tbl1'>".nl2br(parseubb($message_message))."</td>\n</tr>\n";
		echo "</table>\n";
		echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
		echo "<tr>\n<td colspan='2' class='tbl'><a href='".FUSION_SELF."?folder=$folder'>".$locale['432']."</a></td>\n";
		echo "<td align='right' class='tbl'>\n";
		if ($folder == "inbox" && $data['message_folder'] == 0) echo "<input type='submit' name='reply' value='".$locale['439']."' class='button'>\n";
		if ($folder == "inbox" && $data['message_folder'] == 0) echo "<input type='submit' name='save' value='".$locale['412']."' class='button'>\n";
		if ($folder == "archive" && $data['message_folder'] == 2) echo "<input type='submit' name='unsave' value='".$locale['413']."' class='button'>\n";
		echo "<input type='submit' name='delete' value='".$locale['416']."' class='button'>\n";
		echo "</td>\n</tr>\n</table>\n</form>\n";
		closetable();
	} else {
		fallback(FUSION_SELF);
	}
} elseif (isset($msg_send) && isNum($msg_send)) {
	if (isset($_POST['send_preview'])) {
		$subject = stripinput($_POST['subject']);
		$message = stripinput($_POST['message']);
		$message_preview = $message;
		if (isset($_POST['chk_sendtoall']) && isNum($_POST['msg_to_group'])) {
			$msg_to_group = $_POST['msg_to_group'];
			$sendtoall_chk = " checked";
			$msg_to_group_state = "";
			$msg_send_state = " disabled";
		} else {
			$sendtoall_chk = "";
			$msg_to_group_state = " disabled";
			$msg_send_state = "";
		}
		$disablesmileys_chk = isset($_POST['chk_disablesmileys']) ? " checked" : "";
		if (!$disablesmileys_chk) $message_preview = parsesmileys($message_preview);
		opentable($locale['438']);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
		echo "<td class='tbl1'>".nl2br(parseubb($message_preview))."</td>\n</tr>\n";
		echo "</table>\n";
		closetable();
	} else {
		$subject = ""; $message = ""; $msg_send_state = ""; $msg_to_group = "";
		$msg_to_group_state = " disabled"; $sendtoall_chk = ""; $disablesmileys_chk = "";	
	}	
	
	if (isset($msg_id) && isNum($msg_id)) {
		$result = dbquery(
			"SELECT m.*, u.user_id, u.user_name FROM ".$db_prefix."messages m
			LEFT JOIN ".$db_prefix."users u ON m.message_from=u.user_id
			WHERE message_to='".$userdata['user_id']."' AND message_id='$msg_id'"
		);
		$data = dbarray($result);
		$msg_send = $data['user_id'];
		if ($subject == "") $subject = (!strstr($data['message_subject'], "RE: ") ? "RE: " : "").$data['message_subject'];
		$reply_message = $data['message_message'];
		if ($data['message_smileys'] == "y") $reply_message = parsesmileys($reply_message);
	} else {
		$reply_message = "";
	}
		
	if (!isset($_POST['chk_sendtoall']) || $msg_send != "0") {
		$user_list = ""; $user_types = ""; $sel = "";
		$result = dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u ORDER BY user_level DESC, user_name ASC");
		while ($data = dbarray($result)) {
			if ($data['user_id'] != $userdata['user_id']) {
				$sel = ($msg_send == $data['user_id'] ? " selected" : "");
				$user_list .= "<option value='".$data['user_id']."'$sel>".$data['user_name']."</option>\n";
			}
		}
	}

	if ($msg_send == 0) { $user_list .= "<option value='---' disabled>---</option>\n"; }
	
	if (iADMIN && !isset($msg_id)) {
		$user_groups = getusergroups();
		while(list($key, $user_group) = each($user_groups)){
			if ($user_group['0'] != "0") {
				$sel = ($msg_to_group == $user_group['0'] ? " selected" : "");
				$user_types .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
			}
		}
	}

	opentable($locale['420']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?msg_send=0' onSubmit=\"return ValidateForm(this)\">\n";
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	echo "<tr>\n<td align='right' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['421'].":</td>\n";
	if ($msg_send == "0") {
		echo "<td class='tbl1'><select name='msg_send' class='textbox'>\n".$user_list."</select>\n";
	} else {
		$udata = dbarray(dbquery("SELECT u.user_id, u.user_name FROM ".$db_prefix."users u WHERE user_id='$msg_send'"));
		echo "<td class='tbl1'><input type='hidden' name='msg_send' value='".$udata['user_id']."'>\n";
		echo "<a href='profile.php?lookup=".$udata['user_id']."'>".$udata['user_name']."</a>\n";
	}
	if (iADMIN && !isset($msg_id)) {
		echo "<input name='chk_sendtoall' type='checkbox' $sendtoall_chk>\n";
		echo "".$locale['434'].": <select name='msg_to_group' class='textbox'>\n".$user_types."</select>\n";
	}
	echo "</td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' style='white-space:nowrap'>".$locale['405'].":</td>\n";
	echo "<td class='tbl1'><input type='text' name='subject' value='$subject' maxlength='32' class='textbox' style='width:400px;'></td>\n</tr>\n";
	if ($reply_message) {
		echo "<tr>\n<td align='right' class='tbl2' valign='top' style='white-space:nowrap'>".$locale['422'].":</td>\n";
		echo "<td class='tbl1'>".nl2br(parseubb($reply_message))."</td>\n</tr>\n";
	}
	echo "<tr>\n<td align='right' class='tbl2' valign='top' style='white-space:nowrap'>".($reply_message ? $locale['433'] : $locale['422']).":</td>\n";
	echo "<td class='tbl1'><textarea name='message' cols='80' rows='15' class='textbox'>$message</textarea></td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' valign='top'></td>\n<td class='tbl1'>\n";
	echo "<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('message', '[b]', '[/b]');\">\n";
	echo "<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('message', '[i]', '[/i]');\">\n";
	echo "<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('message', '[u]', '[/u]');\">\n";
	echo "<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('message', '[url]', '[/url]');\">\n";
	echo "<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('message', '[mail]', '[/mail]');\">\n";
	echo "<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('message', '[img]', '[/img]');\">\n";
	echo "<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('message', '[center]', '[/center]');\">\n";
	echo "<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('message', '[small]', '[/small]');\">\n";
	echo "<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('message', '[code]', '[/code]');\">\n";
	echo "<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('message', '[quote]', '[/quote]');\"><br><br>\n";
	echo displaysmileys("message")."</td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' valign='top' style='white-space:nowrap'>".$locale['425'].":</td>\n";
	echo "<td class='tbl1'>\n<input type='checkbox' name='chk_disablesmileys' value='y'$disablesmileys_chk>".$locale['427']."</td>\n</tr>\n";
	echo "</table>\n";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr>\n<td class='tbl'><a href='".FUSION_SELF."?folder=inbox'>".$locale['435']."</a></td>\n";
	echo "<td align='right' class='tbl'>\n<input type='submit' name='send_preview' value='".$locale['429']."' class='button'>\n";
	echo "<input type='submit' name='send_message' value='".$locale['430']."' class='button'>\n</td>\n</tr>\n";
	echo "</table>\n</form>\n";
	closetable();
	echo "<script type='text/javascript'>function ValidateForm(frm){\n";
	echo "if (frm.subject.value == \"\" || frm.message.value == \"\"){\n";
	echo "alert(\"".$locale['486']."\");return false;}\n";
	echo "}\n</script>\n";

} else {
	fallback(FUSION_SELF);
}

require_once "side_right.php";
require_once "footer.php";
?>