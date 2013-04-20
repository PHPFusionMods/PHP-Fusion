<?php
// Contact Form
$locale['400'] = "Contact Me";
$locale['401'] = "There are many ways you can contact me, you can Email me directly at
<a href='mailto:".str_replace("@","&#64;",$settings['siteemail'])."'>".str_replace("@","&#64;",$settings['siteemail'])."</a>.
If you are a Member you can send me a <a href='messages.php?msg_send=1'>Private Message</a>.
Alternatively, you can fill in the form on this page, this sends your message to me via Email.";
$locale['402'] = "Name:";
$locale['403'] = "Email Address:";
$locale['404'] = "Subject:";
$locale['405'] = "Message:";
$locale['406'] = "Send Message";
// Contact Errors
$locale['420'] = "You must specify a Name";
$locale['421'] = "You must specify an Email Address";
$locale['422'] = "You must specify a Subject";
$locale['423'] = "You must specify a Message";
// Message Sent
$locale['440'] = "Your Message has been sent";
$locale['441'] = "Thank You";
$locale['442'] = "Your message was not sent for the following reason(s):";
$locale['443'] = "Please try again.";
?>