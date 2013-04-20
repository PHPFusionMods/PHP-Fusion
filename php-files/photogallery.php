<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2005 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------+
| Photo Gallery developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."photogallery.php";
include INCLUDES."comments_include.php";

function checkImageExists($image_file) {
	if(file_exists($image_file)) {
		return $image_file;
	}else{
		return IMAGES."imagenotfound.jpg";
	}
}

if (!isset($rowstart) || !isNum($rowstart)) $rowstart = 0;
if (isset($photo) && !isNum($photo)) fallback(FUSION_SELF);
if (isset($album) && !isNum($album)) fallback(FUSION_SELF);

if(isset($photo)){
	$result=dbquery("UPDATE ".$db_prefix."photos SET photo_views=(photo_views+1) WHERE photo_id='".$photo."'");
	$result=dbquery("SELECT tp.*, user_name FROM ".$db_prefix."photos AS tp INNER JOIN ".$db_prefix."users USING (user_id) WHERE photo_id='".$photo."'");
	$data=dbarray($result);
	opentable($locale['419'].$data['photo_title']);
	if(dbrows($result)!=1){
		echo "<center><br>".$locale['428']."<br><br></center>\n";
	}else{
		$img_filename = PHOTOS.$photo.".jpg";
		$imgsize=@getimagesize($img_filename);
		$prev=@dbresult(@dbquery("SELECT t2.photo_id FROM ".$db_prefix."photos t1 JOIN ".$db_prefix."photos t2 WHERE t1.photo_order=t2.photo_order+1 AND t1.album_id=t2.album_id AND t1.photo_id='".$photo."'"),0);
		$next=@dbresult(@dbquery("SELECT t2.photo_id FROM ".$db_prefix."photos t1 JOIN ".$db_prefix."photos t2 WHERE t1.photo_order=t2.photo_order-1 AND t1.album_id=t2.album_id AND t1.photo_id='".$photo."'"),0);
		echo "<div align='center' style='margin:5px 0px;'>
<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>
<td class='tbl1'><span class='small'><a href='".FUSION_SELF."?".(empty($prev)?"album=".$data['album_id']:"photo=".$prev)."'>".$locale['429']."</a></span></td>
<td class='tbl2'><span class='small'><a href='".FUSION_SELF."?album=".$data['album_id']."'>".$locale['427']."</a></span></td>
<td class='tbl1'><span class='small'><a href='".FUSION_SELF."?".(empty($next)?"album=".$data['album_id']:"photo=".$next)."'>".$locale['430']."</a></span></td>
</tr>\n</table>\n</div>
<div align='center' style='margin:5px 0px;'>
<img src='".checkImageExists($img_filename)."' border='1' title='".$data['photo_title']."' title='".$data['photo_title']."' alt='".$locale['405']."'>
</div>
<div align='center' style='margin:5px 0px;'>
<span class='small2'>".$locale['420'].$imgsize[0]." x ".$imgsize[1].$locale['421']."(".parseByteSize(filesize($img_filename)).")<br>
".$locale['422']."<b>".$data['user_name']."</b>".$locale['423']."<b>".showdate("shortdate", $data['photo_date'])."</b>.<br>
".$locale['424']."<b>".$data['photo_views']."</b>".$locale['425']."</span>
</div>";
	}
	closetable();
	if($settings['album_comments']=="1") showcomments("P","photos","photo_id",$photo,FUSION_SELF."?photo=$photo");
}elseif(isset($album)){
	$data=dbarray(dbquery(
		"SELECT ta.*, COUNT(photo_id) as photo_count, MAX(photo_date) as max_date, user_name
		FROM ".$db_prefix."photo_albums AS ta
		LEFT JOIN ".$db_prefix."photos USING (album_id)
		LEFT JOIN ".$db_prefix."users USING (user_id)
		WHERE ta.album_id='".$album."' GROUP BY album_id"
	));
	$piccnt=$data['photo_count'];
	opentable($locale['408'].$data['album_title']);
	echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td width='25%' rowspan='2' align='center'><img class='activegallery' src='".checkImageExists(PHOTOS."a".$data['album_id'].".jpg")."' width='".$settings['album_image_w']."' height='".$settings['album_image_h']."' title='".($data['album_info']==""?$data['album_title']:$data['album_info'])."' alt='".$locale['405']."'></td>
<td width='75%' valign='top'>".($data['album_info']==""?$data['album_title']:$data['album_info'])."</td>
</tr>
<tr>
<td valign='bottom' class='small2'><hr>".$locale['409']."<b>".($data['photo_count']>0?"$data[photo_count]</b><br>".$locale['410']."<b>".strftime($settings['shortdate'], $data['max_date']+($settings['timeoffset']*3600))."</b>".$locale['411']."<b>".$data['user_name']."</b>":$locale['412']."</b><br /><br />")."</td>
</tr>
<tr>
<td align='center' colspan='2' class='small'><br><a href='".FUSION_SELF."'>".$locale['426']."</a></td>
</tr>
</table>";
	closetable();
	tablebreak();
	opentable($locale['413']);
	$result=dbquery(
		"SELECT tp.*, COUNT(comment_item_id) AS comment_count
		FROM ".$db_prefix."photos AS tp LEFT JOIN ".$db_prefix."comments
		ON photo_id = comment_item_id AND comment_type='P'
		WHERE album_id='".$album."' GROUP BY photo_id
		ORDER BY photo_order LIMIT ".$rowstart.",".$settings['thumbs_per_page']
	);
	if(dbrows($result)>0){
		echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
		$img_cnt=0;
		while($data=dbarray($result)){
			echo "<td class='gallery' width='".round(100/$settings['thumbs_per_row'])."%' align='center' valign='top'>
<a href='".FUSION_SELF."?photo=$data[photo_id]' class='gallery'>
<img src='".checkImageExists(PHOTOS.$data['photo_id']."t.jpg")."' width='".$settings['thumb_image_w']."' height='".$settings['thumb_image_h']."' title='".$data['photo_title']."' alt='".$locale['405']."'>
</a><br />
".$data['photo_title']."<br />
<span class='small2'>".$locale['414']."<b>".($data['photo_views']==0?$locale['412']:$data['photo_views'])."</b><br />
".($data['comment_count']==0?$locale['415']:"<b>".$data['comment_count']."</b> ".($data['comment_count']==1?$locale['416']:$locale['417']))."</span>";
			if(++$img_cnt%$settings['thumbs_per_row']==0) echo "</tr>\n<tr>\n";
		}
		echo "</tr>\n</table>\n";
	}else{
		echo "<center><br />".$locale['418']."<br /><br /></center>";
	}
	closetable();
	if ($piccnt != 0) echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['thumbs_per_page'],$piccnt,3,FUSION_SELF."?album=$album&amp;")."</div>\n";
}else{
	opentable($locale['400']);
	$albcnt=dbresult(dbquery("SELECT COUNT(*) FROM ".$db_prefix."photo_albums"), 0);
	if($albcnt!=0){
		$result=dbquery(
			"SELECT COUNT(photo_id) AS photo_count, MAX(photo_date) AS max_date, ta.*
			FROM ".$db_prefix."photo_albums AS ta LEFT JOIN ".$db_prefix."photos USING (album_id)
			GROUP BY album_id ORDER BY album_order LIMIT ".$rowstart.",".$settings['albums_per_page']
		);
		echo "<table cellpadding='0' cellspacing='0' width='100%'><tr>";
		$img_cnt=0;
		while($data=dbarray($result)){
			echo "<td class='gallery' width='".round(100/$settings['albums_per_row'])."%' align='center' valign='top'>
<a href='".FUSION_SELF."?album=".$data['album_id']."' class='gallery'>
<img src='".checkImageExists(PHOTOS."a".$data['album_id'].".jpg")."' width='".$settings['album_image_w']."' height='".$settings['album_image_h']."' title='".($data['album_info']==""?$data['album_title']:$data['album_info'])."' alt='".$locale['405']."' />
</a><br />
".$data['album_title']."<br />
<span class='small2'>".($data['photo_count']!=0?$data['photo_count'].($data['photo_count']==1?$locale['403']:$locale['404'])."<br>".$locale['402'].showdate("shortdate", $data['max_date']):$locale['401']);
			if($data['max_date']!=NULL && (time()-604800) < $data['max_date']){
				echo "<br /><span class='small2'>".$locale['406']."</span>";
			}
			echo "</span>";
			if(++$img_cnt%$settings['albums_per_row']==0) echo "</tr>\n<tr>\n";
		}
		echo "</tr>\n</table>\n";
		if ($albcnt != 0) echo "<div align='center' style='margin-top:5px;'>".makePageNav($rowstart,$settings['albums_per_page'],$albcnt,3)."</div>\n";
	}else{
		echo "<center><br>".$locale['407']."<br><br></center>\n";
	}
	closetable();
}

require "side_right.php";
require "footer.php";
?>