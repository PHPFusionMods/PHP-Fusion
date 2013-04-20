<?php
$locale['400'] = "Aanmelden";
$locale['401'] = "Account activeren";
// Aanmelding - Fouten
$locale['402'] = "U moet een gebruikernaam, wachtwoord en emailadres opgeven.";
$locale['403'] = "Gebruikernaam bevat ongeldige tekens.";
$locale['404'] = "Uw twee wachtwoorden komen niet overeen.";
$locale['405'] = "Ongeldig wachtwoord, gebruik alleen alfanumerieke tekens.<br>
Een wachtwoord dient minimaal 6 tekens lang te zijn.";
$locale['406'] = "Uw emailadres lijkt niet geldig te zijn.";
$locale['407'] = "Sorry, de gebruikernaam ".(isset($_POST['username']) ? $_POST['username'] : "")." is reeds in gebruik.";
$locale['408'] = "Sorry, het emailadres ".(isset($_POST['email']) ? $_POST['email'] : "")." is reeds in gebruik.";
$locale['409'] = "Een inactief account is aangemeld met dit emailadres.";
$locale['410'] = "Validatiecode incorrect.";
$locale['411'] = "Uw emailadres of emaildomein staat op de Zwarte Lijst.";
// Emailbericht
$locale['449'] = "Welkom bij ".$settings['sitename'];
$locale['450'] = "Hallo ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Welkom bij ".$settings['sitename'].". Hier zijn uw login-gegevens:\n
Gebruikernaam: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Wachtwoord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
U dient uw account via de volgende link te activeren:\n";
// Aanmelding succes/fout
$locale['451'] = "Aanmelding compleet";
$locale['452'] = "U kunt nu inloggen.";
$locale['453'] = "Een beheerder zal binnenkort uw account activeren.";
$locale['454'] = "Uw aanmelding is bijna compleet. U zult een email met uw login-gegevens ontvangen met daarin een link om uw account te activeren.";
$locale['455'] = "Uw account is geverifieerd.";
$locale['456'] = "Aanmelding mislukt";
$locale['457'] = "De email werd niet verzonden, neemt u a.u.b. contact op met de <a href='mailto:".$settings['siteemail']."'>site-beheerder</a>.";
$locale['458'] = "Aanmelding is mislukt om de volgende reden(en):";
$locale['459'] = "Probeer het a.u.b. nogmaals";
// Aanmelding - Formulier
$locale['500'] = "Voer uw gegevens a.u.b. hieronder in. ";
$locale['501'] = "Een verificatie-email zal naar het door u opgegeven emailadres worden gestuurd. ";
$locale['502'] = "Velden die gemarkeerd zijn met <span style='color:#ff0000;'>*</span> moeten worden ingevuld.
Uw gebruikernaam en wachtwoord zijn hoofdlettergevoelig.";
$locale['503'] = " U kunt aanvullende informatie opgeven door naar Profiel wijzigen te gaan zodra u bent ingelogd.";
$locale['504'] = "Validatiecode:";
$locale['505'] = "Voer validatiecode in:";
$locale['506'] = "Aanmelden";
$locale['507'] = "Het aanmeldingssysteem is op het moment uitgeschakeld.";
// Validatie _ Fouten
$locale['550'] = "Geef a.u.b. een gebruikernaam op.";
$locale['551'] = "Geef a.u.b. een wachtwoord.";
$locale['552'] = "Geef a.u.b. een emailadres.";
?>