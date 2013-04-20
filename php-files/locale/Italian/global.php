<?php
/*
Italian Language Fileset
Produced by Php-Fusion.it Community

Email:
scurippio@gmail.com
gameactionadmin@gmail.com


Web:
http://www.php-fusion.it/
http://www.gameaction.altervista.org/
*/

// Locale Settings
setlocale(LC_TIME, "it","IT"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "it";
$locale['phpmailer'] = "it";

// Full & Short Months
setlocale(LC_ALL, 'it_IT' ); 
$locale['months'] = "&nbsp|Gennaio|Febbraio|Marzo|Aprile|Maggio|Giugno|Luglio|Agosto|Settembre|Ottobre|Novembre|Dicembre";
$locale['shortmonths'] = "&nbsp|Gen|Feb|Mar|Apr|Mag|Giun|Lugl|Ago|Sett|Ott|Nov|Dic";

// Standard User Levels
$locale['user0'] = "Ospite";
$locale['user1'] = "Membro";
$locale['user2'] = "Amministratore";
$locale['user3'] = "Super Amministratore";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderatore";
// Navigation
$locale['001'] = "Navigazione";
$locale['002'] = "Nessun collegamento definito";
$locale['003'] = "Solo Membri";
$locale['004'] = "Nessun contenuto per il pannello";
// Users Online
$locale['010'] = "Utenti Online";
$locale['011'] = "Ospiti Online: ";
$locale['012'] = "Membri Online: ";
$locale['013'] = "Nessun Membro Online";
$locale['014'] = "Membri Registrati: ";
$locale['015'] = "Membri Non Attivati: ";
$locale['016'] = "Ultimo Membro: ";
// Sidebar & Other Titles
$locale['020'] = "Discussioni Forum";
$locale['021'] = "Nuove Discussioni";
$locale['022'] = "Discussioni più viste";
$locale['023'] = "Ultimi Articoli";
$locale['024'] = "Benvenuto";
$locale['025'] = "Ultime Discussioni Attive";
$locale['026'] = "Le Mie Discussioni";
$locale['027'] = "I Miei Messaggi";
$locale['028'] = "Nuovi Messaggi";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Discussione";
$locale['032'] = "Vista";
$locale['033'] = "Risposte";
$locale['034'] = "Ultimo Messaggio";
$locale['035'] = "Oggetto";
$locale['036'] = "Scritto";
$locale['037'] = "Non hai ancora cominciato una discussione";
$locale['038'] = "Non hai ancora scritto alcun messaggio";
$locale['039'] = "Ci sono %u messaggi dalla tua ultima visita";
// News & Articles
$locale['040'] = "Scritto da ";
$locale['041'] = "il ";
$locale['042'] = "Leggi tutto";
$locale['043'] = " Commenti";
$locale['044'] = " Letture";
$locale['045'] = "Stampa";
$locale['046'] = "News";
$locale['047'] = "Non sono state scritte News";
$locale['048'] = "Modifica";
// Prev-Next Bar
$locale['050'] = "Prec";
$locale['051'] = "Succ";
$locale['052'] = "Pagina ";
$locale['053'] = " di ";
// User Menu
$locale['060'] = "Login";
$locale['061'] = "Nome Utente";
$locale['062'] = "Password";
$locale['063'] = "Ricorda";
$locale['064'] = "Login";
$locale['065'] = "Non sei ancora un membro?<br><a href='".BASEDIR."register.php' class='side'>Clicca qui</a> per registrarti.";
$locale['066'] = "Dimenticata la password?<br>Richiedine una nuova <a href='".BASEDIR."lostpassword.php' class='side'>qui</a>.";
//
$locale['080'] = "Modifica Profilo";
$locale['081'] = "Messaggi Privati";
$locale['082'] = "Lista Membri";
$locale['083'] = "Pannello Amministratore";
$locale['084'] = "Logout";
$locale['085'] = "Hai %u ";
$locale['086'] = "messaggio";
$locale['087'] = "messaggi";
// Poll
$locale['100'] = "Sondaggi";
$locale['101'] = "Invia Voto";
$locale['102'] = "Devi loggarti per votare";
$locale['103'] = "Voti";
$locale['104'] = "Voti";
$locale['105'] = "Voti: ";
$locale['106'] = "Iniziato: ";
$locale['107'] = "Finito: ";
$locale['108'] = "Archivio Sondaggi";
$locale['109'] = "Seleziona un Sondaggio da vedere dalla lista:";
$locale['110'] = "Vedi";
$locale['111'] = "Vedi Sondaggio";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Nome:";
$locale['122'] = "Messaggio:";
$locale['123'] = "Scrivi";
$locale['124'] = "Aiuto";
$locale['125'] = "Devi loggarti per scrivere un messaggio";
$locale['126'] = "Archivio Shoutbox";
$locale['127'] = "Non sono stati scritti messaggi";
// Footer Counter
$locale['140'] = "Visita";
$locale['141'] = "Visite";
// Admin Navigation
$locale['150'] = "Home Page Amministratore";
$locale['151'] = "Ritorna al Sito";
$locale['152'] = "Pannello Amministratore";
// Miscellaneous
$locale['190'] = "Modalità Manutenzione Attivata";
$locale['191'] = "Il tuo indirizzo IP è disabilitato";
$locale['192'] = "Logging out come ";
$locale['193'] = "Logging in come ";
$locale['194'] = "Questo account è stato sospeso";
$locale['195'] = "Questo account non è stato attivato";
$locale['196'] = "Nome utente o password non validi.";
$locale['197'] = "Aspetta mentre sei reindirizzato...<br><br>
[ <a href='index.php'>Clicca qui se non vuoi attendere</a> ]";
$locale['198'] = "<b>Attenzione:</b> setup.php trovato, cancellalo immediatamente";
?>