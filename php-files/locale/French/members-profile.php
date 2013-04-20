<?php
// Members List
$locale['400'] = "Liste des membres";
$locale['401'] = "Pseudo";
$locale['402'] = "type d'utilisateur";
$locale['403'] = "Il n'y a pas de pseudo commençant par ";
$locale['404'] = "Tout montrer";
// View User Groups
$locale['410'] = "Visualiser Groupes d'utilisateurs";
$locale['411'] = "%u utilisateur";
$locale['412'] = "%u utilisateurs";
// User Profile
$locale['420'] = "Profil de Membre: ";
$locale['422'] = "Statistiques";
$locale['423'] = "Groupes d'utilisateurs";
// Edit Profile
$locale['440'] = "Editer Profil";
$locale['441'] = "Profil mis à jour avec succès";
$locale['442'] = "Impossible de mettre à jour le profil:";
// Edit Profile Form
$locale['460'] = "Mise à jour Profil";
// Update Profile Errors
$locale['480'] = "Vous devez spécifier un pseudo et une adresse Email.";
$locale['481'] = "Votre pseudo contient des caractères invalides.";
$locale['482'] = "Le pseudo ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." est déjà utilisé.";
$locale['483'] = "Adresse Email non valide.";
$locale['484'] = "L'adresse Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." est déjà utilisée.";
$locale['485'] = "Les nouveaux mots de passe ne correspondent pas.";
$locale['486'] = "Mot de passe non valide : veuillez n'utiliser que des caractères alphanumèriques.<br>
Le mot de passe doit avoir un  minimum de 6 caractères.";
$locale['487'] = "<b>Attention :</b> Exécution de script anormale.";
?>
