<?php
// This file is part of the UCLA Site Invitation Plugin for Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local library file to include classes and functions used.
 *
 * @package    enrol_invitation
 * @copyright  2013 UC Regents
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Invitation manager that handles the handling of invitation information.
 *
 * @copyright  2013 UC Regents
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class invitation_manager {
    /**
     * Course id.
     * @var int
     */
    private $courseid = null;

    /**
     * The invitation enrol instance of a course.
     *
     * @var int
     */
    private $enrolinstance = null;

    /**
     * Constant for revoking an active invitation.
     */
    const INVITE_REVOKE = 1;

    /**
     * Constant for extending the expiration time of an active invitation.
     */
    const INVITE_EXTEND = 2;

    /**
     * Constant for resending an expired or revoked invite.
     */
    const INVITE_RESEND = 3;

    /**
     * Constructor.
     *
     * @param int $courseid
     * @param boolean $instancemustexist
     */
    public function __construct($courseid, $instancemustexist = true) {
        $this->courseid = $courseid;
        $this->enrolinstance = $this->get_invitation_instance($courseid, $instancemustexist);
    }

    /**
     * Return HTML invitation menu link for a given course.
     *
     * It's mostly useful to add a link in a block menu - by default icon is
     * displayed.
     * 
     * @param boolean $withicon - set to false to not display the icon
     * @return
     */
    public function get_menu_link($withicon = true) {
        global $OUTPUT;

        $inviteicon = '';
        $link = '';

        if (has_capability('enrol/invitation:enrol', context_course::instance($this->courseid))) {

            // Display an icon with requested (css can be changed in stylesheet).
            if ($withicon) {
                $inviteicon = html_writer::empty_tag('img', array('alt' => "invitation", 'class' => "enrol_invitation_item_icon",
                    'title' => "invitation", 'src' => $OUTPUT->pix_url('invite', 'enrol_invitation')));
            }

            $link = html_writer::link(
                            new moodle_url('/enrol/invitation/invitation.php',
                                    array('courseid' => $this->courseid)), $inviteicon . get_string('inviteusers',
                                        'enrol_invitation'));
        }

        return $link;
    }

    /**
     * Helper function to get privacy notice for project sites.
     *
     * @param int $courseid
     * @return string       Returns null if there is no privacy notice.
     */
    public static function get_project_privacy_notice($courseid) {
        global $CFG;
        require_once($CFG->dirroot . '/' . $CFG->admin . '/tool/uclasiteindicator/lib.php');
        $ret_val = null;

        // Get current course's site type group.
        try {
            $siteindicator_site = new siteindicator_site($courseid);
            $site_type = $siteindicator_site->property->type;
            $siteindicator_manager = new siteindicator_manager();
            $site_type_group = $siteindicator_manager->get_rolegroup_for_type($site_type);

            // If site type group is project, then return some notice.
            if ($site_type_group == siteindicator_manager::SITE_GROUP_TYPE_PROJECT) {
                $ret_val = "\n\n" . get_string('project_privacy_notice', 'enrol_invitation');
            }
        } catch (Exception $e) {
            // Throws exception if no site type found.
        }

        return $ret_val;
    }

    /**
     * Send invitation (create a unique token for each of them).
     *
     * @param array $data       data processed from the invite form, or an invite
     * @param bool $resend     resend the invite specified by $data
     */
    public function send_invitations($data, $resend = false) {
        global $DB, $CFG, $COURSE, $SITE, $USER;

        if (has_capability('enrol/invitation:enrol', context_course::instance($data->courseid))) {

            // Get course record, to be used later.
            $course = $DB->get_record('course', array('id' => $data->courseid), '*', MUST_EXIST);

            if (!empty($data->email)) {

                // Create a new token only if we are not resending an active invite.
                if ($resend) {
                    $token = $data->token;
                } else {
                    // Create unique token for invitation.
                    do {
                        $token = uniqid();
                        $existingtoken = $DB->get_record('enrol_invitation', array('token' => $token));
                    } while (!empty($existingtoken));
                }

                // Save token information in config (token value, course id, TODO: role id).
                $invitation = new stdClass();
                $invitation->email = $data->email;
                $invitation->courseid = $data->courseid;
                $invitation->token = $token;
                $invitation->tokenused = false;
                $invitation->roleid = $resend ? $data->roleid : $data->role_group['roleid'];

                // Set time.
                $timesent = time();
                $invitation->timesent = $timesent;
                $invitation->timeexpiration = $timesent +
                        get_config('enrol_invitation', 'enrolperiod');

                // Update invite to have the proper timesent/timeexpiration.
                if ($resend) {
                    $DB->set_field('enrol_invitation', 'timeexpiration', $invitation->timeexpiration,
                            array('courseid' => $data->courseid,  'id' => $data->id));

                    // Prepend subject heading with a 'Reminder' string.
                    $invitation->subject = get_string('reminder', 'enrol_invitation');
                }

                $invitation->subject .= $data->subject;

                $invitation->inviterid = $USER->id;
                $invitation->notify_inviter = empty($data->notify_inviter) ? 0 : 1;
                $invitation->show_from_email = empty($data->show_from_email) ? 0 : 1;

                // Construct message: custom (if any) + template.
                $message = '';
                if (!empty($data->message)) {
                    $message .= get_string('instructormsg', 'enrol_invitation',
                            $data->message);
                    $invitation->message = $data->message;
                }

                $message_params = new stdClass();
                $message_params->fullname =
                        sprintf('%s: %s', $course->shortname, $course->fullname);
                $message_params->expiration = date('M j, Y g:ia', $invitation->timeexpiration);
                $inviteurl =  new moodle_url('/enrol/invitation/enrol.php',
                                array('token' => $token));
                $inviteurl = $inviteurl->out(false);

                // Append privacy notice, if needed.
                $privacy_notice = $this->get_project_privacy_notice($course->id);
                if (!empty($privacy_notice)) {
                    $inviteurl .= $privacy_notice;
                }

                // Append days expired, if needed.
                if (get_config('enrol_invitation', 'enabletempparticipant')) {
                    $tempparticipant = $DB->get_record('role',
                            array('shortname' => 'tempparticipant'));

                    // If inviting a temporary role, check how many days the
                    // role should be limited to.
                    if ($tempparticipant->id == $invitation->roleid) {
                        // If for some reason the daysexpire is empty, default to 3.
                        $daysexpire = 3;
                        if (!empty($data->daysexpire)) {
                            $daysexpire = $data->daysexpire;
                        }

                        $inviteurl .= "\n\n" . get_string('daysexpire_notice',
                                'enrol_invitation', $daysexpire);
                        $invitation->daysexpire = $daysexpire;
                    }
                }

                $message_params->inviteurl = $inviteurl;
                $message_params->supportemail = $CFG->supportemail;
                $message .= get_string('emailmsgtxt', 'enrol_invitation', $message_params);

                if (!$resend) {
                    $DB->insert_record('enrol_invitation', $invitation);
                }

                // Change FROM to be $CFG->supportemail if user has show_from_email off.
                $fromuser = $USER;
                if (empty($invitation->show_from_email)) {
                    $fromuser = new stdClass();
                    $fromuser->email = $CFG->supportemail;
                    $fromuser->firstname = '';
                    $fromuser->lastname = $SITE->fullname;
                    $fromuser->maildisplay = true;
                }

                // Send invitation to the user.
                $contactuser = new stdClass();
                $contactuser->email = $invitation->email;
                $contactuser->firstname = '';
                $contactuser->lastname = '';
                $contactuser->maildisplay = true;
                email_to_user($contactuser, $fromuser, $invitation->subject, $message);

                // Log activity after sending the email.
                if ($resend) {
                    add_to_log($course->id, 'course', 'invitation extend',
                            "../enrol/invitation/history.php?courseid=$course->id", $course->fullname);
                } else {
                    add_to_log($course->id, 'course', 'invitation send',
                            "../enrol/invitation/history.php?courseid=$course->id", $course->fullname);
                }
            }
        } else {
            throw new moodle_exception('cannotsendinvitation', 'enrol_invitation',
                    new moodle_url('/course/view.php', array('id' => $data['courseid'])));
        }
    }

    /**
     * Checks if user who accepted invite has an access expiration for their
     * enrollment.
     *
     * @param object $invite    Database record
     *
     * @return string           Returns expiration string. Blank if no
     *                          restriction.
     */
    public function get_access_expiration($invite) {
        $expiration = '';

        if (empty($invite->userid)) {
            return $expiration;
        }

        // Check to see if user has a time restriction on their access.
        $timeend = enrol_get_enrolment_end($invite->courseid, $invite->userid);
        if ($timeend === false) {
            // No active enrollment now.
            $expiration = get_string('status_invite_used_noaccess', 'enrol_invitation');
        } else if ($timeend > 0) {
            // Access will end on a certain date.
            $expiration = get_string('status_invite_used_expiration',
                    'enrol_invitation', date('M j, Y', $timeend));
        }
        return $expiration;
    }

    /**
     * Returns status of given invite.
     *
     * @param object $invite    Database record
     *
     * @return string           Returns invite status string.
     */
    public function get_invite_status($invite) {
        if (!is_object($invite)) {
            return get_string('status_invite_invalid', 'enrol_invitation');
        }

        if ($invite->tokenused) {
            // Invite was used already.
            $status = get_string('status_invite_used', 'enrol_invitation');
            return $status;
        } else if ($invite->timeexpiration < time()) {
            // Invite is expired.
            return get_string('status_invite_expired', 'enrol_invitation');
        } else {
            return get_string('status_invite_active', 'enrol_invitation');
        }
        // TO DO: add status_invite_revoked and status_invite_resent status.
    }

    /**
     * Return all invites for given course.
     *
     * @param int $courseid
     * @return array
     */
    public function get_invites($courseid = null) {
        global $DB;

        if (empty($courseid)) {
            $courseid = $this->courseid;
        }

        $invites = $DB->get_records('enrol_invitation', array('courseid' => $courseid));

        return $invites;
    }

    /**
     * Return the invitation instance for a specific course.
     *
     * Note: as using $PAGE variable, this function can only be called in a
     * Moodle script page.
     *
     * @param int $courseid
     * @param boolean $mustexist when set, an exception is thrown if no instance is found
     * @return object
     */
    public function get_invitation_instance($courseid, $mustexist = false) {
        global $PAGE, $CFG, $DB;

        if (($courseid == $this->courseid) and !empty($this->enrolinstance)) {
            return $this->enrolinstance;
        }

        // Find enrolment instance.
        $instance = null;
        require_once("$CFG->dirroot/enrol/locallib.php");
        $course = $DB->get_record('course', array('id' => $courseid), '*', MUST_EXIST);
        $manager = new course_enrolment_manager($PAGE, $course);
        foreach ($manager->get_enrolment_instances() as $tempinstance) {
            if ($tempinstance->enrol == 'invitation') {
                if ($instance === null) {
                    $instance = $tempinstance;
                }
            }
        }

        if ($mustexist and empty($instance)) {
            throw new moodle_exception('noinvitationinstanceset', 'enrol_invitation');
        }

        return $instance;
    }

    /**
     * Enrol the user following the invitation data.
     * @param object $invitation
     */
    public function enroluser($invitation) {
        global $USER;

        // Handle daysexpire by adding making the enrollment expiration be the
        // end of the day after daysexpire days.
        $timeend = 0;
        if (!empty($invitation->daysexpire)) {
            // Get today's date as a timestamp. Ignore the current time.
            $today = strtotime(date('Y/m/d'));
            // Get the day in the future.
            $timeend = strtotime(sprintf('+%d days', $invitation->daysexpire), $today);
            // But make sure the timestamp is for the end of that day. Remember
            // that 86400 is the total seconds in a day. So -1 that is right
            // before midnight.
            $timeend += 86399;
        }

        $enrol = enrol_get_plugin('invitation');
        $enrol->enrol_user($this->enrolinstance, $USER->id,
                $invitation->roleid, 0, $timeend);
    }

    /**
     * Figures out who used an invite.
     *
     * @param object $invite    Invitation record
     *
     * @return object           Returns an object with following values:
     *                          ['username'] - name of who used invite
     *                          ['useremail'] - email of who used invite
     *                          ['roles'] - roles the user has for course that
     *                                      they were invited
     *                          ['timeused'] - formatted string of time used
     *                          Returns false on error or if invite wasn't used.
     */
    public function who_used_invite($invite) {
        global $DB;
        $ret_val = new stdClass();

        if (empty($invite->userid) || empty($invite->tokenused) ||
                empty($invite->courseid) || empty($invite->timeused)) {
            return false;
        }

        // Find user.
        $user = $DB->get_record('user', array('id' => $invite->userid));
        if (empty($user)) {
            return false;
        }
        $ret_val->username = sprintf('%s %s', $user->firstname, $user->lastname);
        $ret_val->useremail = $user->email;

        // Find their roles for course.
        $ret_val->roles = get_user_roles_in_course($invite->userid, $invite->courseid);
        if (empty($ret_val->roles)) {
            // If no roles, then they must have been booted out later.
            return false;
        }
        $ret_val->roles = strip_tags($ret_val->roles);

        // Format string when invite was used.
        $ret_val->timeused = date('M j, Y g:ia', $invite->timeused);

        return $ret_val;
    }

}

