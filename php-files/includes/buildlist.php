<?php
/*---------------------------------------------------+
| buildlist.php - iLister enginge.
+----------------------------------------------------+
| Copyright © 2005 Johs Lind
| http://www.geltzer.dk/
| Inspired by: Php-fusion 6 coding
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }

// images ------------------------
$temp = opendir(IMAGES);
while ($file = readdir($temp)) {
	if (!in_array($file, array(".", "..", "/", "index.php")) && !is_dir($file)) {
		$image_files[][0] = "['Images: ".$file."',";
		$image_files[][1] = "'".$settings['siteurl']."images/".$file."'],\n";
	}
}
closedir($temp);

// articles ---------------
$temp = opendir(IMAGES_A);
while ($file = readdir($temp)) {
	if (!in_array($file, array(".", "..", "/", "index.php"))) {
		$image_files[][0] = "['articles: ".$file."',";
		$image_files[][1] = "'".$settings['siteurl']."images/articles/".$file."'],\n";
	}
}
closedir($temp);
	
// news -------------------
$temp = opendir(IMAGES_N);
while ($file = readdir($temp)) {
	if (!in_array($file, array(".", "..", "/", "index.php"))) {
		$image_files[][0] = "['news: ".$file."',";
		$image_files[][1] = "'".$settings['siteurl']."images/news/".$file."'],\n";
	}
}
closedir($temp);

// photoalbum -------------------
$album = array();
$sql = dbquery("
	SELECT ".$db_prefix."photo_albums.album_title, ".$db_prefix."photos.photo_id
	FROM ".$db_prefix."photo_albums, ".$db_prefix."photos
	WHERE ".$db_prefix."photo_albums.album_id = ".$db_prefix."photos.album_id 
");

while ($data = dbarray($sql)) {
	$album[]=$data['album_title'];
	$album[]=$data['photo_id'];
}

$temp = opendir(PHOTOS);
while ($file = readdir($temp)) {
	if (!in_array($file, array(".", "..", "/", "index.php"))) {
		$slut = strpos($file,".");
		$smlg = substr($file,0,$slut);
		$navn = "";
		for ($i=1;$i < count($album);$i=$i+2){
			if ($smlg == $album[$i]) {
				$navn = $album[$i-1];
				break;
			}
		}
		$image_files[][0] = "['".$navn." album: ".$file."',";
		$image_files[][1] = "'".$settings['siteurl']."images/photoalbum/".$file."'],\n";
	}
}
closedir($temp);
		
// compile list -----------------
if (isset($image_files)) {
	$indhold = "var tinyMCEImageList = new Array(\n";
	for ($i=0;$i < count($image_files);$i++){
		$indhold = $indhold.$image_files[$i][0].$image_files[$i][1];
	}
	$lang = strlen($indhold)-2;
	$indhold = substr($indhold,0,$lang);
	$indhold = $indhold.");\n\n";
	$fp = fopen(IMAGES."imagelist.js","w");
	fwrite($fp, $indhold);
	fclose($fp);
}
?>
