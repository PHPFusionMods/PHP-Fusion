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
+----------------------------------------------------+
| Ratings system developed by CrappoMan
| email: simonpatterson@dsl.pipex.com
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

include LOCALE.LOCALESET."ratings.php";

function showratings($rating_type,$rating_item_id,$rating_link) {

global $settings,$locale,$userdata; $settings['rating_system']=1;
	
	if (isset($_POST['post_rating'])) {
		if ($_POST['rating'] > 0) {
			$result = dbquery("INSERT INTO ".DB_PREFIX."ratings (rating_item_id, rating_type, rating_user, rating_vote, rating_datestamp, rating_ip) VALUES ('$rating_item_id', '$rating_type', '".$userdata['user_id']."', '".$_POST['rating']."', '".time()."', '".USER_IP."')");
		}
		header("Location: ".$rating_link);
	} elseif (isset($_POST['remove_rating'])) {
		$result = dbquery("DELETE FROM ".DB_PREFIX."ratings WHERE rating_item_id='$rating_item_id' AND rating_type='$rating_type' AND rating_user='".$userdata['user_id']."'");
		header("Location: ".$rating_link);
	}
	
	$ratings=array(5=> $locale['r120'], 4=> $locale['r121'], 3=> $locale['r122'], 2=> $locale['r123'], 1=> $locale['r124']);
	
	if ($settings['rating_system']=="1") {
		tablebreak();
		opentable($locale['r100']);
		$d_rating=dbarray(dbquery("SELECT rating_vote,rating_datestamp FROM ".DB_PREFIX."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_user='".$userdata['user_id']."'"));
		if (!iMEMBER) {
			echo "<div align='center'>".$locale['r104']."</div>\n";
		} elseif ($d_rating['rating_vote']>0) {
			echo "<form name='removerating' method='post' action='".$rating_link."'>
<div align='center'>".sprintf($locale['r105'], $ratings[$d_rating['rating_vote']], showdate("longdate", $d_rating['rating_datestamp']))."<br><br>
<input type='submit' name='remove_rating' value='".$locale['r102']."' class='button'></div>
</form>";
		}else{
			echo "<form name='postrating' method='post' action='".$rating_link."'>
<div align='center'>".$locale['r106'].": <select name='rating' class='textbox'>
<option value='0'>".$locale['r107']."</option>\n";
			foreach($ratings as $rating=>$rating_info) {
				echo "<option value='".$rating."'>$rating_info</option>\n";
			}
			echo "</select>\n";
			echo "<input type='submit' name='post_rating' value='".$locale['r103']."' class='button'>
</form>\n";
		}
		echo "<hr>";
		$tot_votes = dbresult(dbquery("SELECT COUNT(rating_item_id) FROM ".DB_PREFIX."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."'"),0);
		if($tot_votes){
			echo "<table align='center' cellpadding='0' cellspacing='1' class='tbl-border'>
<tr>
<td>
<table align='center' cellpadding='0' cellspacing='0'>\n";
			foreach($ratings as $rating=>$rating_info) {
				$num_votes = dbresult(dbquery("SELECT COUNT(rating_item_id) FROM ".DB_PREFIX."ratings WHERE rating_item_id='".$rating_item_id."' AND rating_type='".$rating_type."' AND rating_vote='".$rating."'"),0);
				$pct_rating = number_format(100 / $tot_votes * $num_votes);
				if ($num_votes == 0) {
					$votecount = "[".$locale['r108']."]";
				} elseif ($num_votes == 1) {
					$votecount = "[1 ".$locale['r109']."]";
				} else {
					$votecount = "[".$num_votes." ".$locale['r110']."]";
				}
				$class = ($rating % 2==0?"tbl1":"tbl2");
				echo "<tr>
<td class='$class'>$rating_info</td>
<td width='250' class='$class'><img src='".THEME."images/pollbar.gif' alt='$rating_info' height='12' width='".$pct_rating."%' class='poll'></td>
<td class='$class'>".$pct_rating."%</td>
<td class='$class'>$votecount</td>
</tr>\n";
			}
			echo "</td>\n</table>\n</td>\n</tr>\n</table>";
		}else{
			echo "<div align='center'>".$locale['r101']."</div>\n";
		}
		closetable();
	}
}
?>