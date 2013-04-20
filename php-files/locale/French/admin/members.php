<?php
// Member Management Options
$locale['400'] = "Membres";
$locale['401'] = "Utilisateur";
$locale['402'] = "Ajouter";
$locale['403'] = "Type d'utilisateur";
$locale['404'] = "Options";
$locale['405'] = "Voir";
$locale['406'] = "Editer";
$locale['407'] = "Enlever de la Liste Noire";
$locale['408'] = "Bannir";
$locale['409'] = "Supprimer";
$locale['410'] = "Aucun nom d'utilisateur ne commence par ";
$locale['411'] = "Voir tous les membres";
$locale['412'] = "Activer";
// Ban/Unban/Delete Member
$locale['420'] = "Bannissement Effectué";
$locale['421'] = "Bannissement Supprimé";
$locale['422'] = "Membre supprimé";
$locale['423'] = "Etes vous sûr de vouloir supprimer ce membre ?";
$locale['424'] = "Membre Activé";
$locale['425'] = "Account activated at ";
$locale['426'] = "Bonjour [USER_NAME],\n
Votre compte sur ".$settings['sitename']." à été activé.\n
Vous pouvez maintenant vous connecter en utilisant votre nom d'utilisateur et votre mot de passe.\n
Merci,
".$settings['siteusername'];
// Edit Member Details
$locale['430'] = "Editer un membre";
$locale['431'] = "Détails du membre modifiés";
$locale['432'] = "Retour à l'administration des membres";
$locale['433'] = "Retour à l'administration du site";
$locale['434'] = "Impossible d'effectuer les modifications:";
// Extra Edit Member Details form options
$locale['440'] = "Sauvegarder les modifications";
// Update Profile Errors
$locale['450'] = "Edition du Super Administrateur Impossible.";
$locale['451'] = "Vous devez entrer un nom d'utilisateur & une adresse Email.";
$locale['452'] = "Le nom d'utilisateur contient des caractères invalides.";
$locale['453'] = "Le nom d'utilisteur ".(isset($_POST['user_name']) ? $_POST['user_name'] : "")." est déjà utilisé.";
$locale['454'] = "Adresse Email Invalide.";
$locale['455'] = "Adresse Email ".(isset($_POST['user_email']) ? $_POST['user_email'] : "")." déjà utilisée.";
$locale['456'] = "Nouveaux Mots de Passe introuvables.";
$locale['457'] = "Mot de passe invalide, utilisez des caractères alphanumériques seulement.<br>
Le mot de passe doit contenir au moins 6 caractères.";
$locale['458'] = "<b>Attention:</b> exécution d'un script innatendue.";
// View Member Profile
$locale['470'] = "Profil du membre: ";
$locale['472'] = "Statistiques";
$locale['473'] = "Groupes d'utilisateurs";
// Add Member Errors
$locale['480'] = "Ajouter";
$locale['481'] = "Compte créé.";
$locale['482'] = "Création du compte impossible.";
?>
