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
require_once "maincore.php";
include LOCALE.LOCALESET."photogallery.php";

define("SAFEMODE", @ini_get("safe_mode") ? true : false);

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>
<script type='text/javascript' src='".INCLUDES."jscript.js'></script>
</head>
<body style='margin:0px'>\n";

if (!isset($photo_id) || !isNum($photo_id)) {
	fallback("photogallery.php");
} else {
	$result = dbquery(
		"SELECT tp.*, ta.* FROM ".$db_prefix."photos tp
		LEFT JOIN ".$db_prefix."photo_albums ta USING (album_id)
		WHERE photo_id='$photo_id' GROUP BY tp.photo_id
	");
	if (dbrows($result)) {
		$data = dbarray($result);
		if (!checkgroup($data['album_access'])) {
			fallback(FUSION_SELF);
		} else {
			define("PHOTODIR", PHOTOS.(!SAFEMODE ? "album_".$data['album_id']."/" : ""));
			$photo_file = PHOTODIR.$data['photo_filename'];
			echo "<a href=\"javascript:;\" onclick=\"window.close();\"><img src='$photo_file' alt='".$data['photo_filename']."' title='".$locale['458']."' border='0'></a>\n";
		}
	} else {
		echo "<script language='text/javascript'>window.close();</script>\n";
	}
}

echo "</body>\n</html>\n";
?>