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
 * Lets an invitee without an account create one, then accepts the invitation.
 *
 * The invitation token was emailed to the invitee, so following it proves ownership of the
 * email address. The account is therefore created already confirmed.
 *
 * @package    enrol_invitation
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../config.php');
require_once($CFG->dirroot . '/enrol/invitation/locallib.php');
require_once($CFG->dirroot . '/enrol/invitation/signup_form.php');
require_once($CFG->dirroot . '/user/lib.php');

$token = required_param('token', PARAM_ALPHANUM);

$url = new moodle_url('/enrol/invitation/signup.php', ['token' => $token]);
$enrolurl = new moodle_url('/enrol/invitation/enrol.php', ['token' => $token]);

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('login');
$pagetitle = get_string('signup_title', 'enrol_invitation');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($SITE->fullname);

// Anything other than an unused, unexpired invitation for a person without an account
// is handled by enrol.php (expired message, login, etc.).
$invitation = $DB->get_record('enrol_invitation', ['token' => $token, 'tokenused' => false]);
if (
    empty($invitation)
    || $invitation->timeexpiration < time()
    || !invitation_signup_allowed($invitation)
    || (isloggedin() && !isguestuser())
) {
    redirect($enrolurl);
}

// Used by preparenoticeobject().
$course = $DB->get_record('course', ['id' => $invitation->courseid], '*', MUST_EXIST);

$username = core_text::strtolower(trim($invitation->email));

$mform = new invitation_signup_form($url, ['email' => $invitation->email]);
$mform->set_data(['token' => $token]);

$errormessage = '';
if ($data = $mform->get_data()) {
    // The email must be usable as a username and the username must be free.
    if ($username !== core_user::clean_field($username, 'username')) {
        $errormessage = get_string('signup_invalidusername', 'enrol_invitation');
    } else if ($DB->record_exists('user', ['username' => $username, 'mnethostid' => $CFG->mnet_localhost_id])) {
        $errormessage = get_string('signup_usernameexists', 'enrol_invitation');
    } else {
        $user = new stdClass();
        $user->auth = 'manual';
        $user->confirmed = 1;
        $user->mnethostid = $CFG->mnet_localhost_id;
        $user->username = $username;
        $user->email = trim($invitation->email);
        $user->firstname = trim($data->firstname);
        $user->lastname = trim($data->lastname);
        $user->password = $data->password;
        $user->lang = current_language();

        $userid = user_create_user($user, true, true);

        // Log the new user in, then let enrol.php accept the invitation as usual
        // (enrolment, groups, token marked used, inviter notification).
        $user = get_complete_user_data('id', $userid);
        complete_user_login($user);

        $enrolurl->param('confirm', 1);
        redirect($enrolurl);
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($pagetitle);
echo $OUTPUT->box(get_string('signup_intro', 'enrol_invitation', preparenoticeobject($invitation)));
if ($errormessage !== '') {
    echo $OUTPUT->notification($errormessage, \core\output\notification::NOTIFY_ERROR);
}
$mform->display();
echo $OUTPUT->footer();
