<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: upgrade.php
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
require_once "../maincore.php";

if (!checkrights("U") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

require_once THEMES."templates/admin_header.php";
if (file_exists(LOCALE.LOCALESET."admin/upgrade.php")) {
	include LOCALE.LOCALESET."admin/upgrade.php";
} else {
	include LOCALE."English/admin/upgrade.php";
}

opentable($locale['400']);
echo "<div style='text-align:center'><br />\n";
echo "<form name='upgradeform' method='post' action='".FUSION_SELF.$aidlink."'>\n";

if (str_replace(".", "", $settings['version']) < "70202") {
	if (!isset($_POST['stage'])) {
		echo sprintf($locale['500'], $locale['504'])."<br />\n".$locale['501']."<br /><br />\n";
		echo "<input type='hidden' name='stage' value='2'>\n";
		echo "<input type='submit' name='upgrade' value='".$locale['400']."' class='button'><br /><br />\n";
	} elseif (isset($_POST['upgrade']) && isset($_POST['stage']) && $_POST['stage'] == 2) {
		$check = dbcount("(settings_name)", DB_SETTINGS, "settings_name='forum_edit_timelimit'");
		if (!$check) {
			$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES (settings_name='forum_edit_timelimit', '0')");
		}
		$check = dbcount("(settings_name)", DB_SETTINGS, "settings_name='recaptcha_theme'");
		if (!$check) {
			$result = dbquery("INSERT INTO ".DB_SETTINGS." (settings_name, settings_value) VALUES ('recaptcha_theme', 'red')");
		}
		$result = dbquery("INSERT INTO ".DB_SETTINGS." VALUES ('forum_editpost_to_lastpost', '1')");
		$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='7.02.02' WHERE settings_name='version'");
		$result = dbquery("ALTER TABLE ".DB_USER_FIELDS." ADD field_registration TINYINT(1) UNSIGNED NOT NULL DEFAULT '0' AFTER field_log");
		// Shoutbox Fix
		$result = dbquery("UPDATE ". DB_INFUSIONS." SET inf_folder='shoutbox_panel' WHERE inf_folder='../infusions/shoutbox_panel/shoutbox_admin.php'");

		echo $locale['502']."<br /><br />\n";
	}
} else {
	echo $locale['401']."<br /><br />\n";
}

echo "</form>\n</div>\n";
closetable();

require_once THEMES."templates/footer.php";
?>