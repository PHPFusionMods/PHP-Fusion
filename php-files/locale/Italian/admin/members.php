<?php
// Member Management Options
$locale['400'] = "Membri";
$locale['401'] = "Utente";
$locale['402'] = "Aggiungi";
$locale['403'] = "Tipo Utente";
$locale['404'] = "Opzioni";
$locale['405'] = "Vedi";
$locale['406'] = "Modifica";
$locale['407'] = "Togli Ban";
$locale['408'] = "Banna";
$locale['409'] = "Elimina";
$locale['410'] = "Non ci sono nomi utente che cominciano con ";
$locale['411'] = "Vedi Tutti";
$locale['412'] = "Attiva";
// Ban/Unban/Delete Member
$locale['420'] = "Ban Imposto";
$locale['421'] = "Ban Eliminato";
$locale['422'] = "Membro Eliminato";
$locale['423'] = "Sei sicuro di voler eliminare questo Membro?";
$locale['424'] = "Membro Attivato";
$locale['425'] = "Account attivato a ";
$locale['426'] = "Ciao [USER_NAME],\n
Il tuo account su ".$settings['sitename']." è stato attivato.\n
Ora puoi fare il login utilizzando nome utente e password da te scelti.\n
Saluti,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Modifica Membro";
$locale['431'] = "Dettagli Membro Aggiornati";
$locale['432'] = "Ritorna ad Amministrazione Membri";
$locale['433'] = "Ritorna ad Home Page Amministratore";
$locale['434'] = "Impossibile Aggiornare Dettagli Membro:";
// Extra Edit Member Details form options
$locale['440'] = "Salva Modifiche";
// Update Profile Errors
$locale['450'] = "Impossibile modificare amministratore primario.";
$locale['451'] = "Devi specificare nome utente e indirizzo Email.";
$locale['452'] = "Il Nome Utente contiene caratteri non validi.";
$locale['453'] = "Il Nome Utente ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." è gia in uso.";
$locale['454'] = "Indirizzo Email non valido.";
$locale['455'] = "L'indirizzo Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." è gia in uso.";
$locale['456'] = "Le Nuove Password non corrispondono.";
$locale['457'] = "Password non valida, usa solo numeri e lettere.<br>
La Password deve essere lunga almeno 6 caratteri.";
$locale['458'] = "<b>Attenzione:</b> esecuzione script non prevista.";
// View Member Profile
$locale['470'] = "Profilo Membro";
$locale['472'] = "Statistiche";
$locale['473'] = "Gruppo Utente";
// Add Member Errors
$locale['480'] = "Aggiungi Membro";
$locale['481'] = "L'account del membro è stato creato";
$locale['482'] = "L'account del membro non può essere creato";
?>