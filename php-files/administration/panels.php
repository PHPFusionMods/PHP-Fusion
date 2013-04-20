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
include LOCALE.LOCALESET."admin/panels.php";

if (!checkrights("P") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($panel_id) && !isNum($panel_id)) fallback(FUSION_SELF.$aidlink);
if (isset($panel_side) && !isNum($panel_side)) fallback(FUSION_SELF.$aidlink);
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
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='$panel_side' AND panel_order>='".$data['panel_order']."'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "setstatus") {
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_status='$status' WHERE panel_id='$panel_id'");
}
if ($step == "mup") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "mdown") {
	$data = dbarray(dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='$panel_side' AND panel_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_id='".$data['panel_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order+1 WHERE panel_id='$panel_id'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "mleft") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='1' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='1', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='4' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "mright") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='4' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='4', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='1' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "mupper") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='2' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='2', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='3' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
}
if ($step == "mlower") {
	$result = dbquery("SELECT * FROM ".$db_prefix."panels WHERE panel_side='3' ORDER BY panel_order DESC LIMIT 1");
	if (dbrows($result) != 0) { $data = dbarray($result); $neworder = $data['panel_order'] + 1; } else { $neworder = 1; }
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_side='3', panel_order='$neworder' WHERE panel_id='$panel_id'");
	$result = dbquery("UPDATE ".$db_prefix."panels SET panel_order=panel_order-1 WHERE panel_side='2' AND panel_order>='$order'");
	redirect(FUSION_SELF.$aidlink);
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

$ps = 1; $i = 1;
$result = dbquery("SELECT * FROM ".$db_prefix."panels ORDER BY panel_side,panel_order");
while ($data = dbarray($result)) {
	$numrows = dbcount("(panel_id)", "panels", "panel_side='".$data['panel_side']."'");
	if ($ps != $data['panel_side']) { $ps = $data['panel_side']; $i = 1; }
	if ($numrows != 1) {
		$up = $data['panel_order'] - 1;
		$down = $data['panel_order'] + 1;
		if ($i == 1) {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;step=mdown&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
		} else if ($i < $numrows) {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;step=mup&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>\n";
			$up_down .= " <a href='".FUSION_SELF.$aidlink."&amp;step=mdown&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$down'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['433']."' style='border:0px;'></a>";
		} else {
			$up_down = " <a href='".FUSION_SELF.$aidlink."&amp;step=mup&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."&amp;order=$up'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['432']."' style='border:0px;'></a>";
		}
	} else {
		$up_down = "";
	}
	echo "<tr>\n<td class='tbl1'>".$data['panel_name']."</td>\n";
	echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>";

	if ($data['panel_side'] == 1) { echo $locale['420'];
	} elseif ($data['panel_side'] == 2) { echo $locale['421'];
	} elseif ($data['panel_side'] == 3) { echo $locale['425'];
	} elseif ($data['panel_side'] == 4) { echo $locale['422']; }

	echo "</td>\n<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>";
	
	if ($data['panel_side'] == 1) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;step=mright&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/right.gif' alt='".$locale['442']."' title='".$locale['431']."' style='border:0px;'></a>";
	} elseif ($data['panel_side'] == 2) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;step=mlower&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/down.gif' alt='".$locale['444']."' title='".$locale['446']."' style='border:0px;'></a>";
	} elseif ($data['panel_side'] == 3) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;step=mupper&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/up.gif' alt='".$locale['443']."' title='".$locale['445']."' style='border:0px;'></a>";
	} elseif ($data['panel_side'] == 4) {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;step=mleft&amp;panel_id=".$data['panel_id']."&amp;order=".$data['panel_order']."'><img src='".THEME."images/left.gif' alt='".$locale['441']."' title='".$locale['430']."' style='border:0px;'></a>";
	}
	
	echo "</td>\n<td width='1%' class='tbl1' style='white-space:nowrap'>".$data['panel_order']."$up_down</td>\n";
	echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".($data['panel_type'] == "file" ? $locale['423'] : $locale['424'])."</td>\n";
	echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".getgroupname($data['panel_access'])."</td>\n";
	echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>\n";
	echo "[<a href='panel_editor.php".$aidlink."&amp;step=edit&amp;panel_id=".$data['panel_id']."&amp;panel_side=1'>".$locale['434']."</a>]\n";
	
	if ($data['panel_status'] == 0) {
		echo "[<a href='".FUSION_SELF.$aidlink."&amp;step=setstatus&amp;status=1&amp;panel_id=".$data['panel_id']."'>".$locale['435']."</a>]\n";
	} else {
		echo "[<a href='".FUSION_SELF.$aidlink."&amp;step=setstatus&amp;status=0&amp;panel_id=".$data['panel_id']."'>".$locale['436']."</a>]\n";
	}
	
	echo "[<a href='".FUSION_SELF.$aidlink."&amp;step=delete&amp;panel_id=".$data['panel_id']."&amp;panel_side=".$data['panel_side']."' onClick='return DeleteItem()'>".$locale['437']."</a>]\n";
	echo "</td>\n</tr>\n";
	$i++;
}

echo "<tr>\n<td align='center' colspan='7' class='tbl1'>[ <a href='panel_editor.php".$aidlink."'>".$locale['438']."</a> ]\n";
echo "[ <a href='".FUSION_SELF.$aidlink."&amp;step=refresh'>".$locale['439']."</a> ]</td>\n</tr>\n</table>\n";

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