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
|       Fusion3 Theme for PHP-Fusion v6       |
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

/* Theme Settings */
$body_text = "#000000";
$body_bg = "#577BAB";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {

global $theme_width,$settings;

	echo "<table align='center' class='bodyline' width='$theme_width' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td width='29' nowrap='nowrap' class='lefttd'></td><td>";
	// Start banner code
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='4'><tr>";
	echo "<td width='60%' style='background-image:url(".THEME."images/logo_bg.gif)' height='80'>$header_content</td>";
	echo "</tr></table>";
	// End banner code
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td class='sub-header'><table width='100%' border='0' cellpadding='4' cellspacing='0' style='background-color: #6387B7; border-top: solid #3F5A7F; border-top-width: 1px; border-left: solid #3F5A7F; border-left-width: 1px; border-right: solid #CACACA; border-right-width:1px; border-bottom: solid #CACACA; border-bottom-width:1px;'><tr>";
	echo "<td height='22' class='sub-header'>&nbsp;&nbsp;".showsublinks("<img border='0' src='".THEME."images/divider.gif'>","white")."</td>";
	echo "<td class='sub-header' width='250' height='22' nowrap><img align='right' border='0' src='".THEME."images/bevel.gif'><div align='right'><font class='date'><b>".showsubdate();
	echo "&nbsp;&nbsp;&nbsp;</b></font></div>";
	echo "</td></tr></table></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr><td></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr valign='top'>";
	echo "<td valign='middle' align='right'>";
	echo "<table width='100%' cellpadding='4' bgcolor='#577BAB' cellspacing='0' border='0'><tr>";
}

function render_footer($license=false) {
	
global $theme_width,$settings,$locale;

	echo "</tr>\n</table>\n";
	echo "<table cellpadding='2' cellspacing='0' width='$theme_width'><tr>";
	echo "<td>".stripslashes($settings['footer'])."<br>";
	echo "<table style='background-image:url(".THEME."images/cellpic.gif);border: 1px solid #3F5A7F' cellSpacing='0' cellPadding='2' width='100%' border='0'>";
	echo "<tr><td width='35%' height='22' class='footer' align='left'><div align='left'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'>PHP-Fusion</a> &copy; 2003-2006";
	}
	echo "</div></td>";
	echo "<td class='footer' width='30%' height='22' align='center'>Fusion 3 by: <a href='http://www.phpfusion-themes.com' target='_blank'>PHP-Fusion Themes</a></td>";
	echo "<td class='footer' width='35%' height='22' align='right'>";
	echo "<font class='visits'><b>".$settings['counter']." </b></font>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td></tr></table></td>";
	echo "</tr></table></td></tr></table>";
	echo "<td width='29' nowrap='nowrap' class='righttd'></td></table>";
}

function render_news($subject, $news, $info) {
	
global $locale;
	
	echo "<table class='border2' cellspacing='0' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel'><img align='right' border='0' src='".THEME."images/bevel.gif'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>&nbsp;".newsposter($info)."</td>";
	echo "<td height='24' align='right' class='news-footer'>";
	echo openform("N",$info['news_id']).newsopts($info,"&middot;").closeform("N",$info['news_id']);
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {
	
global $locale;
	
	echo "<table class='border2' cellspacing='0' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel'><img align='right' border='0' src='".THEME."images/bevel.gif'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>".articleposter($info)."</td>";
	echo "<td height='24' align='right' class='news-footer'>";
	echo openform("A",$info['article_id']).articleopts($info,"&middot;").closeform("A",$info['article_id']);
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {
	echo "<table width='100%' cellpadding='2' cellspacing='0' class='border2'>";
	echo "<tr><td class='panel'>";
	echo "<img align='right' border='0' src='".THEME."images/bevel.gif'>$title</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td class='main-body'>\n";
}

// Close table end
function closetable() {
	echo "</td></tr></table>\n";
}

function openside($title) {
	echo "<table bgcolor='#8D8D8D' border='0' style='border: 1px solid #3F5A7F' cellspacing='0' width='100%' cellpadding='2'><tr>";
	echo "<td width='100%' class='panel'>";
	echo "<img align='right' border='0' src='".THEME."images/bevel.gif'>$title</td></tr>";
	echo "<tr><td bgcolor='#AAAAAA' class='side-body' width='100%'>";
}

function closeside() {
	echo "</td></tr><tr><td class='panel2' width='100%'>";
	echo "</td></tr></table>";
	tablebreak();
}

function opensidex($title,$open="on") {
	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table bgcolor='#8D8D8D' border='0' style='border: 1px solid #3F5A7F' cellspacing='0' width='100%' cellpadding='0'><tr>";
	echo "<td width='100%' class='panel'>";
	echo "<img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\">$title";
	echo "</td></tr>";
	echo "<tr><td bgcolor='#AAAAAA' class='side-body'width='100%'>";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}


function closesidex() {
	echo "</div></td></tr><tr><td class='panel2' width='100%'>";
	echo "</td></tr></table>";
	tablebreak();
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='8'></td></tr></table>\n";
}
?>