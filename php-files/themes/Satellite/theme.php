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
|      Satellite Theme for PHP-Fusion v6      |
|---------------------------------------------|
| author: PHP-Fusion Themes - Shedrock © 2005 |
| web: http://phpfusion.org                   |
| email: webmaster@phpfusion.org              |
|---------------------------------------------|
| Released under the terms and conditions of  |
| the GNU General Public License (Version 2)  |
+--------------------------------------------*/

// Theme settings
$body_text = "#000000";
$body_bg = "#FFFFFF";
$theme_width = "100%";
$theme_width_l = "165";
$theme_width_r = "165";

function render_header($header_content) {
global $theme_width,$settings;
	
	echo "<table width='$theme_width' border='0' align='center' cellpadding='0' cellspacing='0'>";
	echo "<tr><td><img src='".THEME."images/space.gif' alt='' width='770' height='1'></td></tr>";
	echo "<tr><td><div class='main-bg'>";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background-image:url(".THEME."images/center_head.jpg)'>";
	echo "<tr><td width='26'><img src='".THEME."images/left_head.jpg' alt='' width='26'></td>";
	echo "<td><table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td height='100' colspan='2' class='title' style='padding-top:16px;'>";
	echo "<table width='100%' border='0' cellpadding='4' cellspacing='0'><tr>";
	echo "<td width='100%'>$header_content</td></tr></table></td></tr>";
	echo "<tr><td width='400' height='29'><div id='search'>";
	//Search Script
	echo "<form name='searchforum' action='".BASEDIR."search.php?stype=f' method='post'>";
	echo "<div align='left' class='search'>";
	echo "<input type='text' class='inputbox' name='stext' value='Search Forums'  onBlur=\"if(this.value=='') this.value='Search Forums';\" onFocus=\"if(this.value=='Search Forums') this.value='';\">";
	echo "<input type='hidden' name='option' value='search'></div></form></div></td>";
	//End Search Script

	echo "<td width='100%' class='nav' height='29' valign='bottom' align='right'>";
	echo "<table cellpadding='0' cellspacing='0'><tr><td>";
	
	$result = dbquery("SELECT * FROM ".DB_PREFIX."site_links WHERE link_position>='2' ORDER BY link_order");
	if (dbrows($result) != 0) {
		$i = 0;
		while($data = dbarray($result)) {
			if (checkgroup($data['link_visibility'])) {
				if ($data['link_url']!="---") {
					if ($i != 0) { echo " <img src='".THEME."images/divider.gif' alt=''> \n"; } else { echo "\n"; }
					$link_target = ($data['link_window'] == "1" ? " target='_blank'" : "");
					if (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
						echo "<a href='".$data['link_url']."'".$link_target." class='nav'>".$data['link_name']."</a>";
					} else {
						echo "<a href='".BASEDIR.$data['link_url']."'".$link_target." class='nav'>".$data['link_name']."</a>";
					}
				}
				$i++;
			}
		}
	}
	echo ($i == 0 ? " " : "")."</td></tr></table></td></tr></table></td>";

	echo "<td width='26'><img src='".THEME."images/right_head.jpg' alt='' width='26'></td></tr></table>";
	echo "<table width='100%' border='0' align='center' cellpadding='0' cellspacing='0'><tr>";
	echo "<td width='11' height='25' style='background-image:url(".THEME."images/border_left.jpg)'></td>";
	echo "<td height='25' bgcolor='#F1F1F1' style='border-bottom: 1px solid #C5C5C5; border-top: 5px solid #FFFFFF;'></td>";
	echo "<td height='25' align='right' bgcolor='#F1F1F1' style='border-bottom: 1px solid #C5C5C5; border-top: 5px solid #FFFFFF;'>";

	// Clock Script
	echo "<div class='date' id='Clock'>";
	echo "<script type='text/javascript'>
		<!--
		var DayNam = new Array(
		'Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		var MnthNam = new Array(
		'January','February','March','April','May','June','July','August','September','October','November','December');
		//-->
		</script>";
	echo "<script type='text/javascript' src='".THEME."clock.js'></script>";
	echo "</div></td>";
	// Clock Script Ends

	echo "<td width='11' height='25' align='right' style='background-image:url(".THEME."images/border_right.jpg)'>&nbsp;</td></tr></table>";
	echo "<table width='100%'  border='0' align='center' cellpadding='0' cellspacing='0'><tr>";
	echo "<td valign='top' align='left' class='left' width='11' style='background-image:url(".THEME."images/border_left.jpg)'>&nbsp;</td>";
	echo "<td valign='top'>";
	echo "<table width='100%' cellpadding='5' cellspacing='0' border='0' align='center'>\n<tr>\n";
}

