<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: edit_profile.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."edit_profile.php";
include LOCALE.LOCALESET."user_fields.php";
include INCLUDES."photo_functions_include.php";

if (!iMEMBER) { redirect("index.php"); }

add_to_title($locale['global_200'].$locale['400']);

$user_data = $userdata;

if (isset($_POST['update_profile'])) { require_once INCLUDES."update_profile_include.php"; }

require_once INCLUDES."bbcode_include.php";
opentable($locale['400']);
$offset_list = "";
for ($i = -13; $i < 17; $i++) {
	if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
	$offset_list .= "<option".($offset == $user_data['user_offset'] ? " selected='selected'" : "").">".$offset."</option>\n";
}
echo "<form name='inputform' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center'>\n";
if (isset($_GET['update_profile'])) {
	echo "<tr>\n<td align='center' colspan='2' class='tbl'>".$locale['411']."<br /><br />\n</td>\n</tr>\n";
} elseif (!isset($_POST['update_profile'])) {
	echo "<tr>\n<td align='center' colspan='2' class='tbl'>".$locale['410']."<br /><br />\n</td>\n</tr>\n";
}
echo "<tr>\n<td class='tbl'>".$locale['u001'].":<span style='color:#ff0000'>*</span></td>\n";
echo "<td class='tbl'><input type='text' name='user_name' value='".$user_data['user_name']."' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['420'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u003'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_new_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u004'].":</td>\n";
echo "<td class='tbl'><input type='password' name='user_new_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
if (iADMIN) {
	if ($user_data['user_admin_password']) {
		echo "<td class='tbl2'>".$locale['421'].":</td>\n";
		echo "<td class='tbl2'><input type='password' name='user_admin_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
		echo "</tr>\n<tr>\n";
	}
	echo "<td class='tbl2'>".$locale['422'].":</td>\n";
	echo "<td class='tbl2'><input type='password' name='user_new_admin_password' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl2'>".$locale['423'].":</td>\n";
	echo "<td class='tbl2'><input type='password' name='user_new_admin_password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td class='tbl'>".$locale['u005'].":<span style='color:#ff0000'>*</span></td>\n";
echo "<td class='tbl'><input type='text' name='user_email' value='".$user_data['user_email']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['u006'].":</td>\n";
echo "<td class='tbl'><label><input type='radio' name='user_hide_email' value='1'".($user_data['user_hide_email'] == "1" ? " checked='checked'" : "")." />".$locale['u007']."</label> ";
echo "<label><input type='radio' name='user_hide_email' value='0'".($user_data['user_hide_email'] == "0" ? " checked='checked'" : "")." />".$locale['u008']."</label></td>\n";
echo "</tr>\n";

if (!$user_data['user_avatar']) {
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
	echo "<td class='tbl'><input type='file' name='user_avatar' class='textbox' style='width:200px;' /><br />\n";
	echo "<span class='small2'>".$locale['u011']."</span><br />\n";
	echo "<span class='small2'>".sprintf($locale['u012'], parsebytesize($settings['avatar_filesize']), $settings['avatar_width'], $settings['avatar_height'])."</span></td>\n";
	echo "</tr>\n";
} else {
	echo "<tr>\n";
	echo "<td valign='top' class='tbl'>".$locale['u010'].":</td>\n";
	echo "<td class='tbl'><img src='".IMAGES."avatars/".$user_data['user_avatar']."' alt='".$locale['u010']."' /><br />\n";
	echo "<label><input type='checkbox' name='del_avatar' value='y' /> ".$locale['u013']."</label>\n";
	echo "<input type='hidden' name='user_avatar' value='".$user_data['user_avatar']."' /></td>\n";
	echo "</tr>\n";
}

$profile_method = "input"; $i = 0; $user_cats = array(); $user_fields = array(); $ob_active = false;
$result2 = dbquery(
	"SELECT * FROM ".DB_USER_FIELDS." tuf
	INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
	ORDER BY field_cat_order, field_order"
);
if (dbrows($result2)) {
	while($data2 = dbarray($result2)) {
		if ($i != $data2['field_cat']) {
			if ($ob_active) {
				$user_fields[$i] = ob_get_contents();
				ob_end_clean();
				$ob_active = false;
			}
			$i = $data2['field_cat'];
			$user_cats[] = array(
				"field_cat_name" => $data2['field_cat_name'],
				"field_cat" => $data2['field_cat']
			);
		}
		if (!$ob_active) {
			ob_start();
			$ob_active = true;
		}
		if (file_exists(LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php")) {
			include LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php";
		}
		if (file_exists(INCLUDES."user_fields/".$data2['field_name']."_include.php")) {
			include INCLUDES."user_fields/".$data2['field_name']."_include.php";
		}
	}
}

if ($ob_active) {
	$user_fields[$i] = ob_get_contents();
	ob_end_clean();
}

foreach ($user_cats as $category) {
	if (array_key_exists($category['field_cat'], $user_fields) && $user_fields[$category['field_cat']]) {
		echo "<tr>\n";
		echo "<td colspan='2' class='tbl2'><strong>".$category['field_cat_name']."</strong></td>\n";
		echo "</tr>\n".$user_fields[$category['field_cat']];
	}
}

echo "<tr>\n<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='hidden' name='user_hash' value='".$user_data['user_password']."' />\n";
echo "<input type='submit' name='update_profile' value='".$locale['424']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
