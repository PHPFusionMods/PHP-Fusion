<?php
$locale['400'] = "Üye Kayýt";
$locale['401'] = "Üye Hesabý Oluþturma";
// Registration Errors
$locale['402'] = "Kayýt için; Kullanýcý adý, Þifre ve E-mail adresinizi giriniz.";
$locale['403'] = "Kullanýcý adýnýzda Türkçe karakter kullanmayýnýz.";
$locale['404'] = "Yazdýðýnýz iki þifre birbirini tutmuyor.";
$locale['405'] = "Geçersiz þifre. Sadece rakam ve Türkçe karakter haricinde harflerden oluþabilir.<br>
Þifreniz en az 6 haneli olmalýdýr.";
$locale['406'] = "Geçersiz e-mail adresi girdiniz lütfen tekrar kontrol ediniz.";
$locale['407'] = "Üzgünüm, ".(isset($_POST['username']) ? $_POST['username'] : "")." kullanýcý adý daha önce alýnmýþ.";
$locale['408'] = "Üzgünüm, kayýt için girdiðiniz ".(isset($_POST['email']) ? $_POST['email'] : "")." e-mail adresi daha önce kullanýlmýþ.";
$locale['409'] = "Bu e-mail adresi aktif edilmemiþ bir kullanýcý hesabýna aittir.";
$locale['410'] = "Yanlýþ Güvenlik Kodu.";
$locale['411'] = "E-mail adresiniz yada domaininiz kara listeye alýnmýþ.";
// Email Message
$locale['450'] = "Merhaba ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
".$settings['sitename'].".Sitesine Hoþgeldiniz. Kullanýcý hesabýnýza ait bilgiler aþaðýdaki gibidir:\n
Kullanýcý Adý: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Þifre: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Üyeliðinizi tamamlayýp aktif hale getirebilmek için lütfen aþaðýdaki linke týklayýnýz:\n";
// Registration Success/Fail
$locale['451'] = "Kaydýnýz baþarýyla tamamlandý.";
$locale['452'] = "Siteye giriþ yapabilirsiniz.";
$locale['453'] = "Kaydýnýz tamamlandý, Birkaç dakika içinde site yöneticisinden bir e-mail alacaksýnýz. Gelen e-mail de size verilen aktivasyon linkini týkladýktan sonra siteye giriþ yapabilirsiniz.";
$locale['454'] = "Kullanýcý hasabýnýzý aktif hale getirebilmek için, vermiþ olduðunuz e-mail adresine bir aktivasyon maili alacaksýnýz orada size belirtilen linki týklayarak hesabýnýzý aktif hale getirip siteye giriþ yapabilirsiniz. Aktivasyon e-maili 1 gün içinde gelecektir. Bu 1 gün içinde üyeliðini aktif etmeyenleri sistem otomatik olarak SÝLECEKTÝR bilginize..!";
$locale['455'] = "Üyelik kaydýnýz tamamlandý.";
$locale['456'] = "Kayýt Hatasý";
$locale['457'] = "Sistem mail gönderemedi, lütfen <a href='mailto:".$settings['siteemail']."'>Site Sahibi yada Yöneticileri</a> ile irtibat geçiniz.";
$locale['458'] = "Aþaðýdaki cebep yada sebeplerden dolayý bir hata oluþtu:";
$locale['459'] = "Lütfen Tekrar Deneyin";
// Register Form
$locale['500'] = "Lütfen aþaðýdaki alanlarý doldurunuz. Üyelik ÜCRETSÝZDÝR..! ";
$locale['501'] = "Kayýt esnasýnda vermiþ olduðunuz e-mail adresine aktivasyon kodu gönderilecektir. ";
$locale['502'] = "Doldurulmasý zorunlu alanlar <span style='color:#ff0000;'>*</span> (yýldýz) ile belirtilmiþtir.";
$locale['503'] = " Daha sonra üyelik bilgilerinizde deðiþiklik yapmak için Profil Düzenle linkini týklayarak bilgilerinizi güncelleyebilirsiniz.";
$locale['504'] = "Güvenlik Kodu:";
$locale['505'] = "Güvenlik Kodunu Giriniz:";
$locale['506'] = "Kayýt Ol";
$locale['507'] = "Üyelik Kaydý Sistemi Dondurulmuþtur.";
// Validation Errors
$locale['550'] = "Lütfen Kullanýcý Adý Giriniz.";
$locale['551'] = "Lütfen Þifre Giriniz.";
$locale['552'] = "Lütfen E-mail Adresi Giriniz.";
?>