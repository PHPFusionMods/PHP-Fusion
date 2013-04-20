<?php
$locale['400'] = "&#206;nregistrare";
$locale['401'] = "Activare cont";
// Registration Errors
$locale['402'] = "Trebuie s&#259; specifici un nume de utilizator, o parol&#259; &#351;i o adres&#259; de email.";
$locale['403'] = "Numele de utilizator con&#355;ine caractere nevalide.";
$locale['404'] = "Cele dou&#259; parole pe care le-ai introdus nu se potrivesc.";
$locale['405'] = "Parol&#259; nevalid&#259;. Folose&#351;te doar caractere alfa-numerice.<br>
Parolele trebuie s&#259; fie de minim 6 caractere lungime.";
$locale['406'] = "Adresa ta de email nu pare s&#259; fie valid&#259;.";
$locale['407'] = "Ne pare r&#259;u, dar numele de utilizator ".(isset($_POST['username']) ? $_POST['username'] : "")." este folosit.";
$locale['408'] = "Ne pare r&#259;u, dar adresa de email ".(isset($_POST['email']) ? $_POST['email'] : "")." este folosit&#259;.";
$locale['409'] = "Un cont inactiv a fost &#238;nregistrat cu adresa de email.";
$locale['410'] = "Cod de validare incorect.";
$locale['411'] = "Adresa sau domeniul t&#259;u de email este &#238;n lista neagr&#259;.";
// Email Message
$locale['449'] = "Bun venit la ".$settings['sitename'];
$locale['450'] = "Salut, ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bun venit la ".$settings['sitename'].". Aici sunt detaliile tale de conectare:\n
Utilizator: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Parola: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Te rog ACTIVEAZA-TI contul folosind legatura urmatoare:\n";
// Registration Success/Fail
$locale['451'] = "&#206;nregistrare finalizat&#259;";
$locale['452'] = "Acum te po&#355;i conecta.";
$locale['453'] = "Un administrator va activa contul t&#259;u &#238;n cel mai scurt timp.";
$locale['454'] = "&#206;nregistrarea ta este aproape complet&#259;. Vei primi un email care con&#355;ine detaliile tale de conectare, &#238;mpreun&#259; cu o leg&#259;tur&#259; pentru verificarea contului t&#259;u.";
$locale['455'] = "Contul t&#259;u a fost verificat.";
$locale['456'] = "&#206;nregistrare e&#351;uat&#259;";
$locale['457'] = "Trimiterea email-ului a e&#351;uat. Te rog contacteaz&#259; <a href='mailto:".$settings['siteemail']."'>administratorul site-ului</a>.";
$locale['458'] = "&#206;nregistrarea a e&#351;uat!";
$locale['459'] = "Te rog &#238;ncearc&#259; din nou";
// Register Form
$locale['500'] = "Te rog introdu detaliile mai jos. ";
$locale['501'] = "Un email de verificare va fi trimis la adresa de email pe care ai specificat-o. ";
$locale['502'] = "C&#226;mpurile marcate cu <span style='color:#ff0000;'>*</span> trebuie completate obligatoriu.
Numele t&#259;u de utilizator &#351;i parola sunt sensibile la caracterele mici &#351;i MARI.";
$locale['503'] = " Po&#355;i ad&#259;uga mai multe informa&#355;ii &#238;n <b>Editare profil</b>, de &#238;ndat&#259; ce te-ai conectat.
<br><br><b><span style='color:red'>ATEN&#354;IE</span>: Dac&#259; folose&#350;ti un cont de HOTMAIL sau YAHOO pentru &#238;nregistrare, filtrele folosite de aceste servicii de mesagerie ar putea clasifica mesajul de activare ca mesaj nesolicitat (SPAM), motiv pentru care te rug&#259;m s&#259; te ui&#355;i &#350;i &#238;n folderele JUNK MAIL sau BULK pentru mesajul de activare. &#206;&#355;i mul&#355;umim.</b>";
$locale['504'] = "Cod validare:";
$locale['505'] = "Introdu codul de validare:";
$locale['506'] = "&#206;nregistrare";
$locale['507'] = "Sistemul de &#238;nregistrare este dezactivat momentan.";
// Validation Errors
$locale['550'] = "Te rog specific&#259; un nume de utilizator.";
$locale['551'] = "Te rog specific&#259; o parol&#259;.";
$locale['552'] = "Te rog specific&#259; o adres&#259; de email.";
?>
