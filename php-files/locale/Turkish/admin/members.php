<?php
// Member Management Options
$locale['400'] = "Üyeler";
$locale['401'] = "Üye";
$locale['402'] = "Ekle";
$locale['403'] = "Üye Tipi";
$locale['404'] = "Seçenekler";
$locale['405'] = "Görünüm";
$locale['406'] = "Düzenle";
$locale['407'] = "Baný Kaldýr";
$locale['408'] = "Banla";
$locale['409'] = "Sil";
$locale['410'] = "Aradýðýnýz kriterlere göre üye bulunamadý.";
$locale['411'] = "Hepsini Göster";
$locale['412'] = "Aktivite Et";
// Ban/Unban/Delete Member
$locale['420'] = "Siteden Banla";
$locale['421'] = "Baný Kaldýr";
$locale['422'] = "Üye Silindi";
$locale['423'] = "Bu üyeyi silmek istediðinizden eminmisiniz?";
$locale['424'] = "Üye Aktivite Edildi";
$locale['425'] = "Aktivite edilen hesap ";
$locale['426'] = "Merhaba [USER_NAME],\n
".$settings['sitename']." sitesindeki üyelik hesabýnýz aktivite edildi.\n
Kullanýcý Adýnýz ve Þifrenizle sitemize giriþ yapabilirsiniz.\n
Teþekkürler,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Kullanýcý Düzenle";
$locale['431'] = "Kullanýcý Bilgileri Güncellendi";
$locale['432'] = "Üye Yönetimine Geri Dön";
$locale['433'] = "Site Yönetimine Geri Dön";
$locale['434'] = "Güncellenemeyen Kullanýcý Bilgileri:";
// Extra Edit Member Details form options
$locale['440'] = "Deðiþiklikleri Kaydet";
// Update Profile Errors
$locale['450'] = "Site Sahibi olan ve Ýlk Admin Olarak tanýmlanan yönetici düzenlenemez.";
$locale['451'] = "Bir Üye ismi ve e-mail adresi belirtmelisiniz.";
$locale['452'] = "Üye isminde geçersiz karakter var.";
$locale['453'] = "Bu kullanýcý adý ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." daha önce alýnmýþ.";
$locale['454'] = "Geçersiz e-mail adresi.";
$locale['455'] = "Bu e-mail adresi ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." daha önce alýnmýþ.";
$locale['456'] = "Þifreler birbirini tutmuyor.";
$locale['457'] = "Geçersiz þifre, Türkçe Karakter Kullanmayýnýz.<br>
Þifreniz minimum 6 karakter uzunluðunda olmalýdýr.";
$locale['458'] = "<b>Dikkat:</b> belirlenemeyen bir script hatasý oluþtu.";
// View Member Profile
$locale['470'] = "Üye Profili: ";
$locale['471'] = "Genel Bilgiler";
$locale['472'] = "Ýstatistik";
$locale['473'] = "Kullanýcý Gruplarý";
// Add Member Errors
$locale['480'] = "Üye Ekle";
$locale['481'] = "Üye Hesabý Oluþturuldu.";
$locale['482'] = "Üye Hesabý Oluþturulamýyor.";
?>