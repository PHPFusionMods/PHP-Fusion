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
|      Valencia Theme for PHP-Fusion v6       |
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

// theme settings
$body_text = "#000000";
$body_bg = "#FFFFFF";
$theme_width = "100%";
$theme_width_l = "180";
$theme_width_r = "180";

function render_header($header_content) {
global $theme_width,$settings;
	
	echo "<table align='center' width='$theme_width' class='bodyline' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td valign='middle' width='100%' style='background-image:url(".THEME."images/logo_bg.gif)' height='85'>";
	// Start banner code
	echo "<table width='100%' cellspacing='0' cellpadding='4'>";
	echo "<tr><td width='100%'>$header_content</td>";
	echo "</tr></table>";
	// End banner code
	echo "</td></tr></table>";
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td height='24'><table width='100%' border='0' cellpadding='4' cellspacing='0'><tr><td class='nav-header'>\n";
	echo showsublinks("<img border='0' src='".THEME."images/divider.gif'>","white")."</td>\n";
	echo "<td align='right' class='nav-header'>".showsubdate()."</td></tr></table>";
	echo "<table width='100%' cellspacing='5' cellpadding='0'>\n<tr>\n";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'>";
	echo "<tr><td>".stripslashes($settings['footer'])."</td></tr></table><br>\n";
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='100%'>";
	echo "<tr><td width='15'></td>";
	echo "<td class='footer' align='left' width='38%'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'>PHP-Fusion</a> &copy; 2003-2006";
	}
	echo "</td>";
	echo "<td class='footer' align='center' width='24%'>Valencia Theme by: <a href='http://www.phpfusion-themes.com' target='_blank'>PHP-Fusion Themes</a>";
	echo "</td>";
	echo "<td class='footer' align='right' width='38%'>";
	echo "<strong>".$settings['counter']." </strong>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td></tr></table></td></tr></table></td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left3'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main3'>$subject</td>";
	echo "<td class='panel-right3'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left3'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "<td class='news-body'><div style='width:100%;vertical-align:top;'>$news</div><br>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar'>".newsposter($info)."</td>";
	echo "<td align='right' class='infobar2'>";
	echo openform("N",$info['news_id']).newsopts($info,"&middot;").closeform("N",$info['news_id']);
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right3'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'>";
	echo "</td></tr><tr>";
	echo "<td class='border-bleft3'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bmain3'><img src='".THEME."images/blank.gif' width='1' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bright3'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function render_article($subject, $article, $info) {

global $locale;
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left4'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main4'>$subject</td>";
	echo "<td class='panel-right4'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left4'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-bodyx'><div style='width:100%;vertical-align:top;'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div><br>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar3'>".articleposter($info)."</td>";
	echo "<td align='right' class='infobar4'>";
	echo openform("A",$info['article_id']).articleopts($info,"&middot;").closeform("A",$info['article_id']);
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right4'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "</tr><tr>";
	echo "<td class='border-bleft4'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bmain4'><img src='".THEME."images/blank.gif' width='1' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bright4'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function opentable($title) {

	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td class='panel-left2'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main2'>$title</td>";
	echo "<td class='panel-right2'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left2'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "<td class='table-body'>\n";
}

function closetable() {

	echo "</td>";
	echo "<td class='border-right2'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft2'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bmain2'><img src='".THEME."images/blank.gif' width='1' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bright2'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function openside($title) {
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$title</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-body'>\n";
}

function closeside() {

	echo "</td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
	tablebreak();
}

function opensidex($title,$open="on") {

	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left4'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "<td class='panel-main4'>$title</td>";
	echo "<td align='right' class='panel-main4'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>";
	echo "<td class='panel-right4'><img src='".THEME."images/blank.gif' width='6' height='21' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left4'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-bodyx'>";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div>";
	echo "<td class='border-right4'><img src='".THEME."images/blank.gif' width='6' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft4'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bmain4'><img src='".THEME."images/blank.gif' width='1' height='16' alt='' style='display:block'></td>";
	echo "<td class='border-bright4'><img src='".THEME."images/blank.gif' width='6' height='16' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function tablebreak() {

	echo "<table width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr><td></td></tr></table>\n";
}
?>