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
| Database Backup developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
require_once "../maincore.php";
require_once BASEDIR."subheader.php";
require_once ADMIN."navigation.php";
include LOCALE.LOCALESET."admin/db-backup.php";

if (!checkrights("DB")) fallback("../index.php");

if (!isset($action)) $action = "";

$dbbackupdir = ADMIN.$settings['db_backup_folder'];
	
if((!file_exists($dbbackupdir))&&is_writable(ADMIN)){
	mkdir($dbbackupdir, 0777);
}
if(isset($_POST['btn_cancel'])){
	redirect(FUSION_SELF);
}elseif(isset($_POST['btn_view'])){
	ob_start();
	@ob_implicit_flush(0);
	readgzfile("$dbbackupdir$file");
	$contents=ob_get_contents();
	ob_end_clean();
	echo "<pre>".htmlspecialchars($contents)."</pre>";
	exit;
}elseif($action == "delete"){
	if(file_exists("$dbbackupdir$file")){@unlink("$dbbackupdir$file");}
	redirect(FUSION_SELF);
}elseif($action == "download"){
	ob_end_clean();
	if(preg_match("/.gz$/",$file)){
		header("Content-type: application/x-gzip");
	}else{
		header("Content-type: text/plain");
	}
	header("Content-Disposition: attachment; filename=\"$file\"\n");
	@readfile("$dbbackupdir$file");
	exit;
}

