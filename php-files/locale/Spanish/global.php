<?php
/*
Spanish Language Fileset
Produced by Gonzalo Suez (Belial)
Email: info@gsuez.cl
Web: http://www.gsuez.cl

Collaborator: Sigstorm
Email: sigstorm@gmail.com
*/

// Locale Settings
setlocale(LC_TIME, "es","ES"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "es";
$locale['phpmailer'] = "es";

// Full & Short Months
setlocale(LC_ALL, 'es_ES' );
$locale['months'] = "&nbsp|Enero|Febrero|Marzo|Abril|Mayo|Junio|Julio|Agosto|Septiembre|Octubre|Noviembre|Diciembre";
$locale['shortmonths'] = "&nbsp|Ene|Feb|Mar|Abr|May|Jun|Jul|Ago|Sept|Oct|Nov|Dic";

// Standard User Levels
$locale['user0'] = "Público";
$locale['user1'] = "Miembro";
$locale['user2'] = "Administrador";
$locale['user3'] = "Super Administrador";
// Forum Moderator Level(s)
$locale['userf1'] = "Moderador";
// Navigation
$locale['001'] = "Navegación";
$locale['002'] = "No hay enlaces definidos";
$locale['003'] = "Sólo Usuarios";
$locale['004'] = "No hay contenido para este panel aún";
// Users Online
$locale['010'] = "En línea";
$locale['011'] = "Invitados: ";
$locale['012'] = "Usuarios: ";
$locale['013'] = "No hay usuarios en línea";
$locale['014'] = "Usuarios Registrados: ";
$locale['015'] = "Usuarios Inactivos: ";
$locale['016'] = "Nuevos: ";
// Sidebar & Other Titles
$locale['020'] = "Temas del Foro";
$locale['021'] = "Nuevos Temas";
$locale['022'] = "Temas más Populares";
$locale['023'] = "Últimos Artículos";
$locale['024'] = "Bienvenido";
$locale['025'] = "Últimos Temas de Foros Activos";
$locale['026'] = "Temas Recientes";
$locale['027'] = "Mensajes Recientes ";
$locale['028'] = "Nuevos Envíos";
// Forum List Texts
$locale['030'] = "Foros";
$locale['031'] = "Temas";
$locale['032'] = "Vistas";
$locale['033'] = "Respuestas";
$locale['034'] = "Ultimos Envíos";
$locale['035'] = "Asunto";
$locale['036'] = "Enviado";
$locale['037'] = "Usted no ha empezado ningún tema de foro todavía.";
$locale['038'] = "Usted no ha enviado ningún mensaje al foro todavía.";
$locale['039'] = "Hay %u nuevos enviós desde su última visita.";
// News & Articles
$locale['040'] = "Enviado por ";
$locale['041'] = "en ";
$locale['042'] = "Leer Más";
$locale['043'] = " Comentarios";
$locale['044'] = " Leer";
$locale['045'] = "Imprimir";
$locale['046'] = "Noticias";
$locale['047'] = "Ninguna Noticias ha sido enviada todavía";
$locale['048'] = "Editar";
// Prev-Next Bar
$locale['050'] = "Anterior";
$locale['051'] = "Próximo";
$locale['052'] = "Página ";
$locale['053'] = " de ";
// User Menu
$locale['060'] = "Registro";
$locale['061'] = "Usuario";
$locale['062'] = "Contraseña";
$locale['063'] = "Recordarme";
$locale['064'] = "Ingresar";
$locale['065'] = "¿Aún no es Usuario?<br><a href='".BASEDIR."register.php' class='side'>Click aquí</a> para registrarser.";
$locale['066'] = "¿Olvidó su contraseña?<br>Pedir una nueva <a href='".BASEDIR."lostpassword.php' class='side'>aquí</a>.";
//
$locale['080'] = "Editar Perfil";
$locale['081'] = "Mensajes Privados";
$locale['082'] = "Lista de Usuarios";
$locale['083'] = "Administración";
$locale['084'] = "Cerrar Sesión";
$locale['085'] = "Tienes %u nuevo";
$locale['086'] = "mensaje";
$locale['087'] = "mensajes";
// Poll
$locale['100'] = "Votación";
$locale['101'] = "Votar";
$locale['102'] = "Debe registrarse para votar.";
$locale['103'] = "Voto";
$locale['104'] = "Votos";
$locale['105'] = "Votos: ";
$locale['106'] = "Comenzó: ";
$locale['107'] = "Finalizó: ";
$locale['108'] = "Archivo de Encuestas";
$locale['109'] = "Seleccione una encuesta de la lista:";
$locale['110'] = "Ver";
$locale['111'] = "Ver Encuesta";
// Shoutbox
$locale['120'] = "Shoutbox";
$locale['121'] = "Nombre:";
$locale['122'] = "Mensaje:";
$locale['123'] = "Shout";
$locale['124'] = "Ayuda";
$locale['125'] = "Debe ser usuario para enviar un mensaje.";
$locale['126'] = "Shoutbox Archivo";
$locale['127'] = "Ningún mensaje ha sido enviado.";
// Footer Counter
$locale['140'] = "Visita Unica";
$locale['141'] = "Visitas Unicas";
// Admin Navigation
$locale['150'] = "Inicio de Administración";
$locale['151'] = "Volver al Sitio";
$locale['152'] = "Paneles de Administración";
// Miscellaneous
$locale['190'] = "Modo Mantención Activado";
$locale['191'] = "Su dirección IP ha sido puesta en la lista negra.";
$locale['192'] = "Salir como ";
$locale['193'] = "Entrar como ";
$locale['194'] = "Esta cuenta está actualmente suspendida.";
$locale['195'] = "Esta cuenta no ha sido activada.";
$locale['196'] = "Nombre de usuario o contraseña incorrectos.";
$locale['197'] = "Por favor espere mientras lo transferimos...<br><br>
[ <a href='index.php'>o haga click aquí si no desea esperar</a> ]";
$locale['198'] = "<b>Advertencia:</b> setup.php detectado, por favor bórrelo inmediatamente";
?>