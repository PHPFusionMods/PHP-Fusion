<?php
// Lid Beheer Opties
$locale['400'] = "Leden";
$locale['401'] = "Gebruiker";
$locale['402'] = "Toevoegen";
$locale['403'] = "Gebruikertype";
$locale['404'] = "Opties";
$locale['405'] = "Bekijken";
$locale['406'] = "Wijzigen";
$locale['407'] = "Verbanning ongedaan maken";
$locale['408'] = "Verbannen";
$locale['409'] = "Verwijderen";
$locale['410'] = "Er zijn geen gebruikernamen beginnend met ";
$locale['411'] = "Toon alle";
$locale['412'] = "Activeren";
// Leden verbanning
$locale['420'] = "Ban opgelegd";
$locale['421'] = "Ban verwijderd";
$locale['422'] = "Lid verwijderd";
$locale['423'] = "Weet u zeker dat u dit lid wilt verwijderen?";
$locale['424'] = "Lidmaatschap geactiveerd";
$locale['425'] = "Account geactiveerd om ";
$locale['426'] = "Hallo [USER_NAME],\n
Uw account op ".$settings['sitename']." is geactiveerd.\n
U kunt nu inloggen met de door u gekozen gebruikernaam en wachtwoord.\n
Met vriendelijke groeten,
".$settings['siteusername'];
// Lidgegevens wijzigen
$locale['430'] = "Lidgegevens wijzigen";
$locale['431'] = "Lidgegevens gewijzigd";
$locale['432'] = "Terug naar Leden Beheer";
$locale['433'] = "Terug naar Beheerder Index";
$locale['434'] = "Niet in staat om lidgegevens te wijzigen:";
// Lidgegevens wijzigen formulier extra opties
$locale['440'] = "Veranderingen opslaan";
// Profiel wijzigen fouten
$locale['450'] = "Superbeheerder kan niet worden gewijzigd.";
$locale['451'] = "U dient een gebruikernaam en emailadres op te geven.";
$locale['452'] = "De gebruikernaam bevat ongeldige tekens.";
$locale['453'] = "De gebruikernaam ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." is reeds in gebruik.";
$locale['454'] = "Ongeldig emailadres.";
$locale['455'] = "Het emailadres ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." is reeds in gebruik.";
$locale['456'] = "De nieuwe wachtwoorden komen niet met elkaar overeen.";
$locale['457'] = "Ongeldig wachtwoord, gebruik alleen alfanumerieke tekens.<br>
Een wachtwoord dient minimaal 6 tekens lang te zijn.";
$locale['458'] = "<b>Waarschuwing:</b> er wordt een onverwacht script uitgevoerd.";
// Lidprofiel bekijken
$locale['470'] = "Lidprofiel";
$locale['472'] = "Statistieken";
$locale['473'] = "Gebruikergroepen";
// Lid toevoegen/fouten
$locale['480'] = "Lid toevoegen";
$locale['481'] = "Lidaccount toegevoegd.";
$locale['482'] = "Lidaccount kon niet worden toegevoegd.";
?>