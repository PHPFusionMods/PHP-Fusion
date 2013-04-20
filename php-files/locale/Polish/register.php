<?php
$locale['400'] = "Rejestracja";
$locale['401'] = "Aktywuj Konto";
// Registration Errors
$locale['402'] = "Musisz podaæ nazwê u¿ytkownika, has³o i email.";
$locale['403'] = "Nazwa U¿ytkownika zawiera nieprawid³owe znaki.";
$locale['404'] = "Has³a nie pasuj± do siebie.";
$locale['405'] = "Nieprawid³owe has³o. U¿ywaj tylko znaków alfanumerycznych.<br>
Minimalna d³ugo¶æ has³a to 6 znaków.";
$locale['406'] = "Adres email,który poda³e¶ jest nieprawid³owy.";
$locale['407'] = "Niestety, ta nazwa u¿ytkownika ".(isset($_POST['username']) ? $_POST['username'] : "")." jest ju¿ u¿ywana.";
$locale['408'] = "Niestety, ten adres email ".(isset($_POST['email']) ? $_POST['email'] : "")." jest ju¿ u¿ywany.";
$locale['409'] = "Nieaktywne konto zosta³o zarejestrowane na ten adres email.";
$locale['410'] = "Nieprawid³owy kod potwierdzaj±cy.";
$locale['411'] = "Twój adres email lub jego domena s± na naszej Czarnej Li¶cie.";
// Email Message
$locale['449'] = "Witaj na stronie ".$settings['sitename'];
$locale['450'] = "Witaj ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Witamy w serwisie ".$settings['sitename'].". Oto Twoje dane potrzebne do zalogowania siê na naszej stronie:\n
Nazwa U¿ytkownika - Nick: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Has³o: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Mo¿esz aktywowaæ swoje konto klikaj±c na poni¿szy odno¶nik:\n";
// Registration Success/Fail
$locale['451'] = "Rejestracja zakoñczona pomy¶lnie";
$locale['452'] = "Teraz mo¿esz siê zalogowaæ.";
$locale['453'] = "Wkrótce Twoje konto zostanie aktywowane przez Administratora.";
$locale['454'] = "Twoja rejestracja jest ju¿ zakoñczona, za chwilê otrzymasz email zawieraj±cy Twoje dane wraz z linkiem zwrotnym, aktywuj±cym konto.";
$locale['455'] = "Twoje konto zosta³o zweryfikowane.";
$locale['456'] = "Rejestracja nie powiod³a siê";
$locale['457'] = "Wys³anie listu z Twoimi danymi nie powiod³o siê, prosimy skontaktowaæ siê z <a href='mailto:".$settings['siteemail']."'>Administratorem Strony</a>.";
$locale['458'] = "Rejestracja nie powiod³a siê z nastêpuj±cych przyczyn:";
$locale['459'] = "Prosimy spróbowaæ ponownie";
// Register Form
$locale['500'] = "Prosimy podaæ poni¿ej swoje dane. ";
$locale['501'] = "Na podany adres email zostanie wys³any list weryfikacyjny. ";
$locale['502'] = "Pola oznaczone znakiem <span style='color:#ff0000;'>*</span> musz± zostaæ wype³nione.
Nazwa U¿ytkownika i has³o s± obowi±zkowe.";
$locale['503'] = " Dodatkowe informacje mo¿esz wpisaæ w pó¼niejszym czasie, edytuj±c swój profil po zalogowaniu siê .";
$locale['504'] = "Kod potwierdzaj±cy:";
$locale['505'] = "Wpisz Kod potwierdzaj±cy:";
$locale['506'] = "Rejestruj";
$locale['507'] = "Aktualnie system rejestracji jest wy³±czony. Przepraszamy.";
// Validation Errors
$locale['550'] = "Prosimy podaæ Nazwê U¿ytkownika.";
$locale['551'] = "Prosimy podaæ Has³o.";
$locale['552'] = "Prosimy podaæ adres email.";
?>
