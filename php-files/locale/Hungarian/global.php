<?php
// Helyi beállítások
setlocale(LC_TIME, "hu", "hu_HU", "hungarian"); // Linux Srerveren (Windows alatt lehet hogy más)
$locale['charset'] = "iso-8859-2";
$locale['tinymce'] = "hu";
$locale['phpmailer'] = "hu";

// Teljes & rövidített hónapok
$locale['months'] = "&nbsp|január|február|március|április|május|június|július|augusztus|szeptember|október|november|december";
$locale['shortmonths'] = "&nbsp|jan|febr|márc|ápr|máj|jún|júl|aug|szept|okt|nov|dec";

// Felhasználói rangok
$locale['user0'] = "Vendég";
$locale['user1'] = "Tag";
$locale['user2'] = "Adminisztrátor";
$locale['user3'] = "Fõ Adminisztrátor";
// Fórum moderátor
$locale['userf1'] = "Moderátor";
// Navigáció
$locale['001'] = "Navigáció";
$locale['002'] = "Nincs megadva link\n";
$locale['003'] = "Csak regisztrált tagoknak";
$locale['004'] = "Ez a panel üres";
// Online felhasználók
$locale['010'] = "Online felhasználók";
$locale['011'] = "Vendég: ";
$locale['012'] = "Tag: ";
$locale['013'] = "Nincs Online tag";
$locale['014'] = "Regisztráltak: ";
$locale['015'] = "Nem aktiváltak: ";
$locale['016'] = "Legújabb tag: ";
// Sidebar & egyéb feliratok
$locale['020'] = "Fórumtémák";
$locale['021'] = "Legújabb témák";
$locale['022'] = "Legnépszerûbb témák";
$locale['023'] = "Legújabb cikkek";
$locale['024'] = "Üdvözlet";
$locale['025'] = "Utolsó aktív fórumtémák";
$locale['026'] = "Legutóbbi fórumtémáim";
$locale['027'] = "Legutóbbi hozzászólásaim";
$locale['028'] = "Új hozzászólások";
// Fórum témák listája
$locale['030'] = "Fórum";
$locale['031'] = "Téma";
$locale['032'] = "Megnyitás";
$locale['033'] = "Válasz";
$locale['034'] = "Utolsó üzenet";
$locale['035'] = "Tárgy";
$locale['036'] = "Írta";
$locale['037'] = "Még egy fórumtémát sem indítottál";
$locale['038'] = "Még egy hozzászólásod sincs a fórumban";
$locale['039'] = "%u új hozzászólás utolsó látogatásod óta";
// Hírek & cikkek
$locale['040'] = " ";
$locale['041'] = " - ";
$locale['042'] = "Részletek";
$locale['043'] = " hozzászólás";
$locale['044'] = " megnyitás";
$locale['045'] = "Nyomtatható változat";
$locale['046'] = "Hírek";
$locale['047'] = "Az oldalon jelenleg nincsenek hírek";
$locale['048'] = "Szerkesztés";
// Prev-Next Bar
$locale['050'] = "Elõzõ";
$locale['051'] = "Következõ";
$locale['052'] = "Oldal: ";
$locale['053'] = " / ";
// Login menü
$locale['060'] = "Bejelentkezés";
$locale['061'] = "Felhasználónév";
$locale['062'] = "Jelszó";
$locale['063'] = "Emlékezzen rám";
$locale['064'] = "Bejelentkezés";
$locale['065'] = "Még nem regisztráltál?<br><a href='".BASEDIR."register.php' class='side'>Kattints ide</a>!";
$locale['066'] = "Elfelejtetted jelszavad?<br><a href='".BASEDIR."lostpassword.php' class='side'>Kérj újat itt</a>.";
// Felhasználói menü
$locale['080'] = "Profil szerkesztése";
$locale['081'] = "Privát üzenetek";
$locale['082'] = "Regisztrált tagok";
$locale['083'] = "Adminisztráció";
$locale['084'] = "Kijelentkezés";
$locale['085'] = "%u új üzeneted van";
$locale['086'] = "";
$locale['087'] = "";
// Szavazás
$locale['100'] = "Szavazás";
$locale['101'] = "Szavazok";
$locale['102'] = "Szavazáshoz be kell jelentkezni";
$locale['103'] = "Szavazat";
$locale['104'] = "Szavazat";
$locale['105'] = "Szavazatok: ";
$locale['106'] = "Indult: ";
$locale['107'] = "Lezárva: ";
$locale['108'] = "Archívum";
$locale['109'] = "Válassz egy szavazást a listából:";
$locale['110'] = "Megtekintés";
$locale['111'] = "Szavazás megtekintése";
// Üzenõfal
$locale['120'] = "Üzenõfal";
$locale['121'] = "Név:";
$locale['122'] = "Üzenet:";
$locale['123'] = "Elküld";
$locale['124'] = "Segítség";
$locale['125'] = "Üzenet küldéséhez be kell jelentkezni";
$locale['126'] = "Archívum";
$locale['127'] = "Még nem küldtek üzenetet";
// Lábjegyzet
$locale['140'] = "látogató";
$locale['141'] = "látogató";
// Admin Navigáció
$locale['150'] = "Adminisztrátori fõmenü";
$locale['151'] = "Fõoldal";
$locale['152'] = "Adminisztráció";
// Vegyes
$locale['190'] = "Az oldal karbantartás alatt áll";
$locale['191'] = "IP címed a feketelistán szerepel";
$locale['192'] = "Kijelentkezés: ";
$locale['193'] = "Bejelentkezés: ";
$locale['194'] = "A hozzáférésed jelenleg fel van függesztv.";
$locale['195'] = "A hozzáférésed még nincs aktiválva";
$locale['196'] = "Hibás felhasználónév vagy jelszó";
$locale['197'] = "Átirányítás folyamatban...<br><br>
[ <a href='index.php'>Kattints ide, ha nem akarsz várni</a> ]";
$locale['198'] = "<b>FIGYELEM:</b> a setup.php állományt még nem törölted, telepítés után minél hamarabb töröld!";
?>