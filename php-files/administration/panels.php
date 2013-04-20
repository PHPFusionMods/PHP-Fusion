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
if (!isset($step)) $step = "";

if ($step == "refresh") {
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
	$i = 1;
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."panels SET panel_order='$i' WHERE panel_id='".$data['panel_id']."'");
		$i++;
	}
}
if ($step == "delete") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_id='$panel_id'"));
	$result = dbquery("DELETE FROM ".$db_prefix."panels WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$side' AND panel_order>='".$data['panel_order']."'");
	redirect("panels.php");
}
if ($step == "setstatus") {
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
}
if ($step == "mup") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
	redirect("panels.php");
}
if ($step == "mdown") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
	redirect("panels.php");
}
if ($step == "mleft") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='1', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='3' AND panel_order>='$order'");
	redirect("panels.php");
}
if ($step == "mright") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='1' AND panel_order>='$order'");
	redirect("panels.php");
}
if ($step == "mupper") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='2', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='4' AND panel_order>='$order'");
	redirect("panels.php");
}
if ($step == "mlower") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='4', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='2' AND panel_order>='$order'");
	redirect("panels.php");
}
opentable($locale['400']);
tablebreak();
echo "<table align='center' cellpadding='0' cellspacing='1' width='80%' class='tbl-border'>
<tr>
<td class='tbl2'><b>".$locale['401']."</b></td>
<td align='center' width='1%' class='tbl2' colspan='2' style='white-space:nowrap'><b>".$locale['402']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['403']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['404']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['405']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['406']."</b></td>
</tr>\n";
// Left Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=1&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=1&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=1&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=1&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['420']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF."?step=mright&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/right.gif' alt='".$locale['442']."' title='".$locale['431']."' style='border:0px;'></a></td>
<td width='1%'class='tbl1' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>
<td align='center' width='1%' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'")." style='white-space:nowrap'>\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&amp;panel_id=".$data['panel_id']."&amp;side=1'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&amp;panel_id=".$data['panel_id']."&amp;side=1' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
// Upper Center Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=2&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=2&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=2&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=2&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['421']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF."?step=mlower&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['446']."' style='border:0px;'></a></td>
<td width='1%' class='tbl1' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>
<td align='center' width='1%' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'")." style='white-space:nowrap'>\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&amp;panel_id=".$data['panel_id']."&amp;side=2'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&amp;panel_id=".$data['panel_id']."&amp;side=2' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
// Lower Center Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=4&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=4&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=4&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=4&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['425']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'><a href='".FUSION_SELF."?step=mupper&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['445']."' style='border:0px;'></a></td>
<td width='1%' class='tbl1' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>
<td align='center' width='1%' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'")." style='white-space:nowrap'>\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&amp;panel_id=".$data['panel_id']."&amp;side=4'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&amp;panel_id=".$data['panel_id']."&amp;side=4' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>
</tr>\n";
$i++;
}
// Right Panels
$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order");
$numrows = dbrows($result);
$i = 1;
while ($data = dbarray($result)) {
if ($numrows != 1) {
	$up = $data['panel_order'] - 1;
	$down = $data['panel_order'] + 1;
	if ($i == 1) {
		$up_down = " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=3&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else if ($i < $numrows) {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=3&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>\n";
		$up_down .= " <a href='".FUSION_SELF."?step=mdown&amp;panel_id=".$data['panel_id']."&amp;side=3&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
	} else {
		$up_down = " <a href='".FUSION_SELF."?step=mup&amp;panel_id=".$data['panel_id']."&amp;side=3&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>";
	}
} else {
	$up_down = "";
}
echo "<tr>
<td class='tbl1'>".$data['panel_name']."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$locale['422']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><a href='".FUSION_SELF."?step=mleft&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/left.gif' alt='".$locale['441']."' title='".$locale['430']."' style='border:0px;'></a></td>
<td width='1%' class='tbl1' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>
<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>
<td align='center' width='1%' ".($data['panel_filename'] != "user_info_panel" ? "class='tbl1'" : "class='tbl2'")." style='white-space:nowrap'>\n";
if ($data['panel_filename'] != "user_info_panel") {
	echo "[<a href='panel_editor.php?step=edit&amp;panel_id=".$data['panel_id']."&amp;side=3'>".$locale['434']."</a>]\n";
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF."?step=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	echo "[<a href='".FUSION_SELF."?step=delete&amp;panel_id=".$data['panel_id']."&amp;side=3' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
}
echo "</td>\n</tr>\n";
$i++;
}
echo "<tr>\n<td align='center' colspan='7' class='tbl1'>[ <a href='panel_editor.php'>".$locale['438']."</a> ]
[ <a href='".FUSION_SELF."?step=refresh'>".$locale['439']."</a> ]</td>\n</tr>\n</table>\n";

tablebreak();
closetable();
echo "<script type='text/javascript'>
function DeleteItem() {
	return confirm('".$locale['440']."');
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>