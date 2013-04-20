<?php
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";

// theme settings
$body_text = "#000000";
$body_bg = "#f1f2f4";
$theme_width = "100%";
$theme_width_l = "170";
$theme_width_r = "170";

function render_header($header_content) {

global $theme_width,$settings;

echo "<table align='center' width='$theme_width' cellspacing='0' cellpadding='0'>
<tr>
<td>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td class='full-header'>
<table width='100%' cellspacing='0' cellpadding='0'>
<tr>
<td>$header_content</td>
</tr>
</table>
</td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>
<td class='white-header'>".showsublinks("&middot;")."</td>
<td align='right' class='white-header'>".showsubdate()."</td>
</tr>
</table>\n";

echo "<table width='100%' cellspacing='0' cellpadding='0'>\n<tr>\n";

}

function render_footer($license=false) {

global $theme_width,$settings,$locale;

echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>
<tr>
<td class='full-header'>".stripslashes($settings['footer'])."</td>
</tr>
<tr>
<td align='center' class='white-header'><br>
".showcounter()."<br><br>\n";
if ($license == false) {
	echo showcopyright()."<br><br>\n";
}
echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_news($subject, $news, $info) {

global $locale;

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$subject</td>
</tr>
<tr>
<td class='main-body'>
$news
</td>
</tr>
<tr>
<td align='center' class='news-footer'>\n";
echo openform("N",$info['news_id']).newsposter($info,"&middot;").newsopts($info,"&middot;").closeform("N",$info['news_id']);
echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function render_article($subject, $article, $info) {

global $locale;
	
echo "<table width='100%' cellpadding='0' cellspacing='0'>
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
echo openform("A",$info['article_id']).articleposter($info,"&middot;").articleopts($info,"&middot;").closeform("A",$info['article_id']);
echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function opentable($title) {

echo "<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='capmain'>$title</td>
</tr>
<tr>
<td class='main-body'>\n";

}

function closetable() {

echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";

}

function openside($title) {
	
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
<tr>
<td class='scapmain'>$title</td>
</tr>
<tr>
<td class='side-body'>\n";

}

function closeside() {

echo "</td>
</tr>
</table>
</td>
</tr>
</table>\n";
tablebreak();

}

function opensidex($title,$open="on") {

$boxname = str_replace(" ", "", $title);
echo "<table width='100%' cellpadding='0' cellspacing='0' class='border'>
<tr>
<td>
<table width='100%' cellpadding='0' cellspacing='0'>
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
</table>
</td>
</tr>
</table>\n";
tablebreak();

}

function tablebreak() {

echo "<table width='100%' cellspacing='0' cellpadding='0'>
<tr><td height='8'></td></tr>
</table>\n";

}
?>