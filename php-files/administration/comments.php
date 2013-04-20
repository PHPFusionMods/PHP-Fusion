<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright (c) 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/comments.php";

if (!checkrights("C")) fallback("../index.php");
if (isset($comment_id) && !isNum($comment_id)) fallback("index.php");

if (!isset($ctype) || !preg_match("/^[0-9A-Z]+$/i", $ctype)) fallback("../index.php");
if (!isset($cid) || !isNum($cid)) fallback("../index.php");

if (isset($_POST['save_comment'])) {
	$comment_message = stripinput($_POST['comment_message']);
	$comment_smileys = isset($_POST['disable_smileys']) ? "0" : "1";
	$result = dbquery("UPDATE ".$db_prefix."comments SET comment_message='$comment_message', comment_smileys='$comment_smileys' WHERE comment_id='$comment_id'");
	redirect("comments.php?ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	redirect("comments.php?ctype=$ctype&cid=$cid");
}
if (isset($step) && $step == "edit") {
	$result = dbquery("SELECT * FROM ".$db_prefix."comments WHERE comment_id='$comment_id'");
	if (!dbrows($result)) fallback("comments.php?ctype=$ctype&cid=$cid");
	$data = dbarray($result);
	$comment_smileys = $data['comment_smileys'] ? "" : " checked";
	tablebreak();
	opentable($locale['400']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?comment_id=$comment_id&amp;ctype=$ctype&amp;cid=$cid'>
<table align='center' cellpadding='0' cellspacing='0' width='400' >
<tr>
<td align='center' class='tbl'><textarea name='comment_message' rows='5' class='textbox' style='width:400px'>".$data['comment_message']."</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('comment_message', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('comment_message', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('comment_message', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('comment_message', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('comment_message', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('comment_message', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('comment_message', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('comment_message', '[small]', '[/small]');\">
</tr>
<tr>
<td align='center' class='tbl'><input type='checkbox' name='disable_smileys' value='1'$comment_smileys>".$locale['402']."<br><br>
<input type='submit' name='save_comment' value='".$locale['401']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
}
opentable($locale['410']);
$i = 0;
$result = dbquery(
	"SELECT * FROM ".$db_prefix."comments LEFT JOIN ".$db_prefix."users
	ON ".$db_prefix."comments.comment_name=".$db_prefix."users.user_id
	WHERE comment_type='$ctype' AND comment_item_id='$cid' ORDER BY comment_datestamp ASC"
);
if (dbrows($result)) {
	echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	while ($data = dbarray($result)) {
		echo "<tr>\n<td class='".($i% 2==0?"tbl1":"tbl2")."'><span class='comment-name'>";
		if ($data['user_name']) {
			echo "<a href='".BASEDIR."profile.php?lookup=".$data['comment_name']."' class='slink'>".$data['user_name']."</a>";
		} else {
			echo $data['comment_name'];
		}
		$comment_message = nl2br(parseubb($data['comment_message']));
		if ($data['comment_smileys'] == "1") $comment_message = parsesmileys($comment_message);
		echo "</span>
<span class='small'>".$locale['041'].showdate("longdate", $data['comment_datestamp'])."</span><br>
".$comment_message."<br>
<span class='small'><a href='".FUSION_SELF."?step=edit&amp;comment_id=".$data['comment_id']."&amp;ctype=$ctype&amp;cid=$cid'>".$locale['411']."</a> -
<a href='".FUSION_SELF."?step=delete&amp;comment_id=".$data['comment_id']."&amp;ctype=$ctype&amp;cid=$cid' onClick='return DeleteItem()'>".$locale['412']."</a> -
<b>".$locale['413'].$data['comment_ip']."</b></span>
</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
} else {
	echo "<center><br>".$locale['415']."<br><br></center>\n";
}
closetable();
echo "<script>
function DeleteItem() {
	return confirm(\"".$locale['414']."\");
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>