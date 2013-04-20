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
include LOCALE.LOCALESET."admin/photos.php";

define("SAFEMODE", @ini_get("safe_mode") ? true : false);

if (!checkrights("PH") || !defined("iAUTH") || $aid != iAUTH) fallback("../index.php");
if (!isset($album_id) && !isNum($album_id)) fallback("photoalbums.php".$aidlink);
if (isset($photo_id) && !isNum($photo_id)) fallback(FUSION_SELF.$aidlink);
if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if (!isset($action)) $action = "";

if (isset($status)) {
	if ($status == "savepn") {
		$title = $locale['400'];
		$message = "<b>".$locale['410']."</b>";
	} elseif ($status == "savepu") {
		$title = $locale['401'];
		$message = "<b>".$locale['411']."</b>";
	} elseif ($status == "delp") {
		$title = $locale['402'];
		$message = "<b>".$locale['412']."</b>";
	} elseif ($status == "delpd") {
		$title = $locale['402'];
		$message = "<b>".$locale['413']."</b>";
	} elseif ($status == "savepe") {
		$title = $locale['420'];
		$message = "<b>".$locale['421']."</b><br>\n";
		if ($error == 1) { $message .= $locale['422']; }
		elseif ($error == 2) { $message .= sprintf($locale['423'], parsebytesize($settings['photo_max_b'])); }
		elseif ($error == 3) { $message .= $locale['424']; }
		elseif ($error == 4) { $message .= sprintf($locale['425'], $settings['photo_max_w'], $settings['photo_max_h']); }
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink."&album_id=$album_id");
}

define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$album_id."/" : ""));

