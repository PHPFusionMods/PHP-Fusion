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
require_once "maincore.php";
require_once "subheader.php";
require_once "side_left.php";
include LOCALE.LOCALESET."contact.php";

if (isset($_POST['sendmessage'])) {
	$mailname = substr(stripinput(trim($_POST['mailname'])),0,50);
	$email = substr(stripinput(trim($_POST['email'])),0,100);
	$subject = substr(str_replace(array("\r","\n","@"),"",stripslash(trim($_POST['subject']))),0,50);
	$message = stripslash(trim($_POST['message']));
	if ($mailname == "") {
		$error .= "· <span class='alt'>".$locale['420']."</span><br>\n";
	}
	if ($email == "" || !preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= "· <span class='alt'>".$locale['421']."</span><br>\n";
	}
	if ($subject == "") {
		$error .= "· <span class='alt'>".$locale['422']."</span><br>\n";
	}
	if ($message == "") {
		$error .= "· <span class='alt'>".$locale['423']."</span><br>\n";
	}
	echo $subject;
	if (!$error) {
		require_once INCLUDES."sendmail_include.php";
		sendemail($settings['siteusername'],$settings['siteemail'],$mailname,$email,$subject,$message);
		opentable($locale['400']);
		echo "<center><br>\n".$locale['440']."<br><br>\n".$locale['441']."</center><br>\n";
		closetable();
	} else {
		opentable($locale['400']);
		echo "<center><br>\n".$locale['442']."<br><br>\n$error<br>\n".$locale['443']."</center><br>\n";
		closetable();
	}
} else {
	opentable($locale['400']);
	echo $locale['401']."<br><br>
<form name='userform' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' class='tbl'>
<tr>
<td width='100'>".$locale['402']."</td>
<td><input type='text' name='mailname' maxlength='50' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='100'>".$locale['403']."</td>
<td><input type='text' name='email' maxlength='100' class='textbox' style='width: 200px;'></td>
</tr>
<tr>
<td width='100'>".$locale['404']."</td>
<td><input type='text' name='subject' maxlength='50' class='textbox' style='width: 200px;'></td>
</tr>
<tr><td valign='top' width='90'>".$locale['405']."</td>
<td><textarea name='message' rows='10' class='textbox' style='width: 320px'></textarea></td>
</tr>
<tr>
<td align='center' colspan='2'>
<input type='submit' name='sendmessage' value='".$locale['406']."' class='button'>
</td>
</tr>
</table>
</form>\n";
	closetable();
}

require_once "side_right.php";
require_once "footer.php";
?>