function render_footer($license=false) {
global $theme_width,$locale,$settings;

	echo "</tr></table><td align='right' width='11' class='right' valign='top'>&nbsp;</td></tr></table>\n";
	echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' style='background-image:url(".THEME."images/footer_main.jpg)'>";
	echo "<tr><td width='26'><img src='".THEME."images/footer_left.jpg' alt='' width='26' height='92'></td>";
	echo "<td><table width='100%'  border='0' cellspacing='0' cellpadding='0'><tr>";
	echo "<td width='30' align='left'><a href='#top'>";
	echo "<img src='".THEME."images/top.gif' alt='' title='Scroll up!' border='0' width='30' height='20'></a></td>";
	echo "<td align='center'><div class='footer'><div align='center'>".stripslashes($settings['footer'])."</div></div>";
	echo "<div align='center'><div class='footer'>";

		if ($license == false) {
	echo "Powered by <a target='_blank' href='http://www.php-fusion.co.uk'>PHP-Fusion</a> v".$settings['version']." &copy; 2003-2005";
	}
	echo " | <span style='color:#ffff00;font-weight:bold'>".$settings['counter']."</span> ".($settings['counter'] == 1 ? $locale['140']."\n" : $locale['141']."\n");
	echo " | Satellite by: <a target='_blank' href='http://phpfusion.org'>PHP-Fusion Themes</a></div></div><br></td>";
	echo "<td width='30' align='right'><a href='#top'>";
	echo "<img src='".THEME."images/top.gif' alt='' title='Scroll up!' border='0' width='30' height='20'></a></td></tr></table></td>";
	echo "<td width='26'><img src='".THEME."images/footer_right.jpg' alt='' width='26' height='92'>";
	echo "</td></tr></table></div></td></tr></table>";
}

function render_news($subject, $news, $info) {
	
global $locale;
	
	echo "<table border='0' style='border: 1px solid #C5C5C5' cellspacing='1' width='100%' cellpadding='1'><tr>";
	echo "<td class='tablehead' height='18'>$subject</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='border'><tr>";
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
	echo "<a href='print.php?type=N&amp;item_id=".$info['news_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

function render_article($subject, $article, $info) {
	
global $locale;
	
	echo "<table border='0' style='border: 1px solid #C5C5C5' cellspacing='1' width='100%' cellpadding='1'><tr>";
	echo "<td class='tablehead' height='18'>$subject</td>";
	echo "</td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='1' class='border'><tr>";
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
	echo "<a href='print.php?type=A&amp;item_id=".$info['article_id']."'><img src='".THEME."images/printer.gif' alt='".$locale['045']."' border='0' style='vertical-align:middle;'></a>";
	echo "</td></tr></table></td></tr></table>\n";
}

// Open table begins
function opentable($title) {

	echo "<table width='100%' cellpadding='1' cellspacing='1' style='border: 1px solid #C5C5C5'>";
	echo "<tr><td bgcolor='#eeeeee' height='18' class='tablehead'>$title</td>";
	echo "</tr></table>";
	echo "<table width='100%' cellpadding='4' cellspacing='0' class='border2'><tr>";
	echo "<td class='main-body'>\n";
}

// Close table end
function closetable() {
	echo "</td></tr></table>\n";
}

function openside($title) {
	
	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td><table width='100%' cellpadding='0' cellspacing='4'>";
	echo "<tr><td class='paneltext2'>$title</td></tr></table></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td class='side-body'>\n";
}

function closeside() {

	echo "</td></tr></table>\n";
}

function opensidex($title,$open="on") {

	$boxname = str_replace(" ", "", $title);
	$box_img = $open == "on" ? "off" : "on";
	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td><table width='100%' cellpadding='0' cellspacing='4'>";
	echo "<tr>";
	echo "<td class='paneltext2'><img src='".THEME."images/panel_$box_img.gif' name='b_$boxname' align='right' alt='' onclick=\"javascript:flipBox('$boxname')\">$title</td>";
	echo "</tr></table></td></tr></table>";
	echo "<table width='100%' cellpadding='0' cellspacing='0'>";
	echo "<tr><td class='side-body'>\n";
	echo "<div id='box_$boxname'".($open=="off" ? "style='display:none'" : "").">\n";
}

function closesidex() {

	echo "</div></td></tr></table>\n";
}

// Table functions
function tablebreak() {
	echo "<table width='100%' cellspacing='0' cellpadding='0'><tr><td height='5'></td></tr></table>\n";
}
?>