if ($action == "deletepic") {
	$data = dbarray(dbquery("SELECT photo_filename,photo_thumb1,photo_thumb2 FROM ".$db_prefix."photos WHERE photo_id='$photo_id'"));
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_filename='', photo_thumb1='', photo_thumb2='' WHERE photo_id='$photo_id'");
	@unlink(PHOTODIR.$data['photo_filename']);
	@unlink(PHOTODIR.$data['photo_thumb1']);
	if ($data['photo_thumb2']) @unlink(PHOTODIR.$data['photo_thumb2']);
	redirect(FUSION_SELF.$aidlink."&status=delp&album_id=$album_id");
} elseif ($action == "delete") {
	$data = dbarray(dbquery("SELECT album_id,photo_filename,photo_thumb1,photo_thumb2,photo_order FROM ".$db_prefix."photos WHERE photo_id='$photo_id'"));
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'".$data['photo_order']."' AND album_id='$album_id'");
	$result = dbquery("DELETE FROM ".$db_prefix."photos WHERE photo_id='$photo_id'");
	$result = dbquery("DELETE FROM ".$db_prefix."comments WHERE comment_item_id='$photo_id' and comment_type='P'");
	$result = dbquery("DELETE FROM ".$db_prefix."ratings WHERE rating_item_id='$photo_id' and rating_type='P'");
	if ($data['photo_filename']) @unlink(PHOTODIR.$data['photo_filename']);
	if ($data['photo_thumb1']) @unlink(PHOTODIR.$data['photo_thumb1']);
	if ($data['photo_thumb2']) @unlink(PHOTODIR.$data['photo_thumb2']);
	redirect(FUSION_SELF.$aidlink."&status=delpd&album_id=$album_id");
} elseif($action=="mup") {
	if (!isNum($order)) fallback(FUSION_SELF.$aidlink."&album_id=$album_id");
	$data = dbarray(dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE album_id='$album_id' AND photo_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='".$data['photo_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='$photo_id'");
	$rowstart = $order > $settings['thumbs_per_page'] ? ((ceil($order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
	redirect(FUSION_SELF.$aidlink."&album_id=$album_id&rowstart=$rowstart");
} elseif ($action=="mdown") {
	if (!isNum($order)) fallback(FUSION_SELF.$aidlink."&album_id=$album_id");
	$data = dbarray(dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE album_id='$album_id' AND photo_order='$order'"));
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='".$data['photo_id']."'");
	$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='$photo_id'");
	$rowstart = $order > $settings['thumbs_per_page'] ? ((ceil($order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
	redirect(FUSION_SELF.$aidlink."&album_id=$album_id&rowstart=$rowstart");
} elseif (isset($_POST['save_photo'])) {
	$error="";
	$photo_title = stripinput($_POST['photo_title']);
	$photo_description = stripinput($_POST['photo_description']);
	$photo_order = isNum($_POST['photo_order']) ? $_POST['photo_order'] : "";
	$photo_comments = isset($_POST['photo_comments']) ? "1" : "0";
	$photo_ratings = isset($_POST['photo_ratings']) ? "1" : "0";
	$photo_file = ""; $photo_thumb1 = ""; $photo_thumb2 = "";
	if (is_uploaded_file($_FILES['photo_pic_file']['tmp_name'])) {
		$photo_types = array(".gif",".jpg",".jpeg",".png");
		$photo_pic = $_FILES['photo_pic_file'];
		$photo_name = strtolower(substr($photo_pic['name'], 0, strrpos($photo_pic['name'], ".")));
		$photo_ext = strtolower(strrchr($photo_pic['name'],"."));
		$photo_dest = PHOTODIR;
		if (!preg_match("/^[-0-9A-Z_\.\[\]]+$/i", $photo_pic['name'])) {
			$error = 1;
		} elseif ($photo_pic['size'] > $settings['photo_max_b']){
			$error = 2;
		} elseif (!in_array($photo_ext, $photo_types)) {
			$error = 3;
		} else {
			$photo_file = image_exists($photo_dest, $photo_name.$photo_ext);
			move_uploaded_file($photo_pic['tmp_name'], $photo_dest.$photo_file);
			chmod($photo_dest.$photo_file, 0644);
			$imagefile = @getimagesize($photo_dest.$photo_file);
			if ($imagefile[0] > $settings['photo_max_w'] || $imagefile[1] > $settings['photo_max_h']) {
				$error = 4;
				unlink($photo_dest.$photo_file);
			} else {
				$photo_thumb1 = image_exists($photo_dest, $photo_name."_t1".$photo_ext);
				createthumbnail($imagefile[2], $photo_dest.$photo_file, $photo_dest.$photo_thumb1, $settings['thumb_w'], $settings['thumb_h']);
				if ($imagefile[0] > $settings['photo_w'] || $imagefile[1] > $settings['photo_h']) {
					$photo_thumb2 = image_exists($photo_dest, $photo_name."_t2".$photo_ext);
					createthumbnail($imagefile[2], $photo_dest.$photo_file, $photo_dest.$photo_thumb2, $settings['photo_w'], $settings['photo_h']);
				}
			}
		}
	}
	if (!$error) {
		if ($action == "edit") {
			$old_photo_order = dbresult(dbquery("SELECT photo_order FROM ".$db_prefix."photos WHERE photo_id='$photo_id'"),0);
			if ($photo_order > $old_photo_order) {
				$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'$old_photo_order' AND photo_order<='$photo_order' AND album_id='$album_id'");
			} elseif ($photo_order < $old_photo_order) {
				$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order<'$old_photo_order' AND photo_order>='$photo_order' AND album_id='$album_id'");
			}
			$update_photos = $photo_file ? "photo_filename='$photo_file', photo_thumb1='$photo_thumb1', photo_thumb2='$photo_thumb2', " : "";
			$result = dbquery("UPDATE ".$db_prefix."photos SET photo_title='$photo_title', photo_description='$photo_description', ".$update_photos."photo_datestamp='".time()."', photo_order='$photo_order', photo_allow_comments='$photo_comments', photo_allow_ratings='$photo_ratings' WHERE photo_id='$photo_id'");
			$rowstart = $photo_order > $settings['thumbs_per_page'] ? ((ceil($photo_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
			redirect(FUSION_SELF.$aidlink."&status=savepu&album_id=$album_id&rowstart=$rowstart");
		}else{
			if (!$photo_order) $photo_order = dbresult(dbquery("SELECT MAX(photo_order) FROM ".$db_prefix."photos WHERE album_id='$album_id'"), 0) + 1;
			$result = dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND album_id='$album_id'");
			$result = dbquery("INSERT INTO ".$db_prefix."photos (album_id, photo_title, photo_description, photo_filename, photo_thumb1, photo_thumb2, photo_datestamp, photo_user, photo_views, photo_order, photo_allow_comments, photo_allow_ratings) VALUES ('$album_id', '$photo_title', '$photo_description', '$photo_file', '$photo_thumb1', '$photo_thumb2', '".time()."', '".$userdata['user_id']."', '0', '$photo_order', '$photo_comments', '$photo_ratings')");
			$rowstart = $photo_order > $settings['thumbs_per_page'] ? ((ceil($photo_order / $settings['thumbs_per_page'])-1)*$settings['thumbs_per_page']) : "0";
			redirect(FUSION_SELF.$aidlink."&status=savepn&album_id=$album_id&rowstart=$rowstart");
		}
	}
	if ($error) {
		redirect(FUSION_SELF.$aidlink."&status=savepe&error=$error&album_id=$album_id");
	}
}else{
	if ($action == "edit") {
		$result = dbquery("SELECT * FROM ".$db_prefix."photos WHERE photo_id='$photo_id'");
		$data = dbarray($result);
		$photo_title = $data['photo_title'];
		$photo_description = $data['photo_description'];
		$photo_filename = $data['photo_filename'];
		$photo_thumb1 = $data['photo_thumb1'];
		$photo_thumb2 = $data['photo_thumb2'];
		$photo_order = $data['photo_order'];
		$photo_comments = $data['photo_allow_comments'] == "1" ? " checked" : "";
		$photo_ratings = $data['photo_allow_ratings'] == "1" ? " checked" : "";
		$formaction = FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=$album_id&amp;photo_id=".$data['photo_id'];
		opentable($locale['401']." - ($photo_id - $photo_title)");
	}else{
		$photo_title = "";
		$photo_description = "";
		$photo_filename = "";
		$photo_thumb1 = "";
		$photo_thumb2 = "";
		$photo_order = "";
		$photo_comments = " checked";
		$photo_ratings = " checked";
		$formaction = FUSION_SELF.$aidlink."&amp;album_id=$album_id";
		opentable($locale['400']);
	}
	echo "<form name='inputform' method='post' action='$formaction' enctype='multipart/form-data'>
	<table align='center' cellspacing='0' cellpadding='0'>
<tr>
<td class='tbl'>".$locale['440']."</td>
<td class='tbl'><input type='text' name='photo_title' value='$photo_title' maxlength='100' class='textbox' style='width:330px;'></td>
</tr>
<tr>
<td valign='top' class='tbl'>".$locale['441']."</td>
<td class='tbl'><textarea name='photo_description' rows='5' cols='60' class='textbox'>$photo_description</textarea><br>
<input type='button' value='b' class='button' style='font-weight:bold;width:25px;' onClick=\"addText('photo_description', '[b]', '[/b]');\">
<input type='button' value='i' class='button' style='font-style:italic;width:25px;' onClick=\"addText('photo_description', '[i]', '[/i]');\">
<input type='button' value='u' class='button' style='text-decoration:underline;width:25px;' onClick=\"addText('photo_description', '[u]', '[/u]');\">
<input type='button' value='url' class='button' style='width:30px;' onClick=\"addText('photo_description', '[url]', '[/url]');\">
<input type='button' value='mail' class='button' style='width:35px;' onClick=\"addText('photo_description', '[mail]', '[/mail]');\">
<input type='button' value='img' class='button' style='width:30px;' onClick=\"addText('photo_description', '[img]', '[/img]');\">
<input type='button' value='center' class='button' style='width:45px;' onClick=\"addText('photo_description', '[center]', '[/center]');\">
<input type='button' value='small' class='button' style='width:40px;' onClick=\"addText('photo_description', '[small]', '[/small]');\">
<input type='button' value='quote' class='button' style='width:45px;' onClick=\"addText('photo_description', '[quote]', '[/quote]');\">
</td>
</tr>
<tr>
<td class='tbl'>".$locale['442']."</td>
<td class='tbl'><input type='text' name='photo_order' value='$photo_order' maxlength='5' class='textbox' style='width:40px;'></td>
</tr>\n";
	if ($action && $photo_thumb1 && file_exists(PHOTODIR.$photo_thumb1)) {
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['443']."</td>
<td class='tbl'><img src='".PHOTODIR.$photo_thumb1."' border='1' alt='$photo_thumb1'></td>
</tr>\n";
	}
	echo "<tr>\n<td valign='top' class='tbl'>".$locale['444'];
	if ($action && $photo_thumb2 && file_exists(PHOTODIR.$photo_thumb2)) {
		echo "<br><br>\n<a class='small' href='".FUSION_SELF.$aidlink."&amp;action=deletepic&amp;album_id=$album_id&amp;photo_id=$photo_id'>".$locale['470']."</a></td>
<td class='tbl'><img src='".PHOTODIR.$photo_thumb2."' border='1' alt='$photo_thumb2'>";
	} elseif ($action && $photo_filename && file_exists(PHOTODIR.$photo_filename)) {
		echo "<br><br>\n<a class='small' href='".FUSION_SELF.$aidlink."&amp;action=deletepic&amp;album_id=$album_id&amp;photo_id=$photo_id'>".$locale['470']."</a></td>
<td class='tbl'><img src='".PHOTODIR.$photo_filename."' border='1' alt='$photo_filename'>";
	} else {
		echo "</td>\n<td class='tbl'><input type='file' name='photo_pic_file' class='textbox' style='width:250px;'>\n";
	}

	echo "</td>
</tr>
<tr>
<td colspan='2' align='center' class='tbl'><br>
<input type='checkbox' name='photo_comments' value='yes'$photo_comments> ".$locale['445']."<br>
<input type='checkbox' name='photo_ratings' value='yes'$photo_ratings> ".$locale['446']."<br><br>
<input type='submit' name='save_photo' value='".$locale['447']."' class='button'>\n";
	if ($action) {
		echo "<input type='submit' name='cancel' value='".$locale['448']."' class='button'>\n";
	}
	echo "</td></tr>\n</table></form>\n";
	closetable();
}
tablebreak();
opentable($locale['460']);
$rows = dbcount("(photo_id)", "photos", "album_id='$album_id'");
if ($rows) {
	$result = dbquery(
		"SELECT tp.*, tu.user_id,user_name FROM ".$db_prefix."photos tp
		LEFT JOIN ".$db_prefix."users tu ON tp.photo_user=tu.user_id
		WHERE album_id='$album_id' ORDER BY photo_order
		LIMIT $rowstart,".$settings['thumbs_per_page']
	);
	$counter = 0; $k = ($rowstart == 0 ? 1 : $rowstart + 1);
	echo "<table cellpadding='0' cellspacing='1' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		$up = ""; $down = "";
		if ($rows != 1){
			$orderu = $data['photo_order'] - 1;
			$orderd = $data['photo_order'] + 1;
			if ($k == 1) {
				$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;album_id=$album_id&amp;rowstart=$rowstart&amp;action=mdown&amp;order=$orderd&amp;photo_id=".$data['photo_id']."'><img src='".THEME."images/right.gif' alt='".$locale['469']."' title='".$locale['469']."' border='0' style='vertical-align:middle'></a>\n";
			} elseif ($k < $rows){
				$up = "<a href='".FUSION_SELF.$aidlink."&amp;album_id=$album_id&amp;rowstart=$rowstart&amp;action=mup&amp;order=$orderu&amp;photo_id=".$data['photo_id']."'><img src='".THEME."images/left.gif' alt='".$locale['468']."' title='".$locale['468']."' border='0' style='vertical-align:middle'></a> &middot;\n";
				$down = " &middot;\n<a href='".FUSION_SELF.$aidlink."&amp;album_id=$album_id&amp;rowstart=$rowstart&amp;action=mdown&amp;order=$orderd&amp;photo_id=".$data['photo_id']."'><img src='".THEME."images/right.gif' alt='".$locale['469']."' title='".$locale['469']."' border='0' style='vertical-align:middle'></a>\n";
			} else {
				$up = "<a href='".FUSION_SELF.$aidlink."&amp;album_id=$album_id&amp;rowstart=$rowstart&amp;action=mup&amp;order=$orderu&amp;photo_id=".$data['photo_id']."'><img src='".THEME."images/left.gif' alt='".$locale['468']."' title='".$locale['468']."' border='0' style='vertical-align:middle'></a> &middot;\n";
			}
		}
		if ($counter != 0 && ($counter % $settings['thumbs_per_row'] == 0)) echo "</tr>\n<tr>\n";
		echo "<td align='center' valign='top' class='tbl'>\n";
		echo "<b>".$data['photo_order']." ".$data['photo_title']."</b><br><br>\n";
		if ($data['photo_thumb1'] && file_exists(PHOTODIR.$data['photo_thumb1'])){
			echo "<img src='".PHOTODIR.$data['photo_thumb1']."' alt='".$locale['461']."' border='0'>";
		} else {
			echo $locale['462'];
		}
		echo "<br><br>\n<span class='small'>".$up;
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=edit&amp;album_id=$album_id&amp;photo_id=".$data['photo_id']."'>".$locale['469']."</a> &middot;\n";
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;album_id=$album_id&amp;photo_id=".$data['photo_id']."'>".$locale['470']."</a> ".$down;
		echo "<br><br>\n".$locale['463'].showdate("shortdate", $data['photo_datestamp'])."<br>\n";
		echo $locale['464']."<a href='".BASEDIR."profile.php?lookup=".$data['user_id']."'>".$data['user_name']."</a><br>\n";
		echo $locale['465'].$data['photo_views']."<br>\n";
		echo $locale['466'].dbcount("(comment_id)", "comments", "comment_type='P' AND comment_item_id='".$data['photo_id']."'")."</span><br>\n";
		echo "</td>\n";
		$counter++; $k++;
	}
	echo "</tr>\n<tr>\n<td align='center' colspan='".$settings['thumbs_per_row']."' class='tbl2'><a href='photoalbums.php".$aidlink."'>".$locale['481']."</a></td>\n</tr>\n</table>\n";
	if ($rows > $settings['thumbs_per_page']) echo "<div align='center' style='margin-top:5px;'>\n".makePageNav($rowstart,$settings['thumbs_per_page'],$rows,3,FUSION_SELF.$aidlink."&amp;album_id=$album_id&amp;")."\n</div>\n";
}else{
	echo "<center>".$locale['480']."</center>\n";
}
closetable();

echo "</td>\n";
require_once BASEDIR."footer.php";
?>