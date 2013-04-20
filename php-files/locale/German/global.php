<?php
/*
Deutsche Sprachdatei
*/

// Locale Settings
setlocale(LC_TIME, "de_DE@euro", "de", "DE"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "de";
$locale['phpmailer'] = "de";

// Full & Short Months
$locale['months'] = "&nbsp|Januar|Februar|März|April|Mai|Juni|Juli|August|September|Oktober|November|Dezember";
$locale['shortmonths'] = "&nbsp|Jan|Feb|Mär|Apr|Mai|Jun|Jul|Aug|Sept|Okt|Nov|Dez";

// Standard User Levels
$locale['user0'] = "Öffentlich";
$locale['user1'] = "Mitglied";
$locale['user2'] = "Administrator";
$locale['user3'] = "Super Administrator";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Navigation";
$locale['002'] = "Es sind keine Links definiert.\n";
$locale['003'] = "Nur für Mitglieder";
$locale['004'] = "Für dieses Panel ist kein Inhalt definiert.";
// Users Online
$locale['010'] = "Benutzer Online";
$locale['011'] = "Gäste Online: ";
$locale['012'] = "Mitglieder Online: ";
$locale['013'] = "Keine Mitglieder Online";
$locale['014'] = "Registrierte Mitglieder: ";
$locale['015'] = "Unaktivierte Mitglieder: ";
$locale['016'] = "Neustes Mitglied: ";
// Sidebar & Other Titles
$locale['020'] = "Forum Themen";
$locale['021'] = "Neuste Themen";
$locale['022'] = "Heißeste Themen";
$locale['023'] = "Letzer Artikel";
$locale['024'] = "Willkommen";
$locale['025'] = "Letztes aktives Forum Thema";
$locale['026'] = "Meine letzten Themen";
$locale['027'] = "Meine letzten Beiträge";
$locale['028'] = "Neue Beiträge";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Thema";
$locale['032'] = "Angesehen";
$locale['033'] = "Antworten";
$locale['034'] = "Letzter Beitrag";
$locale['035'] = "Betreff";
$locale['036'] = "Geschrieben";
$locale['037'] = "Du hast noch keine Forenthemen gestartet.";
$locale['038'] = "Du hast noch keine Forum Beiträge geschrieben.";
$locale['039'] = "Es gibt %u neue Beiträge seit deinem letzten Besuch.";
// News & Articles
$locale['040'] = "Geschrieben von ";
$locale['041'] = "am ";
$locale['042'] = "Mehr Lesen";
$locale['043'] = " Kommentare";
$locale['044'] = " gelesen";
$locale['045'] = "Drucken";
$locale['046'] = "News";
$locale['047'] = "Es wurden noch keine News geschrieben.";
$locale['048'] = "Bearbeiten";
// Prev-Next Bar
$locale['050'] = "Vorheriges";
$locale['051'] = "Nächstes";
$locale['052'] = "Seite ";
$locale['053'] = " von ";
// User Menu
$locale['060'] = "Login";
$locale['061'] = "Username";
$locale['062'] = "Passwort";
$locale['063'] = "Erinnere mich";
$locale['064'] = "Login";
$locale['065'] = "Noch kein Mitglied?<br><a href='".BASEDIR."register.php' class='side'>Klicke hier</a> um dich zu registrieren.";
$locale['066'] = "Passwort vergessen?<br>Fordere <a href='".BASEDIR."lostpassword.php' class='side'>Hier</a> ein neues an";
//
$locale['080'] = "Profil bearbeiten";
$locale['081'] = "Private Nachrichten";
$locale['082'] = "Mitgliederliste";
$locale['083'] = "Admin Bereich";
$locale['084'] = "Logout";
$locale['085'] = "Du hast %u neue ";
$locale['086'] = "Nachricht";
$locale['087'] = "Nachrichten";
// Poll
$locale['100'] = "Mitgliederstimme";
$locale['101'] = "Vote absenden";
$locale['102'] = "Du musst dich einloggen, um zu voten.";
$locale['103'] = "Vote";
$locale['104'] = "Votes";
$locale['105'] = "Votes: ";
$locale['106'] = "Gestartet: ";
$locale['107'] = "Beendet: ";
$locale['108'] = "Umfragenarchiv";
$locale['109'] = "Wähle eine Umfrage zum ansehen aus:";
$locale['110'] = "Anzeigen";
$locale['111'] = "Zeige Abstimmung";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Name:";
$locale['122'] = "Nachricht:";
$locale['123'] = "Shout";
$locale['124'] = "Hilfe";
$locale['125'] = "Du musst Dich einloggen, um eine Nachricht zu schreiben.";
$locale['126'] = "Shoutbox Archiv";
$locale['127'] = "Es wurden keine Shouts gepostet.";
// Footer Counter
$locale['140'] = "Eindeutiger Besuch";
$locale['141'] = "Eindeutige Besuche";
// Admin Navigation
$locale['150'] = "Admin Startseite";
$locale['151'] = "Zurück zur Seite";
$locale['152'] = "Admin Panels";
// Miscellaneous
$locale['190'] = "Wartungsmodus ist aktiviert.";
$locale['191'] = "Deine IP steht auf der Blacklist.";
$locale['192'] = "Logout für ";
$locale['193'] = "Login für ";
$locale['194'] = "Dieser Account ist zurzeit gesperrt.";
$locale['195'] = "Dieser Account wurde noch nicht aktiviert.";
$locale['196'] = "Ungültiger Benutzername oder falsches Passwort.";
$locale['197'] = "Bitte warte, während Du weitergeleitet wirst...<br><br>
[ <a href='index.php'>Oder klicke hier, wenn Du nicht warten willst.</a> ]";
$locale['198'] = "<b>Warnung:</b> Die setup.php wurde gefunden. Bitte sofort löschen!";
?>