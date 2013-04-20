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
+----------------------------------------------------*/
if (eregi("maincore.php", $_SERVER['PHP_SELF'])) die();

// If register_globals is turned off, extract super globals (php 4.2.0+)
if (ini_get('register_globals') != 1) {
	$supers = array("_REQUEST","_ENV","_SERVER","_POST","_GET","_COOKIE","_SESSION","_FILES","_GLOBALS");
	foreach ($supers as $__s) {
		if ((isset($$__s) == true) && (is_array($$__s) == true)) extract($$__s, EXTR_OVERWRITE);
	}
	unset($supers);
}

// Prevent any possible XSS attacks via $_GET.
foreach ($_GET as $check_url) {
	if ((eregi("<[^>]*script*\"?[^>]*>", $check_url)) || (eregi("<[^>]*object*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*iframe*\"?[^>]*>", $check_url)) || (eregi("<[^>]*applet*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*meta*\"?[^>]*>", $check_url)) || (eregi("<[^>]*style*\"?[^>]*>", $check_url)) ||
		(eregi("<[^>]*form*\"?[^>]*>", $check_url)) || (eregi("\([^>]*\"?[^)]*\)", $check_url)) ||
		(eregi("\"", $check_url))) {
	die ();
	}
}
unset($check_url);

// Start Output Buffering
ob_start();
//ob_start("ob_gzhandler");

// Locate config.php and set the basedir path
$folder_level = "";
while (!file_exists($folder_level."config.php")) { $folder_level .= "../"; }
require_once $folder_level."config.php";
define("BASEDIR", $folder_level);

// If config.php is empty, activate setup.php script
if (!isset($db_name)) redirect("setup.php");

// Establish mySQL database connection
$link = dbconnect($db_host, $db_user, $db_pass, $db_name);

