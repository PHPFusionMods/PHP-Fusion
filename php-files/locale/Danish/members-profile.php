<?php
// Members List
$locale['400'] = "Brugerlisteliste";
$locale['401'] = "Brugernavn";
$locale['402'] = "Brugerstatus";
$locale['403'] = "Ingen brugernavne begynder med ";
$locale['404'] = "Vis alle";

//Usergroups
$locale['410'] = "Viser gruppen";
$locale['411'] = "%u bruger";
$locale['412'] = "%u brugere";

// User Profile
$locale['420'] = "Brugerprofilprofil";
$locale['422'] = "Statistik";
$locale['423'] = "Brugergrupper";

// Edit Profile
$locale['440'] = "Rediger profil";
$locale['441'] = "Profilen er opdateret succesfuldt";
$locale['442'] = "Ude af stand til at opdatere profilen:";

// Edit Profile Form
$locale['460'] = "Opdater profil";

// Update Profile Errors
$locale['480'] = "Du skal opgive et brugernavn og en mailadresse.";
$locale['481'] = "Der er anvendt forkerte tegn i brugernavnet.";
$locale['482'] = "Brugernavnet ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." bruges allerede.";
$locale['483'] = "Mailadressen er ikke brugbar.";
$locale['484'] = "Mailadressen ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." bruges allerede.";
$locale['485'] = "De nye kodeord er ikke identiske.";
$locale['486'] = "Kodeordet er forkert. Du må kun bruge alfanumeriske karakterer.<br>
Kodeordet skal være på mindst 6 karakterer.";
$locale['487'] = "<b>Advarsel:</b> uventet resultat af script-udførelsen.";
?>
