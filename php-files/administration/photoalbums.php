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
+----------------------------------------------------+
| Photo Gallery developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/photoalbums.php";

if (!checkrights("PH")) fallback("../index.php");
if (!isset($action)) $action = "";

if(isset($_POST['btn_cancel'])){
	redirect(FUSION_SELF);
}elseif($action=="refresh") {
	$ids_a=array();
	$result_a=dbquery("SELECT album_id FROM ".DB_PREFIX."photo_albums ORDER BY album_order ASC");
	while($data_a=dbarray($result_a)) $ids_a[]=$data_a['album_id'];
	$o_a=1;
	foreach($ids_a as $id_a) {
		dbquery("UPDATE ".DB_PREFIX."photo_albums SET album_order='".$o_a++."' WHERE album_id='{$id_a}'");
		$ids_p=array();
		$result_p=dbquery("SELECT photo_id FROM ".DB_PREFIX."photos WHERE album_id='{$id_a}' ORDER BY photo_order,photo_date,photo_title");
		while($data_p=dbarray($result_p)) $ids_p[]=$data_p['photo_id'];
		$o_p=1;
		foreach($ids_p as $id_p) {
			dbquery("UPDATE ".DB_PREFIX."photos SET photo_order='".$o_p++."' WHERE photo_id='{$id_p}'");
		}
	}
	redirect(FUSION_SELF);
}elseif(($action=="deletealbumpic") && isset($album_id)){
	if(file_exists(PHOTOS."a".$album_id.".jpg")) unlink(PHOTOS."a".$album_id.".jpg");
	redirect(FUSION_SELF."?action=editalbum&album_id=$album_id");
}elseif($action=="deletealbum") {
	if(isset($album_id)){
		$result=dbquery("SELECT * FROM ".$db_prefix."photos WHERE album_id='$album_id'");
		if(dbrows($result)!=0){
			opentable($locale['420']);
			echo "<center><br>".$locale['422']."<br><span class='small'>".$locale['423']."</span><br><br>
<a href='".FUSION_SELF."'>".$locale['429']."</a><br><br>
<a href='index.php'>".$locale['430']."</a><br><br></center>\n";
			closetable();
		}else{
			$odata=dbarray(dbquery("SELECT * FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'"));
			$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order-1) WHERE album_order>'$odata[album_order]'");
			$result=dbquery("DELETE FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'");
			if(file_exists(PHOTOS."a".$album_id.".jpg")) unlink(PHOTOS."a".$album_id.".jpg");
			redirect(FUSION_SELF);
		}
	}
}elseif(isset($_POST['btn_save_album'])){
	$album_title=stripinput($album_title);
	$album_info=stripinput($album_info);
	$result=dbquery("SELECT * FROM ".$db_prefix."photo_albums WHERE album_title='$album_title' AND album_id<>'$album_id'");
	if(dbrows($result)!=0){
		$error=$locale['424'];
	}else{
		$error="";
		if($action=="editalbum"){
			if($album_order<$album_order2){
				$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order+1) WHERE album_order>='$album_order' AND album_order<'$album_order2'");
			}elseif($album_order>$album_order2){
				$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order-1) WHERE album_order>'$album_order2' AND album_order<='$album_order'");
			}
			$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_title='$album_title',album_info='$album_info',album_order='$album_order' WHERE album_id='$album_id'");
		}else{
			if($album_order==""){
				$album_order=dbresult(dbquery("SELECT MAX(album_order) FROM ".$db_prefix."photo_albums"),0)+1;
			}else{
				$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=(album_order+1) WHERE album_order>='$album_order'");
			}
			$result=dbquery("INSERT INTO ".$db_prefix."photo_albums (album_title, album_info, album_order) VALUES ('$album_title', '$album_info', '$album_order')");
		}
		$album_id=dbresult(dbquery("SELECT album_id FROM ".$db_prefix."photo_albums WHERE album_title='$album_title'"),0);
		if(is_uploaded_file($_FILES['album_pic_file']['tmp_name'])){
			$album_pic = $_FILES['album_pic_file'];
			$album_pic_ext = strrchr($album_pic['name'],".");
			if($album_pic['size']>=$settings['album_max_b']){
				$error=sprintf($locale['425'], $settings['album_max_b']);
			}elseif (!eregi(".jpg", $album_pic_ext) && !eregi(".jpeg", $album_pic_ext)) {
				$error=$locale['427'];
			}else{
				if(file_exists(PHOTOS."a".$album_id.".jpg")){unlink(PHOTOS."a".$album_id.".jpg");}
				move_uploaded_file($album_pic['tmp_name'], PHOTOS.basename($album_pic['tmp_name']));
				chmod(PHOTOS.basename($album_pic['tmp_name']),0644);
				$imageFile = @getImageSize(PHOTOS.basename($album_pic['tmp_name']));
				if($imageFile[0]>$settings['album_max_w']||$imageFile[1]>$settings['album_max_h']){
					$error=sprintf($locale['428'], $settings['album_max_w'], $settings['album_max_h']);
					unlink(PHOTOS.basename($album_pic['tmp_name']));
				} else {
					createThumbnail(PHOTOS.basename($album_pic['tmp_name']),PHOTOS."a".$album_id.".jpg",$settings['album_image_w'],$settings['album_image_h']);
					unlink(PHOTOS.basename($album_pic['tmp_name']));
				}
			}
		}
	}
	if($error<>""){
		opentable($locale['420']);
		echo "<center><br><span class='small'>".$locale['421']."</span><br><span class='small'>$error</span><br><br>
<a href='".FUSION_SELF."'>".$locale['429']."</a><br><br>
<a href='".BASEDIR."index.php'>".$locale['430']."</a><br><br></center>\n";
		closetable();
	}else{
		redirect(FUSION_SELF);
	}
}elseif($action=="mup"){
	$data=dbarray(dbquery(
		"SELECT t1.album_id id1, t2.album_id id2
		FROM ".$db_prefix."photo_albums t1 INNER JOIN ".$db_prefix."photo_albums t2
		WHERE t1.album_order = t2.album_order+1 AND t1.album_id = '$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='$id1'");
	$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='$id2'");
	redirect(FUSION_SELF);
}elseif($action=="mdown"){
	$data=dbarray(dbquery(
		"SELECT t1.album_id id1, t2.album_id id2
		FROM ".$db_prefix."photo_albums t1 INNER JOIN ".$db_prefix."photo_albums t2
		WHERE t1.album_order = t2.album_order-1 AND t1.album_id = '$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order+1 WHERE album_id='$id1'");
	$result=dbquery("UPDATE ".$db_prefix."photo_albums SET album_order=album_order-1 WHERE album_id='$id2'");
	redirect(FUSION_SELF);
}else{
	if($action=="editalbum"){
		$result=dbquery("SELECT * FROM ".$db_prefix."photo_albums WHERE album_id='$album_id'");
		$data=dbarray($result);
		$album_title=stripslashes($data['album_title']);
		$album_info=stripslashes($data['album_info']);
		$album_order=$data['album_order'];
		opentable($locale['402']);
	}else{
		$album_id = "";
		$album_title = "";
		$album_info = "";
		$album_order = "";
		opentable($locale['403']);
	}
	echo "<form name='frm_add_album' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
	if($action=="editalbum"){
		echo "<input type='hidden' name='action' value='editalbum'>
<input type='hidden' name='album_id' value='$album_id'>
<input type='hidden' name='album_order2' value='$album_order'>";
	}
	echo "<table align='center' cellspacing='0' cellpadding='0'>
<tr><td class='tbl'>".$locale['405'].":</td><td class='tbl'><input type='textbox' name='album_title' value=\"$album_title\" maxlength='100' class='textbox' style='width:250px;'></td></tr>
<tr><td valign='top' class='tbl'>".$locale['410'].":</td><td class='tbl'><textarea name='album_info' class='textbox' style='width:250px; height:50px;'>$album_info</textarea></td></tr>
<tr><td class='tbl'>".$locale['408'].":</td><td class='tbl'><input type='textbox' name='album_order' value='$album_order' maxlength='2' class='textbox' style='width: 40px;'><span class='small alt'> (";
	if($action=="editalbum"){
		echo $locale['416'].$album_order;
	}else{
		echo $locale['417'];
	}
	echo ")</span></td></tr><tr><td valign='top' class='tbl'>".$locale['411'].":";
	if(file_exists(PHOTOS."a".$album_id.".jpg")){
		echo "<br><br><a class='small' href='".FUSION_SELF."?action=deletealbumpic&album_id=$album_id'>".$locale['412']."</a></td><td><img src='".PHOTOS."a".$album_id.".jpg' border='1' alt='".$locale['418']."' width='".$settings[album_image_w]."' height='".$settings[album_image_h]."'>";
	}else{
		echo "</td><td class='tbl'><input type='file' name='album_pic_file' class='textbox' style='width:250px;'><br>
<span class='small alt'>".sprintf($locale['419'], $settings['album_image_w'], $settings['album_image_h'])."</span>";
	}
	echo "</td></tr>\n";
	echo "<tr><td colspan='2' align='center' class='tbl'><br>\n<input type='submit' name='btn_save_album' value='".$locale['414']."' class='button'>\n";
	if($action=="editalbum"){
		echo "<input type='submit' name='btn_cancel' value='".$locale['415']."' class='button'>\n";
	}
	echo "</td></tr>\n</table></form>\n";
	closetable();
	tablebreak();
	if($action == ""){
		opentable($locale['400']);
		$rows=dbrows(dbquery("SELECT * FROM ".$db_prefix."photo_albums"));
		if($rows!=0){
			$result=dbquery(
				"SELECT ta.*, COUNT(tp.photo_id) photo_count
				FROM ".$db_prefix."photo_albums ta LEFT JOIN ".$db_prefix."photos tp
				USING (album_id) GROUP BY album_id ORDER BY album_order"
			);
			echo "<table align='center' cellpadding='0' cellspacing='1' width='550' class='tbl-border'>
<tr>
<td width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['404']."</b></td>
<td class='tbl2'><b>".$locale['405']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['406']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['407']."</b></td>
<td align='center' width='1%' colspan='2' class='tbl2' style='white-space:nowrap'><b>".$locale['408']."</b></td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><b>".$locale['409']."</b></td>
</tr>\n";
			$r=0;
			while($data=dbarray($result)){
				$album_id=$data['album_id'];
				$album_title=stripslashes($data['album_title']);
				$album_info=stripslashes($data['album_info']);
				$album_order=$data['album_order'];
				$photo_count=$data['photo_count'];
				if($photo_count==0) $photo_count=$locale['432'];
				if($rows!=1){
					if($album_order==1){
						$up_down=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id'><img src='".THEME."images/down.gif' border='0' /></a>";
					}elseif($album_order < $rows){
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id'><img src='".THEME."images/up.gif' border='0' /></a>\n";
						$up_down.=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id'><img src='".THEME."images/down.gif' border='0' /></a>";
					}else{
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id'><img src='".THEME."images/up.gif' border='0' /></a>";
					}
				}else{
					$up_down = "";
				}
				if(($r%2)==0){$class="tbl1";}else{$class="tbl2";}
				echo "<tr><td align='center' width='1%' class='$class' style='white-space:nowrap'>$album_id</td>
<td class='$class'><a title='$album_info' href='".FUSION_SELF."?action=editalbum&album_id=".$data['album_id']."'>$album_title</a></td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>";
				if(file_exists(PHOTOS."a".$album_id.".jpg")){
					echo "<img src='".IMAGES."tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n
<td align='center' width='1%' class='$class' style='white-space:nowrap'>$photo_count</td>
<td align='center' class='$class' width='1%' style='white-space:nowrap'>$album_order</td>
<td align='center' class='$class' width='1%' style='white-space:nowrap'>$up_down</td>
<td align='center' class='$class' width='1%' style='white-space:nowrap'>";
				echo "<a href='photos.php?album_id=".$data['album_id']."'>".$locale['413']."</a>";
				if ($photo_count==0){
					echo " | <a href='".FUSION_SELF."?action=deletealbum&album_id=".$data['album_id']."'>".$locale['412']."</a>";
				}
				echo "</td></tr>";
				$r++;
			}
			echo "<tr>\n<td align='center' colspan='7' class='tbl1'>[ <a href='".FUSION_SELF."?action=refresh'>".$locale['433']."</a> ]</td>\n</tr>\n</table>\n";
			closetable();
		}else{
			echo "<center>".$locale['431']."</center>\n";
			closetable();
		}
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>