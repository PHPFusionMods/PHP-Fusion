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
include LOCALE.LOCALESET."admin/photos.php";

if (!checkrights("PH")) fallback("../index.php");
if (!isset($action)) $action = "";

function builduseroptionlist($selected_user_id=1){
	global $locale;
	$user_option_list="";
	$levels = array_reverse(array(101=>$locale['user1'],$locale['user2'],$locale['user3']), true);
	$modlevel = $levels[$modlevel];
	foreach($levels as $level=>$modlevel){
		$uresult=dbquery("SELECT * FROM ".DB_PREFIX."users WHERE user_level='$level' ORDER BY user_name");
		if(dbrows($uresult)>0){
			$user_option_list.="<optgroup label='$modlevel'>";
			while($udata=dbarray($uresult)){
				if($udata['user_id']==$selected_user_id) {$sel=" selected";}else{$sel="";}
				$user_option_list.="<option $sel value='".$udata['user_id']."'>".$udata['user_name']."</option>";
			}
			$user_option_list.="</optgroup>";
		}
	}
	return $user_option_list;
}

if(isset($_POST['btn_cancel'])){
	redirect(FUSION_SELF."?album_id=$album_id");
}elseif(($action == "deletephotopic") && isset($photo_id)) {
	if(file_exists(PHOTOS.$photo_id.".jpg")) unlink(PHOTOS.$photo_id.".jpg");
	if(file_exists(PHOTOS.$photo_id."t.jpg")) unlink(PHOTOS.$photo_id."t.jpg");
	redirect(FUSION_SELF."?action=editphoto&photo_id=$photo_id");
}elseif($action=="deletephoto"){
	if(isset($photo_id)){
		$data=dbarray(dbquery("SELECT album_id, photo_order FROM ".$db_prefix."photos WHERE photo_id='$photo_id'"));
		$album_id=$data['album_id'];
		$photo_order=$data['photo_order'];
		$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'$photo_order' AND album_id='$album_id'");
		$result=dbquery("DELETE FROM ".$db_prefix."photos WHERE photo_id='$photo_id'");
		if(file_exists(PHOTOS.$photo_id.".jpg")) unlink(PHOTOS.$photo_id.".jpg");
		if(file_exists(PHOTOS.$photo_id."t.jpg")) unlink(PHOTOS.$photo_id."t.jpg");
		redirect(FUSION_SELF."?album_id=$album_id");
	}
}elseif(isset($_POST['btn_save_photo'])){
	$photo_title=stripinput($photo_title);
	$result=dbquery("SELECT photo_title FROM ".$db_prefix."photos WHERE photo_title='$photo_title' AND photo_id<>'$photo_id' AND album_id='$album_id'");
	if(dbrows($result)!=0){
		$error=$locale['422'];
	}else{
		$error="";
		if($action=="editphoto"){
			if($photo_order<$photo_order2){
				$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND photo_order<'$photo_order2' AND album_id='$album_id'");
			}elseif($photo_order>$photo_order2){
				$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order-1) WHERE photo_order>'$photo_order2' AND photo_order<='$photo_order' AND album_id='$album_id'");
			}
			$result=dbquery("UPDATE ".$db_prefix."photos SET album_id='$album_id',photo_title='$photo_title',photo_date='".time()."', user_id='$photo_added_by',photo_order='$photo_order' WHERE photo_id='$photo_id'");
		}else{
			if($photo_order==""){
				$photo_order=dbresult(dbquery("SELECT MAX(photo_order) FROM ".$db_prefix."photos WHERE album_id='$album_id'"),0)+1;
			}else{
				$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=(photo_order+1) WHERE photo_order>='$photo_order' AND album_id='$album_id'");
			}
			$result=dbquery("INSERT INTO ".$db_prefix."photos (album_id, photo_title, photo_date, user_id, photo_views, photo_order) VALUES ('$album_id', '$photo_title', '".time()."', '$photo_added_by', '0', '$photo_order')");
		}
		$photo_id=dbresult(dbquery("SELECT photo_id FROM ".$db_prefix."photos WHERE photo_title='$photo_title' AND album_id='$album_id'"),0);
		if(is_uploaded_file($_FILES['photo_pic_file']['tmp_name'])){
			$photo_pic = $_FILES['photo_pic_file'];
			$photo_pic_ext = strrchr($photo_pic['name'],".");
			if($photo_pic['size']>=$settings['album_max_b']){
				$error=sprintf($locale['423'], $settings['album_max_b']);
			}elseif (!eregi(".jpg", $photo_pic_ext) && !eregi(".jpeg", $photo_pic_ext)) {
				$error=$locale['425'];
			}else{
				if(file_exists(PHOTOS.$photo_id.".jpg")) unlink(PHOTOS.$photo_id.".jpg");
				if(file_exists(PHOTOS.$photo_id."t.jpg")) unlink(PHOTOS.$photo_id."t.jpg");
				move_uploaded_file($photo_pic['tmp_name'], PHOTOS.$photo_id.".jpg");
				chmod(PHOTOS.$photo_id.".jpg",0644);
				$imageFile = @getImageSize(PHOTOS.$photo_id.".jpg");
				if($imageFile[0]>$settings['album_max_w']||$imageFile[1]>$settings['album_max_h']){
					$error=sprintf($locale['426'], $settings['album_max_w'], $settings['album_max_h']);
					unlink(PHOTOS.$photo_id.".jpg");
				} else {
					chmod(PHOTOS.$photo_id.".jpg",0644);
					createThumbnail(PHOTOS.$photo_id.".jpg",PHOTOS.$photo_id."t.jpg",$settings['thumb_image_w'],$settings['thumb_image_h']);
				}
			}
		}
	}
	if($error<>""){
		opentable($locale['420']);
		echo"<center><br><span class='required'>".$locale['421']."</span><br><span class='small'>$error</span><br><br>\n";
		echo"<a href='".FUSION_SELF."?album_id=$album_id'>".$locale['427']."</a><br><br><a href='index.php'>".$locale['428']."</a><br><br></center>\n";
		closetable();
	}else{
		redirect(FUSION_SELF."?album_id=$album_id");
	}
}elseif($action=="mup"){
	$data=dbarray(dbquery(
		"SELECT t1.photo_id id1, t2.photo_id id2, t1.album_id
		FROM ".$db_prefix."photos t1 INNER JOIN ".$db_prefix."photos t2
		WHERE t1.photo_order = t2.photo_order+1 AND t1.photo_id = '$photo_id' AND t2.album_id='$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='$id1'");
	$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='$id2'");
	redirect(FUSION_SELF."?album_id=$album_id");
}elseif($action=="mdown"){
	$data=dbarray(dbquery(
		"SELECT t1.photo_id id1, t2.photo_id id2, t1.album_id
		FROM ".$db_prefix."photos t1 INNER JOIN ".$db_prefix."photos t2
		WHERE t1.photo_order = t2.photo_order-1 AND t1.photo_id = '$photo_id' AND t2.album_id='$album_id'"
	));
	$id1=$data['id1'];
	$id2=$data['id2'];
	$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order+1 WHERE photo_id='$id1'");
	$result=dbquery("UPDATE ".$db_prefix."photos SET photo_order=photo_order-1 WHERE photo_id='$id2'");
	redirect(FUSION_SELF."?album_id=$album_id");
}else{
	if($action=="editphoto"){
		$result=dbquery(
			"SELECT tp.*, user_name, COUNT(comment_id) comment_count
			FROM (".$db_prefix."photos tp INNER JOIN ".$db_prefix."users USING (user_id))
			LEFT JOIN ".$db_prefix."comments ON photo_id = comment_item_id AND comment_type = 'P'
			WHERE photo_id='$photo_id' GROUP BY photo_id ORDER BY photo_order"
		);
		$data=dbarray($result);
		$photo_id=$data['photo_id'];
		$album_id=$data['album_id'];
		$photo_title=stripslashes($data['photo_title']);
		$photo_added_by=$data['user_id'];
		$photo_added_by_name=$data['user_name'];
		$photo_order=$data['photo_order'];
		$photo_comments=$data['comment_count'];
		opentable($locale['401']." - ($photo_id - $photo_title)");
	}else{
		$photo_id="";
		$photo_title="";
		$photo_added_by="";
		$photo_order="";
		opentable($locale['402']);
	}
	echo "<form name='frm_add_photo' method='post' action='".FUSION_SELF."' enctype='multipart/form-data'>\n";
	echo "<table align='center' cellspacing='0' cellpadding='0'>\n";
	echo "<input type='hidden' name='album_id' value='$album_id'>\n";
	if($action=="editphoto"){
		echo "<input type='hidden' name='photo_id' value='$photo_id'>\n";
		echo "<input type='hidden' name='action' value='editphoto'>";
		echo "<input type='hidden' name='photo_order2' value='$photo_order'>";
	}
	echo "<tr><td class='tbl'>".$locale['404'].":</td><td class='tbl'><input type='textbox' name='photo_title' value=\"$photo_title\" maxlength='100' class='textbox' style='width:250px;'></td></tr>\n";
	echo "<tr><td class='tbl'>".$locale['407'].":</td><td class='tbl'><select name='photo_added_by' class='textbox' style='width:250px;'>".builduseroptionlist($photo_added_by)."</select></td></tr>\n";
	echo "<tr><td class='tbl'>".$locale['410'].":</td><td class='tbl'><input type='textbox' name='photo_order' value='$photo_order' maxlength='5' class='textbox' style='width:40px;'><span class='small dimmed6'> (";
	if($action=="editphoto"){
		echo $locale['416']." ".$photo_order;
	}else{
		echo $locale['417'];
	}
	echo ")</span></td></tr>";
	if(file_exists(PHOTOS.$photo_id."t.jpg")){
		echo "<tr><td valign='top' class='tbl'>".$locale['406'].":</td><td><img src='".PHOTOS.$photo_id."t.jpg' border='1' alt='".$locale['418']."' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."'></td></tr>\n";
	}
	echo "<tr><td valign='top' class='tbl'>".$locale['405'].":";
	if(file_exists(PHOTOS.$photo_id.".jpg")){
		echo "<br><br><a class='small' href='".FUSION_SELF."?action=deletephotopic&photo_id=$photo_id'>".$locale['412']."</a></td><td><img src='".PHOTOS.$photo_id.".jpg' border='1' alt='".$locale['418']."'>";
	}else{
		echo "</td><td class='tbl'><input type='file' name='photo_pic_file' class='textbox' style='width:250px;'><br>
<span class='small alt'>".sprintf($locale['419'], $settings['thumb_image_w'], $settings['thumb_image_h'])."</span>";
	}

	echo "</td></tr>\n<tr><td colspan='2' align='center' class='tbl'><br>\n<input type='submit' name='btn_save_photo' value='".$locale['414']."' class='button'>\n";
	if($action=="editphoto"){
		echo "<input type='submit' name='btn_cancel' value='".$locale['415']."' class='button'>\n";
	}
	echo "</td></tr>\n</table></form>\n";
	closetable();
	tablebreak();
	if($action=="editphoto" && $photo_comments>0){
		opentable("Photo Comments");
		$result = dbquery(
			"SELECT user_name, fc.*
			FROM ".$db_prefix."comments AS fc LEFT JOIN ".$db_prefix."users
			ON comment_name = user_id
			WHERE comment_item_id='$photo_id' AND comment_type='P'
			ORDER BY comment_datestamp ASC"
		);
		$cnt_comments = dbrows($result);
		if ($cnt_comments != 0) {
			$i = 1;
			while ($data = dbarray($result)) {
				echo "<span class=\"shoutboxname\">";
				if ($data['user_name']) {
					echo "<a href=\"profile.php?lookup=".$data['comment_name']."\">".$data['user_name']."</a>";
				} else {
					echo $data['comment_name'];
				}
				echo "</span><br>\n".parsesmileys(parseubb($data['comment_message']))."<br>\n<span class=\"shoutboxdate\">";
				echo strftime($settings['longdate'], $data['comment_datestamp'])."</span>\n";
				if ($i != $cnt_comments) { echo "<br><br>\n"; } else { echo "\n"; }
				$i++;
			}
		} else {
			echo $locale['601']."\n";
		}
		closetable();
		tablebreak();
	}
	if($action == ""){
		opentable($locale['400']);
		$result=dbquery(
			"SELECT tp.*, user_name, COUNT(comment_id) comment_count
			FROM (".$db_prefix."photos tp LEFT JOIN ".$db_prefix."users USING (user_id))
			LEFT JOIN ".$db_prefix."comments ON photo_id = comment_item_id AND comment_type = 'P'
			WHERE album_id='$album_id' GROUP BY photo_id ORDER BY photo_order"
		);
		$rows=dbrows($result);
		if($rows!=0){
			echo "<table align='center' cellpadding='0' cellspacing='1' width='550' class='tbl-border'>
<tr>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['403']."</td>
<td class='tbl2'>".$locale['404']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['405']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['406']."</td>
<td align='center' class='tbl2' width='1%' style='white-space:nowrap'>".$locale['407']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['408']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['409']."</td>
<td align='center' width='1%' colspan='2' class='tbl2' style='white-space:nowrap'>".$locale['410']."</td>
<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['411']."</td>
</tr>\n";
			$r=0;
			while($data=dbarray($result)){
				$photo_id=$data['photo_id'];
				$photo_title=stripslashes($data['photo_title']);
				$photo_date=implode('/',array_reverse(split('[-]',$data['photo_date'])));
				$photo_added_by=$data['user_id'];
				$photo_added_by_name=$data['user_name'];
				$photo_views=$data['photo_views'];
				$comment_count=$data['comment_count'];
				if($comment_count==0) $comment_count=$locale['430'];
				$photo_order=$data['photo_order'];
				if($rows!=1){
					if($photo_order==1){
						$up_down=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id&photo_id=$photo_id'><img src='".THEME."images/down.gif' border='0' /></a>";
					}elseif($photo_order<$rows){
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id&photo_id=$photo_id'><img src='".THEME."images/up.gif' border='0' /></a>\n";
						$up_down.=" <a href='".FUSION_SELF."?action=mdown&album_id=$album_id&photo_id=$photo_id'><img src='".THEME."images/down.gif' border='0' /></a>";
					}else{
						$up_down=" <a href='".FUSION_SELF."?action=mup&album_id=$album_id&photo_id=$photo_id'><img src='".THEME."images/up.gif' border='0' /></a>";
					}
				}else{
					$up_down = "";
				}

				if(($r%2)==0){$class="tbl1";}else{$class="tbl2";}
				echo "<td align='center' width='1%' class='$class' style='white-space:nowrap'>$photo_id</td>
<td class='$class'><a href='".FUSION_SELF."?action=editphoto&photo_id=$photo_id'>$photo_title</a></td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>";
				if(file_exists(PHOTOS.$photo_id.".jpg")){
					echo "<img src='".IMAGES."tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n<td align='center' class='$class'>";
				if(file_exists(PHOTOS.$photo_id."t.jpg")){
					echo "<img src='".IMAGES."tick.gif'>";
				}else{
					echo " ";
				}
				echo "</td>\n
<td align='center' width='1%' class='$class' style='white-space:nowrap'><a href='".BASEDIR."profile.php?lookup=$photo_added_by'>$photo_added_by_name</a></td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>".showdate("shortdate", $photo_date)."</td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>$comment_count</td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>$photo_order</td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'>$up_down</td>
<td align='center' width='1%' class='$class' style='white-space:nowrap'><a href='".FUSION_SELF."?action=deletephoto&photo_id=".$data['photo_id']."'>".$locale['412']."</a></td>";
				echo "</tr>";
				$r++;
			}
			echo "<tr>\n<td align='center' colspan='10' class='tbl1'><a href='photoalbums.php'>[".$locale['431']."]</a></td>\n</tr>\n</table>\n";
			closetable();
		}else{
			echo "<center>".$locale['429']."</center>\n";
			closetable();
		}
	}
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>