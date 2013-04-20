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
if (!defined("IN_FUSION")) { header("Location:../../index.php"); exit; }
if (isset($poll_id) && !isNum($poll_id)) { header("Location:index.php"); exit; }

openside($locale['100']);
if (isset($_POST['cast_vote'])) {
	$result = dbquery("SELECT * FROM ".$db_prefix."poll_votes WHERE vote_user='".$userdata['user_id']."' AND poll_id='$poll_id'");
	if (dbrows($result) == "0") {
		$result = dbquery("INSERT INTO ".$db_prefix."poll_votes (vote_user, vote_opt, poll_id) VALUES ('".$userdata['user_id']."', '$voteoption', '$poll_id')");
		header("Location: ".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : ""));
	}
}
$result = dbquery("SELECT * FROM ".$db_prefix."polls ORDER BY poll_started DESC LIMIT 1");
if (dbrows($result) != 0) {
	$data = dbarray($result);
	$poll_title = $data['poll_title'];
	for ($i=0; $i<=9; $i++) {
		if ($data["poll_opt_".$i]) $poll_option[$i] = $data["poll_opt_".$i];
	}
	if (iMEMBER) $result2 = dbquery("SELECT * FROM ".$db_prefix."poll_votes WHERE vote_user='".$userdata['user_id']."' AND poll_id='".$data['poll_id']."'");
	if ((!iMEMBER || !dbrows($result2)) && $data['poll_ended'] == 0) {
		$poll = ""; $i = 0; $num_opts = count($poll_option);
		while ($i < $num_opts) {
			$poll .= "<input type='radio' name='voteoption' value='$i'> $poll_option[$i]<br><br>\n";
			$i++;
		}
		echo "<form name='voteform' method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>
<b>$poll_title</b><br><br>
$poll<center><input type='hidden' name='poll_id' value='".$data['poll_id']."'>\n";
		if (iMEMBER) {
			echo "<input type='submit' name='cast_vote' value='".$locale['101']."' class='button'></center>\n";
		} else {
			echo $locale['102']."</center>\n";
		}
		echo "</form>\n";
	} else {
		$poll =  ""; $i = 0; $num_opts = count($poll_option);
		$poll_votes = dbcount("(vote_opt)", "poll_votes", "poll_id='".$data['poll_id']."'");
		while ($i < $num_opts) {
			$num_votes = dbcount("(vote_opt)", "poll_votes", "vote_opt='$i' AND poll_id='".$data['poll_id']."'");
			$opt_votes = ($poll_votes ? number_format(100 / $poll_votes * $num_votes) : 0);
			$poll .= "<div>".$poll_option[$i]."</div>
<div><img src='".THEME."images/pollbar.gif' alt='".$poll_option[$i]."' height='12' width='$opt_votes' class='poll'></div>
<div>".$opt_votes."% [".$num_votes." ".($num_votes == 1 ? $locale['103'] : $locale['104'])."]</div><br>\n";
			$i++;
		}
		echo "<b>".$poll_title."</b><br><br>
$poll
<center>".$locale['105'].$poll_votes."<br>
".$locale['106'].showdate("shortdate", $data['poll_started']);
		if ($data['poll_ended'] > 0) {
			echo "<br>\n".$locale['107'].showdate("shortdate", $data['poll_ended'])."\n";
		}
		$result = dbquery("SELECT * FROM ".$db_prefix."polls");
		if (dbrows($result) > 1) {
			echo "<br><br><img src='".THEME."images/bullet.gif' alt=''>
<a href='".INFUSIONS."member_poll_panel/polls_archive.php' class='side'>".$locale['108']."</a> <img src='".THEME."images/bulletb.gif' alt=''>\n";
		}
		echo "</center>\n";
	}
} else {
	echo "<center>".$locale['004']."</center>\n";
}
closeside();
?>