<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/

// Locale Settings
setlocale(LC_TIME, "lt_LT"); // Linux Server (Windows may differ)
$locale['charset'] = "windows-1257";
$locale['tinymce'] = "lt";

// Full & Short Months
$locale['months'] = "&nbsp|Sausis|Vasaris|Kovas|Balandis|Geguþë|Birþelis|Liepa|Rugpjûtis|Rugsëjis|Spalis|Lapkritis|Gruodis";
$locale['shortmonths'] = "&nbsp|Sau|Vas|Kov|Bal|Geg|Bir|Lie|Rug|Rgs|Spa|Lap|Gru";

// Standard User Levels
$locale['user0'] = "Sveèias";
$locale['user1'] = "Narys";
$locale['user2'] = "Administratorius";
$locale['user3'] = "Super administratorius";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderatorius";
// Navigation
$locale['001'] = "Navigacija";
$locale['002'] = "Nëra apibûdintø nuorodø";
$locale['003'] = "Tik nariams";
$locale['004'] = "Ðiai panelei nëra turinio";
// Users Online
$locale['010'] = "Vartotojø tinkle";
$locale['011'] = "Sveèiø prisijungta: ";
$locale['012'] = "Nariø prisijungta: ";
$locale['013'] = "Nëra prisijungusiø nariø";
$locale['014'] = "Registruoti nariai: ";
$locale['015'] = "Neaktyvûs nariai: ";
$locale['016'] = "Naujausias narys: ";
// Sidebar
$locale['020'] = "Forumo praneðimai";
$locale['021'] = "Nauji praneðimai";
$locale['022'] = "Populiariø praneðimø";
$locale['023'] = "Paskutiniai straipsniai";
// Welcome Title & Forum List
$locale['024'] = "Pasveikinimas";
$locale['025'] = "Paskutiniai aktyvûs forumo praneðimai";
$locale['026'] = "Mano paskutinis praneðimas";
$locale['027'] = "Mano paskutinis þinutë";
$locale['035'] = "Tema";
$locale['036'] = "Þinutë";
$locale['037'] = "Jus neturite jokiø temø forume.";
$locale['038'] = "Jus neturite jokiø praneðimø forume.";
$locale['030'] = "Forumas";
$locale['031'] = "Praneðimas";
$locale['032'] = "Þiûrëta";
$locale['033'] = "Atsakymai";
$locale['034'] = "Paskutinis praneðimas";
// News & Articles
$locale['040'] = "Paraðë ";
$locale['041'] = "nuo ";
$locale['042'] = "Skaityti daugiau";
$locale['043'] = " Komentarai";
$locale['044'] = " Skaityta";
$locale['045'] = "Spausdinti";
$locale['046'] = "Naujienos";
$locale['047'] = "Kol kas naujienø nëra";
$locale['048'] = "Redaguoti";
// Prev-Next Bar
$locale['050'] = "Ankst";
$locale['051'] = "Sek";
$locale['052'] = "Puslapis ";
$locale['053'] = " ið ";
// User Menu
$locale['060'] = "Registravimasis";
$locale['061'] = "Vartotojas";
$locale['062'] = "Slaptaþodis";
$locale['063'] = "Atsiminti mane";
$locale['064'] = "Prisijungti";
$locale['065'] = "Dar ne narys?<br><a href='".BASEDIR."register.php' class='side'><b>Registruotis</b></a>";
$locale['066'] = "Pamirðai slaptaþodá?<a href='".BASEDIR."lostpassword.php' class='side'><br><b>Uþklausk</b></a>";
//
$locale['080'] = "Redaguoti profilá";
$locale['081'] = "Asmeninës þinutës";
$locale['082'] = "Nariø sàraðas";
$locale['083'] = "Administracijos panelë";
$locale['084'] = "Atsijungti";
$locale['085'] = "Jûs turite %u naujas ";
$locale['086'] = "þinutæ";
$locale['087'] = "þinutes";
// Poll
$locale['100'] = "Apklausa";
$locale['101'] = "Áskaièiuoti balsà";
$locale['102'] = "Norëdamas balsuoti turite prisijungti.";
$locale['103'] = "Balsuoti";
$locale['104'] = "Balsai";
$locale['105'] = "Balsai: ";
$locale['106'] = "Pradëtas: ";
$locale['107'] = "Baigtas: ";
$locale['108'] = "Apklausø archyvas";
$locale['109'] = "Pasirinkti apklausà perþiûrai ið sàraðo:";
$locale['110'] = "Þiûrëti";
$locale['111'] = "Perþiûrëti apklausà";
// Shoutbox
$locale['120'] = "Mini-Èatas";
$locale['121'] = "Vardas:";
$locale['122'] = "Þinutë:";
$locale['123'] = "Sakyti";
$locale['124'] = "Pagalba";
$locale['125'] = "Jei norite raðyti þinutes, turite prisijungti.";
$locale['126'] = "Mini-Èato archyvas";
$locale['127'] = "Nëra naujø þinuèiø.";
// Footer Counter
$locale['140'] = "Unikalus apsilankymas";
$locale['141'] = $settings['sitename']." Unikaliø apsilankymø";
// Admin Navigation
$locale['150'] = "Administracija";
$locale['151'] = "Gráþti á Saità";
$locale['152'] = "Administracijos panelës";
// Miscellaneous
$locale['190'] = "Palaikymo reþimas aktyvuotas";
$locale['191'] = "Jûsø IP adresas juodàjame sàraðe.";
$locale['192'] = "Atsijungti kaip ";
$locale['193'] = "Prisijungti kaip ";
$locale['194'] = "Jûsø dalyvavimas sustabdytas.";
$locale['195'] = "Ðis prisijungimas neaktyvuotas.";
$locale['196'] = "Neteisingas vardas arba slaptaþodis.";
$locale['197'] = "Palaukite, kol mes atidarinëjame...<br><br>
[ <a href='index.php'>Arba spauskite, jei nenorite laukti</a> ]";
$locale['198'] = "<b>Dëmesio:</b> aptiktas setup.php, nedelsiant já iðtrinkite";
?>