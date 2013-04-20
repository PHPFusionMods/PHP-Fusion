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
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

if (iMEMBER) fallback("index.php");

opentable($locale['400']);
if (isset($email) && isset($account)) {
	$error = 0;
	if (FUSION_QUERY != "email=".$email."&account=".$account) fallback("index.php");
	$email = stripinput(trim(eregi_replace(" +", "", $email)));
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) $error = 1;
	if (!preg_match("/^[0-9a-z]{32}$/", $account)) $error = 1;
	if ($error == 0) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_password='$account' AND user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			for ($i=0;$i<=7;$i++) { $new_pass .= chr(rand(97, 122)); }
			$mailbody = str_replace("[NEW_PASS]", $new_pass, $locale['411']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			$result = dbquery("UPDATE ".$db_prefix."users SET user_password=md5('$new_pass') WHERE user_id='".$data['user_id']."'");
			echo "<center><br>\n".$locale['402']."<br><br>\n<a href='index.php'>".$locale['403']."</a><br><br>\n</center>\n";
		} else {
			$error = 1;
		}
	}
	if ($error == 1) redirect("index.php");
} elseif (isset($_POST['send_password'])) {
	$email = stripinput(trim(eregi_replace(" +", "", $_POST['email'])));
	if (preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$new_pass_link = $settings['siteurl']."lostpassword.php?email=".$data['user_email']."&account=".$data['user_password'];
			$mailbody = str_replace("[NEW_PASS_LINK]", $new_pass_link, $locale['410']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			echo "<center><br>\n".$locale['401']."<br><br>\n<a href='index.php'>".$locale['403']."</a><br><br>\n</center>\n";
		} else {
			echo "<center><br>\n".$locale['404']."<br><br>\n<a href='".FUSION_SELF."'>".$locale['406']."</a><br><br>\n</center>\n";
		}
	} else {
		echo "<center><br>\n".$locale['405']."<br><br>\n<a href='".FUSION_SELF."'>".$locale['403']."</a><br><br></center>\n";
	}
} else {
	echo "<form name='passwordform' method='post' action='".FUSION_SELF."'>
<center>".$locale['407']."<br>
<br>
<input type='text' name='email' class='textbox' maxlength='100' style='width:200px;'><br>
<br>
<input type='submit' name='send_password' value='".$locale['408']."' class='button'></center>
</form>\n";
}
closetable();

require_once "side_right.php";
require_once "footer.php";
?>