<?php
/*
Nederlandse Taalbestanden
Structuur door Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk
Vertaling door Paul Beuk (muscapaul)
Email: muscapaul@gmail.com
Web: http://www.phpfusion.nl === http://www.muscapaul.com
*/

// Locale instellingen
setlocale(LC_ALL, 'nld');
$locale['charset'] = "iso-8859-1";
// Wanneer de bovenstaande regels niet werken, probeer dan de onderstaande regels
// die werkten tot en met v5.01. Voeg daartoe commentaar tekens (// ) toe voor de
// bovenstaande regels en verwijder ze bij de betreffende onderstaande regels.
// Linux-server:
// setlocale(LC_TIME, "nl","NL");
// Unix-server:
// setlocale(LC_TIME, 'nl_NL');
// Windows-server:


// Volledige en afgekorte maanden
$locale['months'] = "&nbsp|Januari|Februari|Maart|April|Mei|Juni|Juli|Augustus|September|Oktober|November|December";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Maa|Apr|Mei|Jun|Jul|Aug|Sep|Okt|Nov|Dec";

//TinyMCE HTML editor
$locale['tinymce'] = "nl";
$locale['phpmailer'] = "nl";

// Standaard gebruikerniveaus
$locale['user0'] = "Alle publiek";
$locale['user1'] = "Lid";
$locale['user2'] = "Beheerder";
$locale['user3'] = "Superbeheerder";
// Forum moderatorniveau(s)
$locale['userf1'] = "Moderator";
// Navigatie
$locale['001'] = "Navigatie";
$locale['002'] = "Geen links gedefinieerd\n";
$locale['003'] = "Alleen voor leden";
$locale['004'] = "Er is nog geen inhoud voor dit paneel";
// Online gebruikers
$locale['010'] = "Gebruikers online";
$locale['011'] = "Gasten online: ";
$locale['012'] = "Leden online: ";
$locale['013'] = "Geen leden online";
$locale['014'] = "Aangemelde Leden: ";
$locale['015'] = "Niet-geactiveerde Leden: ";
$locale['016'] = "Nieuwste lid: ";
// Zijbalk
$locale['020'] = "Forumonderwerpen";
$locale['021'] = "Nieuwste onderwerpen";
$locale['022'] = "Actiefste onderwerpen";
$locale['023'] = "Laatste artikelen";
$locale['024'] = "Welkom";
$locale['025'] = "Laatst actieve forumonderwerpen";
$locale['026'] = "Mijn recente onderwerpen";
$locale['027'] = "Mijn recente berichten";
$locale['028'] = "Nieuwe berichten";
// Welkomsttitel en Forumlijst
$locale['030'] = "Forum";
$locale['031'] = "Onderwerp";
$locale['032'] = "keer bekeken";
$locale['033'] = "Antwoorden";
$locale['034'] = "Laatste bericht";
$locale['035'] = "Onderwerp";
$locale['036'] = "Geplaatst";
$locale['037'] = "U heeft nog geen nieuwe forumonderwerpen gestart";
$locale['038'] = "U heeft nog geen forumberichten geplaatst";
$locale['039'] = "Er zijn %u nieuwe forumberichten sinds uw vorige bezoek";
// Nieuws en Artikelen
$locale['040'] = "Geplaatst door ";
$locale['041'] = "op ";
$locale['042'] = "Lees meer";
$locale['043'] = " Reacties";
$locale['044'] = " keer gelezen";
$locale['045'] = "Afdrukken";
$locale['046'] = "Nieuws";
$locale['047'] = "Er is nog geen Nieuws geplaatst";
$locale['048'] = "Wijzigen";
// Vorige-Volgende-balk
$locale['050'] = "Vorige";
$locale['051'] = "Volgende";
$locale['052'] = "Pagina ";
$locale['053'] = " van ";
// Gebruikermenu
$locale['060'] = "Login";
$locale['061'] = "Gebruikernaam";
$locale['062'] = "Wachtwoord";
$locale['063'] = "Herinner mij";
$locale['064'] = "Login";
$locale['065'] = "Nog geen lid?<br><a href='".BASEDIR."register.php' class='side'>Klik hier</a> om aan te melden.";
$locale['066'] = "Wachtwoord vergeten?<br>Vraag <a href='".BASEDIR."lostpassword.php' class='side'>hier</a> om een nieuw wachtwoord.";
//
$locale['080'] = "Profiel wijzigen";
$locale['081'] = "Privé Berichten";
$locale['082'] = "Ledenlijst";
$locale['083'] = "Beheerder Paneel";
$locale['084'] = "Logout";
$locale['085'] = "U heeft %u ";
$locale['086'] = "nieuw bericht";
$locale['087'] = "nieuwe berichten";
// Poll
$locale['100'] = "Ledenenquête";
$locale['101'] = "Stemmen";
$locale['102'] = "U moet inloggen om te kunnen stemmen.";
$locale['103'] = "Stem";
$locale['104'] = "Stemmen";
$locale['105'] = "Stemmen: ";
$locale['106'] = "Gestart: ";
$locale['107'] = "Afgelopen: ";
$locale['108'] = "Enquête-archief";
$locale['109'] = "Kies een enquête uit de lijst om te bekijken:";
$locale['110'] = "Bekijken";
$locale['111'] = "Enquête bekijken";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Naam:";
$locale['122'] = "Bericht:";
$locale['123'] = "Shout";
$locale['124'] = "Help";
$locale['125'] = "U moet inloggen om een bericht te kunnen plaatsen.";
$locale['126'] = "Shoutboxarchief";
$locale['127'] = "Nog geen bericht geplaatst.";
// Footerteller
$locale['140'] = "Uniek bezoek";
$locale['141'] = "Unieke bezoeken";
// Beheerdernavigatie
$locale['150'] = "Beheerder Paneel";
$locale['151'] = "Terug naar Site";
$locale['152'] = "Beheerder Panelen";
// Diverse
$locale['190'] = "Onderhoudsmodus geactiveerd";
$locale['191'] = "Uw IP-adres staat op dit moment op de Zwarte Lijst.";
$locale['192'] = "Uitloggen van ";
$locale['193'] = "Inloggen van ";
$locale['194'] = "Deze account is op het moment geschorst.";
$locale['195'] = "Deze account is nog niet geactiveerd.";
$locale['196'] = "Ongeldige gebruikernaam of wachtwoord.";
$locale['197'] = "Even geduld a.u.b, u wordt doorgestuurd...<br><br>
[ <a href='index.php'>Of klik hier als u niet wenst te wachten</a> ]";
$locale['198'] = "<b>Waarschuwing:</b> setup.php gedetecteerd, onmiddellijk verwijderen a.u.b";
?>