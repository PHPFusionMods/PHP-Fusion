<?php
$locale['400'] = "Registrering";
$locale['401'] = "Aktiver konto";
// Registration Errors
$locale['402'] = "Du må oppgi et brukernavn, et passord og en epostadresse";
$locale['403'] = "Brukernavnet inneholder ugyldige tegn";
$locale['404'] = "Passordene er ikke identiske.";
$locale['405'] = "Ugyldig passord, bare alfanumeriske tegn kan brukes.<br>
Passordet må bestå av minst 6 tegn.";
$locale['406'] = "Din epostadresse ser ikke ut til å være gyldig.";
$locale['407'] = "Beklager, brukernavnet ".(isset($_POST['username']) ? $_POST['username'] : "")." er opptatt.";
$locale['408'] = "Beklager, men epostadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." er opptatt.";
$locale['409'] = "En bruker med en inaktiv konto er allerede registrert med denne epostadressen.";
$locale['410'] = "Sikkerthetskoden er feil.";
$locale['411'] = "Din epostadresse eller epostdomene er sperret.";
// Email Message
$locale['449'] = "Velkommen til ".$settings['sitename'];
$locale['450'] = "Hei ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Velkommen til ".$settings['sitename'].". Her er dine innloggingsdetaljer:\n
Brukernavn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Passord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktiver ditt medlemskap ved å klikke på følgende link:\n";
// Registration Success/Fail
$locale['451'] = "Registreringen er fullført";
$locale['452'] = "Du kan logge inn nå.";
$locale['453'] = "En administrator kommer til å aktivere din konto så fort som mulig.";
$locale['454'] = "Registreringen er nesten ferdig. Du kommer til å få en epost som inneholder dine innloggingsdetaljer sammen med en verifiseringslink.";
$locale['455'] = "Din konto er verifisert";
$locale['456'] = "Registreringen mislyktes";
$locale['457'] = "Det gikk ikke å sende epost. Kontakt <a href='mailto:".$settings['siteemail']."'>Sidens administrator</a>.";
$locale['458'] = "Registreringen mislyktes på grunn av følgende årsak(er):";
$locale['459'] = "Forsøk igjen";
// Register Form
$locale['500'] = "Skriv inn dine opplysninger her.";
$locale['501'] = "Et verifiseringsbrev blir sendt til den epostadressen du oppgir.";
$locale['502'] = "Alle felt markert med <span style='color:#ff0000;'>*</span> må fylles ut. 
OBS! Brukernavn og passord må skrives inn nøyaktig slik du vil ha det!";
$locale['503'] = "Du kan legge til ytterligere informasjon ved å velge Rediger profil når du har logget på.";
$locale['504'] = "Sikkerhetskode:";
$locale['505'] = "Skriv inn sikkerhetskoden:";
$locale['506'] = "Registrer";
$locale['507'] = "Registreringssystemet er for tiden deaktivert.";
// Validation Errors
$locale['550'] = "Du må oppgi et brukernavn.";
$locale['551'] = "Du må oppgi et passord.";
$locale['552'] = "Du må oppgi en epostadresse.";
?>