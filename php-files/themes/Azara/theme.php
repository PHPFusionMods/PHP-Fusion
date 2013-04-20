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
|       Azara Theme for PHP-Fusion v6         |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock © 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/


/* Theme Settings */

$body_text = "#000000";
$body_bg = "#9A978E";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {
global $theme_width,$settings;

	echo "<table align='center' class='bodyline' width='$theme_width' cellspacing='0' cellpadding='0' border='0'>";
	echo "<tr><td width='25' nowrap='nowrap' class='lefttd'></td><td>";
	// Start banner code
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td width='100%' style='background-image:url(".THEME."images/logo_bg.gif)' height='110'>$header_content</td>";
	echo "<td align='right'></td></tr></table>";
	// End banner code
	echo "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td class='sub-header'><table width='100%' border='0' cellpadding='4' cellspacing='0' style='background-image:url(".THEME."images/cellpic_nav.gif);border:#8A8573 1px solid;'><tr>";
	$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
	if (dbrows($result) != 0) {
		echo "<td height='28' class='sub-header'>&nbsp;&nbsp;\n";
		$i = 0;
		while($data = dbarray($result)) {
			if (checkgroup($data['link_visibility'])) {
				if ($data['link_url']!="---") {
					if ($i != 0) { echo " <img border='0' src='".THEME."images/divider.gif'>\n"; } else { echo "\n"; }
					$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
					if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
						echo "<a href='".$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
					} else {
						echo "<a href='".BASEDIR.$data['link_url']."'".$link_target." class='white'>".$data['link_name']."</a>";
					}
				}
				$i++;
			}
		}
	}
	echo ($i == 0 ? " " : "")."</td>\n";
	echo "<td height='28' class='sub-header' width='250' nowrap><img align='right' border='0' src='".THEME."images/bevel.gif'><div align='right'><font class='date'><b>".ucwords(showdate($settings['subheaderdate'], time()))."";
	echo "&nbsp;&nbsp;&nbsp;</b></font></div>";
	echo "</td></tr></table></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr><td></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' border='0' align='center'>";
	echo "<tr valign='top'>";
	echo "<td valign='middle' align='right'>";
	echo "<table width='100%' cellpadding='2' bgcolor='#9A978E' cellspacing='0' border='0'><tr>";
}

function render_footer($license=false) {
	
global $theme_width,$settings,$locale;

	echo "</tr>\n</table>\n";
	echo "<table cellpadding='2' cellspacing='0' width='$theme_width'><tr>";
	echo "<td>".stripslashes($settings['footer'])."<br>";
	echo "<table style='background-image:url(".THEME."images/cellpic_nav.gif);border: 1px solid #8A8573' cellSpacing='0' cellPadding='2' width='100%' border='0'>";
	echo "<tr><td height='28' width='35%' class='footer' align='left'><div align='left'>";
		if ($license == false) {
	echo "Powered by <a target='_blank' href='http://www.php-fusion.co.uk'><img src='".THEME."images/fusion.gif' title='PHP-Fusion' style='vertical-align:middle;'></a> v".$settings['version']." &copy; 2003-2005";
	}
	echo "</div></td>";
	echo "<td class='footer' height='28' width='20%'align='center'>Azara Theme by: <a target='_blank' href='http://phpfusion.org'><img title='PHP-Fusion Themes' src='".THEME."images/fthemes.gif' style='vertical-align:middle;'></a>";
	echo "<td class='footer' height='28' width='35%' align='right'>";
	echo "<font class='visits'><b>".$settings['counter']." </b></font>".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo "</td></tr></table></td>";
	echo "</tr></table></td></tr></table>";
	echo "<td width='25' nowrap='nowrap' class='righttd'></td></table>";

}

function render_news($subject, $news, $info) {
	
global $locale;
	
	echo "<table bgcolor='#ABA8A0' class='border2' cellspacing='0' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel'><img align='right' border='0' src='".THEME."images/bevel.gif'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>$news</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>&nbsp;";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['news_date'])." </td>";
	echo "<td height='24' align='right' class='news-footer'>";
	echo "".($info['news_ext'] == "y" ? "<a href='news.php?readmore=".$info['news_id']."'>".$locale['042']."</a> ·\n" : "")."";
	if ($info['news_allow_comments']) 
	echo "<a href='news.php?readmore=".$info['news_id']."'>".$info['news_comments'].$locale['043']."</a> · ";
	echo "".$info['news_reads'].$locale['044']." ";
	echo "<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {
	
global $locale;
	
	echo "<table bgcolor='#ABA8A0' class='border2' cellspacing='0' width='100%' cellpadding='3'><tr>";
	echo "<td class='panel'><img align='right' border='0' src='".THEME."images/bevel.gif'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'><tr>";
	echo "<td><table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='main-body'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'><tr>";
	echo "<td class='news-footer'>";
	echo "".$locale['040']."<a href='profile.php?lookup=".$info['user_id']."'>".$info['user_name']."</a> ";
	echo "".$locale['041'].showdate("longdate", $info['article_date'])."</td>";
	echo "<td height='24' align='right' class='news-footer'>";
	if ($info['article_allow_comments']) echo $info['article_comments'].$locale['043']." · ";
	echo "".$info['article_reads'].$locale['044']." ";
	echo "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' style='vertical-align:middle;border:0px;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {

	echo "<table bgcolor='#ABA8A0' width='100%' cellpadding='2' cellspacing='0' class='border2'>";
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

	echo "<table bgcolor='#ABA8A0' border='0' style='border: 1px solid #8A8573' cellspacing='0' width='100%' cellpadding='0'><tr>";
	echo "<td width='100%' class='panel'>";
	echo "<img align='right' border='0' src='".THEME."images/bevel.gif'>$title</td></tr>";
	echo "<tr><td bgcolor='#BAB7AE' class='side-body' width='100%'>";
}

function closeside() {
	echo "</td></tr></table>";
	tablebreak();
}

function opensidex($title,$open="on") {
	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table bgcolor='#ABA8A0' border='0' style='border: 1px solid #8A8573' cellspacing='0' width='100%' cellpadding='0'><tr>";
	echo "<td width='100%' class='panel'>";
	echo "<img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' alt='' onclick=\"javascript:flipBox('$boxname')\">$title";
	echo "</td></tr>";
	echo "<tr><td bgcolor='#BAB7AE' class='side-body' width='100%'>";
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