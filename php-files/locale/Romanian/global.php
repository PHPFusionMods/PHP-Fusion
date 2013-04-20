<?php
/*
Romanian Language Fileset
Produced by Forum IT
Email: office@forumit.ro
Web: http://www.forumit.ro/
*/

// Locale Settings
setlocale(LC_TIME, "ro","RO"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-2";
$locale['tinymce'] = "ro";
$locale['phpmailer'] = "ro";

// Full & Short Months
$locale['months'] = "&nbsp|Ianuarie|Februarie|Martie|Aprilie|Mai|Iunie|Iulie|August|Septembrie|Octombrie|Noiembrie|Decembrie";
$locale['shortmonths'] = "&nbsp|Ian|Feb|Mar|Apr|Mai|Iun|Iul|Aug|Sept|Oct|Nov|Dec";

// Standard User Levels
$locale['user0'] = "Public";
$locale['user1'] = "Membru";
$locale['user2'] = "Administrator";
$locale['user3'] = "Super Administrator";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Navigare";
$locale['002'] = "Nu exist&#259; leg&#259;turi definite\n";
$locale['003'] = "Doar pentru membri";
$locale['004'] = "&#206;nc&#259; nu exist&#259; con&#355;inut pentru acest panou";
// Users Online
$locale['010'] = "Utilizatori conecta&#355;i";
$locale['011'] = "Vizitatori conecta&#355;i: ";
$locale['012'] = "Membri conecta&#355;i: ";
$locale['013'] = "Nici un membru conectat";
$locale['014'] = "Membri &#238;nregistra&#355;i: ";
$locale['015'] = "Membri dezactiva&#355;i: ";
$locale['016'] = "Cel mai nou membru: ";
// Sidebar & Other Titles
$locale['020'] = "Discu&#355;ii pe forum";
$locale['021'] = "Cele mai noi discu&#355;ii";
$locale['022'] = "Cele mai active discu&#355;ii";
$locale['023'] = "Cele mai noi articole";
$locale['024'] = "Bun venit";
$locale['025'] = "Cele mai noi discu&#355;ii active pe forum";
$locale['026'] = "Discu&#355;iile mele recente";
$locale['027'] = "Post&#259;rile mele recente";
$locale['028'] = "Post&#259;ri noi";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Discu&#355;ie";
$locale['032'] = "Vizualiz&#259;ri";
$locale['033'] = "R&#259;spunsuri";
$locale['034'] = "Ultima postare";
$locale['035'] = "Subiect";
$locale['036'] = "Postat";
$locale['037'] = "&#206;nc&#259; nu ai pornit nici o discu&#355;ie pe forum.";
$locale['038'] = "&#206;nc&#259; nu ai postat nici un mesaj pe forum.";
$locale['039'] = "Exist&#259; %u noi post&#259;ri de la ultima ta vizit&#259;.";
// News & Articles
$locale['040'] = "Postat de ";
$locale['041'] = "la ";
$locale['042'] = "Cite&#351;te mai mult";
$locale['043'] = " Comentarii";
$locale['044'] = " Vizualiz&#259;ri";
$locale['045'] = "Imprim&#259;";
$locale['046'] = "&#350;tiri";
$locale['047'] = "&#206;nc&#259; nu au fost postate &#351;tiri";
$locale['048'] = "Editare";
// Prev-Next Bar
$locale['050'] = "&#206;napoi";
$locale['051'] = "&#206;nainte";
$locale['052'] = "Pagina ";
$locale['053'] = " din ";
// User Menu
$locale['060'] = "Conectare";
$locale['061'] = "Utilizator";
$locale['062'] = "Parol&#259;";
$locale['063'] = "Re&#355;ine-m&#259;";
$locale['064'] = "Conectare";
$locale['065'] = "&#206;nc&#259; nu e&#351;ti membru?<br><a href='".BASEDIR."register.php' class='side'>Apas&#259; aici</a> pentru &#238;nregistrare.";
$locale['066'] = "&#354;i-ai uitat parola?<br>Solicit&#259; una nou&#259; <a href='".BASEDIR."lostpassword.php' class='side'>aici</a>.";
//
$locale['080'] = "Editare profil";
$locale['081'] = "Mesaje private";
$locale['082'] = "List&#259; membri";
$locale['083'] = "Panoul de administrare";
$locale['084'] = "Deconectare";
$locale['085'] = "Ai %u ";
$locale['086'] = "mesaj nou";
$locale['087'] = "mesaje noi";
// Poll
$locale['100'] = "Sondaj membri";
$locale['101'] = "Trimite vot";
$locale['102'] = "Trebuie s&#259; te conectezi pentru a vota.";
$locale['103'] = "Vot";
$locale['104'] = "Voturi";
$locale['105'] = "Voturi: ";
$locale['106'] = "Pornit: ";
$locale['107'] = "&#206;ncheiat: ";
$locale['108'] = "Arhiva sondaje";
$locale['109'] = "Alege un sondaj pentru vizualizare &#238;n list&#259;:";
$locale['110'] = "Vizualizare";
$locale['111'] = "Vizualizare sondaj";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Nume:";
$locale['122'] = "Mesaj:";
$locale['123'] = "Shout";
$locale['124'] = "Ajutor";
$locale['125'] = "Trebuie s&#259; fii conectat pentru a posta un mesaj.";
$locale['126'] = "Arhiv&#259; shoutbox";
$locale['127'] = "Nu exist&#259; mesaje postate.";
// Footer Counter
$locale['140'] = "vizit&#259; unic&#259;";
$locale['141'] = "vizite unice";
// Admin Navigation
$locale['150'] = "Index Administrare";
$locale['151'] = "Revenire la site";
$locale['152'] = "Panouri administrare";
// Miscellaneous
$locale['190'] = "Mod de &#238;ntre&#355;inere activat";
$locale['191'] = "Adresa ta de IP este &#238;n lista neagr&#259; momentan.";
$locale['192'] = "Deconectare cu utilizatorul: ";
$locale['193'] = "Conectare cu utilizatorul: ";
$locale['194'] = "Acest cont este momentan suspendat.";
$locale['195'] = "Acest cont &#238;nc&#259; nu a fost activat.";
$locale['196'] = "Nume sau parol&#259; incorecte.";
$locale['197'] = "Te rog a&#351;teapt&#259; transferul...<br><br>
[ <a href='index.php'>Sau apas&#259; aici dac&#259; nu dore&#351;ti s&#259; a&#351;tep&#355;i</a> ]";
$locale['198'] = "<b>Aten&#355;ie:</b> <span style='color:navy'>setup.php</span> detectat. Te rog &#351;terge-l imediat";
?>
