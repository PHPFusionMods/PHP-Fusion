<?php
/*
Ensemble de fichier de langue française
Basé sur:
English Language Fileset
Produced by Nick Jones (Digitanium)
Email: digitanium@php-fusion.co.uk
Web: http://www.php-fusion.co.uk

Traduits par http://www.phpfusion-fr.com, ajouts par http://surgele.free.fr
*/

// Locale Settings
setlocale(LC_TIME, "fr","FR"); // Linux Server (Windows may differ)
$locale['charset'] = "iso-8859-1";
$locale['tinymce'] = "fr";
$locale['phpmailer'] = "fr";

// Full & Short Months
$locale['months'] = "&nbsp|janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre";
$locale['shortmonths'] = "&nbsp|Jan|Fev|Mar|Avr|Mai|Jun|Jul|Aou|Sept|Oct|Nov|Dec";

// Standard User Levels
$locale['user0'] = "Public";
$locale['user1'] = "Membre";
$locale['user2'] = "Administrateur";
$locale['user3'] = "Super Administrateur";
// Forum Moderator Level(s)
$locale['userf1'] = "Modérateur";
// Navigation
$locale['001'] = "Navigation";
$locale['002'] = "Aucun lien défini\n";
$locale['003'] = "Membres seulement";
$locale['004'] = "Il n'y a pas encore de contenu pour ce panneau";
// Users Online
$locale['010'] = "Utilisateur en ligne";
$locale['011'] = "Invité en ligne: ";
$locale['012'] = "Membres en ligne: ";
$locale['013'] = "Aucun Membre en ligne";
$locale['014'] = "Membres enregistrés: ";
$locale['015'] = "Membres non activés: ";
$locale['016'] = "Nouveau Membre: ";
// Sidebar & Other Titles
$locale['020'] = "Sujets Forum";
$locale['021'] = "Nouveaux Sujets";
$locale['022'] = "Sujets Actifs";
$locale['023'] = "Derniers Articles";
$locale['024'] = "Bienvenue";
$locale['025'] = "Derniers Sujets de Forum Actifs";
$locale['026'] = "Mes Derniers Sujets";
$locale['027'] = "Mes Récents Posts";
$locale['028'] = "Posts Nouveaux";
// Forum List Texts
$locale['030'] = "Forum";
$locale['031'] = "Sujet";
$locale['032'] = "Vues";
$locale['033'] = "Réponses";
$locale['034'] = "Dernier Post";
$locale['035'] = "Sujet";
$locale['036'] = "Posté";
$locale['037'] = "Vous n'avez pas encore commencé de Sujet.";
$locale['038'] = "Vous n'avez pas encore posté de Sujet.";
$locale['039'] = "Il y a %u nouveaux posts depuis votre dernière visite.";
// News & Articles
$locale['040'] = "Posté par ";
$locale['041'] = "le ";
$locale['042'] = "Lire la suite";
$locale['043'] = " Commentaire(s)";
$locale['044'] = " Lecture(s)";
$locale['045'] = "Imprimer";
$locale['046'] = "Nouvelles";
$locale['047'] = "Aucune nouvelle n'est encore postée";
$locale['048'] = "Editer";
// Prev-Next Bar
$locale['050'] = "Préc.";
$locale['051'] = "Suiv.";
$locale['052'] = "Page ";
$locale['053'] = " de ";
// User Menu
$locale['060'] = "Connexion";
$locale['061'] = "Pseudo";
$locale['062'] = "Mot de passe";
$locale['063'] = "Se souvenir de moi";
$locale['064'] = "Connexion";
$locale['065'] = "Pas encore membre ?<br><a href='".BASEDIR."register.php' class='side'>Enregistrez-vous ici</a>.";
$locale['066'] = "Mot de passe oublié ?<br><a href='".BASEDIR."lostpassword.php' class='side'>Cliquez ici.</a>.";
//
$locale['080'] = "Edition du Profil";
$locale['081'] = "Messages Privés";
$locale['082'] = "Liste des membres";
$locale['083'] = "Panneau Administration";
$locale['084'] = "Sortie";
$locale['085'] = "Vous avez %u nouveau(x) ";
$locale['086'] = "message";
$locale['087'] = "messages";
// Poll
$locale['100'] = "Sondage Membre";
$locale['101'] = "Soumettre un sondage";
$locale['102'] = "Vous devez être connecté pour voter.";
$locale['103'] = "Voter";
$locale['104'] = "Votes";
$locale['105'] = "Votes : ";
$locale['106'] = "Début : ";
$locale['107'] = "Fin : ";
$locale['108'] = "Archive sondages";
$locale['109'] = "Sélectionnez un sondage dans la liste :";
$locale['110'] = "Voir";
$locale['111'] = "Voir sondage";
// Shoutbox
$locale['120'] = "Boite de dialogue";
$locale['121'] = "Nom :";
$locale['122'] = "Message :";
$locale['123'] = "Valider";
$locale['124'] = "Aide";
$locale['125'] = "Vous devez être connecté pour dialoguer.";
$locale['126'] = "Archive Dialogues";
$locale['127'] = "Aucun message n'a été posté.";
// Footer Counter
$locale['140'] = "Visite Unique ";
$locale['141'] = "Visites Uniques";
// Admin Navigation
$locale['150'] = "Accueil Admin";
$locale['151'] = "Retour au site";
$locale['152'] = "Panneau Admin";
// Miscellaneous
$locale['190'] = "Mode de maintenance activé";
$locale['191'] = "Votre adresse IP est actuellement sur liste noire.";
$locale['192'] = "Déconnexion ";
$locale['193'] = "Connexion ";
$locale['194'] = "Ce compte est actuellement suspendu.";
$locale['195'] = "Ce compte n'a pas été activé.";
$locale['196'] = "Pseudo ou mot de passe invalide.";
$locale['197'] = "Merci de patienter...<br><br>
[ <a href='index.php'>Ou cliquez ici si vous ne voulez pas attendre</a> ]";
$locale['198'] = "<b>Attention:</b> setup.php détecté, veuillez l'effacer maintenant";
?>