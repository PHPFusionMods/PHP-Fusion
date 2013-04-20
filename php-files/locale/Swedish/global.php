<?php
/*
Swedish Language Fileset
Produced by Happy Svensson (KEFF)
Email: keff@php-fusion.se
Web: http://www.php-fusion.se
*/

// Locale Settings
setlocale(LC_TIME, "swedish"); // Linuxsystem, Windows kan variera
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "sv";
$locale['phpmailer'] = "en";

//Full & Short Months
$locale['months'] = "&nbsp;|Januari|Februari|Mars|April|Maj|Juni|Juli|Augusti|September|Oktober|November|December";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Mar|Apr|Maj|Jun|Jul|Aug|Sept|Okt|Nov|Dec";

// Standard User Levels
$locale['user0'] = "Besökare";
$locale['user1'] = "Användare";
$locale['user2'] = "Administratör";
$locale['user3'] = "Superadministratör";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Navigation";
$locale['002'] = "Det finns inga länkar definierade";
$locale['003'] = "Endast för registrerade användare";
$locale['004'] = "Denna panel har inget innehåll än";
// Users Online
$locale['010'] = "Inloggade användare";
$locale['011'] = "Gäster: ";
$locale['012'] = "Inloggade användare: ";
$locale['013'] = "Inga användare inloggade";
$locale['014'] = "Antal registrerade användare: ";
$locale['015'] = "Inaktiverade användare: ";
$locale['016'] = "Senast registrerade användare: ";
// Sidebar
$locale['020'] = "Senaste debatter";
$locale['021'] = "Nyaste debatter";
$locale['022'] = "Populäraste debatter";
$locale['023'] = "Nyaste artiklar";
$locale['024'] = "Välkommen";
$locale['025'] = "Senaste aktiva debattämnen";
$locale['026'] = "Mina senaste debatter";
$locale['027'] = "Mina senaste inlägg";
$locale['028'] = "Nya inlägg";
// Forum List Texts
$locale['030'] = "Debattforum";
$locale['031'] = "Ämne";
$locale['032'] = "Antal visningar";
$locale['033'] = "Svar";
$locale['034'] = "Senaste inlägg";
$locale['035'] = "Ämne";
$locale['036'] = "Publicerat";
$locale['037'] = "Du har inte startat ett ämne ännu.";
$locale['038'] = "Du har inte postat några inlägg ännu.";
$locale['039'] = "Det finns %u nya inlägg postade sedan ditt senaste besök.";
// News & Articles
$locale['040'] = "Publicerad av ";
$locale['041'] = " datum: ";
$locale['042'] = "Läs mera";
$locale['043'] = " Kommentarer";
$locale['044'] = " Antal visningar";
$locale['045'] = "Skriv ut";
$locale['046'] = "Nyheter";
$locale['047'] = "Det finns inga nyheter publicerade ännu";
$locale['048'] = "Redigera";
// Prev-Next Bar
$locale['050'] = "Föregående";
$locale['051'] = "Nästa";
$locale['052'] = "Sida ";
$locale['053'] = " av ";
// User Menu
$locale['060'] = "Logga in";
$locale['061'] = "Användarnamn";
$locale['062'] = "Lösenord";
$locale['063'] = "Spara mitt lösenord";
$locale['064'] = "Logga in";
$locale['065'] = "Är du inte registrerad användare?<br><a href='".BASEDIR."register.php' class='side'>Klicka här</a> för att registrera dig.";
$locale['066'] = "Förlorat lösenordet? <br>Begär ett nytt <a href='".BASEDIR."lostpassword.php' class='side'>här</a>.";
//
$locale['080'] = "Redigera din profil";
$locale['081'] = "Privata meddelanden";
$locale['082'] = "Användarlista";
$locale['083'] = "Administrationspanel";
$locale['084'] = "Logga ut";
$locale['085'] = "Det finns %u ";
$locale['086'] = "nytt meddelande";
$locale['087'] = "nya meddelanden";
// Poll
$locale['100'] = "Omröstning";
$locale['101'] = "Rösta";
$locale['102'] = "Du måste logga in för att kunna rösta.";
$locale['103'] = "Röst";
$locale['104'] = "Röster";
$locale['105'] = "Röster: ";
$locale['106'] = "Påbörjad: ";
$locale['107'] = "Avslutad: ";
$locale['108'] = "Arkiv omröstningar";
$locale['109'] = "Välj en omröstning från listan:";
$locale['110'] = "Visa";
$locale['111'] = "Visa omröstning";
// Shoutbox
$locale['120'] = "Klotterplanket";
$locale['121'] = "Namn:";
$locale['122'] = "Meddelande:";
$locale['123'] = "Klottra!";
$locale['124'] = "Hjälp";
$locale['125'] = "Du måste logga in för att skriva ett meddelande.";
$locale['126'] = "Arkiv Klotterplanket";
$locale['127'] = "Inga meddelanden har skickats.";
// Footer Counter
$locale['140'] = "Unikt besök";
$locale['141'] = "Unika besök";
// Admin Navigation
$locale['150'] = "Administrationspanel";
$locale['151'] = "Tillbaka till huvudsidan";
$locale['152'] = "Administration";

// Miscellaneous
$locale['190'] = "Underhållsläge aktiverat";
$locale['191'] = "Ditt IP - nummer är f n spärrat.";
$locale['192'] = "Loggar ut som ";
$locale['193'] = "Loggar in som ";
$locale['194'] = "Detta användarkonto är f n avstängt.";
$locale['195'] = "Detta användarkonto är inte aktiverat.";
$locale['196'] = "Ogiltigt användarnamn eller lösenord.";
$locale['197'] = "Vänta medan du förflyttas...<br><br>
[ <a href='index.php'>Eller klicka här om du inte önskar vänta</a> ]";
$locale['198'] = "<b>Varning:</b> filen setup.php är kvar på servern, radera den omedelbart!";
?>
