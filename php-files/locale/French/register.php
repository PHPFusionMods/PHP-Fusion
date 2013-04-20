<?php
$locale['400'] = "Enregistrement";
$locale['401'] = "Compte activé";
// Registration Errors
$locale['402'] = "Vous devez spécifier un pseudo, mot de passe et adresse Email.";
$locale['403'] = "Le pseudo contient des caractères non valides.";
$locale['404'] = "Vos deux mots de passe ne correspondent pas.";
$locale['405'] = "Mot de passe non valide, utilisez seulement des caractère alphanumériques.<br>
Le mot de passe doit être d'une longueur minimum de 6 caractères.";
$locale['406'] = "Votre adresse Email ne semble pas valide.";
$locale['407'] = "Désolé, le pseudo ".(isset($_POST['username']) ? $_POST['username'] : "")." est déjà utilisé.";
$locale['408'] = "Désolé, l'adresse Email ".(isset($_POST['email']) ? $_POST['email'] : "")." est déjà utilisée";
$locale['409'] = "Un compte actif a été enregistré avec cette adresse Email.";
$locale['410'] = "Erreur de code de validation.";
$locale['411'] = "Votre adresse Email ou votre nom de Domaine Email est sur liste noire.";
// Email Message
$locale['449'] = "Bienvenue chez ".$settings['sitename'];
$locale['450'] = "Bonjour ".(isset($_POST['username']) ? $_POST['username'] : "").",\n
bienvenue sur ".$settings['sitename'].". Ici vos détails de connexion:\n
Pseudo : ".(isset($_POST['username']) ? $_POST['username'] : "")."
Mot de passe: ".(isset($_POST['password1']) ? $_POST['password1'] : "")."\n
Veuillez activer votre compte via le lien suivant:\n";
// Registration Success/Fail
$locale['451'] = "Enregistrement complet";
$locale['452'] = "Vous pouvez vous connecter.";
$locale['453'] = "Un administrateur va bientôt activer votre compte.";
$locale['454'] = "Votre enregistrement est presque terminé, vous allez recevoir un Email contenant le détail de votre compte et un lien pour la vérification.";
$locale['455'] = "Votre compte a été vérifié.";
$locale['456'] = "L'enregistrement a échoué";
$locale['457'] = "L'envoi du mail a échoué, veuillez contacter <a href='mailto:".$settings['siteemail']."'>l'Administrateur</a>.";
$locale['458'] = "L'enregistrement à échoué pour la/les raison(s) suivante(s):";
$locale['459'] = "Veuillez réessayer";
// Register Form
$locale['500'] = "Veuillez entrer vos informations ci-dessous. ";
$locale['501'] = "Un Email de vérification vous sera envoyé à l'adresse spécifiée. ";
$locale['502'] = "Les champs marqués <span style='color:#ff0000;'>*</span> doivent être remplis.
Votre pseudo et mot de passe sont sensibles à la casse(MAJ/MIN).";
$locale['503'] = " Vous pouvez mettre des informations complémentaires en éditant votre profil une fois connecté.";
$locale['504'] = "Code de validation:";
$locale['505'] = "Entrez le code de validation:";
$locale['506'] = "Enregistrer";
$locale['507'] = "L'enregistrement est momentanément désactivé.";
// Validation Errors
$locale['550'] = "Veuillez spécifier un pseudo.";
$locale['551'] = "Veuillez spécifier un mot de passe.";
$locale['552'] = "Veuillez spécifier une adresse email.";
?>