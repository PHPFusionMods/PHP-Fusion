<?php
$locale['400'] = "Bli användare";
$locale['401'] = "Aktivera användarkonto";
// Registration Errors
$locale['402'] = "Du måste välja ett användarnamn, ett lösenord, samt ange en epostadress";
$locale['403'] = "Ditt användarnamn innehåller otillåtna tecken";
$locale['404'] = "Lösenorden är inte identiska.";
$locale['405'] = "Ogiltigt lösenord, endast alfanumeriska tecken får användas.<br>
Lösenordet måste bestå av minst 6 tecken.";
$locale['406'] = "Din epostadress förefaller ej giltlig.";
$locale['407'] = "Tyvärr, användarnamnet ".(isset($_POST['username']) ? $_POST['username'] : "")." är upptaget.";
$locale['408'] = "Tyvärr, men epostadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." är upptagen.";
$locale['409'] = "En användare med ett inaktivt konto är redan registrerad med denna epostadress.";
$locale['410'] = "Säkerhetskoden är felaktig.";
$locale['411'] = "Din epostadress eller epostdomän är spärrad.";
// Email Message
$locale['449'] = "Välkommen till ".$settings['sitename'];
$locale['450'] = "Hej ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Välkommen till ".$settings[sitename].", här är dina inloggningsuppgifter:\n
Användarnamn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Lösenord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktivera ditt medlemskap genom att klicka på följande länk:\n";
// Registration Success/Fail
$locale['451'] = "Registreringen fullständig";
$locale['452'] = "Du kan logga in nu.";
$locale['453'] = "En administratör kommer att aktivera ditt konto snarast.";
$locale['454'] = "Registreringen är nästan klar, du kommer att få epost innehållande dina inloggningsdetaljer tillsammans med en verifieringslänk.";
$locale['455'] = "Ditt konto är verifierat";
$locale['456'] = "Registreringen misslyckades";
$locale['457'] = "Det gick inte att skicka epost. Kontakta <a href='mailto:".$settings['siteemail']."'>sidans administratör</a>.";
$locale['458'] = "Registreringen misslyckades på grund av följande orsak:";
$locale['459'] = "Försök igen";
// Register Form
$locale['500'] = "Skriv in dina uppgifter nedan. ";
$locale['501'] = "Ett verifieringsbrev skickas till den epostadress du har uppgivit...";
$locale['502'] = "Alla markerade fält <span style='color:#ff0000;'>*</span> skall fyllas i. OBS! Användarnamn och lösenord är skiftlägeskänsliga!";
$locale['503'] = " Du kan lägga till ytterligare information genom att välja Redigera profil när du har loggat på.";
$locale['504'] = "Säkerhetskod:";
$locale['505'] = "Skriv in säkerhetskoden:";
$locale['506'] = "Registrera";
$locale['507'] = "Registreringssystemet är tillfälligt deaktiverat.";
// Validation Errors
$locale['550'] = "Du måste ange ett användarnamn.";
$locale['551'] = "Du måste ange ett lösenord.";
$locale['552'] = "Du måste ange en epostadress.";
?>
