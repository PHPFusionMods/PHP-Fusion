<?php
// Contact Form
$locale['400'] = "Kontakt meg";
$locale['401'] = "Det er flere måter du kan kontakte meg, du kan sende en epost direkte her 
<a href='mailto:".str_replace("@","&#64;",$settings['siteemail'])."'>".str_replace("@","&#64;",$settings['siteemail'])."</a>.
Hvis du er medlem kan du sende en <a href='messages.php?msg_send=1'>Privat Melding</a>.
Som et alternativ kan du bruke skjemaet på denne siden, det sender meldingen din som epost til meg.";
$locale['402'] = "Navn:";
$locale['403'] = "Epostadresse:";
$locale['404'] = "Emne:";
$locale['405'] = "Melding:";
$locale['406'] = "Send Melding";
// Contact Errors
$locale['420'] = "Du må oppgi et navn";
$locale['421'] = "Du må oppgi en epostadresse";
$locale['422'] = "Du må oppgi et emne";
$locale['423'] = "Du må skrive en melding";
// Message Sent
$locale['440'] = "Meldingen din er sendt";
$locale['441'] = "Takk";
$locale['442'] = "Meldingen din ble ikke sendt av følgende årsak(er):";
$locale['443'] = "Vennligst forsøk igjen.";
?>