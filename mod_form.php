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
 * The main mod_mosaic configuration form.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class mod_mosaic_mod_form extends moodleform_mod {

    /**
     * Defines forms elements.
     *
     * @return void
     */
    protected function definition() {
        global $CFG;

        $mform = $this->_form;

        // Adding the "general" fieldset, where all the common settings are shown.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('mosaicname', 'mod_mosaic'), ['size' => '64']);
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

        // Adding the standard "intro" and "introformat" fields.
        $this->standard_intro_elements();

        // Board layout selection.
        $mform->addElement('header', 'layoutsettings', get_string('layoutsettings', 'mod_mosaic'));

        $layouts = [
            'wall' => get_string('layout_wall', 'mod_mosaic'),
            'grid' => get_string('layout_grid', 'mod_mosaic'),
            'canvas' => get_string('layout_canvas', 'mod_mosaic'),
            'stream' => get_string('layout_stream', 'mod_mosaic'),
            'timeline' => get_string('layout_timeline', 'mod_mosaic'),
        ];

        $mform->addElement('select', 'layout', get_string('layout', 'mod_mosaic'), $layouts);
        $mform->setDefault('layout', 'wall');
        $mform->addHelpButton('layout', 'layout', 'mod_mosaic');

        // Add standard elements.
        $this->standard_coursemodule_elements();

        // Add standard buttons.
        $this->add_action_buttons();
    }
}
