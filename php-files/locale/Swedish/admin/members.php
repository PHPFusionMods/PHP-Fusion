<?php
// Member Management Options
$locale['400'] = "Användaradministration";
$locale['401'] = "Användare";
$locale['402'] = "Lägg till";
$locale['403'] = "Användarstatus";
$locale['404'] = "Inställningar";
$locale['405'] = "Granska";
$locale['406'] = "Redigera";
$locale['407'] = "Upphäv uteslutning";
$locale['408'] = "Uteslut användare";
$locale['409'] = "Radera";
$locale['410'] = "Det finns inget användarnamn som börjar med ";
$locale['411'] = "Visa alla";
$locale['412'] = "Aktivera";
// Ban/Unban/Delete Member
$locale['420'] = "Uteslutning genomförd";
$locale['421'] = "Uteslutning upphävd";
$locale['422'] = "Användaren är raderad";
$locale['423'] = "Är du säker på att du vill radera denna användare?";
$locale['424'] = "Användaren aktiverad";
$locale['425'] = "Användarkontot aktiverat den ";
$locale['426'] = "Hej [USER_NAME],\n
Ditt konto ".$settings['sitename']." har blivit aktiverat\n
Du kan logga in med ditt användarnamn och lösenord.\n
Med vänliga hälsningar,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Redigera användaruppgifter";
$locale['431'] = "Användarupplysningar är ändrade";
$locale['432'] = "Tillbaka till användaradministration";
$locale['433'] = "Tillbaka till administrationspanel";
$locale['434'] = "Det gick inte att ändra användaruppgifter:";
// Extra Edit Member Details form options
$locale['440'] = "Spara ändringar";
// Update Profile Errors
$locale['450'] = "Huvudadministratören kan ej ändras.";
$locale['451'] = "Du måste ange ett användarnamn och en epostadress.";
$locale['452'] = "Användarnamnet innehåller ogiltiga tecken.";
$locale['453'] = "Användarnamnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." används redan.";
$locale['454'] = "Felaktig epostadress.";
$locale['455'] = "Epostadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." används redan.";
$locale['456'] = "Lösenorden är inte identiska.";
$locale['457'] = "Ogiltigt lösenord, endast alfanumeriska tecken får användas.
<br>Lösenordet måste bestå av minst 6 tecken.";
$locale['458'] = "<b>Varning:</b> oväntad scripthändelse.";
// View Member Profile
$locale['470'] = "Användaruppgifter:";
$locale['472'] = "Statistik";
$locale['473'] = "Användargrupper";
// Update Profile Errors
$locale['480'] = "Lägg till användare";
$locale['481'] = "Användarkontot är upprättat.";
$locale['482'] = "Användarkontot kunde ej upprättas.";
?>
