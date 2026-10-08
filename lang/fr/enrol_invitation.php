<?php
// This file is part of Invitation for Moodle - https://moodle.org/
//
// Invitation is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Invitation is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Strings for component 'enrol_invitation', language 'fr'
 *
 * @package    enrol_invitation
 * @copyright  2021-2024 TNG Consulting Inc. {@link https://www.tngconsulting.ca}
 * @author     Michael Milette
 * @copyright  2013 UC Regents
 * @copyright  2011 Jerome Mouneyrac {@link http://www.moodleitandme.com}
 * @author     Jerome Mouneyrac
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['a_day'] = '1 jour';
$string['a_minute'] = '1 minute';
$string['about_hour'] = 'environ 1 heure';
$string['about_x_hours'] = 'environ {$a} heures';
$string['accepteddescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a accepté une invitation pour le cours avec l\'identifiant \'{$a->courseid}\'.';
$string['action_extend_invite'] = 'Prolonger l\'invitation';
$string['action_resend_invite'] = 'Renvoyer l\'invitation';
$string['action_revoke_invite'] = 'Révoquer l\'invitation';
$string['anonymoususer'] = '(inconnu)';
$string['assignrole'] = 'Attribuer un rôle';
$string['assigngroup'] = 'Attribuer des groupes';
$string['customnamecourse'] = 'Format personnalisé';
$string['customsubjectformat'] = '{$a->shortname} - {$a->fullname}';
$string['default_subject'] = 'Invitation au cours : {$a}';
$string['defaultinvitevalues'] = 'Valeurs d\'invitation par défaut';
$string['defaultsubjectformat'] = 'Format de l\'objet par défaut';
$string['defaultsubjectformat_desc'] = 'Format du nom de cours utilisé par défaut dans l\'objet des courriels d\'invitation. Ce réglage ne s\'applique qu\'aux instances de la méthode d\'inscription au moment de leur création. Si vous sélectionnez <strong>format personnalisé</strong>, vous pouvez <a href="../admin/tool/customlang/">personnaliser la chaîne de langue <strong>\'customsubjectformat\'</strong></a> du plugin <strong>enrol_invitation</strong> en combinant le nom abrégé et/ou le nom complet du cours. À l\'installation du plugin, le format personnalisé est \'nom abrégé - nom complet\'.';
$string['deleteddescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a supprimé une invitation pour le cours avec l\'identifiant \'{$a->courseid}\' envoyée à \'{$a->email}\'.';
$string['editenrolment'] = 'Modifier l\'inscription';
$string['email_clarification'] = 'Vous pouvez indiquer plusieurs adresses courriel en les séparant par des points-virgules, des virgules, des espaces ou des retours à la ligne';
$string['emailaddressnumber'] = 'Adresse courriel';
$string['emailmessageuserenrolled'] = 'Bonjour,

{$a->userfullname} ({$a->useremail}) a accepté votre invitation à accéder au cours {$a->coursefullname} en tant que « {$a->rolename} ». Vous pouvez vérifier le statut de cette invitation en consultant :

* La liste des participants : {$a->courseenrolledusersurl}
* L\'historique des invitations : {$a->invitehistoryurl}

{$a->sitename}
-------------
{$a->supportemail}';
$string['emailmsghtml'] = 'Aperçu';
$string['emailmsghtml_help'] = '<p>Bonjour,</p>
<p>Vous êtes invité(e) à rejoindre le cours suivant :</p>
<ul>
  <li>Nom du cours : <b>{$a->coursename}</b></li>
</ul>
{$a->message}
<p>Connectez-vous pour confirmer votre inscription au cours. Si vous n\'avez pas encore de compte, vous pourrez en créer un après avoir cliqué sur le bouton ci-dessous.</p>
<p>En utilisant ce lien, vous confirmez être la personne à qui ce courriel a été adressé et à qui cette invitation est destinée.</p>
<p><a class="btn btn-primary" href="{$a->inviteurl}">{$a->acceptinvitation}</a></p>
<p>Si vous ne souhaitez pas rejoindre ce cours, veuillez plutôt utiliser le lien suivant :</p>
<p><a class="btn btn-danger" href="{$a->rejecturl}">{$a->rejectinvitation}</a></p>
<p>Veuillez noter que ces liens expireront le <b>{$a->expiration}</b>.</p>
<p>Au plaisir de vous voir dans le cours.</p>
';
$string['emailmsgunsubscribe'] = '<span class="apple-link">Si vous pensez avoir reçu ce message par erreur, si vous avez besoin d\'aide ou si vous ne souhaitez plus recevoir d\'invitations pour ce cours, veuillez communiquer avec :</span> <a href="mailto:{$a->supportemail}">{$a->supportemail}</a>.';
$string['emailtitleuserenrolled'] = '{$a->userfullname} a accepté l\'invitation au cours {$a->coursefullname}.';
$string['enrolconfimation'] = 'Exiger que l\'étudiant confirme son inscription';
$string['err_cohortlist'] = 'Ou vous devez sélectionner des cohortes ici.';
$string['err_userlist'] = 'Ou vous devez sélectionner des utilisateurs ici.';
$string['event_invitation_accepted'] = 'Acceptation';
$string['event_invitation_attempted'] = 'Tentative';
$string['event_invitation_deleted'] = 'Suppression';
$string['event_invitation_rejected'] = 'Refus';
$string['event_invitation_sent'] = 'Envoi';
$string['event_invitation_updated'] = 'Mise à jour';
$string['event_invitation_viewed'] = 'Consultation';
$string['expiredtoken'] = 'Le jeton d\'invitation est expiré ou a déjà été utilisé.';
$string['extend_invite_sucess'] = 'Invitation prolongée avec succès';
$string['failuredescription'] = 'Échec : utilisateur avec l\'identifiant {$a->userid}, cours avec l\'identifiant \'{$a->courseid}\'. Raison : {$a->errormsg}.';
$string['half_minute'] = 'une demi-minute';
$string['header_email'] = 'Qui voulez-vous inviter?';
$string['header_role'] = 'Quel rôle voulez-vous attribuer à la personne invitée?';
$string['header_group'] = 'À quel groupe voulez-vous ajouter la personne invitée?';
$string['historyactions'] = 'Actions';
$string['historydateexpiration'] = 'Date d\'expiration';
$string['historydatesent'] = 'Date d\'envoi';
$string['historyexpires_in'] = 'expire dans';
$string['historyinvitee'] = 'Personne invitée';
$string['historyrole'] = 'Rôle';
$string['historystatus'] = 'Statut';
$string['historyundefinedrole'] = 'Impossible de trouver le rôle. Veuillez renvoyer l\'invitation en choisissant un autre rôle.';
$string['invitation:config'] = 'Configurer les instances d\'invitation';
$string['invitation:enrol'] = 'Inviter des utilisateurs';
$string['invitation:manage'] = 'Gérer les inscriptions par invitation';
$string['invitation:unenrol'] = 'Désinscrire des utilisateurs du cours';
$string['invitation:unenrolself'] = 'Se désinscrire du cours';
$string['invitation_acceptance_title'] = 'Acceptation de l\'invitation';
$string['invitationacceptance'] = '<p>Vous êtes invité(e) à accéder au cours <strong>{$a->coursefullname}</strong> en tant que <strong>{$a->rolename}</strong>. Veuillez confirmer que vous acceptez de rejoindre ce cours.</p>';
$string['invitationacceptancebutton'] = 'Accepter l\'invitation';
$string['invitationrejectbutton'] = 'Refuser l\'invitation';
$string['invitationrejected'] = 'Invitation refusée';
$string['invitationsuccess'] = 'Invitation envoyée avec succès';
$string['inviteexpiration'] = 'Expiration de l\'invitation';
$string['inviteexpiration_desc'] = 'Durée de validité d\'une invitation (en secondes). La valeur par défaut est de 2 semaines.';
$string['invitehistory'] = 'Historique des invitations';
$string['inviteusers'] = 'Inviter des utilisateurs';
$string['invtitation_rejected_notice'] = '<p>Cette invitation a été refusée.</p>';
$string['less_minute'] = 'moins d\'une minute';
$string['less_than_x_seconds'] = 'moins de {$a} secondes';
$string['loggedinnot'] = '<p>Vous devez vous connecter avant de pouvoir accepter cette invitation.</p>';
$string['message'] = 'Message';
$string['message_help_link'] = 'voir les instructions envoyées aux personnes invitées';
$string['noenddate'] = 'Aucune date de fin';
$string['noinvitationinstanceset'] = 'Aucune instance d\'inscription par invitation n\'a été trouvée. Veuillez d\'abord ajouter une instance d\'inscription par invitation à votre cours.';
$string['noinvitehistory'] = 'Aucune invitation envoyée pour le moment';
$string['nopermissiontosendinvitation'] = 'Vous n\'avez pas la permission d\'envoyer des invitations';
$string['norole'] = 'Veuillez choisir un rôle.';
$string['notify_inviter'] = 'M\'aviser à {$a->email} lorsque les personnes invitées acceptent cette invitation';
$string['notsentdescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} n\'a pas pu envoyer d\'invitation pour le cours avec l\'identifiant \'{$a->courseid}\', car aucun compte n\'est associé à l\'adresse courriel \'{$a->email}\'.';
$string['pluginname'] = 'Invitation';
$string['pluginname_desc'] = 'Le module Invitation permet d\'envoyer des invitations par courriel. Chaque invitation ne peut être utilisée qu\'une seule fois. Les utilisateurs qui cliquent sur le lien du courriel sont inscrits automatiquement.';
$string['registeredonly'] = 'Envoyer l\'invitation uniquement aux utilisateurs inscrits';
$string['registeredonly_help'] = 'L\'invitation sera envoyée uniquement aux adresses courriel associées à des utilisateurs inscrits sur le site.';
$string['rejecteddescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a refusé une invitation pour le cours avec l\'identifiant \'{$a->courseid}\'.';
$string['reminder'] = 'Rappel : ';
$string['resend_invite_sucess'] = 'Invitation renvoyée avec succès';
$string['returntocourse'] = 'Retour au cours';
$string['returntoinvite'] = 'Envoyer une autre invitation';
$string['revoke_invite_sucess'] = 'Invitation révoquée avec succès';
$string['sentdescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a envoyé une invitation pour le cours avec l\'identifiant \'{$a->courseid}\' à \'{$a->email}\'.';
$string['show_from_email'] = 'Permettre à la personne invitée de me contacter à {$a->email} (votre adresse apparaîtra dans le champ « De ». Sinon, le champ « De » sera {$a->supportemail})';
$string['status'] = 'Autoriser les invitations';
$string['status_desc'] = 'Permettre par défaut aux utilisateurs d\'inviter des personnes à s\'inscrire à un cours.';
$string['status_invite_active'] = 'Active';
$string['status_invite_expired'] = 'Expirée';
$string['status_invite_invalid'] = 'Invalide';
$string['status_invite_rejected'] = 'Refusée';
$string['status_invite_resent'] = 'Renvoyée';
$string['status_invite_revoked'] = 'Révoquée';
$string['status_invite_used'] = 'Acceptée';
$string['status_invite_used_expiration'] = '(l\'accès se termine le {$a})';
$string['status_invite_used_noaccess'] = '(n\'a plus accès)';
$string['subject'] = 'Objet';
$string['unenrol'] = 'Désinscrire l\'utilisateur';
$string['unenroluser'] = 'Voulez-vous vraiment désinscrire « {$a->user} » du cours « {$a->course} »?';
$string['updateddescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a prolongé l\'invitation pour le cours avec l\'identifiant \'{$a->courseid}\' envoyée à \'{$a->email}\'.';
$string['used_by'] = ' par {$a->username} ({$a->roles}, {$a->useremail}) le {$a->timeused}';
$string['usedefaultvalues'] = 'Utiliser l\'invitation avec les valeurs par défaut';
$string['usernotmatch'] = '<p>Cette invitation est destinée à un autre utilisateur.</p>';
$string['vieweddescription'] = 'L\'utilisateur avec l\'identifiant {$a->userid} a consulté l\'invitation pour le cours avec l\'identifiant \'{$a->courseid}\'.';
$string['x_days'] = '{$a} jours';
$string['x_minutes'] = '{$a} minutes';

// Account creation from an invitation.
$string['allowsignup'] = 'Permettre la création de compte à partir de l\'invitation';
$string['allowsignup_desc'] = 'Si cette option est activée, les personnes invitées qui n\'ont pas encore de compte peuvent en créer un directement à partir du lien d\'invitation en saisissant leur nom et un mot de passe. Leur adresse courriel sert de nom d\'utilisateur et l\'invitation est acceptée automatiquement.';
$string['signup_intro'] = '<p>Vous avez été invité(e) au cours <b>{$a->coursefullname}</b>. Vous n\'avez pas encore de compte : remplissez le formulaire ci-dessous pour le créer. Votre adresse courriel <b>{$a->email}</b> sera votre nom d\'utilisateur.</p>';
$string['signup_invalidusername'] = 'Cette adresse courriel ne peut pas être utilisée comme nom d\'utilisateur. Veuillez communiquer avec le soutien technique du site pour obtenir de l\'aide.';
$string['signup_submit'] = 'Créer mon compte et rejoindre le cours';
$string['signup_title'] = 'Créez votre compte';
$string['signup_usernameexists'] = 'Un compte avec ce nom d\'utilisateur existe déjà. Veuillez plutôt vous connecter, ou communiquer avec le soutien technique du site pour obtenir de l\'aide.';
