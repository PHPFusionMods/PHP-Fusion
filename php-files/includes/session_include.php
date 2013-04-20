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
if (!defined("IN_FUSION")) { header("Location: ../index.php"); exit; }

function open_session($save_path,$session_name) {
	return true;
}

function close_session() {
	return true;
}

function read_session($session_id) {
	global $db_prefix,$sess_read;
	$result = dbquery("SELECT * FROM {$db_prefix}sessions WHERE session_id='$session_id'");
	if (dbrows($result) == 1) {
		$sess_read = dbarray($result);
		return $sess_read['session_data'];
	} else {
		return false;
	}
}

function write_session($session_id,$session_data) {
	global $db_prefix,$sess_read;
	if (!$session_data) {
		return false;
	} else {
		$session_expire = time()+(60*24*30);
		if ($sess_read) {
			$result = dbquery("UPDATE {$db_prefix}sessions SET session_expire='$session_expire', session_data='$session_data' WHERE session_id='$session_id'");
		} else {
			$result = dbquery("INSERT INTO {$db_prefix}sessions (session_id, session_started, session_expire, session_ip, session_data) VALUES ('$session_id', '".time()."', '$session_expire', '".USER_IP."', '$session_data')");
		}
		return true;
	}
}

function destroy_session($session_id) {
	global $db_prefix;
	$result = dbquery("DELETE FROM {$db_prefix}sessions WHERE session_id = '$session_id'");
	return true;
}

function gc_session() {
	global $db_prefix;
	$result = dbquery("DELETE FROM {$db_prefix}sessions WHERE session_expire >= ".time());
	return true;
}

session_set_save_handler ("open_session", "close_session", "read_session", "write_session", "destroy_session", "gc_session");

session_set_cookie_params(60*24*30, "/", "", false);

session_start();
?>