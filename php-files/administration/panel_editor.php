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
include LOCALE.LOCALESET."admin/panels.php";

if (!checkrights("P")) fallback("../index.php");
if (isset($panel_id) && !isNum($panel_id)) fallback(FUSION_SELF);

$temp = opendir(INFUSIONS);
while ($folder = readdir($temp)) {
	if (!in_array($folder, array(".","..")) && strstr($folder, "_panel")) {
		if (is_dir(INFUSIONS.$folder)) $panel_list[] = $folder;
	}
}
closedir($temp); sort($panel_list); array_unshift($panel_list, "none");

if (isset($_POST['save'])) {
	$error = "";
	$panel_name = stripinput($_POST['panel_name']);
	if ($panel_name == "") $error .= $locale['470']."<br>";
	if ($_POST['panel_filename'] == "none") {
		$panel_filename = "";
		$panel_content = addslash($_POST['panel_content']);
		$panel_type = "php";
	} else {
		$panel_type = "file";
		$panel_content = "";
	}
	$panel_side = isNum($_POST['panel_side']) ? $_POST['panel_side'] : "1";
	$panel_access = isNum($_POST['panel_access']) ? $_POST['panel_access'] : "0";
	if ($panel_side == "1" || $panel_side == "3") {
		$panel_display = "0";
	} else {
		$panel_display = isset($_POST['panel_display']) ? "1" : "0";
	}
	if (isset($panel_id)) {
		if ($panel_name != "") {
			$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
			if ($panel_name != $data['panel_name']) {
				$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_name='$panel_name'");
				if (dbrows($result) != 0) $error .= $locale['471']."<br>";
			}
		}
		if ($panel_type == "php" && $panel_content == "") $error .= $locale['472']."<br>";
		if ($error == "") {
			$result = dbquery("UPDATE ".$db_prefix."panels SET panel_name='$panel_name', panel_filename='$panel_filename', panel_content='$panel_content', panel_access='$panel_access', panel_display='$panel_display' WHERE panel_id='$panel_id'");
		}
		opentable($locale['480']);
		echo "<center><br>\n";
		if ($error != "") {
			echo $locale['481']."<br><br>\n".$error."<br>\n";
		} else {
			echo $locale['482']."<br><br>\n";
		}
		echo "<a href='panels.php'>".$locale['486']."</a><br><br>
<a href='index.php'>".$locale['487']."</a><br><br>
</center>\n";
		closetable();
	} else {
		if ($panel_name != "") {
			$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_name='$panel_name'");
			if (dbrows($result) != 0) $error .= $locale['471']."<br>";
		}
		if ($panel_type == "php" && $panel_content == "") $error .= $locale['472']."<br>";
		if ($panel_type == "file" && $panel_filename == "none") $error .= $locale['473']."<br>";
		if ($error == "") {
			$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' ORDER BY panel_order DESC LIMIT 1");
			if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
			$result = dbquery("INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('$panel_name', '$panel_filename', '$panel_content', '$panel_side', '$neworder', '$panel_type', '$panel_access', '$panel_display', '0')");
		}
		opentable($locale['483']);
		echo "<center><br>\n";
		if ($error != "") {
			echo $locale['484']."<br><br>
".$error."<br>\n";
		} else {
			echo $locale['485']."<br><br>\n";
		}
		echo "<a href='panels.php'>".$locale['486']."</a><br><br>
<a href='index.php'>".$locale['487']."</a><br><br>
</center>\n";
		closetable();
	}
} else {
	if (isset($_POST['preview'])) {
		$panel_name = stripinput($_POST['panel_name']);
		$panel_filename = $_POST['panel_filename'];
		$panel_content = isset($_POST['panel_content']) ? $_POST['panel_content'] : "";
		$panel_access = $_POST['panel_access'];
		$panel_side = $_POST['panel_side'];
		$panelon = isset($_POST['panel_display']) ? " checked" : "";
		$panelopts = $_POST['panel_side'] == "1" || $_POST['panel_side'] == "3" ? " style='display:none'" : " style='display:block'";
		$panel_content = stripslash($panel_content);
		opentable($panel_name);
		if ($panel_filename != "none") {
			@include INFUSIONS.$panel_filename."/".$panel_filename.".php";
			$panel_type = "file";
		} else {
			eval($panel_content);
			$panel_type = "php";
		}
		//$panel_content = stripinput((QUOTES_GPC ? addslashes($panel_content) : $panel_content));
		$panel_content = phpentities($panel_content);
		closetable();
		tablebreak();
	}
	if (isset($step) && $step == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$panel_name = $data['panel_name'];
			$panel_filename = $data['panel_filename'];
			//$panel_content = stripinput((QUOTES_GPC ? $data['panel_content'] : stripslashes($data['panel_content'])));
			$panel_content = phpentities(stripslashes($data['panel_content']));
			$panel_type = $data['panel_type'];
			$panel_access = $data['panel_access'];
			$panel_side = $data['panel_side'];
			$panelon = $data['panel_display'] == "1" ? " checked" : "";
			$panelopts = $panel_side == "1" || $panel_side == "3" ? " style='display:none'" : " style='display:block'";
		}
	}
	if (isset($panel_id)) {
		$action = FUSION_SELF."?panel_id=$panel_id";
		opentable($locale['450']);
	} else {
		if (!isset($_POST['preview'])) {
			$panel_name = "";
			$panel_filename = "";
			$panel_content = "openside(\"name\");\n"."  echo \"content\";\n"."closeside();";
			$panel_type = "";
			$panel_access = "";
			$panel_side = "";
			$panelon = "";
			$panelopts = " style='display:none'";
		}
		$action = FUSION_SELF;
		opentable($locale['451']);
	}
	$user_groups = getusergroups(); $access_opts = "";
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($panel_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='editform' method='post' action='$action'>
<table align='center' cellpadding='0' cellspacing='0'>
<tr>
<td class='tbl'>".$locale['452']."</td>
<td class='tbl'><input type='text' name='panel_name' value='$panel_name' class='textbox' style='width:200px;'></td>
</tr>\n";
	if (isset($panel_id)) {
		if ($panel_type == "file") {
			echo "<tr>
<td class='tbl'>".$locale['453']."</td>
<td class='tbl'><select name='panel_filename' class='textbox' style='width:200px;'>\n";
			for ($i=0;$i < count($panel_list);$i++) {
				echo "<option".($panel_filename == $panel_list[$i] ? " selected" : "").">$panel_list[$i]</option>\n";
			}
			echo "</select></td>\n</tr>\n";
		}
	} else {
		echo "<tr>
<td class='tbl'>".$locale['453']."</td>
<td class='tbl'><select name='panel_filename' class='textbox' style='width:200px;'>\n";
		for ($i=0;$i < count($panel_list);$i++) {
			echo "<option".($panel_filename == $panel_list[$i] ? " selected" : "").">$panel_list[$i]</option>\n";
		}
		echo "</select>&nbsp;&nbsp;<span class='small2'>".$locale['454']."</span></td>\n</tr>\n";
	}
	if (isset($panel_id)) {
		if ($panel_type == "php") {
			echo "<tr>
<td valign='top' class='tbl'>".$locale['455']."</td>
<td class='tbl'><textarea name='panel_content' cols='95' rows='15' class='textbox'>$panel_content</textarea></td>
</tr>\n";
		}
	} else {
		echo "<tr>
<td valign='top' class='tbl'>".$locale['455']."</td>
<td class='tbl'><textarea name='panel_content' cols='95' rows='15' class='textbox'>$panel_content</textarea></td>
</tr>\n";
	}
	if (!isset($panel_id)) {
		echo "<tr>
<td class='tbl'>".$locale['456']."</td>
<td class='tbl'><select name='panel_side' class='textbox' style='width:150px;' onchange=\"showopts(this.options[this.selectedIndex].value);\">
<option value='1'".($panel_side == "1" ? " selected" : "").">".$locale['420']."</option>
<option value='2'".($panel_side == "2" ? " selected" : "").">".$locale['421']."</option>
<option value='3'".($panel_side == "3" ? " selected" : "").">".$locale['422']."</option>
<option value='4'".($panel_side == "4" ? " selected" : "").">".$locale['425']."</option>
</select></td>
</tr>\n";
	}
echo "<tr>
<td class='tbl'>".$locale['457']."</td>
<td class='tbl'><select name='panel_access' class='textbox' style='width:150px;'>
$access_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<div id='panelopts'".$panelopts."><input type='checkbox' name='panel_display' value='1'$panelon>".$locale['460']."</div>
<br>\n";
	if (isset($panel_id)) {
		if ($panel_type == "php") echo "<input type='hidden' name='panel_filename' value='none'>\n";
		echo "<input type='hidden' name='panel_side' value='$panel_side'>\n";
	}
	echo "<input type='submit' name='preview' value='".$locale['458']."' class='button'>
<input type='submit' name='save' value='".$locale['459']."' class='button'></td>
</tr>
</table>
</form>\n";
	closetable();
}

echo "<script type='text/javascript'>
	function showopts(panelside) {
		if (panelside == 1 || panelside == 3) {
			panelopts.style.display = 'none';
		} else {
			panelopts.style.display = 'block';
		}
	}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>