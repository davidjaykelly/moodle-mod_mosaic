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
 * Board model class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

namespace mod_mosaic;

/**
 * Board model class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class board {

    /** @var int Board ID */
    public $id;

    /** @var int Course ID */
    public $course;

    /** @var string Board name */
    public $name;

    /** @var string Introduction text */
    public $intro;

    /** @var int Introduction format */
    public $introformat;

    /** @var string Layout type */
    public $layout;

    /** @var string Theme configuration JSON */
    public $theme_config;

    /** @var string Board settings JSON */
    public $settings;

    /** @var int Template ID */
    public $template_id;

    /** @var int Time created */
    public $timecreated;

    /** @var int Time modified */
    public $timemodified;

    /**
     * Constructor.
     *
     * @param int $id Board ID.
     * @throws \dml_exception If board not found.
     */
    public function __construct($id = 0) {
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * Load board from database.
     *
     * @param int $id Board ID.
     * @return void
     * @throws \dml_exception If board not found.
     */
    public function load($id) {
        global $DB;

        $record = $DB->get_record('mosaic', ['id' => $id], '*', MUST_EXIST);

        foreach ($record as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get board by ID.
     *
     * @param int $id Board ID.
     * @return board|null Board object or null if not found.
     */
    public static function get_by_id($id) {
        global $DB;

        if (!$DB->record_exists('mosaic', ['id' => $id])) {
            return null;
        }

        return new self($id);
    }

    /**
     * Get all cards for this board.
     *
     * @param bool $activeonly Whether to only return active cards.
     * @return array Array of card objects.
     */
    public function get_cards($activeonly = true) {
        global $DB;

        $params = ['boardid' => $this->id];
        if ($activeonly) {
            $params['status'] = 1;
        }

        return $DB->get_records('mosaic_cards', $params, 'timecreated ASC');
    }

    /**
     * Get all sections for this board.
     *
     * @return array Array of section objects.
     */
    public function get_sections() {
        global $DB;
        return $DB->get_records('mosaic_sections', ['boardid' => $this->id], 'position ASC');
    }

    /**
     * Get board settings as associative array.
     *
     * @return array Board settings.
     */
    public function get_settings() {
        if (empty($this->settings)) {
            return [];
        }
        return json_decode($this->settings, true) ?: [];
    }

    /**
     * Get theme configuration as associative array.
     *
     * @return array Theme configuration.
     */
    public function get_theme_config() {
        if (empty($this->theme_config)) {
            return [];
        }
        return json_decode($this->theme_config, true) ?: [];
    }

    /**
     * Update board settings.
     *
     * @param array $settings Settings array.
     * @return bool True on success.
     */
    public function update_settings(array $settings) {
        global $DB;

        $this->settings = json_encode($settings);
        $this->timemodified = time();

        return $DB->update_record('mosaic', $this);
    }

    /**
     * Update theme configuration.
     *
     * @param array $themeconfig Theme configuration array.
     * @return bool True on success.
     */
    public function update_theme_config(array $themeconfig) {
        global $DB;

        $this->theme_config = json_encode($themeconfig);
        $this->timemodified = time();

        return $DB->update_record('mosaic', $this);
    }

    /**
     * Get course module for this board.
     *
     * @return \stdClass Course module object.
     * @throws \dml_exception If course module not found.
     */
    public function get_cm() {
        return get_coursemodule_from_instance('mosaic', $this->id, $this->course, false, MUST_EXIST);
    }

    /**
     * Get context for this board.
     *
     * @return \context_module Module context.
     */
    public function get_context() {
        $cm = $this->get_cm();
        return \context_module::instance($cm->id);
    }

    /**
     * Delete this board and all related data.
     *
     * @return bool True on success.
     */
    public function delete() {
        global $DB;

        // Delete all cards and their reactions/comments.
        $cards = $DB->get_records('mosaic_cards', ['boardid' => $this->id]);
        foreach ($cards as $card) {
            $cardobj = new card(0);
            $cardobj->id = $card->id;
            $cardobj->delete();
        }

        // Delete sections.
        $DB->delete_records('mosaic_sections', ['boardid' => $this->id]);

        // Delete board.
        return $DB->delete_records('mosaic', ['id' => $this->id]);
    }
}
