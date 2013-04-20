<?php
// Member Management Options
$locale['400'] = "Medlemmer";
$locale['401'] = "Bruker";
$locale['402'] = "Tilføy";
$locale['403'] = "Brukerstatus";
$locale['404'] = "Valgmuligheter";
$locale['405'] = "Vis";
$locale['406'] = "Rediger";
$locale['407'] = "Opphev utestengelse";
$locale['408'] = "Utesteng";
$locale['409'] = "Slett";
$locale['410'] = "Det er ingen brukernavn som begynner med ";
$locale['411'] = "Vis alle";
$locale['412'] = "Aktiver";
// Ban/Unban/Delete Member
$locale['420'] = "Utestengelse gjennomført";
$locale['421'] = "Utestengelse opphevet";
$locale['422'] = "Brukeren er slettet";
$locale['423'] = "Er du sikker på at du vil slette denne bruker?";
$locale['424'] = "Brukerkonto aktivert";
$locale['425'] = "Kontoen aktivert d. ";
$locale['426'] = "Hei [USER_NAME],\n
Din konto på ".$settings['sitename']." er blitt aktivert.\n
Du kan nå logge inn med det brukernavn og passord som du har valgt.\n
Vennlig hilsen,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Rediger brukeropplysninger";
$locale['431'] = "Brukeropplysninger er oppdatert";
$locale['432'] = "Tilbake til brukeradministrasjon";
$locale['433'] = "Tilbake til administrasjonsside";
$locale['434'] = "Var ikke i stand til å oppdatere brukeropplysninger:";
// Extra Edit Member Details form options
$locale['440'] = "Lagre endringene";
// Update Profile Errors
$locale['450'] = "Hovedadministratoren kan ikke redigeres.";
$locale['451'] = "Du må oppgi et brukernavn og en mailadresse.";
$locale['452'] = "Brukernavnet inneholder ikke-aksepterte tegn.";
$locale['453'] = "Brukernavnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." er allerede i bruk.";
$locale['454'] = "Mailadressen er feil.";
$locale['455'] = "Mailadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." er allerede i bruk.";
$locale['456'] = "Passordene er ikke identiske.";
$locale['457'] = "Passordets format er feil. Du kan kun bruke alfanumeriske karakterer<br>
Passordet må være på minst 6 tegn.";
$locale['458'] = "<b>Advarsel:</b> feil i utførelse av scriptet.";
// View Member Profile
$locale['470'] = "Brukerprofil";
$locale['472'] = "Statistikk";
$locale['473'] = "Brukergrupper";
// Add Member Errors
$locale['480'] = "Tilføy bruker";
$locale['481'] = "Brukerkontoen er opprettet.";
$locale['482'] = "Brukerkontoen kunne ikke opprettes.";
?>