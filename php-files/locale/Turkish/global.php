<?php
/*
Turkish Language Fileset
MAxwELL_TR - Barzo (phpfusion-tr)
Email: webmaster@phpfusion-tr.com
Web: http://www.phpfusion-tr.com
*/

// Locale Settings
setlocale(LC_TIME, "tr","TR"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-9";
$locale['tinymce'] = "tr";
$locale['phpmailer'] = "tr";

// Full & Short Months
$locale['months'] = "&nbsp|Ocak|Þubat|Mart|Nisan|Mayýs|Haziran|Temmuz|Aðustos|Eylül|Ekim|Kasým|Aralýk";
$locale['shortmonths'] = "&nbsp|Ock|Þbt|Mar|Nis|May|Haz|Tem|Agus|Eki|Eyl|Kas|Arlk";

// Standard User Levels
$locale['user0'] = "Genel";
$locale['user1'] = "Üye";
$locale['user2'] = "Yönetici";
$locale['user3'] = "Süper Yönetici";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['001'] = "Ana Menü";
$locale['002'] = "Kayýtlý Link Yok\n";
$locale['003'] = "Sadece Üyeler";
$locale['004'] = "Bu paneli sadece üyelere görüntüleyebilir yada henüz bir içerik eklenmemiþ olabilir.";
// Users Online
$locale['010'] = "Çevrimiçi Yöneticiler";
$locale['011'] = "Çevrimiçi Ziyaretçiler: ";
$locale['012'] = "Çevrimiçi Yönetici: ";
$locale['013'] = "Çevrimiçi Yönetici Yok";
$locale['014'] = "Kayýtlý Yöneticiler: ";
$locale['015'] = "Aktivite Edilmemiþ Yöneticiler: ";
$locale['016'] = "Aktif Yönetici: ";
// Sidebar
$locale['020'] = "Forum Baþlýklarý";
$locale['021'] = "En Yeni Baþlýklar";
$locale['022'] = "En Fazla Ýlgilenilen Baþlýklar";
$locale['023'] = "En Son Ýnceleme";
$locale['024'] = "Hoþgeldiniz";
$locale['025'] = "Enson Aktif Forum Baþlýklarý";
$locale['026'] = "Yeni Baþlýklarým";
$locale['027'] = "Yeni Forum Mesajlarým";
$locale['028'] = "Yeni Mesajlar";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Baþlýklar";
$locale['032'] = "Görüntülenme";
$locale['033'] = "Cevaplar";
$locale['034'] = "Enson Forum Mesajý";
$locale['035'] = "Konu";
$locale['036'] = "Gönderen";
$locale['037'] = "Henüz yazmýþ olduðunuz hiç forum baþlýðýnýz yok.";
$locale['038'] = "Henüz yazmýþ olduðunuz hiç forum mesajýnýz yok.";
$locale['039'] = "Son geliþinizden bu yana %u yeni forum mesajý var.";
// News & Articles
$locale['040'] = "Yazar ";
$locale['041'] = "- ";
$locale['042'] = "Devamý";
$locale['043'] = " Yorumlar";
$locale['044'] = " Okuma";
$locale['045'] = "Yazdýr";
$locale['046'] = "Haber Yok";
$locale['047'] = "Henüz haber gönderilmemiþ.";
$locale['048'] = "Düzenle";
// Prev-Next Bar
$locale['050'] = "Önceki";
$locale['051'] = "Sonraki";
$locale['052'] = "Sayfa ";
$locale['053'] = " - ";
// User Menu
$locale['060'] = "Yönetici Giriþi";
$locale['061'] = "Kullanýcý Adý";
$locale['062'] = "Þifre";
$locale['063'] = "Beni Hatýrla";
$locale['064'] = "Giriþ";
$locale['065'] = "Henüz ÜYE Olmadýnýz mý?<br><a href='".BASEDIR."register.php' class='side'>Buraya Týklayarak</a> Üye Olabilirsiniz.";
$locale['066'] = "Þifremi Unuttum?<br>Þifrenizi öðrenebilmek için <a href='".BASEDIR."lostpassword.php' class='side'>Buraya Týklayýn</a>.";
//
$locale['080'] = "Profil Düzenle";
$locale['081'] = "Özel Mesajlar";
$locale['082'] = "Üye Listesi";
$locale['083'] = "Yönetici Paneli";
$locale['084'] = "Çýkýþ";
$locale['085'] = "%u Yeni ";
$locale['086'] = "mesaj";
$locale['087'] = "mesajlar";
// Poll
$locale['100'] = "Anket";
$locale['101'] = "Oy Ver";
$locale['102'] = "Ankete katýlabilmek için üye olmanýz yada üye giriþi yapmanýz gerekmektedir.";
$locale['103'] = "Oy";
$locale['104'] = "Oylar";
$locale['105'] = "Oylar: ";
$locale['106'] = "Baþlangýç: ";
$locale['107'] = "Bitiþ: ";
$locale['108'] = "Anket Arþivi";
$locale['109'] = "Listeden bir anket seçiniz:";
$locale['110'] = "Göster";
$locale['111'] = "Anket Göster";
// Shoutbox
$locale['120'] = "Kýsa Mesajlar";
$locale['121'] = "Ýsim:";
$locale['122'] = "Mesajýnýz:";
$locale['123'] = "Gönder";
$locale['124'] = "Yardým";
$locale['125'] = "Mesajýnýzý gönderebilmeniz için üye olmanýz yada üye giriþi yapmanýz gerekmektedir.";
$locale['126'] = "Kýsa Mesajlar Arþivi";
$locale['127'] = "Henüz Mesaj Gönderilmemiþ.";
// Footer Counter
$locale['140'] = "Ziyaretçi";
$locale['141'] = "Tekil Ziyaretçiler";
// Admin Navigation
$locale['150'] = "Admin Paneli";
$locale['151'] = "Siteye Geri Dön";
$locale['152'] = "Admin Panelleri";
// Miscellaneous
$locale['190'] = "Site Bakým Modunu Aktif Et";
$locale['191'] = "IP Adresiniz Kara Listeye Alýnmýþtýr.";
$locale['192'] = "Sitemizden Çýkýþ Yapan Üye ";
$locale['193'] = "Sitemize Giriþ Yapan Üye ";
$locale['194'] = "Üye Hesabýnýz Askýya Alýnmýþtýr.";
$locale['195'] = "Bu Üye Hesabý Aktivite Edilmemiþ.";
$locale['196'] = "Geçersiz Kullanýcý Adý yada Þifre.";
$locale['197'] = "Lütfen bekleyin ana sayfaya yönlendiriliyorsunuz....<br><br>
[ <a href='index.php'>Eðer sayfaya yönlendirilmiyorsanýz lütfen buraya týklayýn</a> ]";
$locale['198'] = "<b>Dikkat : </b> setup.php dosyasý bulundu. Lütfen Siliniz";
?>