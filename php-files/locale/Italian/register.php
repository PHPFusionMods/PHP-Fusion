<?php
$locale['400'] = "Registrati";
$locale['401'] = "Attiva Account";
// Registration Errors
$locale['402'] = "Devi specificare Nome Utente, Password e indirizzo Email.";
$locale['403'] = "Il Nome Utente contiene caratteri non validi.";
$locale['404'] = "Le due Password non coinciono.";
$locale['405'] = "Password non valida, usa solo numeri e lettere.<br>
La password deve essere lunga almeno 6 caratteri.";
$locale['406'] = "Indirizzo Email non valido.";
$locale['407'] = "Il nome utente ".(isset($_POST['username']) ? $_POST['username'] : "")." è gia in uso.";
$locale['408'] = "L'indirizzo Email ".(isset($_POST['email']) ? $_POST['email'] : "")." è gia in uso.";
$locale['409'] = "Un account non attivo è stato registrato con l'indirizzo Email.";
$locale['410'] = "Codice di conferma non valido.";
$locale['411'] = "Il tuo indirizzo Email o Dominio email non può essere utilizzato.";
// Email Message
$locale['449'] = "Benvenuto su ".$settings['sitename'];
$locale['450'] = "Ciao ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Benvenuto su ".$settings['sitename'].". Ecco i tuoi dati per il login:\n
Nome Utente: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Password: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Attiva il tuo account tramite il seguente link:\n";
// Registration Success/Fail
$locale['451'] = "Registrazione completata";
$locale['452'] = "Ora puoi fare il login.";
$locale['453'] = "Un amministratore attiverà il tuo account a breve.";
$locale['454'] = "La tua registrazione è quasi completa, riceverai una Email contenente i dati per il login e per attivare l'account.";
$locale['455'] = "Il tuo account è stato verificato.";
$locale['456'] = "Registrazione Fallita";
$locale['457'] = "Invio Email fallito, contatta <a href='mailto:".$settings['siteemail']."'>l'Amministratore</a>.";
$locale['458'] = "Registrazione fallita per i seguenti motivi:";
$locale['459'] = "Prego riprova";
// Register Form
$locale['500'] = "Inserisci i tuoi dati. ";
$locale['501'] = "Una Email di verifica verrà inviata all'indirizzo Email da te specificato. ";
$locale['502'] = "I campi segnati <span style='color:#ff0000;'>*</span> devono essere compilati.
Nome Utente e Password sono case-sensitive.";
$locale['503'] = " Puoi inserire ulteriori informazioni andando su Modifica Profilo dopo aver fatto il login.";
$locale['504'] = "Codice di Conferma:";
$locale['505'] = "Inserisci Codice di Conferma:";
$locale['506'] = "Registrati";
$locale['507'] = "Il sistema di Registrazione è disabilitato.";
// Validation Errors
$locale['550'] = "Devi specificare un Nome Utente.";
$locale['551'] = "Devi specificare una Password.";
$locale['552'] = "Devi specificare un Indirizzo Email.";
?>