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
 * Prints an instance of mod_mosaic.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$m = optional_param('m', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('mosaic', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
    $mosaic = $DB->get_record('mosaic', ['id' => $cm->instance], '*', MUST_EXIST);
} else {
    $mosaic = $DB->get_record('mosaic', ['id' => $m], '*', MUST_EXIST);
    $course = $DB->get_record('course', ['id' => $mosaic->course], '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('mosaic', $mosaic->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

// Trigger module viewed event.
$event = \mod_mosaic\event\course_module_viewed::create([
    'objectid' => $mosaic->id,
    'context' => $modulecontext,
]);
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('mosaic', $mosaic);
$event->trigger();

// Completion.
$completion = new completion_info($course);
$completion->set_module_viewed($cm);

// Set up the page.
$PAGE->set_url('/mod/mosaic/view.php', ['id' => $cm->id]);
$PAGE->set_title(format_string($mosaic->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($mosaic->intro) {
    echo $OUTPUT->box(format_module_intro('mosaic', $mosaic, $cm->id), 'generalbox mod_introbox', 'mosaicintro');
}

// Check view capability.
require_capability('mod/mosaic:view', $modulecontext);

// Determine user capabilities.
$canpost = has_capability('mod/mosaic:post', $modulecontext);
$canmoderate = has_capability('mod/mosaic:moderate', $modulecontext);

// Pass configuration to JavaScript.
$config = [
    'boardid' => $mosaic->id,
    'cmid' => $cm->id,
    'contextid' => $modulecontext->id,
    'courseid' => $course->id,
    'layout' => $mosaic->layout,
    'permissions' => [
        'canview' => true,
        'canpost' => $canpost,
        'canmoderate' => $canmoderate,
    ],
    'wwwroot' => $CFG->wwwroot,
    'sesskey' => sesskey(),
];

// Add the Vue.js app container.
echo html_writer::start_div('mosaic-board-container', ['id' => 'mosaic-board-app', 'data-config' => json_encode($config)]);
echo html_writer::div(
    html_writer::div(
        html_writer::tag('div', '', ['class' => 'spinner-border text-primary', 'role' => 'status']) .
        html_writer::tag('span', get_string('loading'), ['class' => 'sr-only']),
        'text-center'
    ),
    'mosaic-loading'
);
echo html_writer::end_div();

// Load the Vue.js application.
$PAGE->requires->js_call_amd('mod_mosaic/loader', 'init', [$config]);

// Finish the page.
echo $OUTPUT->footer();