if(isset($_POST['btn_create_backup'])){
	$db_tables=$_POST['db_tables'];
	if(count($db_tables)>0){
		$crlf="\n";
		ob_start();
		@ob_implicit_flush(0);
		echo "#----------------------------------------------------------".$crlf;
		echo "# PHP-Fusion SQL Data Dump".$crlf;
		echo "# Database Name: `$db_name`".$crlf;
		echo "# Table Prefix: `".$db_prefix."`".$crlf;
		echo "# Date: `".date("d/m/Y H:i")."`".$crlf;
		echo "#----------------------------------------------------------".$crlf;
		dbquery('SET SQL_QUOTE_SHOW_CREATE=1');
		foreach($db_tables as $table){
			@set_time_limit(1200);
			dbquery("OPTIMIZE TABLE $table");
			echo $crlf."#".$crlf."# Structure for Table `".$table."`".$crlf."#".$crlf;
			echo "DROP TABLE IF EXISTS `$table`;$crlf";
			$row=dbarraynum(dbquery("SHOW CREATE TABLE $table"));
			echo $row[1].";".$crlf;
			$result=dbquery("SELECT * FROM $table");
			if($result&&dbrows($result)){
				echo $crlf."#".$crlf."# Table Data for `".$table."`".$crlf."#".$crlf;
				$column_list="";
				$num_fields=mysql_num_fields($result);
				for($i=0;$i<$num_fields;$i++){
					$column_list.=(($column_list!="")?", ":"")."`".mysql_field_name($result,$i)."`";
				}
			}
			while($row=dbarraynum($result)){
				$dump="INSERT INTO `$table` ($column_list) VALUES (";
				for($i=0;$i<$num_fields;$i++){
					$dump.=($i>0)?", ":"";
					if(!isset($row[$i])){
						$dump.="NULL";
					}elseif($row[$i]=="0"||$row[$i]!=""){
						$type=mysql_field_type($result,$i);
						if($type=="tinyint"||$type=="smallint"||$type=="mediumint"||$type=="int"||$type=="bigint"||$type=="timestamp"){
							$dump.=$row[$i];
						}else{
							$search_array=array('\\','\'',"\x00","\x0a","\x0d","\x1a");
							$replace_array=array('\\\\','\\\'','\0','\n','\r','\Z');
							$row[$i]=str_replace($search_array,$replace_array,$row[$i]);
							$dump.="'$row[$i]'";
						}
					}else{
					$dump.="''";
					}
				}
				$dump.=');';
				echo $dump.$crlf;
			}
		}
		$contents=ob_get_contents();
		ob_end_clean();
		@umask(0111);
		mt_srand((double)microtime()*1000000);
		$backup_rand = rand(1000000, 9999999);
		$backup_hash = substr(md5($backup_rand), 8, 8); 
		$file="backup_".$backup_hash."_".date('Y-m-d_Hi').".sql";
		$fp=fopen("$dbbackupdir$file","w");
		$ok=fwrite($fp,$contents);
		fclose($fp);
		if($_POST['backup_type']==".gz"){if(gzcompressfile("$dbbackupdir$file",9)!=false){unlink("$dbbackupdir$file");}}
	}
	redirect(FUSION_SELF);
}elseif(isset($_POST['btn_do_restore'])){
	$result=gzfile("$dbbackupdir$file");
	if((preg_match("/# Database Name: `(.+?)`/i", $result[2], $tmp1)<>0)&&(preg_match("/# Table Prefix: `(.+?)`/i", $result[3], $tmp2)<>0)){
		$inf_dbname=$tmp1[1];
		$inf_tblpre=$tmp2[1];
		$result=array_slice($result,7);
		$results=preg_split("/;$/m",implode("",$result));
		if(count($list_tbl)>0){
			foreach($results as $result){
				$result=html_entity_decode($result, ENT_QUOTES);
				if(preg_match("/^DROP TABLE IF EXISTS `(.*?)`/im",$result,$tmp)<>0){
					$tbl=$tmp[1];
					if(in_array($tbl, $list_tbl)){
						$result=preg_replace("/^DROP TABLE IF EXISTS `$inf_tblpre(.*?)`/im","DROP TABLE IF EXISTS `$restore_tblpre\\1`",$result);
						mysql_unbuffered_query($result);
					}
				}
				if(preg_match("/^CREATE TABLE `(.*?)`/im",$result,$tmp)<>0){
					$tbl=$tmp[1];
					if(in_array($tbl, $list_tbl)){
						$result=preg_replace("/^CREATE TABLE `$inf_tblpre(.*?)`/im","CREATE TABLE `$restore_tblpre\\1`",$result);
						mysql_unbuffered_query($result);
					}
				}
			}
		}
		if(count($list_ins)>0){
			foreach($results as $result){
				if(preg_match("/INSERT INTO `(.*?)`/i",$result,$tmp)<>0){
					$ins=$tmp[1];
					if(in_array($ins, $list_ins)){
						$result=preg_replace("/INSERT INTO `$inf_tblpre(.*?)`/i","INSERT INTO `$restore_tblpre\\1`",$result);
						mysql_unbuffered_query($result);
					}
				}
			}
		}
		redirect(FUSION_SELF);
	}else{
		opentable($locale['400']);
		echo "<center><b>".$locale['401']."</b><br><br>".$locale['402']."<br><br>";
		echo "<form action='".FUSION_SELF."' name='frm_info' method='post'>";
		echo "<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".$locale['403']."'></td></tr>";
		echo "</form></center>";
		closetable();
	}
}elseif($action == "info"){
	$result=gzfile("$dbbackupdir$file");
	$info_tbls=array();
	$info_ins_cnt=array();
	$info_inserts=array();
	foreach($result as $resultline){
		if(preg_match_all("/^# Database Name: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_dbname=$resultinfo[1][0]; }
		if(preg_match_all("/^# Table Prefix: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_tblpref=$resultinfo[1][0]; }
		if(preg_match_all("/^# Date: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_date=$resultinfo[1][0]; }
		if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $resultline, $resultinfo)<>0){ $info_tbls[]=$resultinfo[1][0]; }
		if(preg_match_all("/^INSERT INTO `(.+?)`/i", $resultline, $resultinfo)<>0){ $info_ins_cnt[]=$resultinfo[1][0]; }
	}
	$insert_ins_cnt=array_count_values($info_ins_cnt);
	$table_opt_list="";
	$insert_opt_list="";
	sort($info_tbls);
	foreach($info_tbls as $key=>$info_tbl){
		$table_opt_list.="$info_tbl<br>";
		if(isset($insert_ins_cnt[$info_tbl])){$insert_opt_list.="($insert_ins_cnt[$info_tbl])";}
		$insert_opt_list.="<br>";
	}
	opentable($locale['410']);
	echo "<form action='".FUSION_SELF."' name='frm_info' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='4' class='tbl2' align='left'>".$locale['411']."</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['412']."</td>
<td colspan='2'>$file</td>
</tr>
<tr><td class='alt' align='right'>".$locale['413']."</td>
<td colspan='2'>$info_date</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['414']."</td>
<td colspan='2'>$info_dbname</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['415']."</td>
<td colspan='2'>$info_tblpref</td>
</tr>
<tr valign='top'><td class='alt' align='right'>".$locale['416']."</td>
<td>$table_opt_list</td>
<td align='right'>$insert_opt_list</td></tr>
</tr>
<tr>
<td colspan='3' align='center'><hr>
<input type='hidden' name='file' value='$file'>
<input class='button' type='submit' name='btn_view' style='width:100px;' value='".$locale['417']."'>
<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".$locale['418']."'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}elseif($action=="restore"){
	$result=gzfile("$dbbackupdir$file");
	$info_tbls=array();
	$info_ins_cnt=array();
	$info_inserts=array();
	foreach($result as $resultline){
		if(preg_match_all("/^# Database Name: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_dbname=$resultinfo[1][0]; }
		if(preg_match_all("/^# Table Prefix: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_tblpref=$resultinfo[1][0]; }
		if(preg_match_all("/^# Date: `(.*?)`/", $resultline, $resultinfo)<>0){ $info_date=$resultinfo[1][0]; }
		if(preg_match_all("/^CREATE TABLE `(.+?)`/i", $resultline, $resultinfo)<>0){ $info_tbls[]=$resultinfo[1][0]; }
		if(preg_match_all("/^INSERT INTO `(.+?)`/i", $resultline, $resultinfo)<>0){
			if(!in_array($resultinfo[1][0], $info_inserts)) { $info_inserts[]=$resultinfo[1][0]; }
			$info_ins_cnt[]=$resultinfo[1][0];
		}
	}
	$table_opt_list="";
	sort($info_tbls);
	foreach($info_tbls as $key=>$info_tbl){
		$table_opt_list.="<option value='$info_tbl' selected>$info_tbl</option>";
	}
	$insert_ins_cnt=array_count_values($info_ins_cnt);
	$insert_opt_list="";
	sort($info_inserts);
	foreach($info_inserts as $key=>$info_insert){
		$insert_opt_list.="<option value='$info_insert' selected>$info_insert (".$insert_ins_cnt[$info_insert].")</option>";
	}
	$maxrows=max(count($info_tbls),count($info_inserts));
	opentable($locale['400']);
	echo "<script type='text/javascript'>
<!--
function tableSelectAll(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=true;}}
function tableSelectNone(){for(i=0;i<document.frmrestore.elements['list_tbl[]'].length;i++){document.frmrestore.elements['list_tbl[]'].options[i].selected=false;}}
function populateSelectAll(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=true;}}
function populateSelectNone(){for(i=0;i<document.frmrestore.elements['list_ins[]'].length;i++){document.frmrestore.elements['list_ins[]'].options[i].selected=false;}}
//-->
</script>
<form action='".FUSION_SELF."' name='frmrestore' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='2' class='tbl2' align='left'>".$locale['430']."</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".$locale['431']."</span> $file</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".$locale['414']."</span> $info_dbname</td>
</tr>
<tr>
<td colspan='2'><span class='alt'>".$locale['432']."</span> $info_date</td>
</tr>
<tr>
<td colspan='2' class='alt'>".$locale['415']."
<input class='textbox' type='text' name='restore_tblpre' value='$info_tblpref' style='width:150px'>
</td>
</tr>
<tr>
<td class='alt' valign='top'>".$locale['433']."<br>
<select style='width:180px;' class='textbox' id='list_tbl' name='list_tbl[]' size='$maxrows' multiple>".$table_opt_list."</select>
<div align='center'>".$locale['435']." [<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectAll()\">".$locale['436']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:tableSelectNone()\">".$locale['437']."</a>]</div></td>
<td class='alt' valign='top'>".$locale['434']."<br>
<select style='width:180px;' class='textbox' id='list_ins' name='list_ins[]' size='$maxrows' multiple>".$insert_opt_list."</select>
<div align='center'>".$locale['435']." [<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectAll()\">".$locale['436']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:populateSelectNone()\">".$locale['437']."</a>]</div></td>
</tr>
<tr>
<td colspan='2' align='center'><hr>
<input type='hidden' name='file' value='$file'>
<input class='button' type='submit' name='btn_do_restore' style='width:100px;' value='".$locale['438']."'>
<input class='button' type='submit' name='btn_cancel' style='width:100px;' value='".$locale['439']."'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}else{
	$table_opt_list="";
	$result=dbquery("SHOW tables");
	while($row=dbarraynum($result)){
		$table_opt_list.="<option value='".$row[0]."'";
		if(preg_match("/^".$db_prefix."/i",$row[0])){
			$table_opt_list.=" selected";
		}
		$table_opt_list.=">".$row[0]."</option>\n";
	}
	opentable($locale['450']);
	echo "
<script type='text/javascript'>
<!--
function backupDelete(what){if(confirm('".$locale['460']."\\n\\n'+what)){window.location='".FUSION_SELF."?action=delete&file='+what;}}
function backupSelectCore(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=(document.frmbackup.elements['db_tables[]'].options[i].text).match(/^$db_prefix/);}}
function backupSelectAll(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=true;}}
function backupSelectNone(){for(i=0;i<document.frmbackup.elements['db_tables[]'].length;i++){document.frmbackup.elements['db_tables[]'].options[i].selected=false;}}
//-->
</script>
<form action='".FUSION_SELF."' name='frmbackup' method='post'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td valign='top'>
<table align='center' cellspacing='0' cellpadding='0' class='tbl'>
<tr>
<td colspan='2' class='tbl2' align='left'>".$locale['451']."</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['414']."</td>
<td>$db_name</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['415']."</td>
<td>".$db_prefix."</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['452']."</td>
<td>".parseByteSize(get_database_size(),2,false)." (".get_table_count()." tables)</td>
</tr>
<tr>
<td class='alt' align='right'>".$locale['453']."</td>
<td>".parseByteSize(get_database_size($db_prefix),2,false)." (".get_table_count($db_prefix)." tables)</td>
</tr>
<tr>
<td colspan='2' class='tbl2' align='left'>".$locale['454']."</td>
</tr>\n";
//<tr>
//<td class='alt' align='right'>".$locale['431']."</td>
//<td><input class='textbox' type='text' name='backup_filename' value='backup_".date('Y-m-d-H:i')."' style='width:200px;'></td>
//</tr>
echo "<tr>
<td class='alt' align='right'>".$locale['455']."</td>
<td><select class='textbox' name='backup_type'>
<option value='.gz' selected>.sql.gz ".$locale['456']."</option>
<option value='.sql'>.sql</option>
</select></td>
</tr>
</table>
</td>
<td valign='top'>
<table border='0' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td class='tbl2'>".$locale['457']."</td>
</tr>
<tr>
<td>
<select style='margin:5px 0px' class='textbox' id='tablelist' name='db_tables[]' size='17' multiple>".$table_opt_list."</select>
<div align='center'>".$locale['435']." [<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectCore()\">".$locale['458']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectAll()\">".$locale['436']."</a>]
[<a href=\"javascript:void(0)\" onclick=\"javascript:backupSelectNone()\">".$locale['437']."</a>]</div>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan='2' align='center'><hr>
<input class='button' type='submit' name='btn_create_backup' style='width:100px;' value='".$locale['459']."'></td>
</tr>
</table>
</form>\n";
	closetable();
	tablebreak();
	opentable($locale['480']);
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr><td>";
	$dh=@opendir("$dbbackupdir");
	if($dh!=false){
		$filelist=array();
		while(false!==($file=@readdir($dh))){
			if(is_file("$dbbackupdir$file")&&$file!="."&&$file!=".."&&preg_match("/.(sql|gz)$/",$file)){
				$filelist[]=array(filemtime("$dbbackupdir$file"), $file);
			}
		}
		@closedir($dh);
		if(!empty($filelist)){
			echo "<tr><td class='tbl2'>".$locale['481']."</td>";
			echo "<td class='tbl2' align='center'>".$locale['482']."</b></td>";
			echo "<td class='tbl2' align='right'>".$locale['483']."</td>";
			echo "<td class='tbl2' align='right'>".$locale['484']."</td></tr>";
			rsort($filelist);
			$i=0;
			foreach($filelist as $key=>$file){
				if(($i%2)==0){echo "<tr class='forum1'>";}else{echo "<tr class='forum2'>";}
				echo "<td><a href='".FUSION_SELF."?action=download&file=$file[1]'>$file[1]</a></td>";
				echo "<td align='center'>".strftime($settings['shortdate'], $file[0])."</td>";
				echo "<td align='right'>".parseByteSize(@filesize("$dbbackupdir$file[1]"),2,false)."</td>";
				echo "<td align='right'>";
				echo "[<a href=\"javascript:backupDelete('$file[1]')\">".$locale['485']."</a>]&nbsp;";
				echo "[<a href='".FUSION_SELF."?action=restore&file=$file[1]'>".$locale['486']."</a>]&nbsp;";
				echo "[<a href='".FUSION_SELF."?action=info&file=$file[1]'>".$locale['487']."</a>]";
				echo "</td></tr>";
				$i++;
			}
		}else{
			echo "<tr><td colspan='4' align='center'>".$locale['488']."</td></tr>";
		}
	}else{
		echo "<tr><td colspan='4' align='center'>".$locale['489']."'$dbbackupdir'".$locale['490']."</td></tr>";
	}
	echo "</table>";
	closetable();
}

function get_database_size($prefix=""){
	global $db_name;
	$db_size=0;
	$result=dbquery("SHOW TABLE STATUS FROM `".$db_name."`");
	while($row=dbarray($result)){
		if (!isset($row['Type'])) $row['Type'] = "";
		if (!isset($row['Engine'])) $row['Engine'] = "";
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type'])) || (eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Engine'])) && (preg_match("/^".$prefix."/",$row['Name']))){
			$db_size+=$row['Data_length']+$row['Index_length'];
		}
	}
	return $db_size;
}

function get_table_count($prefix=""){
	global $db_name;
	$tbl_count=0;
	$result=dbquery("SHOW TABLE STATUS FROM `".$db_name."`");
	while($row=dbarray($result)){
		if (!isset($row['Type'])) $row['Type'] = "";
		if (!isset($row['Engine'])) $row['Engine'] = "";
		if((eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Type'])) || (eregi('^(MyISAM|ISAM|HEAP|InnoDB)$',$row['Engine'])) && (preg_match("/^".$prefix."/",$row['Name']))){
			$tbl_count++;
		}
	}
	return $tbl_count;
}

function gzcompressfile($source,$level=false){
	$dest=$source.'.gz';
	$mode='wb'.$level;
	$error=false;
	if($fp_out=gzopen($dest,$mode)){
		if($fp_in=fopen($source,'rb')){
			while(!feof($fp_in))
			gzputs($fp_out,fread($fp_in,1024*512));
			fclose($fp_in);
		}else $error=true;
		gzclose($fp_out);
	}else $error=true;
	if($error)return false; else return $dest;
}

echo "</td>\n";
require_once BASEDIR."footer.php";
?>