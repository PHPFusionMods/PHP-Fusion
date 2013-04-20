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
include LOCALE.LOCALESET."admin/polls.php";

if (!checkrights("PO") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($poll_id) && !isNum($poll_id)) fallback(FUSION_SELF);

if (isset($status)) {
	if ($status == "su") {
		$title = $locale['400'];
		$message = "<b>".$locale['401']."</b>";
	} elseif ($status == "sn") {
		$title = $locale['400'];
		$message = "<b>".$locale['402']."</b>";
	} elseif ($status == "del") {
		$title = $locale['403'];
		$message = "<b>".$locale['404']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($_POST['save'])) {
	$poll_title = stripinput($poll_title);
	foreach($_POST['poll_option'] as $key => $value) {
		$poll_option[$key] = stripinput($_POST['poll_option'][$key]);
	}
	if (isset($poll_id)) {
		if (!isNum($poll_id)) fallback("polls.php");
		$ended = (isset($_POST['close']) ? time() : 0);
		$result = dbquery("UPDATE ".$db_prefix."polls SET poll_title='$poll_title', poll_opt_0='$poll_option[0]', poll_opt_1='$poll_option[1]', poll_opt_2='$poll_option[2]', poll_opt_3='$poll_option[3]', poll_opt_4='$poll_option[4]', poll_opt_5='$poll_option[5]', poll_opt_6='$poll_option[6]', poll_opt_7='$poll_option[7]', poll_opt_8='$poll_option[8]', poll_opt_9='$poll_option[9]', poll_ended='$ended' WHERE poll_id='$poll_id'");
		redirect(FUSION_SELF.$aidlink."&amp;status=su");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."polls SET poll_ended='".time()."' WHERE poll_ended='0'");
		$result = dbquery("INSERT INTO ".$db_prefix."polls (poll_title, poll_opt_0, poll_opt_1, poll_opt_2, poll_opt_3, poll_opt_4, poll_opt_5, poll_opt_6, poll_opt_7, poll_opt_8, poll_opt_9, poll_started, poll_ended) VALUES ('$poll_title', '$poll_option[0]', '$poll_option[1]', '$poll_option[2]', '$poll_option[3]', '$poll_option[4]', '$poll_option[5]', '$poll_option[6]', '$poll_option[7]', '$poll_option[8]', '$poll_option[9]', '".time()."', '0')");
		redirect(FUSION_SELF.$aidlink."&amp;status=sn");
	}
} else if (isset($_POST['delete'])) {
	$result = dbquery("SELECT * FROM ".$db_prefix."polls WHERE poll_id='$poll_id'");
	if (dbrows($result) != 0) $result = dbquery("DELETE FROM ".$db_prefix."polls WHERE poll_id='$poll_id'");
	redirect(FUSION_SELF.$aidlink."&amp;status=del");
} else {
	if (isset($_POST['preview'])) {
		$poll = ""; $i = 0;
		$poll_title = stripinput($poll_title);
		while ($i < count($_POST['poll_option'])) {
			$poll_option[$i] = stripinput($_POST['poll_option'][$i]);
			$poll .= "<input type='checkbox' name='option[$i]'> $poll_option[$i]<br><br>\n";
			$i++;
		}
		$opt_count = (isset($_POST['opt_count']) && $_POST['opt_count'] != 10 ? count($poll_option) : $_POST['opt_count']);
		opentable($locale['410']);
		echo "<table align='center' cellpadding='0' cellspacing='0' width='280'>
<tr>
<td class='tbl'>$poll_title<br><br>
$poll</td>
</tr>
<tr>
<td align='center' class='tbl'><input type='button' name='blank' value='".$locale['411']."' class='button' style='width:70px'></td>
</tr>
</table>\n";
		closetable();
		tablebreak();
	}
	$editlist = "";
	$result = dbquery("SELECT * FROM ".$db_prefix."polls ORDER BY poll_id DESC");
	if (dbrows($result) != 0) {
		while ($data = dbarray($result)) {
			$editlist .= "<option value='".$data['poll_id']."'>".$data['poll_title']."</option>\n";
		}
		opentable($locale['420']);
		echo "<form name='editform' method='post' action='".FUSION_SELF.$aidlink."'>
<center>
<select name='poll_id' class='textbox' style='width:200px;'>
$editlist</select>
<input type='submit' name='edit' value='".$locale['421']."' class='button'>
<input type='submit' name='delete' value='".$locale['422']."' class='button'>
</center>
</form>\n";
		closetable();
		tablebreak();
	}
	if (isset($_POST['edit'])) {
		if (!isNum($poll_id)) { header("Location:polls.php"); exit; }
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."polls WHERE poll_id='$poll_id'"));
		$poll_title = $data['poll_title'];
		for ($i=0; $i<=9; $i++) {
			if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
		}
		$opt_count = count($poll_option);
		$poll_started = $data['poll_started'];
		$poll_ended = $data['poll_ended'];
	}
	if (isset($_POST['addoption'])) {
		$poll_title = stripinput($_POST['poll_title']);
		foreach($_POST['poll_option'] as $key => $value) {
			$poll_option[$key] = stripinput($_POST['poll_option'][$key]);
		}
		$opt_count = ($_POST['opt_count'] != 10 ? count($poll_option) + 1 : $_POST['opt_count']);
	}
	$i = 0; $opt = 1;
	$poll_title = isset($poll_title) ? $poll_title : "";
	$opt_count = isset($opt_count) ? $opt_count : 2;
	if (isset($poll_id)) $poll_ended = isset($poll_ended) ? $poll_ended : 0;
	opentable((isset($poll_id) ? $locale['431'] : $locale['430']));
	echo "<form name='pollform' method='post' action='".FUSION_SELF.$aidlink.(isset($poll_id) ? "&amp;poll_id=$poll_id&amp;poll_ended=$poll_ended" : "")."'>
<table align='center' cellpadding='0' cellspacing='0' width='280'>
<tr>
<td width='80' class='tbl'>".$locale['433']."</td>
<td class='tbl'><input type='text' name='poll_title' value='$poll_title' class='textbox' style='width:200px'></td>
</tr>\n";
	while ($i != $opt_count) {
		$poll_opt = isset($poll_option[$i]) ? $poll_option[$i] : "";
		echo "<tr>\n<td width='80' class='tbl'>".$locale['434']."$opt</td>\n";
		echo "<td class='tbl'><input type='text' name='poll_option[$i]' value='$poll_opt' class='textbox' style='width:200px'></td>\n</tr>\n";
		$i++; $opt++;
	}
	echo "</table>
<table align='center' cellpadding='0' cellspacing='0' width='280'>
<tr>
<td align='center' class='tbl'><br>\n";
	if (isset($poll_id) && $poll_ended == 0) {
		echo "<input type='checkbox' name='close' value='yes'>".$locale['435']."<br><br>\n";
	}
	if (!isset($poll_id) || isset($poll_id) && $poll_ended == 0) {
		echo "<input type='hidden' name='opt_count' value='$opt_count'>
<input type='submit' name='addoption' value='".$locale['438']."' class='button'>
<input type='submit' name='preview' value='".$locale['439']."' class='button'>
<input type='submit' name='save' value='".$locale['440']."' class='button'>\n";
	} else {
		echo $locale['436'].showdate("shortdate", $poll_started)."<br>\n";
		echo $locale['437'].showdate("shortdate", $poll_ended)."<br>\n";
	}
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>