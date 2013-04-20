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
include LOCALE.LOCALESET."admin/image_uploads.php";

if (!checkrights("IM")) fallback("../index.php");
if (!isset($ifolder)) $ifolder = "images";

if (isset($action) && $action = "update") include INCLUDES."buildlist.php";

if ($ifolder == "images") { $afolder = IMAGES; }
elseif ($ifolder == "imagesa") { $afolder = IMAGES_A; }
elseif ($ifolder == "imagesn") { $afolder = IMAGES_N; }
elseif ($ifolder == "imagesnc") { $afolder = IMAGES_NC; }

if (isset($status)) {
	if ($status == "del") {
		$title = $locale['400'];
		$message = "<b>".$locale['401']."</b>";
	} elseif ($status == "upn") {
		$title = $locale['420'];
		$message = "<b>".$locale['425']."</b>";
	} elseif ($status == "upy") {
		$title = $locale['420'];
		$message = "<img src='".$afolder.$img."' alt='$img'><br><br>\n<b>".$locale['426']."</b>";
	}
	opentable($title);
	echo "<div align='center'>".$message."</div>\n";
	closetable();
	tablebreak();
}

if (isset($del)) {
	unlink($afolder."$del");
	if ($settings['tinymce_enabled'] == 1) include INCLUDES."buildlist.php";
	redirect(FUSION_SELF."?status=del&ifolder=$ifolder");
} else if (isset($_POST['uploadimage'])) {
	$error = "";
	$image_types = array(
		".gif",
		".GIF",
		".jpeg",
		".JPEG",
		".jpg",
		".JPG",
		".png",
		".PNG"
	);
	$imgext = strrchr($_FILES['myfile']['name'], ".");
	$imgname = $_FILES['myfile']['name'];
	$imgsize = $_FILES['myfile']['size'];
	$imgtemp = $_FILES['myfile']['tmp_name'];
	if (!in_array($imgext, $image_types)) {
		redirect(FUSION_SELF."?status=upn&ifolder=$ifolder");
	} elseif (is_uploaded_file($imgtemp)){
		move_uploaded_file($imgtemp, $afolder.$imgname);
		chmod($afolder.$imgname,0644);
		if ($settings['tinymce_enabled'] == 1) include INCLUDES."buildlist.php";
		redirect(FUSION_SELF."?status=upy&ifolder=$ifolder&img=$imgname");
	}
} else {
	opentable($locale['420']);
	echo "<form name='uploadform' method='post' action='".FUSION_SELF."?ifolder=$ifolder' enctype='multipart/form-data'>
<table align='center' cellpadding='0' cellspacing='0' width='350'>
<tr>
<td width='80' class='tbl'>".$locale['421']."</td>
<td class='tbl'><input type='file' name='myfile' class='textbox' style='width:250px;'></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'>
<input type='submit' name='uploadimage' value='".$locale['420']."' class='button' style='width:100px;'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	if (isset($view)) {
		opentable($locale['440']);
		echo "<center><br>\n";
		$image_ext = strrchr($afolder.$view,".");
		if (in_array($image_ext, array(".gif",".GIF",".jpg",".JPG",".jpeg",".JPEG",".png",".PNG"))) {
			echo "<img src='".$afolder.$view."' alt='$view'><br><br>\n";
		} else {
			echo $locale['441']."<br><br>\n";
		}
		echo "<a href='".FUSION_SELF."?del=$view'>".$locale['442']."</a><br><br>\n<a href='".FUSION_SELF."'>".$locale['402']."</a><br><br>\n</center>\n";
		closetable();
	} else {
		$image_list = makefilelist($afolder, ".|..|imagelist.js|index.php", true);
		if ($image_list) { $image_count = count($image_list); }
		opentable($locale['460']);
		echo "<table align='center' cellpadding='0' cellspacing='1' width='400' class='tbl-border'>
<tr>
<td align='center' colspan='2' class='tbl2'><a href='".FUSION_SELF."?ifolder=images'>".$locale['422']."</a> |
<a href='".FUSION_SELF."?ifolder=imagesa'>".$locale['423']."</a> |
<a href='".FUSION_SELF."?ifolder=imagesn'>".$locale['424']."</a> |
<a href='".FUSION_SELF."?ifolder=imagesnc'>".$locale['427']."</a></td>
</tr>\n";
		if ($image_list) {
			for ($i=0;$i < $image_count;$i++) {
				if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
				echo "<tr>\n<td class='$row_color'>$image_list[$i]</td>
<td align='right' width='1%' class='$row_color' style='white-space:nowrap'><a href='".FUSION_SELF."?ifolder=$ifolder&amp;view=$image_list[$i]'>".$locale['461']."</a> -
<a href='".FUSION_SELF."?ifolder=$ifolder&amp;del=$image_list[$i]'>".$locale['462']."</a></td>
</tr>\n";
			}
			if ($settings['tinymce_enabled'] == 1) echo "<tr>\n<td align='center' colspan='2' class='tbl1'><a href='".FUSION_SELF."?ifolder=$ifolder&amp;action=update'>".$locale['464']."</a></td>\n</tr>\n";
		} else {
			echo "<tr>\n<td align='center' class='tbl1'>".$locale['463']."</td>\n</tr>\n";
		}
		echo "</table>\n";
		closetable();
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>