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
 * Form allowing an invitee without an account to create one.
 *
 * @package    enrol_invitation
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Account creation form for invitees.
 *
 * Expected custom data: 'email' => the invitation email address.
 */
class invitation_signup_form extends moodleform {
    /**
     * Form definition.
     */
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('hidden', 'token');
        $mform->setType('token', PARAM_ALPHANUM);

        // The email comes from the invitation and cannot be changed. It is also the username.
        $mform->addElement('static', 'emaildisplay', get_string('email'), s($this->_customdata['email']));

        $mform->addElement('text', 'firstname', get_string('firstname'), 'maxlength="100" size="30"');
        $mform->setType('firstname', PARAM_NOTAGS);
        $mform->addRule('firstname', get_string('missingfirstname'), 'required', null, 'client');

        $mform->addElement('text', 'lastname', get_string('lastname'), 'maxlength="100" size="30"');
        $mform->setType('lastname', PARAM_NOTAGS);
        $mform->addRule('lastname', get_string('missinglastname'), 'required', null, 'client');

        $policy = print_password_policy();
        if (!empty($policy)) {
            $mform->addElement('static', 'passwordpolicyinfo', '', $policy);
        }

        $mform->addElement('password', 'password', get_string('password'), ['autocomplete' => 'new-password']);
        $mform->setType('password', PARAM_RAW);
        $mform->addRule('password', get_string('required'), 'required', null, 'client');

        $mform->addElement('password', 'password2', get_string('password') . ' (' . get_string('again') . ')',
            ['autocomplete' => 'new-password']);
        $mform->setType('password2', PARAM_RAW);
        $mform->addRule('password2', get_string('required'), 'required', null, 'client');

        $this->add_action_buttons(false, get_string('signup_submit', 'enrol_invitation'));
    }

    /**
     * Validate the submitted data.
     *
     * @param array $data
     * @param array $files
     * @return array errors
     */
    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (trim($data['firstname']) === '') {
            $errors['firstname'] = get_string('missingfirstname');
        }
        if (trim($data['lastname']) === '') {
            $errors['lastname'] = get_string('missinglastname');
        }

        if ($data['password'] !== $data['password2']) {
            $errors['password2'] = get_string('passwordsdiffer');
        } else {
            $errmsg = '';
            if (!check_password_policy($data['password'], $errmsg)) {
                $errors['password'] = $errmsg;
            }
        }

        return $errors;
    }
}
