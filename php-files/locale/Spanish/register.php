<?php
$locale['400'] = "Registro";
$locale['401'] = "Activar Cuenta";
// Registration Errors
$locale['402'] = "Usted debe especificar un nombre de usuario, contraseña y la dirección de email.";
$locale['403'] = "El nombre del usuario contiene caracteres incorrectos.";
$locale['404'] = "Las dos contraseñas no coinciden.";
$locale['405'] = "Contraseña incorrecta, sólo use caracteres alfanuméricos.<br>
La contraseña debe tener un mínimo de 6 caracteres.";
$locale['406'] = "Su dirección de email no parece ser válida.";
$locale['407'] = "Lo sentimos, el nombre de usuario ".(isset($_POST['username']) ? $_POST['username'] : "")." está en uso.";
$locale['408'] = "Lo sentimos, la dirección de email ".(isset($_POST['email']) ? $_POST['email'] : "")." está en uso.";
$locale['409'] = "Una cuenta inactiva ha sido registrada con la dirección de email.";
$locale['410'] = "Código de aprobación incorrecto.";
$locale['411'] = "Su dirección de email o su dominio esta en la lista negra.";
// Email Message
$locale['449'] = "Bienvenido a ".$settings['sitename'];
$locale['450'] = "Hola ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
Bienvenido a ".$settings['sitename'].". Aquí estan los detalles de su ingreso:\n
Usuario: ".(isset($_POST['username']) ? $_POST['username'] : "")."
Contraseña: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Por favor active su cuenta pulsando el siguiente link:\n";
// Registration Success/Fail
$locale['451'] = "Registro completo";
$locale['452'] = "Ahora puede ingresar.";
$locale['453'] = "Un administrador activará su cuenta.";
$locale['454'] = "Su registro está casi completo, usted recibirá un email que contiene los detalles de la inscripción junto con un link para verificar su cuenta.";
$locale['455'] = "Su cuenta a sido verificada.";
$locale['456'] = "El Registro falló";
$locale['457'] = "El envío de email falló, contacte al <a href='mailto:".$settings['siteemail']."'>Administrador</a>.";
$locale['458'] = "El registro falló por la(s) siguiente(s) razón(es):";
$locale['459'] = "Por favor inténtelo otra vez";
// Register Form
$locale['500'] = "Ingrese los detalles solicitados abajo. ";
$locale['501'] = "Un email del verificación se enviarán a la dirección especificada. ";
$locale['502'] = "Los campos marcados <span style='color:#ff0000;'>*</span> son obligatorios.
Los nombres de usuario y contraseña son sensibles a mayúsculas y minúsculas.";
$locale['503'] = " Puede ingresar información adicional en <b>Editar Perfil</b> una vez Inscrito.";
$locale['504'] = "Código de Validación:";
$locale['505'] = "Ingrese Código de Validación:";
$locale['506'] = "Registro";
$locale['507'] = "El sistema del registro está actualmente desactivado.";
// Validation Errors
$locale['550'] = "Especifique un nombre de usuario.";
$locale['551'] = "Especifique una contraseña.";
$locale['552'] = "Especifique una dirección de email.";
?>