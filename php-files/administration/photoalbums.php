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
require_once INCLUDES."photo_functions_include.php";
include LOCALE.LOCALESET."admin/photoalbums.php";

define("SAFEMODE", @ini_get("safe_mode") ? true : false);

if (!checkrights("PH") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (isset($album_id) && !isNum($album_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if (!isset($action)) $action = "";

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink);
}

if ($action=="refresh") {
	$i = 1; $k = 1;
	$result = dbquery("SELECT album_id FROM ".$db_prefix."photo_albums ORDER BY album_order");
	while ($data = dbarray($result)) {
		$result2 = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order='$i' WHERE album_id='".$data['album_id']."'");
		$result2 = dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE album_id='".$data['album_id']."' ORDER BY photo_order");
		while ($data2 = dbarray($result2)) {
			$result3 = dbquery("UPDATE ".$db_prefix."photos SET photo_order='$k' WHERE photo_id='".$data2['photo_id']."'");
			$k++;
		}
		$i++; $k = 1;
	}
	redirect(FUSION_SELF.$aidlink);
}

if (isset($status)) {
	if ($status == "savean") {
		$title = $locale['400'];
		$message = "<b>".$locale['410']."</b>";
	} elseif ($status == "saveau") {
		$title = $locale['401'];
		$message = "<b>".$locale['411']."</b>";
	} elseif ($status == "saveae") {
		$title = $locale['420'];
		$message = "<b>".$locale['421']."</b><br>\n";
		if ($error == 1) { $message = $locale['422']; }
		elseif ($error == 2) { $message = sprintf($locale['425'], parsebytesize($settings['photo_max_b'])); }
		elseif ($error == 3) { $message = $locale['424']; }
		elseif ($error == 4) { $message = sprintf($locale['423'], $settings['photo_max_w'], $settings['photo_max_h']); }
	} elseif ($status == "delt") {
		$title = $locale['402'];
		$message = "<b>".$locale['412']."</b>";
	} elseif ($status == "dely") {
		$title = $locale['402'];
		$message = "<b>".$locale['413']."</b>";
	} elseif ($status == "deln") {
		$title = $locale['402'];
		$message = "<b>".$locale['414']."</b><br>\n".$locale['415'];
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink);
}

if ($action == "deletethumb") {
	$data = dbarray(dbquery("SELECT album_thumb,album_order FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'"));
	$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_thumb='$album_thumb' WHERE album_id='$album_id'");
	@unlink(PHOTOS.$data['album_thumb']);
	redirect(FUSION_SELF.$aidlink."&status=delt&album_id=$album_id");
} elseif ($action == "delete") {
	if (dbcount("(album_id)", "photos", "album_id='$album_id'")) {
		redirect(FUSION_SELF.$aidlink."&status=deln");
	} else {
		$data = dbarray(dbquery("SELECT album_thumb,album_order FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'"));
		$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order-1) WHERE album_order>'".$data['album_order']."'");
		$result = dbquery("DELETE FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'");
		if ($data['album_thumb']) @unlink(PHOTOS.$data['album_thumb']);
		if (!SAFEMODE) rmdir(PHOTOS."album_".$album_id);
		redirect(FUSION_SELF.$aidlink."&status=dely");
	}
} elseif ($action == "mup") {
	if (!isNum($order)) fallback(FUSION_SELF.$aidlink);
	$data = dbarray(dbquery("SELECT album_id FROM ".$db_prefix."photo_albums WHERE album_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='".$data['album_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='$album_id'");
	$rowstart = $order > $settings['thumbs_per_page'] ? ((ceil($order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
	redirect(FUSION_SELF.$aidlink."&rowstart=$rowstart");
} elseif ($action == "mdown") {
	if (!isNum($order)) fallback(FUSION_SELF.$aidlink);
	$data = dbarray(dbquery("SELECT album_id FROM ".$db_prefix."photo_albums WHERE album_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='".$data['album_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='$album_id'");
	$rowstart = $order > $settings['thumbs_per_page'] ? ((ceil($order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
	redirect(FUSION_SELF.$aidlink."&rowstart=$rowstart");
} elseif (isset($_POST['save_album'])) {
	$error = "";
	$album_title = stripinput($_POST['album_title']);
	$album_description = stripinput($_POST['album_description']);
	$album_access = isNum($_POST['album_access']) ? $_POST['album_access'] : "0";
	$album_order = isNum($_POST['album_order']) ? $_POST['album_order'] : "";
	if (!SAFEMODE && $action != "edit") {
		$result = dbarray(dbquery("SHOW TABLE STATUS LIKE '".$db_prefix."photo_albums'"));
		$album_id = $result['Auto_increment'];
		@mkdir(PHOTOS."album_".$album_id, 0755);
		@copy(IMAGES."index.php", PHOTOS."album_".$album_id."/index.php");
	}
	if (is_uploaded_file($_FILES['album_pic_file']['tmp_name'])) {
		$album_types = array(".gif",".jpg",".jpeg",".png");
		$album_pic = $_FILES['album_pic_file'];
		$album_ext = strtolower(strrchr($album_pic['name'],"."));
		if (!preg_match("/^[-0-9A-Z_\.\[\]\s]+$/i", $album_pic['name'])) {
			$error = 1;
		} elseif ($album_pic['size'] > $settings['photo_max_b']){
			$error = 2;
		} elseif (!in_array($album_ext, $album_types)) {
			$error = 3;
		} else {
			@unlink(PHOTOS."temp".$album_ext);
			move_uploaded_file($album_pic['tmp_name'], PHOTOS."temp".$album_ext);
			chmod(PHOTOS."temp".$album_ext, 0644);
			$imagefile = @getimagesize(PHOTOS."temp".$album_ext);
			if ($imagefile[0] > $settings['photo_max_w'] || $imagefile[1] > $settings['photo_max_h']) {
				$error = 4;
				@unlink(PHOTOS."temp".$album_ext);
			} else {
				$album_thumb = image_exists(PHOTOS, $album_pic['name']);
				createthumbnail($imagefile[2], PHOTOS."temp".$album_ext, PHOTOS.$album_thumb, $settings['thumb_w'], $settings['thumb_h']);
				@unlink(PHOTOS."temp".$album_ext);
			}
		}
	}
	if (!$error) {
		if ($action == "edit") {
			$old_album_order = dbresult(dbquery("SELECT album_order FROM ".$db_prefix."photoalbums WHERE album_id='$album_id'"),0);
			if ($album_order > $old_album_order) {
				$result = dbquery("UPDATE ".$db_prefix."photoalbums SET album_order=(album_order-1) WHERE album_order>'$old_album_order' AND album_order<='$album_order'");
			} elseif ($album_order < $old_album_order) {
				$result = dbquery("UPDATE ".$db_prefix."photoalbums SET album_order=(album_order+1) WHERE album_order<'$old_album_order' AND album_order>='$album_order'");
			}
			$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_title='$album_title', album_description='$album_description',".(isset($album_thumb)?" album_thumb='$album_thumb',":"")." album_user='".$userdata['user_id']."', album_access='$album_access', album_order='$album_order' WHERE album_id='$album_id'");
			$rowstart = $album_order > $settings['thumbs_per_page'] ? ((ceil($album_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
			redirect(FUSION_SELF.$aidlink."&status=saveau&rowstart=$rowstart");
		} else {
			if (!$album_order) $album_order = dbresult(dbquery("SELECT MAX(album_order) FROM ".$db_prefix."photo_albums"), 0) + 1;
			$result = dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order+1) WHERE album_order>='$album_order'");
			$result = dbquery("INSERT INTO ".$db_prefix."photo_albums (album_title, album_description, album_thumb, album_user, album_access, album_order, album_datestamp) VALUES ('$album_title', '$album_description', '$album_thumb', '".$userdata['user_id']."', '$album_access', '$album_order', '".time()."')");
			$rowstart = $album_order > $settings['thumbs_per_page'] ? ((ceil($album_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
			redirect(FUSION_SELF.$aidlink."&status=savean&rowstart=$rowstart");
		}
	} else {
		redirect(FUSION_SELF.$aidlink."&status=saveae&error=$error");
	}
} else {
	if ($action == "edit"){
		$result = dbquery("SELECT * FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'");
		$data = dbarray($result);
		$album_title = $data['album_title'];
		$album_description = $data['album_description'];
		$album_thumb = $data['album_thumb'];
		$album_access = $data['album_access'];
		$album_order = $data['album_order'];
		$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=$album_id";
		opentable($locale['401']);
	} else {
		$album_id = "";
		$album_title = "";
		$album_description = "";
		$album_thumb = "";
		$album_access = "";
		$album_order = "";
		$formaction = FUSION_SELF.$aidlink;
		opentable($locale['400']);
	}
	$access_opts = ""; $sel = "";
	$user_groups = getusergroups();
	while(list($key, $user_group) = each($user_groups)){
		$sel = ($album_access == $user_group['0'] ? " selected" : "");
		$access_opts .= "<option value='".$user_group['0']."'$sel>".$user_group['1']."</option>\n";
	}
	echo "<form name='inputform' method='post' action='$formaction' enctype='multipart/form-data'>
<table align='center' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['440']."</td>
<td class='tbl'><input type='text' name='album_title' value='$album_title' maxlength='100' class='textbox' style='width:330px;'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['441']."</td>
<td class='tbl'><textarea name='album_description' rows='5' cols='60' class='textbox'>$album_description</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('album_description', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('album_description', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('album_description', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('album_description', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('album_description', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('album_description', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('album_description', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('album_description', '[small]', '[/small]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('album_description', '[quote]', '[/quote]');\">
</td>
</tr>
<tr>
<td class='tbl'>".$locale['442']."</td>
<td class='tbl'><select name='album_access' class='textbox' style='width:150px;'>
$access_opts</select>
<input type='text' name='album_order' value='$album_order' maxlength='4' class='textbox' style='width:40px;'>
</td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['444'];
	if ($action && $album_thumb && file_exists(PHOTOS.$album_thumb)) {
		echo "<br><br>\n<a class='small' href='".FUSION_SELF.$aidlink."&amp;action=deletethumb&amp;album_id=$album_id'>".$locale['470']."</a></td>
<td class='tbl'><img src='".PHOTOS.$album_thumb."' alt='album_thumb'>";
	} else {
		echo "</td>\n<td class='tbl'><input type='file' name='album_pic_file' class='textbox' style='width:250px;'>";
	}
	echo "</td>
</tr>
<tr>
<td colspan='2' align='center' class='tbl'><br>
<input type='submit' name='save_album' value='".$locale['445']."' class='button'>\n";
	if ($action) {
		echo "<input type='submit' name='cancel' value='".$locale['446']."' class='button'>\n";
	}
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
}
tablebreak();
opentable($locale['460']);
$rows = dbcount("(album_id)", "photo_albums");
if ($rows) {
	$result = dbquery(
		"SELECT ta.*, tu.user_id,user_name FROM ".$db_prefix."photo_albums ta
		LEFT JOIN ".$db_prefix."users tu ON ta.album_user=tu.user_id
		ORDER BY album_order LIMIT $rowstart,".$settings['thumbs_per_page']
	);
	$counter = 0; $k = ($rowstart == 0 ? 1 : $rowstart + 1);
	echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		$up = ""; $down = "";
		if ($rows != 1){
			$orderu = $data['album_order'] - 1;
			$orderd = $data['album_order'] + 1;
			if ($k == 1){
				$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;rowstart=$rowstart&amp;action=mdown&amp;order=$orderd&amp;album_id=".$data['album_id']."'><img src='".THEME."images/right.gif' alt='".$locale['468']."' title='".$locale['468']."' border='0' style='vertical-align:middle'></a>\n";
			}elseif ($k < $rows){
				$up = "<a href='".FUSION_SELF.$aidlink."&amp;rowstart=$rowstart&amp;action=mup&amp;order=$orderu&amp;album_id=".$data['album_id']."'><img src='".THEME."images/left.gif' alt='".$locale['467']."' title='".$locale['467']."' border='0' style='vertical-align:middle'></a> &middot;\n";
				$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;rowstart=$rowstart&amp;action=mdown&amp;order=$orderd&amp;album_id=".$data['album_id']."'><img src='".THEME."images/right.gif' alt='".$locale['468']."' title='".$locale['468']."' border='0' style='vertical-align:middle'></a>\n";
			} else {
				$up = "<a href='".FUSION_SELF.$aidlink."&amp;rowstart=$rowstart&amp;action=mup&amp;order=$orderu&amp;album_id=".$data['album_id']."'><img src='".THEME."images/left.gif' alt='".$locale['467']."' title='".$locale['467']."' border='0' style='vertical-align:middle'></a> &middot;\n";
			}
		}
		if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) echo "</tr>\n<tr>\n";
		echo "<td align='center' valign='top' class='tbl'>\n";
		echo "<b>".$data['album_title']."</b><br><br>\n<a href='photos.php".$aidlink."&amp;album_id=".$data['album_id']."'>";
		if ($data['album_thumb'] && file_exists(PHOTOS.$data['album_thumb'])){
			echo "<img src='".PHOTOS.$data['album_thumb']."' alt='".$locale['461']."' border='0'>";
		} else {
			echo $locale['462'];
		}
		echo "</a><br><br>\n<span class='small'>".$up;
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=".$data['album_id']."'>".$locale['469']."</a> &middot;\n";
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;album_id=".$data['album_id']."'>".$locale['470']."</a> ".$down;
		echo "<br><br>\n".$locale['463'].showdate("shortdate", $data['album_datestamp'])."<br>\n";
		echo $locale['464']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
		echo $locale['465'].getgroupname($data['album_access'])."<br>\n";
		echo $locale['466'].dbcount("(photo_id)", "photos", "album_id='".$data['album_id']."'")."</span><br>\n";
		echo "</td>\n";
		$counter++; $k++;
	}
	echo "</tr>\n<tr>\n<td align='center' colspan='".$settings['thumbs_per_row']."' class='tbl2'><a href='".FUSION_SELF.$aidlink."&amp;action=refresh'>".$locale['480']."</a></td>\n</tr>\n</table>\n";
	if ($rows > $settings['thumbs_per_page']) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$settings['thumbs_per_page'],$rows,3,FUSION_SELF.$aidlink."&amp;")."\n</div>\n";
}else{
	echo "<center>".$locale['481']."</center>\n";
}
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>