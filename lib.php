<?php
// This file is part of Moodle - http://moodle.org/
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
 * Library of interface functions and constants.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports a specific feature.
 *
 * @param string $feature FEATURE_xx constant for requested feature.
 * @return mixed True if the feature is supported, null if unknown.
 */
function mosaic_supports($feature) {
    switch($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_SHOW_DESCRIPTION:
            return true;
        case FEATURE_BACKUP_MOODLE2:
            return true;
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true;
        case FEATURE_GRADE_HAS_GRADE:
            return false;
        case FEATURE_GROUPS:
            return true;
        case FEATURE_GROUPINGS:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mosaic into the database.
 *
 * @param stdClass $mosaic An object from the form in mod_form.php.
 * @param mod_mosaic_mod_form $mform The form.
 * @return int The id of the newly inserted mosaic record.
 */
function mosaic_add_instance($mosaic, $mform = null) {
    global $DB;

    $mosaic->timecreated = time();
    $mosaic->timemodified = $mosaic->timecreated;

    // Set default values if not provided.
    if (empty($mosaic->layout)) {
        $mosaic->layout = 'wall';
    }

    // Insert the record.
    $mosaic->id = $DB->insert_record('mosaic', $mosaic);

    return $mosaic->id;
}

/**
 * Updates an instance of the mosaic in the database.
 *
 * @param stdClass $mosaic An object from the form in mod_form.php.
 * @param mod_mosaic_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function mosaic_update_instance($mosaic, $mform = null) {
    global $DB;

    $mosaic->timemodified = time();
    $mosaic->id = $mosaic->instance;

    return $DB->update_record('mosaic', $mosaic);
}

/**
 * Removes an instance of the mosaic from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false otherwise.
 */
function mosaic_delete_instance($id) {
    global $DB;

    if (!$mosaic = $DB->get_record('mosaic', ['id' => $id])) {
        return false;
    }

    // Delete all cards.
    $DB->delete_records('mosaic_cards', ['boardid' => $id]);

    // Delete all reactions.
    $cardids = $DB->get_fieldset_select('mosaic_cards', 'id', 'boardid = ?', [$id]);
    if (!empty($cardids)) {
        list($sql, $params) = $DB->get_in_or_equal($cardids);
        $DB->delete_records_select('mosaic_reactions', "cardid $sql", $params);
        $DB->delete_records_select('mosaic_comments', "cardid $sql", $params);
    }

    // Delete sections.
    $DB->delete_records('mosaic_sections', ['boardid' => $id]);

    // Delete the mosaic instance.
    $DB->delete_records('mosaic', ['id' => $id]);

    return true;
}

/**
 * Returns the information on whether the module supports a feature.
 *
 * @param cm_info $cm Course module info object.
 * @return cached_cm_info Info to customise main mosaic display.
 */
function mosaic_get_coursemodule_info($cm) {
    global $DB;

    $mosaic = $DB->get_record('mosaic', ['id' => $cm->instance], 'id, name, intro, introformat');
    if (!$mosaic) {
        return null;
    }

    $info = new cached_cm_info();
    $info->name = $mosaic->name;

    if ($cm->showdescription) {
        $info->content = format_module_intro('mosaic', $mosaic, $cm->id, false);
    }

    return $info;
}

/**
 * Return a small object with summary information about what a user has done.
 *
 * @param stdClass $course The course record.
 * @param stdClass $user The user record.
 * @param cm_info|stdClass $mod The course module info object or record.
 * @param stdClass $mosaic The mosaic instance record.
 * @return stdClass|null Summary of user activity.
 */
function mosaic_user_outline($course, $user, $mod, $mosaic) {
    global $DB;

    $count = $DB->count_records('mosaic_cards', [
        'boardid' => $mosaic->id,
        'userid' => $user->id,
        'status' => 1,
    ]);

    if ($count) {
        $result = new stdClass();
        $result->info = get_string('postcountuser', 'mod_mosaic', $count);
        $result->time = $DB->get_field_sql(
            'SELECT MAX(timecreated) FROM {mosaic_cards} WHERE boardid = ? AND userid = ?',
            [$mosaic->id, $user->id]
        );
        return $result;
    }
    return null;
}

/**
 * Print a detailed representation of what a user has done.
 *
 * @param stdClass $course The course record.
 * @param stdClass $user The user record.
 * @param cm_info|stdClass $mod The course module info object or record.
 * @param stdClass $mosaic The mosaic instance record.
 * @return void
 */
function mosaic_user_complete($course, $user, $mod, $mosaic) {
    global $DB, $OUTPUT;

    $cards = $DB->get_records('mosaic_cards', [
        'boardid' => $mosaic->id,
        'userid' => $user->id,
        'status' => 1,
    ], 'timecreated DESC');

    if ($cards) {
        echo $OUTPUT->heading(get_string('posts', 'mod_mosaic') . ': ' . count($cards), 3);
        foreach ($cards as $card) {
            echo html_writer::div(
                userdate($card->timecreated) . ' - ' . format_string($card->title),
                'mosaic-user-post'
            );
        }
    } else {
        echo html_writer::div(get_string('noposts', 'mod_mosaic'));
    }
}