/**
 * Setups the object used in the notice strings for when a user is accepting
 * a site invitation.
 *
 * @param object $invitation
 * @return object
 */
function prepare_notice_object($invitation) {
    global $CFG, $course, $DB;

    $noticeobject = new stdClass();
    $noticeobject->email = $invitation->email;
    $noticeobject->coursefullname = $course->fullname;
    $noticeobject->supportemail = $CFG->supportemail;

    // Get role name for use in acceptance message.
    $role = $DB->get_record('role', array('id' => $invitation->roleid));
    $noticeobject->rolename = $role->name;
    $noticeobject->roledescription = strip_tags($role->description);

    return $noticeobject;
}

/**
 * Prints out tabs and highlights the appropiate current tab.
 * 
 * @param string $active_tab  Either 'invite' or 'history'
 */
function print_page_tabs($active_tab) {
    global $CFG, $COURSE;

    $tabs[] = new tabobject('history',
                    new moodle_url('/enrol/invitation/history.php',
                            array('courseid' => $COURSE->id)),
                    get_string('invitehistory', 'enrol_invitation'));
    $tabs[] = new tabobject('invite',
                    new moodle_url('/enrol/invitation/invitation.php',
                            array('courseid' => $COURSE->id)),
                    get_string('inviteusers', 'enrol_invitation'));

    // Display tabs here.
    print_tabs(array($tabs), $active_tab);
}