<?php
// Member Management Options
$locale['400'] = "Tagok";
$locale['401'] = "Tag";
$locale['402'] = "Hozzáadás";
$locale['403'] = "Felhasználó típusa";
$locale['404'] = "Lehetõségek";
$locale['405'] = "Megtekint";
$locale['406'] = "Szerkeszt";
$locale['407'] = "Ban visszavonás";
$locale['408'] = "Ban";
$locale['409'] = "Töröl";
$locale['410'] = "Nincs ilyen betûvel kezdõdõ nevû felhasználó: ";
$locale['411'] = "Összes";
$locale['412'] = "Aktivál";
// Ban/Unban/Delete Felhasználó
$locale['420'] = "Felhasználó kitiltva";
$locale['421'] = "Felhasználó engedélyezve";
$locale['422'] = "Felhasználó törölve";
$locale['423'] = "Biztosan törölni akarod ezt a felhasználót?";
$locale['424'] = "Felhasználó aktiválva";
$locale['425'] = "Hozzáférésed aktiválva: ";
$locale['426'] = "Üdv [USER_NAME],\n
A hozzáférésed aktiválva lett oldalunkon - ".$settings['sitename']."\n
Most már be tudsz jelentkezni a megadott felhasználóneveddel és jelszavaddal\n
Üdvözlettel, 
".$settings['siteusername'];
// Felhasználó adatainak szerkesztése
$locale['430'] = "Felhasználó szerkesztése";
$locale['431'] = "Felhasználó adatai frissítve";
$locale['432'] = "Vissza a felhasználók adminisztrációjához";
$locale['433'] = "Vissza az admin fõoldalra";
$locale['434'] = "Nem lehet frissíteni a felhasználó adatait:";
// Extra lehetõségek
$locale['440'] = "Változások mentése";
// Profil frissítési hiba
$locale['450'] = "A fõ adminisztrátort nem lehet szerkeszteni";
$locale['451'] = "Meg kell adni a felhasználónevet és a jelszót";
$locale['452'] = "A felhasználónév nem engedélyezett karaktereket tartalmaz";
$locale['453'] = "A felhasználónév (".(isset($_POST['user_name']) ? $_POST['user_name'] : "").") már használatban van";
$locale['454'] = "Érvénytelen e-mail cím";
$locale['455'] = "Az e-mail cím (".(isset($_POST['user_email']) ? $_POST['user_email'] : "").") már használatban van";
$locale['456'] = "Az új jelszavak nem egyeznek meg";
$locale['457'] = "Rossz jelszó. Csak alfanumerikus karaktereket lehet használni!<br> Legalább 6 karakter hosszúnak kell lennie a jelszónak!";
$locale['458'] = "<b>Figyelem:</b> nemvárt script végrehajtása.";
// Profil megtekintése
$locale['470'] = "Felhasználó profilja";
$locale['471'] = "Általános információk";
$locale['472'] = "Statisztika";
$locale['473'] = "Felhasználói csoportok";
// Felhasználó hozzáadása hiba
$locale['480'] = "Felhasználó hozzáadása";
$locale['481'] = "A felhasználó létrehozva";
$locale['482'] = "Nem lehet létrehozni a felhasználót";
?>