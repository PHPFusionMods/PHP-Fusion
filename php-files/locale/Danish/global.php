<?php
/*
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
*/

// Locale Settings
setlocale(LC_ALL, "danish","DK"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";

// Full & Short Months
$locale['months'] = "&nbsp|Januar|Februar|Marts|April|Maj|Juni|Juli|August|September|Oktober|November|December";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Mar|Apr|Maj|Jun|Jul|Aug|Sep|Okt|Nov|Dec";

//tinyMCE
$locale['tinymce'] = "da";
$locale['phpmailer'] = "dk";

// Standard User Levels
$locale['user0'] = "Gæster";
$locale['user1'] = "Brugere";
$locale['user2'] = "Administratorer";
$locale['user3'] = "Super Administrator";
// Forum Moderator Level(s)
$locale['userf1'] = "Ordstyrer";
// Navigation
$locale['001'] = "Navigation";
$locale['002'] = "Der er ikke oprettet links\n";
$locale['003'] = "Kun for registrerede brugere";
$locale['004'] = "Der er intet indhold i dette element endnu";
// Users Online
$locale['010'] = "Besøgende";
$locale['011'] = "Gæster online: ";
$locale['012'] = "Brugere online: ";
$locale['013'] = "Ingen brugere online";
$locale['014'] = "Antal brugere: ";
$locale['015'] = "Ikke aktiverede brugere: ";
$locale['016'] = "Nyeste bruger: ";

// Sidebar
$locale['020'] = "Debatemner";
$locale['021'] = "Nyeste emne";
$locale['022'] = "Mest aktive emner";
$locale['023'] = "Nyeste artikler";
$locale['024'] = "Velkommen";
$locale['025'] = "Sidst aktive debatter";
$locale['026'] = "Mine nyeste emner";
$locale['027'] = "Mine nyeste indlæg";
$locale['028'] = "Nye indlæg";

// Welcome Title & Forum List
$locale['030'] = "Debat";
$locale['031'] = "Emne";
$locale['032'] = "Fremvisninger";
$locale['033'] = "Svar";
$locale['034'] = "Sidste indlæg";
$locale['035'] = "Emne";
$locale['036'] = "Offentliggjort";
$locale['037'] = "Du har endnu ikke oprettet emner.";
$locale['038'] = "Du har endnu ikke skrevet indlæg.";
$locale['039'] = "Der er lavet %u nye indlæg siden dit sidste besøg.";

// News & Articles
$locale['040'] = "Skrevet af ";
$locale['041'] = "d. ";
$locale['042'] = "Læs mere";
$locale['043'] = " Kommentar(er)";
$locale['044'] = " Fremvisninger";
$locale['045'] = "Udskriv";
$locale['046'] = "Nyheder";
$locale['047'] = "Der er ikke oprettet nyheder endnu";
$locale['048'] = "Rediger";

// Prev-Next Bar
$locale['050'] = "Foregående";
$locale['051'] = "Næste";
$locale['052'] = "Side ";
$locale['053'] = " af ";
// User Menu
$locale['060'] = "Indlogning";
$locale['061'] = "Brugernavn";
$locale['062'] = "Kodeord";
$locale['063'] = "Husk mig";
$locale['064'] = "Log ind";
$locale['065'] = "Er du ikke registreret bruger endnu?<br><a href='".BASEDIR."register.php' class='side'>Klik her</a> for at blive det.";
$locale['066'] = "Har du glemt dit kodeord?<br>Få tilsendt et nyt ved at klikke <a href='".BASEDIR."lostpassword.php' class='side'>her</a>.";
//
$locale['080'] = "Rediger profil";
$locale['081'] = "Beskeder";
$locale['082'] = "Brugerliste";
$locale['083'] = "Administration";
$locale['084'] = "Log ud";
$locale['085'] = "Der er %u <br>";
$locale['086'] = "ny besked til dig";
$locale['087'] = "nye beskeder til dig";
// Poll
$locale['100'] = "Brugerafstemning";
$locale['101'] = "Stem";
$locale['102'] = "Du er nødt til at logge på for at stemme.";
$locale['103'] = "Stemme";
$locale['104'] = "Stemmer";
$locale['105'] = "Stemmer: ";
$locale['106'] = "Påbegyndt: ";
$locale['107'] = "Afsluttet: ";
$locale['108'] = "Afstemningsarkiv";
$locale['109'] = "Vælg en afstemning fra listen:";
$locale['110'] = "Se";
$locale['111'] = "Se afstemning";
// Shoutbox
$locale['120'] = "Replikboks";
$locale['121'] = "Navn:";
$locale['122'] = "Replik:";
$locale['123'] = "Gem";
$locale['124'] = "Hjælp";
$locale['125'] = "Du er nødt til at logge på for at skrive en replik.";
$locale['126'] = "Replikarkiv";
$locale['127'] = "Der er endnu ikke skrevet replikker.";

// Footer Counter
$locale['140'] = "Unikt besøg";
$locale['141'] = "Unikke besøg";

// Admin Navigation
$locale['150'] = "Administration";
$locale['151'] = "Forside";
$locale['152'] = "Administration";

// Miscellaneous
$locale['190'] = "Vedligeholdelsestilstand er aktiveret";
$locale['191'] = "Din IP adresse er aktuelt udelukket.";
$locale['192'] = "Logger ud som ";
$locale['193'] = "Logger ind som ";
$locale['194'] = "Denne konto er aktuelt lukket.";
$locale['195'] = "Denne konto er endnu ikke aktiveret.";
$locale['196'] = "Forkert brugernavn eller kodeord.";
$locale['197'] = "Vent et øjeblik, mens vi overfører dig ...<br><br>
[ <a href='index.php'>Eller klik her, hvis du ikke ønsker at vente</a> ]";
$locale['198'] = "<b>Advarsel:</b> Du har glemt at slette setup.php. Slet den med det samme!";
?>
