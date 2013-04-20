<?php
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";

// theme settings
$body_text = "#444444";
$body_bg = "#ffffff";
$theme_width = "100%";
$theme_width_l = "175";
$theme_width_r = "175";

function render_header($header_content) {

global $theme_width;

echo "<table align='center' cellspacing='0' cellpadding='0' width='$theme_width'>
<tr>
<td>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='full-header'>
<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td>$header_content</td>
</tr>
</table>
</td>
</tr>
</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>
<td class='white-header'>".showsublinks("&middot;","white")."</td>
<td align='right' class='white-header'>".showsubdate()."</td></td>
</tr>
</table>\n";
tablebreak();

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings;

echo "</tr>\n</table>\n";
tablebreak();
echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='footer'><br>".stripslashes($settings['footer'])."</td>
</tr>
<tr>
<td align='center' class='footer'>\n";
if (!$license) { echo showcopyright()."<br><br>\n"; } echo showcounter()."<br><br>
</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {

echo "<table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom:2px;'>
<tr>
<td class='capmain'>$subject</td>
</tr>
</table>
<table cellpadding='0' cellspacing='0' width='100%' class='border'>
<tr>
<td class='main-body'>
$news
</td>
</tr>
<tr>
<td align='center' class='news-footer'>\n";
echo openform("N",$info['news_id']).newsposter($info,"<br>").newsopts($info,"&middot;").closeform("N",$info['news_id']);
echo "</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {
	
echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject</td>
</tr>
<tr>
<td class='main-body'>
".($info['article_breaks'] == "y" ? nl2br($article) : $article)."
</td>
</tr>
<tr>
<td align='center' class='news-footer'>\n";
echo openform("A",$info['article_id']).articleposter($info,"<br>").articleopts($info,"&middot;").closeform("A",$info['article_id']);
echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table cellpadding='0' cellspacing='0' width='100%' style='margin-bottom:2px;'>
<tr>
<td class='capmain'>$title</td>
</tr>
</table>
<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td class='main-body'>\n";

}

function closetable() {

echo "</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='side-body'><img src='".THEME."images/blank.gif' alt='' height='2' width='1'></td>
</tr>
<tr>
<td class='scapmain'>$title</td>
</tr>
<tr>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
</tr>
</table>\n";

}

function opensidex($title,$state="on") {

$boxname = str_replace(" ", "", $title);
echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td colspan='2' class='side-body'><img src='".THEME."images/blank.gif' alt='' height='2' width='1'></td>
</tr>
<tr>
<td class='scapmain'>$title</td>
<td class='scapmain' align='right'>".panelbutton($state,$boxname)."</td>
</tr>
<tr>
<td colspan='2' class='side-body'>
<div id='box_$boxname'".($state=="off"?" style='display:none'":"").">\n";

}

function closesidex() {

echo "</div>
</td>
</tr>
</table>\n";

}

function tablebreak() {

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr><td height='8'></td></tr>
</table>\n";

}
?>