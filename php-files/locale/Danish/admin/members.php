<?php
// Member Management Options
$locale['400'] = "Brugere";
$locale['401'] = "Bruger";
$locale['402'] = "Tilføj";
$locale['403'] = "Brugerstatus";
$locale['404'] = "Valgmuligheder";
$locale['405'] = "Se";
$locale['406'] = "Rediger";
$locale['407'] = "Ophæv udelukkelse";
$locale['408'] = "Udeluk";
$locale['409'] = "Slet";
$locale['410'] = "Der er ingen brugernavne, som begynder med ";
$locale['411'] = "Vis alle";
$locale['412'] = "Aktiver";

// Ban/Unban/Delete Member
$locale['420'] = "Udelukkelse gennemført";
$locale['421'] = "Udelukkelse ophævet";
$locale['422'] = "Brugeren er slettet";
$locale['423'] = "Er du sikker på, at du vil slette denne bruger?";
$locale['424'] = "Brugerkontoen aktiveret";
$locale['425'] = "Kontoen aktiveret d. ";
$locale['426'] = "Hej [USER_NAME],\n
Din konto på ".$settings['sitename']." er blevet aktiveret.\n
Du kan nu logge ind med det brugernavn og det kodeord, du har valgt.\n
Venlig hilsen,
".$settings['siteusername'];

// Edit Member Details
$locale['430'] = "Rediger brugeroplysninger";
$locale['431'] = "Brugeroplysninger er opdateret";
$locale['432'] = "Tilbage til brugeradministration";
$locale['433'] = "Tilbage til administrationsside";
$locale['434'] = "Var ikke i stand til at opdatere brugeroplysninger:";

// Extra Edit Member Details form options
$locale['440'] = "Gem ændringerne";

// Update Profile Errors
$locale['450'] = "Hovedadministratoren kan ikke redigeres.";
$locale['451'] = "Du skal angive et brugernavn og en mailadresse.";
$locale['452'] = "Brugernavnet indeholder uacceptable karakterer.";
$locale['453'] = "Brugernavnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." anvendes i forvejen.";
$locale['454'] = "Mailadressen er fejlbehæftet.";
$locale['455'] = "Mailadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." anvendes i forvejen.";
$locale['456'] = "De to kodeord er ikke identiske.";
$locale['457'] = "Kodeordets format er forkert. Du må kun bruge alfanumeriske karakterer.<br>
Et kodeord skal være på mindst 6 karakterer.";
$locale['458'] = "<b>Advarsel:</b> fejl i udførelse af scriptet.";

// View Member Profile
$locale['470'] = "Brugerprofil";
$locale['472'] = "Statistik";
$locale['473'] = "Brugergrupper";

// Add Member Errors
$locale['480'] = "Tilføj bruger";
$locale['481'] = "Brugerkontoen er oprettet.";
$locale['482'] = "Brugerkontoen kunne ikke oprettes.";
?>
