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
+----------------------------------------------------+
| Comments system developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

include LOCALE.LOCALESET."comments.php";

function showcomments($ctype,$cdb,$ccol,$cid,$clink) {

	global $settings,$locale,$userdata;
	
	if ((iMEMBER || $settings['guestposts'] == "1") && isset($_POST['post_comment'])) {
		if (dbrows(dbquery("SELECT $ccol FROM ".DB_PREFIX."$cdb WHERE $ccol='$cid'"))==0) {
			fallback(BASEDIR."index.php");
		}
		if (iMEMBER) {
			$comment_name = $userdata['user_id'];
		} elseif ($settings['guestposts'] == "1") {
			$comment_name = trim(stripinput($_POST['comment_name']));
			$comment_name = preg_replace("(^[0-9]*)", "", $comment_name);
			if (isNum($comment_name)) $comment_name="";
		}
		$comment_message = trim(stripinput(censorwords($_POST['comment_message'])));
		$comment_smileys = isset($_POST['disable_smileys']) ? "0" : "1";
		if ($comment_name != "" && $comment_message != "") {
			$result = dbquery("INSERT INTO ".DB_PREFIX."comments (comment_item_id, comment_type, comment_name, comment_message, comment_smileys, comment_datestamp, comment_ip) VALUES ('$cid', '$ctype', '$comment_name', '$comment_message', '$comment_smileys', '".time()."', '".USER_IP."')");
		}
		redirect($clink);
	}

	tablebreak();
	opentable($locale['c100']);
	$result = dbquery(
		"SELECT tcm.*,user_name FROM ".DB_PREFIX."comments tcm
		LEFT JOIN ".DB_PREFIX."users tcu ON tcm.comment_name=tcu.user_id
		WHERE comment_item_id='$cid' AND comment_type='$ctype'
		ORDER BY comment_datestamp ASC"
	);
	if (dbrows($result) != 0) {
		$i = 0;
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>\n";
			if ($data['user_name']) {
				echo "<a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."'>".$data['user_name']."</a>";
			} else {
				echo $data['comment_name'];
			}
			if ($data['comment_smileys'] == "1") {
				$comment_message = parsesmileys($data['comment_message']);
			} else {
				$comment_message = $data['comment_message'];
			}
			$comment_message = nl2br(parseubb($comment_message));
			echo "</span>\n<span class='small'>".$locale['041'].showdate("longdate", $data['comment_datestamp'])."</span><br>\n";
			echo $comment_message."</td>\n</tr>\n";
			$i++;
		}
		if (checkrights("C")) echo "<tr>\n<td align='right' class='".($i% 2==0?"tbl1":"tbl2")."'><a href='".ADMIN."comments.php?ctype=$ctype&cid=$cid'>".$locale['c106']."</a></td>\n</tr>\n";
		echo "</table>\n";
	} else {
		echo $locale['c101']."\n";
	}
	closetable();
	tablebreak();
	opentable($locale['c102']);
	if (iMEMBER || $settings['guestposts'] == "1") {
		echo "<form name='inputform' method='post' action='$clink'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>\n";
		if (iGUEST) {
			echo "<tr>
<td>".$locale['c103']."</td>
</tr>
<tr>
<td><input type='text' name='comment_name' maxlength='30' class='textbox' style='width:100%;'></td>
</tr>\n";
		}
		echo "<tr>
<td align='center'><textarea name='comment_message' rows='6' class='textbox' style='width:400px'></textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('comment_message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('comment_message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('comment_message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('comment_message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('comment_message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('comment_message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('comment_message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('comment_message', '[small]', '[/small]');\">
<input type='button' value='code' class='button' style='width:40px;' onClick=\"addText('comment_message', '[code]', '[/code]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('comment_message', '[quote]', '[/quote]');\">
<br><br>
".displaysmileys("comment_message")."
</tr>
<tr>
<td align='center'><input type='checkbox' name='disable_smileys' value='1'>".$locale['c107']."<br><br>
<input type='submit' name='post_comment' value='".$locale['c102']."' class='button'></td>
</tr>
</table>
</form>\n";
	} else {
		echo $locale['c105']."\n";
	}
	closetable();
}
?>