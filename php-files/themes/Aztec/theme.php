<?php
/*--------------------------------------------+
| PHP-Fusion v6 - Content Management System   |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
/*--------------------------------------------+
|       Aztec Theme for PHP-Fusion v6         |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock © 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";

/************************/
/* Theme Settings		*/
/************************/

$body_text = "#000000";
$body_bg = "#505050";
$theme_width = "100%";
$theme_width_l = "160";
$theme_width_r = "160";

function render_header($header_content) {

include LOCALE.LOCALESET."forum/main.php";

global $theme_width,$settings;
	
	echo "<table cellpadding='0' cellspacing='0' width='$theme_width' border='0' align='center' bgcolor='#EFEFEF'>";
	echo "<tr><td bgcolor='#EFEFEF'>";
	echo "<img height='16' alt='' hspace='0' src='".THEME."images/corner-top-left.gif' width='17' align='left'>";
	// Start banner code
	echo "$header_content</td>";
	// End banner code
	echo "<td bgcolor='#EFEFEF'><IMG src='".THEME."images/pixel.gif' width='1' height='1' alt='' border='0' hspace='0'></td>";
	echo "<td bgcolor='#EFEFEF' align='center'>";

	// Search script //
	echo "<form name='search' method='post' action='".BASEDIR."search.php?stype=n'><span class='side-small'><b>News Search: </b></span>";
	echo "<input type='textbox' value='Enter search here...' name='stext' class='textbox' style='width:130px' onBlur=\"if(this.value=='') this.value='Enter search here...';\" onFocus=\"if(this.value=='Enter search here...') this.value='';\"> ";
	echo "<input type='submit' name='search' value='".$locale['550']."' class='button'></form></td>";
	// Search script end //

	echo "<td bgcolor='#EFEFEF' valign='top'><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-right.gif' width='17' align='right'>";
	echo "</td></tr></table>";
	echo "<table cellpadding='0' cellspacing='0' width='$theme_width' border='0' align='center'><tr>";
	echo "<td bgcolor='#000000' colspan='4'><IMG src='".THEME."images/pixel.gif' width='1' height=1 alt='' border='0' hspace='0'>";
	echo "</td></tr>";
	echo "<tr valign='middle' bgcolor='#dedebb'><td class='white-header'>\n";
	echo showsublinks("&middot;")."</td>";
	echo "<td align='right' class='white-header'>".showsubdate()."</td></tr>";
	echo "<tr><td bgcolor='#000000' colspan='4'><IMG src='".THEME."images/pixel.gif' width='1' height='1' alt='' border='0' hspace='0'>";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr valign='top'>";
	echo "<td valign='middle' align='right'>";
	echo "<table width='$theme_width' cellpadding='4' bgcolor='#EFEFEF' cellspacing='0' border='0' align='center'><tr>";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table width='$theme_width' cellpadding='0' cellspacing='0' border='0' bgcolor='#EFEFEF' align='center'>";
	echo "<tr valign='top'><td align='center' height='17'>";
	echo "<img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-left.gif' width='17' align='left'>";
	echo "<img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-right.gif' width='17' align='right'>";
	echo "</td></tr></table>";
	tablebreak();
	echo "<table width='$theme_width' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr><td style='color:#dddddd'>".stripslashes($settings['footer'])."</td></tr></table>";
	tablebreak();
	echo "<table width='$theme_width' cellpadding='0' cellspacing='0' border='0' bgcolor='#EFEFEF' align='center'>";
	echo "<tr valign='middle'>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-left.gif' width='17' align='left'></td>";
	echo "<td class='footer' align='center' width='100%'>";
	echo "<strong>".$settings['counter']." </strong>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-top-right.gif' width='17' align='right'></td></tr>";
	echo "<tr valign='middle' align='center'><td class='footer' width='100%' colspan='3'>";
	echo "</td></tr>";
	echo "<tr><td><img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-left.gif' width='17' align='left'></td>";
	echo "<td align='center' width='100%'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'>PHP-Fusion</a> &copy; 2003-2006";
	}
	echo " - Aztec Theme by: <a href='http://www.phpfusion-themes.com' target='_blank'>PHP-Fusion Themes</a></td>";
	echo "<td><img height='17' alt='' hspace='0' src='".THEME."images/corner-bottom-right.gif' width='17' align='right'>";
	echo "</td></tr></table></td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;
	
	echo "<table border='0' class='border1' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>".newsposter($info)."</td>";
	echo "<td height='20' align='right' class='news-footer'>";
	echo openform("N",$info['news_id']).newsopts($info,"&middot;").closeform("N",$info['news_id']);
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {

global $locale;
	
	echo "<table border='0' class='border1' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>".articleposter($info)."</td>";
	echo "<td height='20' align='right' class='news-footer'>";
	echo openform("A",$info['article_id']).articleopts($info,"&middot;").closeform("A",$info['article_id']);
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {

	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>$title</td></tr>";
	echo "<tr><td class='main-body' width='100%'>";
}

// Close table end
function closetable() {
	echo "</td></tr></table>\n";
}

function openside($title) {

	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>$title</td></tr>";
	echo "<tr><td class='side-body' width='100%'>";
}

function closeside() {
	echo "</td></tr></table>";
	tablebreak();
}

function opensidex($title,$open="on") {
	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table border='0' style='border: 1px solid #000000' cellspacing='1' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel-header' height='20' width='100%'>";
	echo "<img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\">$title";
	echo "</td></tr>";
	echo "<tr><td class='side-body' width='100%'>";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div></td></tr></table>";
	tablebreak();
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='8'></td></tr></table>\n";
}
?>