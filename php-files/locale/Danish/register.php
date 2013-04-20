<?php
$locale['400'] = "Bliv registreret bruger";
$locale['401'] = "Aktiver konto";
// Registration Errors
$locale['402'] = "Du skal opgive et brugernavn, et kodeord og en mailadresse.";
$locale['403'] = "Brugernavnet indeholder forkerte karakterer.";
$locale['404'] = "De to kodeord er ikke identiske.";
$locale['405'] = "Forkert kodeord. Du må kun bruge alfanumeriske karakterer.<br>
Et kodeord skal være mindst 6 karakterer langt.";
$locale['406'] = "Det ser ud, som om der er fejl i din mailadresse.";
$locale['407'] = "Beklager, men brugernavnet ".(isset($_POST['username']) ? $_POST['username'] : "")." anvendes allerede.";
$locale['408'] = "Beklager, men mailadressen ".(isset($_POST['email']) ? $_POST['email'] : "")." anvendes allerede.";
$locale['409'] = "En ikke aktiveret konto er blevet oprettet med denne mailadresse.";
$locale['410'] = "Sikkerhedskoden er forkert.";
$locale['411'] = "Din mailadresse eller dit maildomæne er udelukket.";
// Email Message
$locale['449'] = "Velkommen som bruger på ".$settings['sitename'];
$locale['450'] = "Hej ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Velkommen som medlem på ".$settings['sitename'].". Her er dine pålogningsoplysninger:\n
Brugernavn: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Kodeord: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Vær så venlig at aktivere din konto ved at trykke på dette link:\n";
// Registration Success/Fail
$locale['451'] = "Oprettelse gennemført";
$locale['452'] = "Du kan nu logge på.";
$locale['453'] = "En administrator vil aktivere din konto meget snart.";
$locale['454'] = "Din tilmelding er næsten gennemført. Du vil modtage en email med dine pålogningsoplysninger og et link, så du kan aktivere din brugerkonto.";
$locale['455'] = "Din tilmelding er godkendt.";
$locale['456'] = "Brugeroprettelse kunne ikke gennemføres";
$locale['457'] = "Vi kunne ikke sende dig en mail. Kontakt <a href='mailto:".$settings['siteemail']."'>sidens administrator</a>.";
$locale['458'] = "Brugeroprettelse gik galt af følgende årsag(er):";
$locale['459'] = "Prøv igen";
// Register Form
$locale['500'] = "Indskriv de nødvendige oplysninger herunder. ";
$locale['501'] = "En email vil blive sendt til den adresse, du har opgivet. ";
$locale['502'] = "Felter markeret med <span style='color:#ff0000;'>*</span> skal udfyldes.
Der skelnes mellem store og små bogstaver i brugernavn og kodeord.";
$locale['503'] = " Du kan tilføje informationer ved at åbne Rediger profil, når du først er logget på.";
$locale['504'] = "Sikkerhedskode:";
$locale['505'] = "Skriv sikkerhedskode:";
$locale['506'] = "Opret bruger";
$locale['507'] = "Brugeroprettelse er slået fra i øjeblikket.";
// Validation Errors
$locale['550'] = "Du skal angive et brugernavn.";
$locale['551'] = "Du skal angive et kodeord.";
$locale['552'] = "Du skal angive en mailadresse.";
?>
