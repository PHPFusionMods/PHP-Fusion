<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: panels.php
| Author: Nick Jones (Digitanium)
| Co Author: Hans Kristian Flaatten (Starefossen)
| Co Author: bartek124
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }

// Calculate current true url
$script_url = explode("/", $_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY : ""));
$url_count = count($script_url);
$base_url_count = substr_count(BASEDIR, "/") + 1;
$start_page = "";
while ($base_url_count != 0) {
	$current = $url_count - $base_url_count;
	$start_page .= "/".$script_url[$current];
	$base_url_count--;
}

define("START_PAGE", substr(preg_replace("#(&amp;|\?)(s_action=edit&amp;shout_id=)([0-9]+)#s", "", $start_page), 1));

$p_sql = false; $p_arr = array(1 => false, 2 => false, 3 => false, 4 => false);
if (!defined("ADMIN_PANEL")) {
	if (check_panel_status("left")) {
		$p_sql = "panel_side='1'";
	}
	if (check_panel_status("upper")) {
		$p_sql .= ($p_sql ? " OR " : "");
		$p_sql .= ($settings['opening_page'] != START_PAGE ? "(panel_side='2' AND panel_display='1')" : "panel_side='2'");
	}
	if (check_panel_status("lower")) {
		$p_sql .= ($p_sql ? " OR " : "");
		$p_sql .= ($settings['opening_page'] != START_PAGE ? "(panel_side='3' AND panel_display='1')" : "panel_side='3'");
	}
	if (check_panel_status("right")) {
		$p_sql .= ($p_sql ? " OR " : "")."panel_side='4'";
	}

	$p_sql = ($p_sql ? " AND (".$p_sql.")" : false);

	if ($p_sql) {
		$p_res = dbquery(
			"SELECT panel_side, panel_type, panel_filename, panel_content FROM ".DB_PANELS."
			WHERE panel_status='1'".$p_sql." AND ".groupaccess('panel_access')."
			ORDER BY panel_side, panel_order"
		);
		if (dbrows($p_res)) {
			$current_side = 0;
			while ($p_data = dbarray($p_res)) {
				if ($current_side == 0) {
					ob_start();
					$current_side = $p_data['panel_side'];
				}
				if ($current_side > 0 && $current_side != $p_data['panel_side']) {
					$p_arr[$current_side] = ob_get_contents();
					ob_end_clean();
					$current_side = $p_data['panel_side'];
					ob_start();
				}
				if ($p_data['panel_type'] == "file") {
					if (file_exists(INFUSIONS.$p_data['panel_filename']."/".$p_data['panel_filename'].".php")) {
						include INFUSIONS.$p_data['panel_filename']."/".$p_data['panel_filename'].".php";
					}
				} else {
					eval(stripslashes($p_data['panel_content']));
				}
			}
			$p_arr[$current_side] .= ob_get_contents();
			ob_end_clean();
		}
	}
} else {
	ob_start();
	require_once ADMIN."navigation.php";
	$p_arr[1] = ob_get_contents();
	ob_end_clean();
}

if (!defined("ADMIN_PANEL")) {
	$admin_messages = array(); $message_div = "";
	$p_arr[2] = "<a id='content' name='content'></a>\n".$p_arr[2];
	if (iADMIN && $settings['maintenance']) {
		$admin_messages[] = $locale['global_190'];
	}
	if (iSUPERADMIN && file_exists(BASEDIR."setup.php")) {
		$admin_messages[] = $locale['global_198'];
	}
	if (iADMIN && !$userdata['user_admin_password']) {
		$admin_messages[] = $locale['global_199'];
	}
	if (!empty($admin_messages)) {
		$message_div = "<div class='admin-message'>";
		foreach ($admin_messages as $message) {
			$message_div .= $message."<br />";
		}
		$message_div .= "</div>";
		$p_arr[2] = $message_div.$p_arr[2];
	}
	$p_arr[2] = "<noscript><div class='noscript-message admin-message'>".$locale['global_303']."</div>\n</noscript>\n".$p_arr[2];
}

define("LEFT", $p_arr[1]);
define("U_CENTER", $p_arr[2]);
define("L_CENTER", $p_arr[3]);
define("RIGHT", $p_arr[4]);
unset($p_arr);

// Set the require div-width class
if (defined("ADMIN_PANEL")) {
	$main_style = "side-left";
} elseif (LEFT && RIGHT) {
	$main_style = "side-both";
} elseif (LEFT && !RIGHT) {
	$main_style = "side-left";
} elseif (!LEFT && RIGHT) {
	$main_style = "side-right";
} elseif (!LEFT && !RIGHT) {
	$main_style = "";
}
?>