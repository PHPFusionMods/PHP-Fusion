<?php
/*--------------------------------------------+
| PHP-Fusion 6 - Content Management System    |
|---------------------------------------------|
| author: Nick Jones (Digitanium) © 2002-2005 |
| web: http://www.php-fusion.co.uk            |
| email: nick@php-fusion.co.uk                |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/
/*--------------------------------------------+
|      Milestone Theme for PHP-Fusion v6      |
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
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {
global $theme_width,$settings;
	
	echo "<table align='center' style='background-image:url(".THEME."images/header-center.gif)' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='15'><img src='".THEME."images/header-left.gif' width='22' height='82'></td>";
	// Start banner code
	echo "<td width='100%' class='header-center'>";
	echo "<table width='100%' cellspacing='0' cellpadding='0'>";
	echo "<tr><td>$header_content</td></tr>";
	echo "</table></td>";
	// End banner code
	echo "<td width='15'><img src='".THEME."images/header-right.gif' width='22' height='82'></td></tr></table>\n";
	echo "<img src='".THEME."images/pixel.gif' height='5'>";
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='42'><img src='".THEME."images/nav-left.gif' width='16' height='26'></td>";
	echo "<td width='75%' style='background-image:url(".THEME."images/nav-center.gif)'>";
	echo showsublinks("&middot;")."</td>";
	echo "<td align='right' width='25%' style='background-image:url(".THEME."images/nav-center.gif)'>";
	echo showsubdate()."</td>";
	echo "<td width='42'><img src='".THEME."images/nav-right.gif' width='16' height='26'></td></tr></table>";
	echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'>\n<tr>\n";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr>\n</table>\n";
	echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'><tr>";
	echo "<td class='header'>".stripslashes($settings['footer'])."</td></tr></table>\n";
	echo "<table align='center' border='0' cellpadding='0' cellspacing='0' width='$theme_width'>";
	echo "<tr><td width='15'><img src='".THEME."images/nav-left.gif' width='16' height='26'></td>";
	echo "<td class='copyright' align='left' width='38%' style='background-image:url(".THEME."images/nav-center.gif)'>";
	if ($license == false) {
		echo "Powered by <a href='http://www.php-fusion.co.uk' target='_blank'>PHP-Fusion</a> &copy; 2003-2006";
	}
	echo "</td>";
	echo "<td class='copyright' align='center' width='24%' style='background-image:url(".THEME."images/nav-center.gif)'>Milestone Theme by: <a href='http://www.phpfusion-themes.com' target='_blank'>PHP-Fusion Themes</a>";
	echo "</td>";
	echo "<td class='copyright' align='right' width='38%' style='background-image:url(".THEME."images/nav-center.gif)'>";
	echo "<strong>".$settings['counter']." </strong>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td>";
	echo "<td width='15'><img src='".THEME."images/nav-right.gif' width='16' height='26'></td></tr></table>";
}

function render_news($subject, $news, $info) {

global $locale;
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$subject</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'><div style='width:100%;vertical-align:top;'>$news</div><br>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar'>".newsposter($info)."</td>";
	echo "<td align='right' class='infobar2'>";
	echo openform("N",$info['news_id']).newsopts($info,"&middot;").closeform("N",$info['news_id']);
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'>";
	echo "</td></tr><tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function render_article($subject, $article, $info) {

global $locale;
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$subject</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'><div style='width:100%;vertical-align:top;'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div><br>";
	echo "<div style='margin-top:5px'>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='infobar'>".articleposter($info)."</td>";
	echo "<td align='right' class='infobar2'>";
	echo openform("A",$info['article_id']).articleopts($info,"&middot;").closeform("A",$info['article_id']);
	echo "</td></tr></table></div></td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "</tr><tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function opentable($title) {

	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td class='panel-left'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$title</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "<td class='main-body'>\n";
}

function closetable() {

	echo "</td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function openside($title) {
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "<td width='100%' class='panel-main'>$title</td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-body'>\n";
}

function closeside() {

	echo "</td>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function opensidex($title,$open="on") {

	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='panel-left'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "<td class='panel-main'>$title</td>";
	echo "<td align='right' class='panel-main'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' align='right' alt='' onclick=\"javascript:flipBox('$boxname')\"></td>";
	echo "<td class='panel-right'><img src='".THEME."images/blank.gif' width='13' height='26' alt='' style='display:block'></td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='border-left'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td>";
	echo "<td class='side-body'>";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div>";
	echo "<td class='border-right'><img src='".THEME."images/blank.gif' width='13' height='1' alt='' style='display:block'></td></tr>";
	echo "<tr>";
	echo "<td class='border-bleft'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bmain'><img src='".THEME."images/blank.gif' width='1' height='20' alt='' style='display:block'></td>";
	echo "<td class='border-bright'><img src='".THEME."images/blank.gif' width='13' height='20' alt='' style='display:block'></td>";
	echo "</tr></table>\n";
}

function tablebreak() {

	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td></td></tr></table>\n";
}
?>