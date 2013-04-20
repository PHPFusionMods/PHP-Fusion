<?php
// Member Management Options
$locale['400'] = "كاربران";
$locale['401'] = "شناسه كاربران";
$locale['402'] = "افزودن كاربر جديد";
$locale['403'] = "سطح دسترسي";
$locale['404'] = "تنظيمات";
$locale['405'] = "مشاهده";
$locale['406'] = "ويرايش";
$locale['407'] = "دادن دسترسي";
$locale['408'] = "عدم دسترسي";
$locale['409'] = "حذف";
$locale['410'] = "شناسه هيچ كاربري با اين حرف آغاز نمي شود: ";
$locale['411'] = "نمايش ليست كامل";
$locale['412'] = "فعاليت";
// Ban/Unban/Delete Member
$locale['420'] = "دسترسي كاربر مورد نظر قطع شد";
$locale['421'] = "دسترسي به كاربر مورد نظر داده شد";
$locale['422'] = "حذف كاربر";
$locale['423'] = "آيا از حذف اين كاربر اطمينان داريد ؟";
$locale['424'] = "كاربر فعال شد";
$locale['425'] = "Account activated at ";
$locale['426'] = "Hello [USER_NAME],\n
Your account at ".$settings['sitename']." has been activated.\n
You can now login using your chosen username and password.\n
Regards,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "ويرايش كاربر";
$locale['431'] = "مشخصات كاربر به روز شد";
$locale['432'] = "بازگشت به مديريت كاربران";
$locale['433'] = "بازگشت به فهرست مديريت";
$locale['434'] = "امكان ويرايش و به روز رساني مشخصات كاربر نيست:";
// Extra Edit Member Details form options
$locale['440'] = "ذخيره تغييرات";
// Update Profile Errors
$locale['450'] = "امكان ويرايش مشخصات مدير كل سايت وجود ندارد.";
$locale['451'] = "شناسه كاربري و ياادرس پست الكترونكي را مشخص نكرده ايد.";
$locale['452'] = "نام كاربري شامل كاراكترهاي غير معتبر است";
$locale['453'] = "نام كاربري ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." قبلاً انتخاب شده است.";
$locale['454'] = "آدرس پست الكترونكي معتبر نمي باشد.";
$locale['455'] = "پست الكترونيكي ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." قبلاً انتخاب شده است.";
$locale['456'] = "رمزهاي عبور جديد يكي نيستند.";
$locale['457'] = "رمز عبور معتبر نمي باشد از حروف و اعداد استفاده نماييد.<br>
حداقل طول رمز عبور 6 كاراكتر است.";
$locale['458'] = "<b>Warning:</b> unexpected script execution.";
// View Member Profile
$locale['470'] = "مشخصات كاربري: ";
$locale['471'] = "اطلاعات عمومي";
$locale['472'] = "آمار";
$locale['473'] = "گروه كاربري";
// Add Member Errors
$locale['480'] = "افزودن كاربر جديد";
$locale['481'] = "حساب كاربري ايجاد شد.";
$locale['482'] = ">حساب كاربري ايجاد نشد.";
?>