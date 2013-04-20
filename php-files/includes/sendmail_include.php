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
if (!defined("IN_FUSION")) { header("Location:../index.php"); exit; }

function sendemail($toname,$toemail,$fromname,$fromemail,$subject,$message,$type="plain",$cc="",$bcc="") {

	global $settings, $locale;
	
	require_once INCLUDES."phpmailer_include.php";
	
	$mail = new PHPMailer();
	if (file_exists(INCLUDES."languages/phpmailer.lang-".$locale['phpmailer'].".php")) {
		$mail->SetLanguage($locale['phpmailer'], INCLUDES."language/");
	} else {
		$mail->SetLanguage("en", INCLUDES."language/");
	}

	if ($settings['smtp_host']=="") {
		$mail->IsMAIL();
	} else {
		$mail->IsSMTP();
		$mail->Host = $settings['smtp_host'];
		$mail->SMTPAuth = true;
		$mail->Username = $settings['smtp_username'];
		$mail->Password = $settings['smtp_password'];
	}
	
	$mail->CharSet = $locale['charset'];
	$mail->From = $fromemail;
	$mail->FromName = $fromname;
	$mail->AddAddress($toemail, $toname);
	$mail->AddReplyTo($fromemail, $fromname);
	if ($cc) { 
		$cc = explode(", ", $cc);
		foreach ($cc as $ccaddress) {
			$mail->AddCC($ccaddress);
		}
	}
	if ($bcc) {
		$bcc = explode(", ", $bcc);
		foreach ($bcc as $bccaddress) {
			$mail->AddBCC($bccaddress);
		}
	}
	if ($type == "plain") {
		$mail->IsHTML(false);
	} else {
		$mail->IsHTML(true);
	}
	
	$mail->Subject = $subject;
	$mail->Body = $message;
	
	if(!$mail->Send()) {
		$mail->ErrorInfo;
		$mail->ClearAllRecipients();
		$mail->ClearReplyTos();
		return false;
	} else {
		$mail->ClearAllRecipients(); 
		$mail->ClearReplyTos();
		return true;
	}

}
?>
