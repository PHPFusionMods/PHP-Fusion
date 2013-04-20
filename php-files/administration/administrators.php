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
include LOCALE.LOCALESET."admin/admins.php";

if (!checkrights("AD") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");

if (isset($_POST['add_admin'])) {
	$user_id = isNum($_POST['user_id']) ? $_POST['user_id'] : "0";
	$user_level = isset($_POST['make_super']) ? "103" : "102";
	if (isset($_POST['all_rights']) || isset($_POST['make_super'])) {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='$user_level', user_rights='A.AC.AD.B.C.CP.DB.DC.D.FQ.F.IM.I.IP.M.N.NC.P.PH.PI.PO.S.SL.S1.S2.S3.S4.S5.S6.S7.SU.UG.U.W.WC' WHERE user_id='$user_id'");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='$user_level' WHERE user_id='$user_id'");
	}
	redirect(FUSION_SELF.$aidlink);
}

if (isset($remove)) {
	if (isNum($remove) && $remove != "1") {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_level='101', user_rights='' WHERE user_id='$remove' AND user_level>='102'");
	}
	redirect(FUSION_SELF.$aidlink);
}

if (isset($_POST['update_admin'])) {
	if (!isNum($user_id) || $user_id == "1") fallback(FUSION_SELF.$aidlink);
	if (isset($_POST['rights'])) {
		$user_rights = "";
		for ($i = 0;$i < count($_POST['rights']);$i++) {
			$user_rights .= stripinput($_POST['rights'][$i]);
			if ($i != (count($_POST['rights'])-1)) $user_rights .= ".";
		}
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='$user_rights' WHERE user_id='$user_id' AND user_level>='102'");
	} else {
		$result = dbquery("UPDATE ".$db_prefix."users SET user_rights='' WHERE user_id='$user_id' AND user_level>='102'");
	}
	redirect(FUSION_SELF.$aidlink);
}

$i = 0;
opentable($locale['400']);
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level>='102' ORDER BY user_level DESC, user_name");
echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
<tr>
<td class='tbl2'>".$locale['401']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['402']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['403']."</td>
</tr>\n";
while ($data = dbarray($result)) {
	$row_color = $i % 2 == 0 ? "tbl1" : "tbl2";
	echo "<tr>
<td class='$row_color'><span title='".($data['user_rights'] ? str_replace(".", " ", $data['user_rights']) : "".$locale['405']."")."' style='cursor:hand;'>".$data['user_name']."</span></td>
<td align='center' width='1%' class='$row_color' style='white-space:nowrap'>".getuserlevel($data['user_level'])."</td>
<td align='center' width='1%' class='$row_color' style='white-space:nowrap'>\n";
	if ($data['user_level'] == "103" && $userdata['user_id'] == "1") { $can_edit = true;
	} elseif ($data['user_level'] != "103") { $can_edit = true;
	} else { $can_edit = false; }
	if ($can_edit == true && $data['user_id'] != "1") {
		echo "<a href='".FUSION_SELF.$aidlink."&amp;edit=".$data['user_id']."'>".$locale['406']."</a> |\n";
		echo "<a href='".FUSION_SELF.$aidlink."&amp;remove=".$data['user_id']."'>".$locale['407']."</a>\n";
	}
	echo "</td>\n</tr>\n";
	$i++;
}
$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_level<'102' ORDER BY user_name");
if (dbrows($result)) {
	echo "<tr>
<td align='center' colspan='3' class='tbl1'>
<form name='adminform' method='post' action='".FUSION_SELF.$aidlink."'>
<select name='user_id' class='textbox'>\n";
	while ($data = dbarray($result)) {
		echo "<option value='".$data['user_id']."'>".$data['user_name']."</option>\n";
	}
	echo "</select>
<input type='submit' name='add_admin' value='".$locale['410']."' class='button'><br>
<input type='checkbox' name='all_rights' value='1'> ".$locale['411'];
if ($userdata['user_id'] == "1") echo "<br>\n<input type='checkbox' name='make_super' value='1'> ".$locale['412'];
echo "\n</form>\n</td>\n</tr>\n";
}
echo "</table>\n";
closetable();
tablebreak();
if (isset($edit)) {
	if (!isNum($edit) || $edit == "1") fallback(FUSION_SELF.$aidlink);
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$edit' AND user_level>='102' ORDER BY user_id");
	if (dbrows($result)) {
		$data = dbarray($result);
		$user_rights = explode(".", $data['user_rights']);
		$result2 = dbquery("SELECT * FROM ".$db_prefix."admin WHERE admin_page!='4' ORDER BY admin_page ASC,admin_title");
		opentable($locale['420']);
		$columns = 2; $counter = 0; $page = 1;
		$admin_page = array($locale['421'],$locale['422'],$locale['423']);
		echo "<form name='rightsform' method='post' action='".FUSION_SELF.$aidlink."&amp;user_id=$edit'>\n";
		echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>\n";
		echo "<tr>\n<td colspan='2' class='tbl2'>".$admin_page['0']."</td>\n</tr>\n<tr>\n";
		while ($data2 = dbarray($result2)) {
			if ($page != $data2['admin_page']) {
				echo ($counter % $columns == 0 ? "</tr>\n" : "<td width='50%' class='tbl1'></td>\n</tr>\n");
				echo "<tr>\n<td colspan='2' class='tbl2'>".$admin_page[$page]."</td>\n</tr>\n<tr>\n";
				$page++; $counter = 0;
			}
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
		        echo "<td width='50%' class='tbl1'><input type='checkbox' name='rights[]' value='".$data2['admin_rights']."'".(in_array($data2['admin_rights'], $user_rights) ? " checked" : "")."> ".$data2['admin_title']."</td>\n";
			$counter++;
		}
		echo "</tr>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='button' class='button' onclick=\"setChecked('rightsform','rights[]',1);\" value='".$locale['425']."'>
<input type='button' class='button' onclick=\"setChecked('rightsform','rights[]',0);\" value='".$locale['426']."'>
</td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl1'>
<input type='submit' name='update_admin' value='".$locale['424']."' class='button'>
</td>
</tr>
</table>
</form>\n";
		closetable();
	}
}
	echo "<script type='text/javascript'>
function setChecked(frmName,chkName,val){
	dml=document.forms[frmName]; len=dml.elements.length;
	for(i=0;i<len;i++){if(dml.elements[i].name==chkName){dml.elements[i].checked=val;}}
}
</script>\n";

echo "</td>\n";
require_once BASEDIR."footer.php";
?>