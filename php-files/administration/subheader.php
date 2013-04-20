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
if (!defined("IN_FUSION")) { header("Location: index.php"); exit; }

require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) fallback(BASEDIR."maintenance.php");
if (iMEMBER) $result = dbquery("UPDATE ".$db_prefix."users SET user_lastvisit='".time()."', user_ip='".USER_IP."' WHERE user_id='".$userdata['user_id']."'");

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">
<html>
<head>
<title>".$settings['sitename']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>
<meta name='description' content='".$settings['description']."'>
<meta name='keywords' content='".$settings['keywords']."'>
<link rel='stylesheet' href='".THEME."styles.css' type='text/css'>
<script language='javascript' type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";

if ($settings['tinymce_enabled'] == 1) {
	echo "<script language='javascript' type='text/javascript' src='".INCLUDES."jscripts/tiny_mce/tiny_mce_gzip.php'></script>
<script type='text/javascript'>
function advanced() {
	tinyMCE.init({
	mode:'textareas',
	theme:'advanced',
	width:'100%',
	height:'250',
	language:'".$locale['tinymce']."',
	entities:'60,lt,62,gt',
	document_base_url:'".$settings['siteurl']."',
	relative_urls:'false',
	convert_newlines_to_brs:'true',
	force_br_newlines:'true',
	force_p_newlines:'false',
	plugins:'table,advhr,advimage,advlink,insertdatetime,searchreplace,contextmenu,ibrowser',
	theme_advanced_buttons1_add:'fontsizeselect',
	theme_advanced_buttons2_add:'separator,insertdate,inserttime,separator,forecolor,backcolor',
	theme_advanced_buttons3_add_before:'ibrowser,tablecontrols,separator',
	theme_advanced_buttons3_add:'advhr',
	theme_advanced_toolbar_location:'bottom',
	theme_advanced_toolbar_align:'center',
	theme_advanced_path_location:'none',
	theme_advanced_toolbar_location:'top',
	content_css:'".THEME."styles.css',
	external_image_list_url:'".IMAGES."imagelist.js',
	plugin_insertdate_dateFormat:'%d-%m-%Y',
	plugin_insertdate_timeFormat:'%H:%M:%S',
	invalid_elements:'script,object,applet,iframe',
	extended_valid_elements:'a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]'
	});
}
function simple() {
	tinyMCE.init({
	mode:'textareas',
	theme:'simple',
	language:'en',
	convert_newlines_to_brs:'true',
	force_br_newlines:'true',
	force_p_newlines:'false'
	});
}

function showtiny(EditorID) {
	tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
	tinyMCE.addMCEControl(document.getElementById(EditorID),EditorID);
}

function hidetiny(EditorID) {
	tinyMCE.removeMCEControl(tinyMCE.getEditorId(EditorID));
}
</script>\n";
}

echo "</head>
<body bgcolor='$body_bg' text='$body_text'>\n";

render_header("<img src='".BASEDIR.$settings['sitebanner']."' alt='".$settings['sitename']."' title='".$settings['sitename']."'>");
?>