<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: session_include.php
| Author: Nick Jones (Digitanium)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

function open_session($save_path, $session_name) {
	return true;
}

function close_session() {
	return true;
}

function read_session($session_id) {
	global $sess_read;
	$result = dbquery("SELECT session_data FROM ".DB_SESSIONS." WHERE session_id='".valid_session($session_id)."'");
	if (dbrows($result)) {
		$sess_read = dbarray($result);
		return $sess_read['session_data'];
	} else {
		return $sess_read = "";
	}
}

function write_session($session_id, $session_data) {
	global $sess_read;
	if (!$session_data) {
		return false;
	} else {
		if ($sess_read) {
			$result = dbquery("UPDATE ".DB_SESSIONS." SET session_expire='".(time() + (60 * 24 * 30))."', session_data='".$session_data."' WHERE session_id='".valid_session($session_id)."'");
		} else {
			$result = dbquery("INSERT INTO ".DB_SESSIONS." (session_id, session_started, session_expire, session_ip, session_data) VALUES ('".valid_session($session_id)."', '".time()."', '".(time() + (60 * 24 * 30))."', '".USER_IP."', '".$session_data."')");
		}
		return true;
	}
}

function destroy_session($session_id) {
	$result = dbquery("DELETE FROM ".DB_SESSIONS." WHERE session_id = '".valid_session($session_id)."'");
	return true;
}

function gc_session() {
	$result = dbquery("DELETE FROM ".DB_SESSIONS." WHERE session_expire < ".time());
	return true;
}

function valid_session($id) {
	if (preg_check("/^[0-9a-z]+$/", $id)) {
		return $id;
	} else {
		return "";
	}
}

session_set_save_handler("open_session", "close_session", "read_session", "write_session", "destroy_session", "gc_session");

session_set_cookie_params(60 * 24 * 30, "/", "", false);

session_start();

if (isset($_POST['login']) && isset($_POST['user_name']) && isset($_POST['user_pass'])) {
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
	$user_pass = md5($_POST['user_pass']);
	$result = dbquery("SELECT user_id, user_name, user_status, user_actiontime  FROM ".DB_USERS." WHERE user_name='".$user_name."' AND user_password='".md5($user_pass)."' LIMIT 1");
	if (dbrows($result)) {
		$data = dbarray($result);
		$session_value = $data['user_id'].".".$user_pass;
		if ($data['user_status'] == 0 && $data['user_actiontime'] == 0) {
			$_SESSION[COOKIE_PREFIX.'user_id'] = $data['user_id'];
			$_SESSION[COOKIE_PREFIX.'user_pass'] = $user_pass;
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
		} elseif ($data['user_status'] == 1) {
			redirect(BASEDIR."setuser.php?error=1&id=".$data['user_id'], true);
		} elseif ($data['user_status'] == 2) {
			redirect(BASEDIR."setuser.php?error=2", true);
		} elseif ($data['user_status'] == 3) {
			if ($data['user_actiontime'] < time()) {
				require_once INCLUDES."suspend_include.php";
				require_once INCLUDES."sendmail_include.php";
				$_SESSION[COOKIE_PREFIX.'user_id'] = $data['user_id'];
				$_SESSION[COOKIE_PREFIX.'user_pass'] = $user_pass;
				$result = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".$data['user_id']."'");
				unsuspend_log($data['user_id'], 3, $locale['global_450'], true);
				// Send mail
				$subject = $locale['global_453'];
				$message = str_replace("USER_NAME", $data['user_name'], $locale['global_452']);
				sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
				// Send mail
				redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
			} else {
				redirect(BASEDIR."setuser.php?error=3&id=".$data['user_id'], true);
			}
		} elseif ($data['user_status'] == 4) {
			redirect(BASEDIR."setuser.php?error=4&id=".$data['user_id'], true);
		} elseif ($data['user_status'] == 5) {
			redirect(BASEDIR."setuser.php?error=5", true);
		} elseif ($data['user_status'] == 6) {
			redirect(BASEDIR."setuser.php?error=6", true);
		} elseif ($data['user_status'] == 7) {
			require_once INCLUDES."sendmail_include.php";
			$_SESSION[COOKIE_PREFIX.'user_id'] = $data['user_id'];
			$_SESSION[COOKIE_PREFIX.'user_pass'] = $user_pass;
			$result = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".$data['user_id']."'");
			// Send mail
			$subject = $locale['global_454'];
			$message = str_replace("USER_NAME", $data['user_name'], $locale['global_455']);
			sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
			// Send mail
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
		}
	} else {
		redirect(BASEDIR."setuser.php?error=8");
	}
}

if (isset($_SESSION[COOKIE_PREFIX.'user_id']) && isset($_SESSION[COOKIE_PREFIX.'user_pass'])) {
	$cookie_1 = isnum($_SESSION[COOKIE_PREFIX.'user_id']) ? $_SESSION[COOKIE_PREFIX.'user_id'] : "0";
	$cookie_2 = (preg_check("/^[0-9a-z]{32}$/", $_SESSION[COOKIE_PREFIX.'user_pass']) ? $_SESSION[COOKIE_PREFIX.'user_pass'] : "");
	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$cookie_1."' AND user_password='".md5($cookie_2)."' LIMIT 1");
	unset($cookie_1, $cookie_2);
	if (dbrows($result)) {
		$userdata = dbarray($result);
		if ($userdata['user_status'] == 0) {
			if ($userdata['user_theme'] != "Default" && file_exists(THEMES.$userdata['user_theme']."/theme.php") && ($settings['userthemes'] == 1 || $userdata['user_level'] >= 102)) {
				if (!theme_exists($userdata['user_theme'])) {
					echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
					echo $locale['global_301'];
					die();
				}
			} else {
				if (!theme_exists($settings['theme'])) {
					echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
					echo $locale['global_301'];
					die();
				}
			}
			if (!isset($_SESSION[COOKIE_PREFIX.'lastvisit']) || !isnum($_SESSION[COOKIE_PREFIX.'lastvisit'])) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_threads='' WHERE user_id='".$userdata['user_id']."'");
				$_SESSION[COOKIE_PREFIX.'lastvisit'] = $userdata['user_lastvisit'];
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_SESSION[COOKIE_PREFIX.'lastvisit'];
			}
			if ($userdata['user_level'] > 101) {
				if (isset($_SESSION[COOKIE_PREFIX.'admin']) && (!stristr(FUSION_REQUEST, str_replace("../", "", "/".ADMIN)) || USER_IP != $userdata['user_ip'])) {
					$_SESSION[COOKIE_PREFIX.'admin'] = "";
				}
			}
		} else {
			session_destroy();
			redirect(BASEDIR."index.php", true);
		}
	} else {
		session_destroy();
		redirect(BASEDIR."index.php", true);
	}
} else {
	if (!theme_exists($settings['theme'])) {
		echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
		echo $locale['global_301'];
		die();
	}
	$userdata = "";	$userdata['user_level'] = 0; $userdata['user_rights'] = ""; $userdata['user_groups'] = "";
}
?>