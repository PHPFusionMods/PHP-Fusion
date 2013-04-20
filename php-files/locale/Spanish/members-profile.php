<?php
// Members List
$locale['400'] = "Lista de Usuarios";
$locale['401'] = "Nombre de Usuario";
$locale['402'] = "Tipo de Usuario";
$locale['403'] = "No hay nombres de usuarios que empiezen con ";
$locale['404'] = "Mostrar Todo";
// View User Groups
$locale['410'] = "Ver Grupo del Usuario";
$locale['411'] = "%u usuario";
$locale['412'] = "%u usuarios";
// User Profile
$locale['420'] = "Perfil de Usuario";
$locale['422'] = "Estadísticas";
$locale['423'] = "Grupos de Usuarios";
// Edit Profile
$locale['440'] = "Editar Perfil";
$locale['441'] = "Perfil Exitosamente Actualizado";
$locale['442'] = "No se puede actualizar perfil:";
// Edit Profile Form
$locale['460'] = "Actualizar Perfil";
// Update Profile Errors
$locale['480'] = "Debe especificar un nombre de usuario y un Email.";
$locale['481'] = "El nombre de usuario contiene caracteres incorrectos.";
$locale['482'] = "El nombre de usuario ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." ya existe.";
$locale['483'] = "Email incorrecto.";
$locale['484'] = "La dirección Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." ya existe.";
$locale['485'] = "Las nuevas contraseñas no son iguales.";
$locale['486'] = "Contraseña incorrecta, use sólo caracteres alfanumericos.<br>
La contraseña debe ser como mínimo de 6 caracteres.";
$locale['487'] = "<b>Advertencia:</b> ejecución inesperada.";
?>