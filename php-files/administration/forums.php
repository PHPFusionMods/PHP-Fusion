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
include LOCALE.LOCALESET."admin/forums.php";

if (!checkrights("F") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($forum_id) && !isNum($forum_id)) fallback(FUSION_SELF);
if (!isset($action)) $action = "";
if (!isset($t)) $t = "";

if ($action == "refresh") {
	$i = 1; $k = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."forums SET forum_order='$i' WHERE forum_id='".$data['forum_id']."'");
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
		while ($data2 = dbarray($result2)) {
			$result3 = dbquery("UPDATE ".$db_prefix."forums SET forum_order='$k' WHERE forum_id='".$data2['forum_id']."'");
			$k++;
		}
		$i++; $k = 1;
	}
	redirect(FUSION_SELF.$aidlink);
}

if (isset($status)) {
	if ($status == "savece") {
		$title = $locale['400'];
		$message = "<b>".$locale['401']."</b>";
	} elseif ($status == "savecu") {
		$title = $locale['402'];
		$message = "<b>".$locale['403']."</b>";
	} elseif ($status == "savefe") {
		$title = $locale['404'];
		$message = "<b>".$locale['405']."</b>";
	} elseif ($status == "savefu") {
		$title = $locale['406'];
		$message = "<b>".$locale['407']."</b>";
	} elseif ($status == "savefm") {
		$title = $locale['408'];
		$message = "<b>".$locale['409']."</b>";
	} elseif ($status == "delc1") {
		$title = $locale['410'];
		$message = "<b>".$locale['411']."</b>";
	} elseif ($status == "delc2") {
		$title = $locale['410'];
		$message = "<b>".$locale['412']."</b><br>\n".$locale['413'];
	} elseif ($status == "delf1") {
		$title = $locale['414'];
		$message = "<b>".$locale['415']."</b>";
	} elseif ($status == "delf2") {
		$title = $locale['414'];
		$message = "<b>".$locale['416']."</b><br>\n".$locale['417'];
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}	

if (isset($_POST['save_cat'])) {
	$cat_name = stripinput($_POST['cat_name']);
	if ($action == "edit" && $t == "cat") {
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$cat_name' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savece");
	} else {
		if ($cat_name != "") {
			$cat_order = isNum($_POST['cat_order']) ? $_POST['cat_order'] : "";
			if(!$cat_order) $cat_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='0'"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='0' AND forum_order>='$cat_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."forums (forum_cat, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_posting, forum_lastpost, forum_lastuser) VALUES ('0', '$cat_name', '$cat_order', '', '', '0', '0', '0', '0')");
		}
		redirect(FUSION_SELF.$aidlink."&status=savecu");
	}
} elseif (isset($_POST['save_forum'])) {
	$forum_name = stripinput($_POST['forum_name']);
	$forum_description = stripinput($_POST['forum_description']);
	$forum_cat = isNum($_POST['forum_cat']) ? $_POST['forum_cat'] : "";
	$forum_access = $_POST['forum_access'];
	$forum_posting = $_POST['forum_posting'];
	if ($action == "edit" && $t == "forum") {
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_name='$forum_name', forum_cat='$forum_cat', forum_description='$forum_description', forum_access='$forum_access', forum_posting='$forum_posting' WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=savefe");
	} else {
		if ($forum_name != "") {
			$forum_mods = "";
			$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level='102'");
			while ($data = dbarray($result)) {
				$forum_mods .= $data['user_id'];
				if ($i < dbrows($result)) $forum_mods .= ".";
				$i++;
			}
			$forum_order = isNum($_POST['forum_order']) ? $_POST['forum_order'] : "";
			if(!$forum_order) $forum_order=dbresult(dbquery("SELECT MAX(forum_order) FROM ".$db_prefix."forums WHERE forum_cat='$forum_cat'"),0)+1;
			$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_cat='$forum_cat' AND forum_order>='$forum_order'");	
			$result = dbquery("INSERT INTO ".$db_prefix."forums (forum_cat, forum_name, forum_order, forum_description, forum_moderators, forum_access, forum_posting, forum_lastpost, forum_lastuser) VALUES ('$forum_cat', '$forum_name', '$forum_order', '$forum_description', '$forum_mods', '$forum_access', '$forum_posting', '0', '0')");
		}
		redirect(FUSION_SELF.$aidlink."&status=savefu");
	}
} elseif (isset($_POST['save_forum_mods'])) {
	$forum_mods = $_POST['forum_mods'];
	$result = dbquery("UPDATE ".$db_prefix."forums SET forum_moderators='$forum_mods' WHERE forum_id='".$_POST['forum_id']."'");
	redirect(FUSION_SELF.$aidlink."&status=savefm");
} elseif ($action == "moveup") {
	if ($t == "cat") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='$forum_id'");
	} elseif ($t == "forum") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='$cat' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='$forum_id'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ($action == "movedown") {
	if ($t == "cat") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='$forum_id'");
	} elseif ($t == "forum") {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='$cat' AND forum_order='$order'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_id='".$data['forum_id']."'");
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order+1 WHERE forum_id='$forum_id'");
	}
	redirect(FUSION_SELF.$aidlink);
} elseif ($action == "delete" && $t == "cat") {
	if (dbcount("(*)", "forums", "forum_cat='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='0' AND forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=delc1");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delc2");
	}
} elseif ($action == "delete" && $t == "forum") {
	if (dbcount("(*)", "posts", "forum_id='$forum_id'") == 0) {
		$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'"));
		$result = dbquery("UPDATE ".$db_prefix."forums SET forum_order=forum_order-1 WHERE forum_cat='".$data['forum_cat']."' AND forum_order>'".$data['forum_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
		redirect(FUSION_SELF.$aidlink."&status=delf1");
	} else {
		redirect(FUSION_SELF.$aidlink."&status=delf2");
	}
} else {
	if ($action == "edit") {
		if ($t == "cat") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$cat_name = $data['forum_name'];
			$cat_title = $locale['420'];
			$cat_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=cat";
			$forum_title = $locale['421'];
			$forum_action = FUSION_SELF.$aidlink;
		} elseif ($t == "forum") {
			$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_id='$forum_id'");
			$data = dbarray($result);
			$forum_name = $data['forum_name'];
			$forum_description = $data['forum_description'];
			$forum_cat = $data['forum_cat'];
			$forum_access = $data['forum_access'];
			$forum_posting = $data['forum_posting'];
			$forum_title = $locale['422'];
			$forum_action = FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=forum";
			$cat_title = $locale['423'];
			$cat_action = FUSION_SELF.$aidlink;
		}
	} else {
		$cat_name = "";
		$cat_order = "";
		$cat_title = $locale['423'];
		$cat_action = FUSION_SELF.$aidlink;
		$forum_name = "";
		$forum_description = "";
		$forum_cat = "";
		$forum_order = "";
		$forum_access = "";
		$forum_posting = "";
		$forum_title = $locale['421'];
		$forum_action = FUSION_SELF.$aidlink;
	}
	if ($t != "forum") {
		opentable($cat_title);
		echo "<form name='addcat' method='post' action='$cat_action'>
<table align='center' cellpadding='0' cellspacing='0' width='300'>
<tr>
<td class='tbl'>".$locale['440']."<br>
<input type='text' name='cat_name' value='$cat_name' class='textbox' style='width:230px;'></td>
<td width='50' class='tbl'>";
		if ($action != "edit") {
			echo $locale['441']."<br>
<input type='text' name='cat_order' value='$cat_order' class='textbox' style='width:45px;'></td>\n";
		}
		echo "</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_cat' value='".$locale['442']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
	}
	if ($t == "") tablebreak();
	if ($t != "cat") {
		$cat_opts = ""; $sel = "";
		$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result2) != 0) {
			while ($data2 = dbarray($result2)) {
				if ($action == "edit" && $t == "forum") $sel = ($data2['forum_id'] == $forum_cat ? " selected" : "");
				$cat_opts .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
			}
		}
		$user_groups = getusergroups(); $access_opts = "";
		while(list($key, $user_group) = each($user_groups)){
			$sel = ($forum_access == $user_group['0'] ? " selected" : "");
			$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
		}
		$post_groups = getusergroups(); $posting_opts = "";
		while(list($key, $user_group) = each($post_groups)){
			if ($user_group['0'] != "0") {
				$sel = ($forum_posting == $user_group['0'] ? " selected" : "");
				$posting_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
			}
		}
		opentable($forum_title);
		echo "<form name='addforum' method='post' action='$forum_action'>
<table align='center' cellpadding='0' cellspacing='0' width='300'>
<tr>
<td colspan='2' class='tbl'>".$locale['460']."<br>
<input type='text' name='forum_name' value='$forum_name' class='textbox' style='width:285px;'></td>
</tr>
<tr>
<td colspan='2' class='tbl'>".$locale['461']."<br>
<textarea name='forum_description' rows='2' cols='51' class='textbox'>$forum_description</textarea></td>
</tr>
<tr>
<td class='tbl'>".$locale['462']."<br>
<select name='forum_cat' class='textbox' style='width:225px;'>
$cat_opts</select></td>
<td width='55' class='tbl'>";
		if ($action != "edit") {
			echo $locale['463']."<br>
<input type='text' name='forum_order' value='$forum_order' class='textbox' style='width:45px;'></td>\n";
		}
		echo "</tr>
<tr>
<td colspan='2' class='tbl'>".$locale['464']."<br>
<select name='forum_access' class='textbox' style='width:225px;'>
$access_opts</select></td>
</tr>
<tr>
<td colspan='2' class='tbl'>".$locale['465']."<br>
<select name='forum_posting' class='textbox' style='width:225px;'>
$posting_opts</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='save_forum' value='".$locale['466']."' class='button'></td>
</tr>
</table>
</form>\n";
		closetable();
		if ($action == "edit" && $t == "forum") {
			tablebreak();
			opentable($locale['408']);
			$result = dbquery("SELECT user_id,user_name FROM ".$db_prefix."users ORDER BY user_level DESC, user_name");
			while ($data2 = dbarray($result)) {
				$user_id = $data2['user_id'];
 				if (!preg_match("(^{$user_id}$|^{$user_id}\.|\.{$user_id}\.|\.{$user_id}$)", $data['forum_moderators'])) {
					$mods1_user_id[] = $data2['user_id'];
					$mods1_user_name[] = $data2['user_name'];
				} else {
					$mods2_user_id[] = $data2['user_id'];
					$mods2_user_name[] = $data2['user_name'];
				}
				unset($user_id);
			}
			echo "<form name='modsform' method='post' action='".FUSION_SELF.$aidlink."'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td>
<select multiple size='15' name='modlist1' id='modlist1' class='textbox' style='width:150' onChange=\"addUser('modlist2','modlist1');\">\n";
		for ($i=0;$i < count($mods1_user_id);$i++) echo "<option value='".$mods1_user_id[$i]."'>".$mods1_user_name[$i]."</option>\n";
		echo "</select>
</td>
<td align='center' valign='middle'>
</td>
<td>
<select multiple size='15' name='modlist2' id='modlist2' class='textbox' style='width:150' onChange=\"addUser('modlist1','modlist2');\">\n";
		for ($i=0;$i < count($mods2_user_id);$i++) echo "<option value='".$mods2_user_id[$i]."'>".$mods2_user_name[$i]."</option>\n";
		echo "</select>
</td>
</tr>
<tr>
<td align='center' colspan='3'><br>
<input type='hidden' name='forum_mods'>
<input type='hidden' name='forum_id' value='".$data['forum_id']."'>
<input type='hidden' name='save_forum_mods'>
<input type='button' name='update' value='".$locale['467']."' class='button' onclick='saveMods();'></td>
</tr>
</table>
</form>\n";
			closetable();
			echo "<script type='text/javascript'>
function addUser(toGroup,fromGroup) {
	var listLength = document.getElementById(toGroup).length;
	var selItem = document.getElementById(fromGroup).selectedIndex;
	var selText = document.getElementById(fromGroup).options[selItem].text;
	var selValue = document.getElementById(fromGroup).options[selItem].value;
	var i; var newItem = true;
	for (i = 0; i < listLength; i++) {
		if (document.getElementById(toGroup).options[i].text == selText) {
			newItem = false; break;
		}
	}
	if (newItem) {
		document.getElementById(toGroup).options[listLength] = new Option(selText, selValue);
		document.getElementById(fromGroup).options[selItem] = null;
	}
}

function saveMods() {
	var strValues = \"\";
	var boxLength = document.getElementById('modlist2').length;
	var count = 0;
	if (boxLength != 0) {
		for (i = 0; i < boxLength; i++) {
			if (count == 0) {
				strValues = document.getElementById('modlist2').options[i].value;
			} else {
				strValues = strValues + \".\" + document.getElementById('modlist2').options[i].value;
			}
			count++;
		}
	}
	if (strValues.length == 0) {
		document.forms['modsform'].submit();
	} else {
		document.forms['modsform'].forum_mods.value = strValues;
		document.forms['modsform'].submit();
	}
}
</script>\n";
		}
	}
	tablebreak();
	opentable($locale['480']);
	$forums_defined = false;
	$forum = "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	$result = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='0' ORDER BY forum_order");
	if (dbrows($result) != 0) {
		$forums_defined = true;
		$forum .= "<tr>\n<td class='tbl2'><b>".$locale['485']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['486']."</b></td>
<td align='center' colspan='2' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['487']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['488']."</b></td>
</tr>\n";
		$i = 1;
		while ($data = dbarray($result)) {
			$forum .= "<tr>
<td class='tbl2' colspan='2'>".$data['forum_name']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['forum_order']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>\n";
			if (dbrows($result) != 1) {
				$up = $data['forum_order'] - 1;
				$down = $data['forum_order'] + 1;
				if ($i == 1) {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
				} elseif ($i < dbrows($result)) {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
				} else {
					$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data['forum_id']."&amp;t=cat'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
				}
			}
			$i++;
			$forum .= "</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data['forum_id']."&amp;t=cat'>".$locale['481']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data['forum_id']."&amp;t=cat'>".$locale['482']."</a></td>
</tr>\n";
			$result2 = dbquery("SELECT * FROM ".$db_prefix."forums WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
			if (dbrows($result2) != 0) {
				$k = 1;
				while ($data2 = dbarray($result2)) {
					$forum .= "<tr>
<td class='tbl1'><span class='alt'>".$data2['forum_name']."</span><br>
<span class='small'>".$data2['forum_description']."</span></td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data2['forum_access'])."<br>
<span class='small2'>".getgroupname($data2['forum_posting'])."</span></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data2['forum_order']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
					if (dbrows($result2) != 1) {
						$up = $data2['forum_order'] - 1;
						$down = $data2['forum_order'] + 1;
						if ($k == 1) {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
						} elseif ($k < dbrows($result2)) {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=movedown&amp;order=$down&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/down.gif' alt='".$locale['490']."' title='".$locale['492']."' style='border:0px;'></a>\n";
						} else {
							$forum .= "<a href='".FUSION_SELF.$aidlink."&amp;action=moveup&amp;order=$up&amp;forum_id=".$data2['forum_id']."&amp;t=forum&amp;cat=".$data2['forum_cat']."'><img src='".THEME."images/up.gif' alt='".$locale['489']."' title='".$locale['491']."' style='border:0px;'></a>\n";
						}
					}
					$k++;
					$forum .= "</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;forum_id=".$data2['forum_id']."&amp;t=forum'>".$locale['481']."</a> -
<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;forum_id=".$data2['forum_id']."&amp;t=forum'>".$locale['482']."</a></td>
</tr>\n";
				}
			} else {
				$forum .= "<tr>\n<td align='center' colspan='5' class='tbl1'>".$locale['483']."</td>\n</tr>\n";
			}
		}
	} else {
		$forum .= "<tr>\n<td align='center' class='tbl1'>".$locale['484']."</td>\n</tr>\n";
	}
	echo $forum;
	if ($forums_defined) echo "<tr>\n<td align='center' colspan='5' class='tbl1'>[ <a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['493']."</a> ]</td>\n</tr>\n";
	echo "</table>\n";
	closetable();
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>