<?php
$locale['400'] = "Registracija";
$locale['401'] = "Aktyvuoti prisijungimà";
// Registration Errors
$locale['402'] = "Turite áraðyti vartotojo vardà, slaptaþodá, bei e-paðto adresà.";
$locale['403'] = "Vartotojo varde yra neleistinø simboliø.";
$locale['404'] = "Abu slaptaþodþiai nesutampa.";
$locale['405'] = "Neteisingas slaptaþodis, naudokite tik raides ir skaièius.<br>
Slaptaþodá turi sudaryti maþiausiai 6 simboliai.";
$locale['406'] = "Jûsø e-paðto adresas negaliojantis.";
$locale['407'] = "Atleiskit, toks vardas ".(isset($_POST['username']) ? $_POST['username'] : "")." jau naudojamas.";
$locale['408'] = "Atleiskit toks e-paðto adresas ".(isset($_POST['email']) ? $_POST['email'] : "")." jau naudojamas.";
$locale['409'] = "Ðiuo e-paðto adresu prisiregistravo neaktyvus vartotojas.";
$locale['410'] = "Neteisingas patvirtinimo kodas.";
$locale['411'] = "Jûsø e-paðto adresas arba e-paðto domenas yra átrauktas á juodàjá sàraðà.";
// Email Message
$locale['449'] = "Sveiki prisijungæ prie ".$settings['sitename'];
$locale['450'] = "Sveiki ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
atvykæ á ".$settings['sitename'].". Jûsø prisijungimo duomenys:\n
Vartotojo vardas: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Slaptaþodis: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Aktyvuokite savo prisijungimà paspausdami ant ðios nuorodos:\n";
// Registration Success/Fail
$locale['451'] = "Registracija baigta";
$locale['452'] = "Dabar galite prisijungti.";
$locale['453'] = "Administratorius per trumpà laikà aktyvuos jûsø prisijungimà.";
$locale['454'] = "Jûsø registracija beveik baigta, dabar jûs gausite e-paðto þinutæ su prisijungimo duomenimis, bei nuoroda, kurià paspaudæ, aktyvuosite savo prisijungimà.";
$locale['455'] = "Jûsø prisijungimas patvirtintas.";
$locale['456'] = "Registracija nutraukta";
$locale['457'] = "Ávyko klaida, siunèiant e-paðtà, praðome susisiekti su <a href='mailto:".$settings['siteemail']."'>tinklapio administracija</a>.";
$locale['458'] = "Registracija nutraukta dël sekanèios(iø) prieþasties(èiø):";
$locale['459'] = "Bandykite dar kartà!";
// Register Form
$locale['500'] = "Praðome ávesti jûsø duomenis. ";
$locale['501'] = "Patvirtinimas paðtu bus nusiøstas ðiuo paðto adresu, kurá èia nurodëte. ";
$locale['502'] = "Paþymëti laukeliai <span style='color:#ff0000;'>*</span> turi bûti uþpildyti.
Jûsø vartotojo vardas ir slaptaþodis yra labai svarbûs.";
$locale['503'] = " Papildomà informacijà galësite pridëti, kai prisijungsite ir nueisite á panelæ <b>Redaguoti apraðymà.</b>";
$locale['504'] = "Patvirtinimo kodas:";
$locale['505'] = "Áveskite patvirtinimo kodà:";
$locale['506'] = "Registruotis";
$locale['507'] = "Registracijos sistema laikinai iðjungta.";
// Validation Errors
$locale['550'] = "Praðom ávesti vartotojo vardà.";
$locale['551'] = "Praðom ávesti slaptaþodá.";
$locale['552'] = "Praðom ávesti e-paðto adresà.";
?>