// Create Validation image if $vimage is set and die();
if (isset($vimage)) {
	$check_url = (isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
	if (eregi("register.php", $check_url) && preg_match("/^[0-9a-z]{32}$/", $vimage)) {
		$vres = dbquery("SELECT * FROM ".$db_prefix."vcode WHERE vcode_2='$vimage'");
		if (dbrows($vres)) {
			$vdata = dbarray($vres);
			$imf = rand(3,5); $imx = rand(15,40); $imy = rand(2,7);
			$im = ImageCreateFromJPEG("images/validate_bg.jpg");
			$tcolor = ImageColorAllocate($im, 40, 40, 40);
			Header("Content-type: image/jpeg");
			ImageString ($im, $imf, $imx, $imy, $vdata['vcode_1'], $tcolor);
			ImageJPEG($im, '', 80);
			ImageDestroy($im);
		}
	}
	die();
	break;
}

// Fetch the Site Settings from the database and store them in the $settings variable
$settings = dbarray(dbquery("SELECT * FROM ".$db_prefix."settings"));

// Common definitions
define("IN_FUSION", TRUE);
define("FUSION_REQUEST", isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != "" ? $_SERVER['REQUEST_URI'] : $_SERVER['SCRIPT_NAME']);
define("FUSION_QUERY", isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : "");
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
define("USER_IP", $_SERVER['REMOTE_ADDR']);
define("QUOTES_GPC", (ini_get('magic_quotes_gpc') ? TRUE : FALSE));
// Path definitions
define("ADMIN", BASEDIR."administration/");
define("IMAGES", BASEDIR."images/");
define("IMAGES_A", IMAGES."articles/");
define("IMAGES_N", IMAGES."news/");
define("IMAGES_NC", IMAGES."news_cats/");
define("INCLUDES", BASEDIR."includes/");
define("LOCALE", BASEDIR."locale/");
define("LOCALESET", $settings['locale']."/");
define("FORUM", BASEDIR."forum/");
define("INFUSIONS", BASEDIR."infusions/");
define("PHOTOS", IMAGES."photoalbum/");
define("THEMES", BASEDIR."themes/");

// MySQL database functions
function dbquery($query) {
	$result = @mysql_query($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbcount($field,$table,$conditions="") {
	$cond = ($conditions ? " WHERE ".$conditions : "");
	$result = @mysql_query("SELECT Count".$field." FROM ".DB_PREFIX.$table.$cond);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		$rows = mysql_result($result, 0);
		return $rows;
	}
}

function dbresult($query, $row) {
	$result = @mysql_result($query, $row);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbrows($query) {
	$result = @mysql_num_rows($query);
	return $result;
}

function dbarray($query) {
	$result = @mysql_fetch_assoc($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbarraynum($query) {
	$result = @mysql_fetch_row($query);
	if (!$result) {
		echo mysql_error();
		return false;
	} else {
		return $result;
	}
}

function dbconnect($db_host, $db_user, $db_pass, $db_name) {
	$db_connect = @mysql_connect($db_host, $db_user, $db_pass);
	$db_select = @mysql_select_db($db_name);
	if (!$db_connect) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to establish connection to MySQL</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	} elseif (!$db_select) {
		die("<div style='font-family:Verdana;font-size:11px;text-align:center;'><b>Unable to select MySQL database</b><br>".mysql_errno()." : ".mysql_error()."</div>");
	}
}

// Load the Global language file
include LOCALE.LOCALESET."global.php";

// Check if users full or partial ip is blacklisted
$sub_ip1 = substr(USER_IP,0,strlen(USER_IP)-strlen(strrchr(USER_IP,".")));
$sub_ip2 = substr($sub_ip1,0,strlen($sub_ip1)-strlen(strrchr($sub_ip1,".")));
if (dbcount("(*)", "blacklist", "blacklist_ip='".USER_IP."' OR blacklist_ip='$sub_ip1' OR blacklist_ip='$sub_ip2'")) {
	header("Location: http://www.google.com/"); exit;
}

// PHP-Fusion user cookie functions
if (!isset($_COOKIE['fusion_visited'])) {
	$result=dbquery("UPDATE ".$db_prefix."settings SET counter=counter+1");
	setcookie("fusion_visited", "yes", time() + 31536000, "/", "", "0");
}

if (isset($_POST['login'])) {
	$user_pass = md5($_POST['user_pass']);
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_name='$user_name' AND user_password='$user_pass'");
	if (dbrows($result) != 0) {
		$data = dbarray($result);
		$cookie_value = $data['user_id'].".".$data['user_password'];
		if ($data['user_status'] == 0) {	
			$cookie_exp = isset($_POST['remember_me']) ? time() + 3600*24*30 : time() + 3600*3;
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("fusion_user", $cookie_value, $cookie_exp, "/", "", "0");
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], "script");
		} elseif ($data['user_status'] == 1) {
			redirect(BASEDIR."setuser.php?error=1", "script");
		} elseif ($data['user_status'] == 2) {
			redirect(BASEDIR."setuser.php?error=2", "script");
		}
	} else {
		redirect(BASEDIR."setuser.php?error=3");
	}
}

if (isset($_COOKIE['fusion_user'])) {
	$cookie_vars = explode(".", $_COOKIE['fusion_user']);
	$cookie_1 = isNum($cookie_vars['0']) ? $cookie_vars['0'] : "0";
	$cookie_2 = (preg_match("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='$cookie_1' AND user_password='$cookie_2'");
	unset($cookie_vars,$cookie_1,$cookie_2);
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		if ($userdata['user_status'] != 1) {
			if ($userdata['user_theme'] != "Default" && file_exists(THEMES.$userdata['user_theme']."/theme.php")) {
				define("THEME", THEMES.$userdata['user_theme']."/");
			} else {
				define("THEME", THEMES.$settings['theme']."/");
			}
			if ($userdata['user_offset'] <> 0) {
				$settings['timeoffset'] = $settings['timeoffset'] + $userdata['user_offset'];
			}
			if (empty($_COOKIE['fusion_lastvisit'])) {
				setcookie("fusion_lastvisit", $userdata['user_lastvisit'], time() + 3600, "/", "", "0");
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_COOKIE['fusion_lastvisit'];
			}
		} else {
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie("fusion_user", "", time() - 7200, "/", "", "0");
			setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
			redirect(BASEDIR."index.php", "script");
		}
	} else {
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		setcookie("fusion_user", "", time() - 7200, "/", "", "0");
		setcookie("fusion_lastvisit", "", time() - 7200, "/", "", "0");
		redirect(BASEDIR."index.php", "script");
	}
} else {
	define("THEME", THEMES.$settings['theme']."/");
	$userdata = "";	$userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}

// Redirect browser using the header function
function redirect($location, $type="header") {
	if ($type == "header") {
		header("Location: ".$location);
	} else {
		echo "<script type='text/javascript'>document.location.href='".$location."'</script>\n";
	}
}

// Fallback to safe area in event of unauthorised access
function fallback($location) {
	header("Location: ".$location);
	exit;
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// stripslash function, only stripslashes if magic_quotes_gpc is on
function stripslash($text) {
	if (QUOTES_GPC) $text = stripslashes($text);
	return $text;
}

// stripslash function, add correct number of slashes depending on quotes_gpc
function addslash($text) {
	if (!QUOTES_GPC) {
		$text = addslashes(addslashes($text));
	} else {
		$text = addslashes($text);
	}
	return $text;
}

// htmlentities is too agressive so we use this function
function phpentities($text) {
	$search = array("&", "\"", "'", "\\", "<", ">");
	$replace = array("&amp;", "&quot;", "&#39;", "&#92;", "&lt;", "&gt;");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Trim a line of text to a preferred length
function trimlink($text, $length) {
	$dec = array("\"", "'", "\\", '\"', "\'", "<", ">");
	$enc = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;");
	$text = str_replace($enc, $dec, $text);
	if (strlen($text) > $length) $text = substr($text, 0, ($length-3))."...";
	$text = str_replace($dec, $enc, $text);
	return $text;
}

// Validate numeric input
function isNum($value) {
	return (preg_match("/^[0-9]+$/", $value));
}

// Validate bbcode images
function isImage($matches) {
	$img = $matches[1].str_replace(array("?","&","="),"",$matches[3]).$matches[4];
	if (@getimagesize($img)) {
		$res = "<img src='".$img."' style='border:0px;'>";
	} else {
		$res = "[img]".$img."[/img]";
	}
	return $res;
}

// Parse smiley bbcode into HTML images
function parsesmileys($message) {
	$smiley = array(
		"#\:\)#si" => "<img src='".IMAGES."smiley/smile.gif' alt='smiley'>",
		"#\;\)#si" => "<img src='".IMAGES."smiley/wink.gif' alt='smiley'>",
		"#\:\(#si" => "<img src='".IMAGES."smiley/sad.gif' alt='smiley'>",
		"#\:\|#si" => "<img src='".IMAGES."smiley/frown.gif' alt='smiley'>",
		"#\:o#si" => "<img src='".IMAGES."smiley/shock.gif' alt='smiley'>",
		"#\:p#si" => "<img src='".IMAGES."smiley/pfft.gif' alt='smiley'>",
		"#b\)#si" => "<img src='".IMAGES."smiley/cool.gif' alt='smiley'>",
		"#\:d#si" => "<img src='".IMAGES."smiley/grin.gif' alt='smiley'>",
		"#\:@#si" => "<img src='".IMAGES."smiley/angry.gif' alt='smiley'>"
	);
	foreach($smiley as $key=>$smiley_img) $message = preg_replace($key, $smiley_img, $message);
	return $message;
}

// Show smiley icons in comments, forum and other post pages
function displaysmileys($textarea) {
	$smiles = "";
	$smileys = array (
		":)" => "smile.gif",
		";)" => "wink.gif",
		":|" => "frown.gif",
		":(" => "sad.gif",
		":o" => "shock.gif",
		":p" => "pfft.gif",
		"B)" => "cool.gif",
		":D" => "grin.gif",
		":@" => "angry.gif"
	);
	foreach($smileys as $key=>$smiley) $smiles .= "<img src='".IMAGES."smiley/$smiley' onClick=\"insertText('$textarea', '$key');\">\n";
	return $smiles;
}

// Parse bbcode into HTML code
function parseubb($text) {
	$text = preg_replace('#\[b\](.*?)\[/b\]#si', '<b>\1</b>', $text);
	
	$text = preg_replace('#\[i\](.*?)\[/i\]#si', '<i>\1</i>', $text);
	$text = preg_replace('#\[u\](.*?)\[/u\]#si', '<u>\1</u>', $text);
	$text = preg_replace('#\[center\](.*?)\[/center\]#si', '<center>\1</center>', $text);
	
	$text = preg_replace('#\[url\]([\r\n]*)(http://|https://)([^\s\'\";:\+]*?)([\r\n]*)\[/url\]#si', '<a href=\'\2\3\' target=\'_blank\'>\2\3</a>', $text);
	$text = preg_replace('#\[url\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/url\]#si', '<a href=\'http://\2\' target=\'_blank\'>\2</a>', $text);
	$text = preg_replace('#\[url=([\r\n]*)(http://|https://)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'\2\3\' target=\'_blank\'>\4</a>', $text);
	$text = preg_replace('#\[url=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/url\]#si', '<a href=\'http://\2\' target=\'_blank\'>\3</a>', $text);
	
	$text = preg_replace('#\[mail\]([\r\n]*)([^\s\'\";:\+]*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	$text = preg_replace('#\[mail=([\r\n]*)([^\s\'\";:\+]*?)\](.*?)([\r\n]*)\[/mail\]#si', '<a href=\'mailto:\2\'>\2</a>', $text);
	
	$text = preg_replace('#\[small\](.*?)\[/small\]#si', '<span class=\'small\'>\1</span>', $text);
	$text = preg_replace('#\[color=(black|blue|brown|cyan|gray|green|lime|maroon|navy|olive|orange|purple|red|silver|violet|white|yellow)\](.*?)\[/color\]#si', '<span style=\'color:\1\'>\2</span>', $text);
	
	$text = preg_replace('#\[flash width=([0-9]*?) height=([0-9]*?)\]([^\s\'\";:\+]*?)(\.swf)\[/flash\]#si', '<object classid=\'clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\' codebase=\'http://active.macromedia.com/flash6/cabs/swflash.cab#version=6,0,0,0\' id=\'\3\4\' width=\'\1\' height=\'\2\'><param name=movie value=\'\3\4\'><param name=\'quality\' value=\'high\'><param name=\'bgcolor\' value=\'#ffffff\'><embed src=\'\3\4\' quality=\'high\' bgcolor=\'#ffffff\' width=\'\1\' height=\'\2\' type=\'application/x-shockwave-flash\' pluginspage=\'http://www.macromedia.com/go/getflashplayer\'></embed></object>', $text);
	$text = preg_replace_callback("#\[img\]((http|ftp|https|ftps)://)(.*?)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))\[/img\]#si","isImage",$text);

	$qcount = substr_count($text, "[quote]"); $ccount = substr_count($text, "[code]");
	for ($i=0;$i < $qcount;$i++) $text = preg_replace('#\[quote\](.*?)\[/quote\]#si', '<div class=\'quote\'>\1</div>', $text);
	for ($i=0;$i < $ccount;$i++) $text = preg_replace('#\[code\](.*?)\[/code\]#si', '<div class=\'quote\' style=\'width:400px;white-space:nowrap;overflow:auto\'><code style=\'white-space:nowrap\'>\1<br><br><br></code></div>', $text);

	$text = descript($text,false);

	return $text;
}

// This function sanitises news & article submissions
function descript($text,$striptags=true) {
	// Convert problematic ascii characters to their true values
	$search = array("40","41","58","65","66","67","68","69","70",
		"71","72","73","74","75","76","77","78","79","80","81",
		"82","83","84","85","86","87","88","89","90","97","98",
		"99","100","101","102","103","104","105","106","107",
		"108","109","110","111","112","113","114","115","116",
		"117","118","119","120","121","122"
		);
	$replace = array("(",")",":","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z","a","b","c","d","e","f","g","h",
		"i","j","k","l","m","n","o","p","q","r","s","t","u",
		"v","w","x","y","z"
		);
	$entities = count($search);
	for ($i=0;$i < $entities;$i++) $text = preg_replace("#(&\#)(0*".$search[$i]."+);*#si", $replace[$i], $text);
	// the following is based on code from bitflux (http://blog.bitflux.ch/wiki/)
	// Kill hexadecimal characters completely
	$text = preg_replace('#(&\#x)([0-9A-F]+);*#si', "", $text);
	// remove any attribute starting with "on" or xmlns
	$text = preg_replace('#(<[^>]+[\\"\'\s])(onmouseover|onmousedown|onmouseup|onmouseout|onmousemove|onclick|ondblclick|onload|xmlns)[^>]*>#iU', ">", $text);
	// remove javascript: and vbscript: protocol
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)script:#iU', '$1=$2nojscript...', $text);
	$text = preg_replace('#([a-z]*)=([\`\'\"]*)javascript:#iU', '$1=$2nojavascript...', $text);
	$text = preg_replace('#([a-z]*)=([\'\"]*)vbscript:#iU', '$1=$2novbscript...', $text);
        //<span style="width: expression(alert('Ping!'));"></span> (only affects ie...)
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*expression\([^>]*>#iU', "$1>", $text);
	$text = preg_replace('#(<[^>]+)style=([\`\'\"]*).*behaviour\([^>]*>#iU', "$1>", $text);
	if ($striptags) {
		do {
	        	$thistext = $text;
			$text = preg_replace('#</*(applet|meta|xml|blink|link|style|script|embed|object|iframe|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i', "", $text);
		} while ($thistext != $text);
	}
	return $text;
}

// Replace offensive words with the defined replacement word
function censorwords($text) {
	global $settings;
	if ($settings['bad_words_enabled'] == "1" && $settings['bad_words'] != "" ) {
		$word_list = explode("\r\n", $settings['bad_words']);
		for ($i=0;$i < count($word_list);$i++) {
			if ($word_list[$i] != "") $text = preg_replace("/".$word_list[$i]."/si", $settings['bad_word_replace'], $text);
		}
	}
	return $text;
}

// Display the user's level
function getuserlevel($userlevel) {
	global $locale;
	if ($userlevel==101) { return $locale['user1']; }
	elseif ($userlevel==102) { return $locale['user2']; }
	elseif ($userlevel==103) { return $locale['user3']; }
}

// Check if Administrator has correct rights assigned
function checkrights($right) {
	if (iADMIN && in_array($right, explode(".", iUSER_RIGHTS))) {
		return true;
	} else {
		return false;
	}
}

// Check if user is assigned to the specified user group
function checkgroup($group) {
	if (iSUPERADMIN && ($group == "0" || $group == "101" || $group == "102" || $group == "103")) { return true; }
	elseif (iADMIN && ($group == "0" || $group == "101" || $group == "102")) { return true; }
	elseif (iMEMBER && ($group == "0" || $group == "101")) { return true; }
	elseif (iGUEST && $group == "0") { return true; }
	elseif (iMEMBER && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

// Compile access levels & user group array
function getusergroups() {
	global $locale;
	$groups_array = array(
		array("0", $locale['user0']),
		array("101", $locale['user1']),
		array("102", $locale['user2']),
		array("103", $locale['user3'])
	);
	$gsql = dbquery("SELECT group_id,group_name FROM ".DB_PREFIX."user_groups");
	while ($gdata = dbarray($gsql)) {
		array_push($groups_array, array($gdata['group_id'], $gdata['group_name']));
	}
	return $groups_array;
}

// Get the name of the access level or user group
function getgroupname($group) {
	global $locale;
	if ($group == "0") { return $locale['user0']; }
	elseif ($group == "101") { return $locale['user1']; }
	elseif ($group == "102") { return $locale['user2']; }
	elseif ($group == "103") { return $locale['user3'];
	} else {
		$gsql = dbquery("SELECT group_id,group_name FROM ".DB_PREFIX."user_groups WHERE group_id='$group'");
		if (dbrows($gsql)!=0) {
			$gdata = dbarray($gsql);
			return $gdata['group_name'];
		} else {
			return "N/A";
		}
	}
}

function groupaccess($field) {
	if (iSUPERADMIN) { $res = "($field='0' OR $field='101' OR $field='102' OR $field='103'";
	} elseif (iADMIN) { $res = "($field='0' OR $field='101' OR $field='102'";
	} elseif (iMEMBER) { $res = "($field='0' OR $field='101'";
	} elseif (iGUEST) { $res = "($field='0'"; }
	if (iUSER_GROUPS != "") $res .= " OR $field='".str_replace(".", "' OR $field='", iUSER_GROUPS)."'";
	$res .= ")";
	return $res;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected="") {
	$res = "";
	for ($i=0;$i < count($files);$i++) {
		$sel = ($selected == $files[$i] ? " selected" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}

// Universal page pagination function by CrappoMan
function makepagenav($start,$count,$total,$range=0,$link=""){
	global $locale;
	if ($link == "") $link = FUSION_SELF."?";
	$res="";
	$pg_cnt=ceil($total / $count);
	if ($pg_cnt > 1) {
		$idx_back = $start - $count;
		$idx_next = $start + $count;
		$cur_page=ceil(($start + 1) / $count);
		$res.="<table cellspacing='1' cellpadding='1' border='0' class='tbl-border'>\n<tr>\n";
		$res.="<td class='tbl2'><span class='small'>".$locale['052']."$cur_page".$locale['053']."$pg_cnt</span></td>\n";
		if ($idx_back >= 0) {
			if ($cur_page > ($range + 1)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=0'>&lt;&lt;</a></td>\n";
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_back'>&lt;</a></td>\n";
		}
		$idx_fst=max($cur_page - $range, 1);
		$idx_lst=min($cur_page + $range, $pg_cnt);
		if ($range==0) {
			$idx_fst = 1;
			$idx_lst=$pg_cnt;
		}
		for($i=$idx_fst;$i<=$idx_lst;$i++) {
			$offset_page=($i - 1) * $count;
			if ($i==$cur_page) {
				$res.="<td class='tbl1'><span class='small'><b>$i</b></span></td>\n";
			} else {
				$res.="<td class='tbl1'><a class='small' href='$link"."rowstart=$offset_page'>$i</a></td>\n";
			}
		}
		if ($idx_next < $total) {
			$res.="<td class='tbl2'><a class='small' href='$link"."rowstart=$idx_next'>&gt;</a></td>\n";
			if ($cur_page < ($pg_cnt - $range)) $res.="<td class='tbl2'><a class='small' href='$link"."rowstart=".($pg_cnt-1)*$count."'>&gt;&gt;</a></td>\n";
		}
		$res.="</tr>\n</table>\n";

	}
	return $res;
}

// Format the date & time accordingly
function showdate($format, $val) {
	global $settings;
	if ($format == "shortdate" || $format == "longdate" || $format == "forumdate") {
		return strftime($settings[$format], $val+($settings['timeoffset']*3600));
	} else {
		return strftime($format, $val+($settings['timeoffset']*3600));
	}
}

// Photo Gallery functions by CrappoMan
function constrainImage($width,$height,$max_width,$max_height){
	if (!$height||!$width||!$max_height||!$max_width) {
		return false;
	} elseif ($height>$max_height||$width>$max_width) {
		if ($height>$width) {
			if ($img_width>$max_width) {
				$img_width=$max_width;
				$img_height=round(($max_width*$max_height)/$img_width);
			} else {
				$img_width=round(($width*$max_height)/$height);
				$img_height=$max_height;
			}
		} else {
			if ($img_height>$max_height) {
				$img_width=round(($max_width*$max_height)/$img_height);
				$img_height=$max_height;
			} else {
				$img_width=$max_width;
				$img_height=round(($height*$max_width)/$width);
			}
		}
		return array('width'=>$img_width,'height'=>$img_height);
	} else {
		return array('width'=>$width,'height'=>$height);
	}
}

function createthumbnail($origfile,$thumbfile,$new_w,$new_h) {
	
	global $settings;
	
	$origimage=imagecreatefromjpeg($origfile);
	$origwidth=imagesx($origimage);
	$origheight=imagesy($origimage);
	$size=constrainImage($origwidth,$origheight,$new_w,$new_h);
	if ($settings['thumb_compression']=="gd1") {
		$thumbimage=imagecreate($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresized($thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	} else {
		$thumbimage=imagecreatetruecolor($new_w,$new_h);
		$background=imagecolorallocate($thumbimage,255,255,255);
		imagefill($thumbimage,0,0,$background);
		$result=imagecopyresampled($thumbimage, $origimage,round(($new_w-$size['width'])/2),round(($new_h-$size['height'])/2),0,0,$size['width'],$size['height'],$origwidth,$origheight);
	}
	touch($thumbfile);
	imageJPEG($thumbimage,$thumbfile);
}

// Translate bytes into kb, mb, gb or tb by CrappoMan
function parsebytesize($size,$digits=2,$dir=false) {
	$kb=1024; $mb=1024*$kb; $gb=1024*$mb; $tb=1024*$gb;
	if (($size==0)&&($dir)) { return "Empty"; }
	elseif ($size<$kb) { return $size."Bytes"; }
	elseif ($size<$mb) { return round($size/$kb,$digits)."Kb"; }
	elseif ($size<$gb) { return round($size/$mb,$digits)."Mb"; }
	elseif ($size<$tb) { return round($size/$gb,$digits)."Gb"; }
	else { return round($size/$tb,$digits)."Tb"; }
}

// User level, Admin Rights & User Group definitions
define("iGUEST",$userdata['user_level'] == 0 ? 1 : 0);
define("iMEMBER", $userdata['user_level'] >= 101 ? 1 : 0);
define("iADMIN", $userdata['user_level'] >= 102 ? 1 : 0);
define("iSUPERADMIN", $userdata['user_level'] == 103 ? 1 : 0);
define("iUSER", $userdata['user_level']);
define("iUSER_RIGHTS", $userdata['user_rights']);
define("iUSER_GROUPS", substr($userdata['user_groups'], 1));
?>