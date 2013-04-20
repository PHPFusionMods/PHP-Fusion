<?php
/*---------------------------------------------------+
| PHP-Fusion 6 Content Management System
+----------------------------------------------------+
| Copyright © 2002 - 2006 Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }
if (!iMEMBER) fallback("index.php");

$error = ""; $set_avatar = "";

$username = trim(eregi_replace(" +", " ", $_POST['user_name']));
if ($username == "" || $_POST['user_email'] == "") {
	$error .= $locale['480']."<br>\n";
} else {
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) $error .= $locale['481']."<br>\n";
	
	if ($username != $userdata['user_name']) {
		$result = dbquery("SELECT user_name FROM ".$db_prefix."users WHERE user_name='$username'");
		if (dbrows($result) != 0) $error = $locale['482']."<br>\n";
	}
	
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $_POST['user_email'])) $error .= $locale['483']."<br>\n";
	
	if ($_POST['user_email'] != $userdata['user_email']) {
		$result = dbquery("SELECT user_email FROM ".$db_prefix."users WHERE user_email='".$_POST['user_email']."'");
		if (dbrows($result) != 0) $error = $locale['484']."<br>\n";
	}
}

if ($_POST['user_newpassword'] != "") {
	if ($_POST['user_newpassword2'] != $_POST['user_newpassword']) {
		$error .= $locale['485']."<br>";
	} else {
		if ($_POST['user_hash'] == $userdata['user_password']) {
			if (!preg_match("/^[0-9A-Z@]{6,20}$/i", $_POST['user_newpassword'])) {
				$error .= $locale['486']."<br>\n";
			}
		} else {			
			$error .= $locale['487']."<br>\n";
		}
	}
}

$user_hide_email = isNum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
$user_location = isset($_POST['user_location']) ? stripinput(trim($_POST['user_location'])) : "";
if ($_POST['user_month'] != "--" && $_POST['user_day'] != "--" && $_POST['user_year'] != "----") {
	$user_birthdate = (isNum($_POST['user_year']) ? $_POST['user_year'] : "0000")
	."-".(isNum($_POST['user_month']) ? $_POST['user_month'] : "00")
	."-".(isNum($_POST['user_day']) ? $_POST['user_day'] : "00");
} else {
	$user_birthdate = "0000-00-00";
}
$user_aim = isset($_POST['user_aim']) ? stripinput(trim($_POST['user_aim'])) : "";
$user_icq = isset($_POST['user_icq']) ? stripinput(trim($_POST['user_icq'])) : "";
$user_msn = isset($_POST['user_msn']) ? stripinput(trim($_POST['user_msn'])) : "";
$user_yahoo = isset($_POST['user_yahoo']) ? stripinput(trim($_POST['user_yahoo'])) : "";
$user_web = isset($_POST['user_web']) ? stripinput(trim($_POST['user_web'])) : "";
$user_theme = stripinput($_POST['user_theme']);
$user_offset = is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0";
$user_sig = isset($_POST['user_sig']) ? stripinput(trim($_POST['user_sig'])) : "";

if ($error == "") {
	$newavatar = $_FILES['user_avatar'];
	if ($userdata['user_avatar'] == "" && !empty($newavatar['name']) && is_uploaded_file($newavatar['tmp_name'])) {
		$avatarext = strrchr($newavatar['name'],".");
		$avatarname = substr($newavatar['name'], 0, strrpos($newavatar['name'], "."));
		if (preg_match("/^[-0-9A-Z_\[\]]+$/i", $avatarname) && preg_match("/(\.gif|\.GIF|\.jpg|\.JPG|\.png|\.PNG)$/", $avatarext) && $newavatar['size'] <= 30720) {
			$avatarname = $avatarname."[".$userdata['user_id']."]".$avatarext;
			$set_avatar = "user_avatar='$avatarname', ";
			move_uploaded_file($newavatar['tmp_name'], IMAGES."avatars/".$avatarname);
			chmod(IMAGES."avatars/".$avatarname,0644);
			if ($size = @getimagesize(IMAGES."avatars/".$avatarname)) {
				if ($size['0'] > 100 || $size['1'] > 100) {
					unlink(IMAGES."avatars/".$avatarname);
					$set_avatar = "";
				}
			} else {
				unlink(IMAGES."avatars/".$avatarname);
				$set_avatar = "";
			}
		}
	}
	
	if (isset($_POST['del_avatar'])) {
		$set_avatar = "user_avatar='', ";
		unlink(IMAGES."avatars/".$userdata['user_avatar']);
	}
	
	if ($user_newpassword != "") { $newpass = " user_password=md5('$user_newpassword'), "; } else { $newpass = " "; }
	$result = dbquery("UPDATE ".$db_prefix."users SET user_name='$username',".$newpass."user_email='".$_POST['user_email']."', user_hide_email='$user_hide_email', user_location='$user_location', user_birthdate='$user_birthdate', user_aim='$user_aim', user_icq='$user_icq', user_msn='$user_msn', user_yahoo='$user_yahoo', user_web='$user_web', user_theme='$user_theme', user_offset='$user_offset', ".$set_avatar."user_sig='$user_sig' WHERE user_id='".$userdata['user_id']."'");
	$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result) != 0) {
		$userdata = dbarray($result);
		redirect("edit_profile.php?update_profile=ok");
	}
}
?>