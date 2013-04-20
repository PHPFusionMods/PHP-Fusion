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
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/shoutbox.php";

if (!checkrights("S") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($shout_id) && !isNum($shout_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($action)) $action = "";

if (isset($status)) {
	if ($status == "delall") {
		$title = $locale['400'];
		$message = "<b>".$numr." ".$locale['401']."</b>";
	} elseif ($status == "del") {
		$title = $locale['404'];
		$message = "<b>".$locale['405']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if ($action == "deleteshouts") {
	$deletetime = time() - ($_POST['num_days'] * 86400);
	$result = dbquery("SELECT * FROM ".$db_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
	$numrows = dbrows($result);
	$result = dbquery("DELETE FROM ".$db_prefix."shoutbox WHERE shout_datestamp < '$deletetime'");
	redirect(FUSION_SELF.$aidlink."&status=delall&numr=$numrows");
} else if ($action == "delete") {
	$result = dbquery("DELETE FROM ".$db_prefix."shoutbox WHERE shout_id='$shout_id'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	if (isset($_POST['saveshout'])) {
		if ($action == "edit") {
			$shout_message = str_replace("\n", " ", $_POST['shout_message']);
			$shout_message = preg_replace("/^(.{255}).*$/", "$1", $shout_message);
			$shout_message = preg_replace("/([^\s]{25})/", "$1\n", $shout_message);
			$shout_message = stripinput($shout_message);
			$shout_message = str_replace("\n", "<br>", $shout_message);
			$result = dbquery("UPDATE ".$db_prefix."shoutbox SET shout_message='$shout_message' WHERE shout_id='$shout_id'");
			redirect(FUSION_SELF.$aidlink);
		}
	}
	if ($action == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."shoutbox WHERE shout_id='$shout_id'");
		$data = dbarray($result);
		opentable($locale['420']);
		echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['421']."</td>
</tr>
<tr>
<td class='tbl'><textarea name='shout_message' rows='3' cols='44' class='textbox'>".str_replace("<br>", "", $data['shout_message'])."</textarea></td>
</tr>
<tr>
<td align='center' class='tbl'><input type='submit' name='saveshout' value='".$locale['422']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		tablebreak();
	}
	opentable($locale['440']);
	$result = dbquery("SELECT * FROM ".$db_prefix."shoutbox");
	$rows = dbrows($result);
	if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
	echo "<form name='deleteform' method='post' action='".FUSION_SELF.$aidlink."&amp;action=deleteshouts'>\n<div align='center'>\n";
	if ($rows != 0) {
		if ($rowstart == 0) {
			$opts = "<option value='90'>90</option>
<option value='60'>60</option>
<option value='30'>30</option>
<option value='20'>20</option>
<option value='10'>10</option>\n";
			echo $locale['430']." <select name='num_days' class='textbox' style='width:50px'>$opts</select>".$locale['431']."<br><br>
<input type='submit' name='deleteshouts' value='".$locale['432']."' class='button'><br><hr>
</div>
</form>\n";
		}
		$i = 0;
		$result = dbquery(
			"SELECT * FROM ".$db_prefix."shoutbox LEFT JOIN ".$db_prefix."users
			ON ".$db_prefix."shoutbox.shout_name=".$db_prefix."users.user_id
			ORDER BY shout_datestamp DESC LIMIT $rowstart,20"
		);
		echo "<table align='center' cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
		while ($data = dbarray($result)) {
			echo "<tr>\n<td class='".($i % 2 == 0 ? "tbl1" : "tbl2")."'><span class='comment-name'>";
			if ($data['user_name']) {
				echo "<a href='".BASEDIR."profile.php?lookup=".$data['shout_name']."' class='slink'>".$data['user_name']."</a>";
			} else {
				echo $data['shout_name'];
			}
			echo "</span>
<span class='small'>".$locale['041'].showdate("longdate", $data['shout_datestamp'])."</span><br>
".str_replace("<br>", "", parsesmileys($data['shout_message']))."<br>
<span class='small'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;shout_id=".$data['shout_id']."'>".$locale['441']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;shout_id=".$data['shout_id']."'>".$locale['442']."</a> -
<b>".$locale['443'].$data['shout_ip']."</b></span>
</td>\n</tr>\n";
			$i++;
		}
		echo "</table>\n";
	} else {
		echo "<center><br>\n".$locale['444']."<br><br>\n</center>\n";
	}
	closetable();
	echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,20,$rows,3,FUSION_SELF.$aidlink."&amp;")."\n</div>\n";
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>