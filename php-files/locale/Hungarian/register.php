<?php
$locale['400'] = "Regisztráció";
$locale['401'] = "Aktiválás";
// Registration Errors
$locale['402'] = "Meg kell adni a felhasználónevet, jelszót és e-mail címet.";
$locale['403'] = "A felhasználónév nem engedélyezett karaktereket tartalmaz.";
$locale['404'] = "A két jelszó nem egyezik meg.";
$locale['405'] = "Rossz jelszó, csak betûket és számokat használj.<br>A jelszónak legalább 6 karakter hosszúnak kell lennie.";
$locale['406'] = "Az e-mail cím érvénytelennek tûnik.";
$locale['407'] = "".(isset($_POST['username']) ? $_POST['username'] : "")." felhasználónév használatban van.";
$locale['408'] = "".(isset($_POST['email']) ? $_POST['email'] : "")." e-mail cím már használatban van.";
$locale['409'] = "Ezzel az e-mail címmel már van egy nem aktivált felhasználó.";
$locale['410'] = "Helytelen ellenõrzõ kód.";
$locale['411'] = "Az e-mail címed, vagy az e-mail domain része feketelistán van.";
// Email Message
$locale['449'] = "Üdvözlünk - ".$settings['sitename'];
$locale['450'] = "Szia ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Üdvözlünk oldalunkon - ".$settings['sitename'].". 
Felhasználói azonosítóid:\n
Felhasználónév: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Jelszó: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
A következõ linken aktiválhatod hozzáférésed:\n";
// Registration Success/Fail
$locale['451'] = "Regisztráció befejezve";
$locale['452'] = "Most már be tudsz jelentkezni.";
$locale['453'] = "Egy adminisztrátor hamarosan aktiválja hozzáférésed.";
$locale['454'] = "A regisztráció már majdnem kész.<br> Hamarosan kapsz egy aktiváló e-mail-t a felhasználóneveddel, jelszavaddal és egy linkkel, amin aktiválhatod magad.";
$locale['455'] = "Hozzáférésed aktiválva.";
$locale['456'] = "Regisztráció sikertelen";
$locale['457'] = "Nem sikerült elküldeni a levelet. Kérlek lépj kapcsolatba az oldal <a href='mailto:".$settings['siteemail']."'>Adminisztrátorával</a>";
$locale['458'] = "Regisztráció sikertelen a következõ okok miatt:";
$locale['459'] = "Próbáld újra";
// Register Form
$locale['500'] = "Add meg a regisztrációhoz szükséges adatokat. ";
$locale['501'] = "Egy ellenõrzõ levelet küldtünk az általad megadott e-mail címre. ";
$locale['502'] = "A <span style='color:#ff0000;'>*</span>-gal megjelölt mezõket kötelezõ kitölteni
A rendszer megkülönbözteti a kis- és nagybetûket.";
$locale['503'] = " Belépés után szerkesztheted és bõvítheted az adataidat.";
$locale['504'] = "Ellenõrzõ kód:";
$locale['505'] = "Írd be az ellenõrzõ kódot:";
$locale['506'] = "Regisztrálok";
$locale['507'] = "A regisztráció szünetel";
// Validation Errors
$locale['550'] = "Add meg a felhasználónevet!";
$locale['551'] = "Add meg a jelszót!";
$locale['552'] = "Add meg az e-mail címed!";
?>