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
 * Card model class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

namespace mod_mosaic;

/**
 * Card model class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class card {

    /** @var int Card ID */
    public $id;

    /** @var int Board ID */
    public $boardid;

    /** @var int User ID */
    public $userid;

    /** @var int Section ID */
    public $section_id;

    /** @var string Card type */
    public $type;

    /** @var string Card title */
    public $title;

    /** @var string Card content */
    public $content;

    /** @var string Media data JSON */
    public $media_data;

    /** @var string Position data JSON */
    public $position_data;

    /** @var string Style data JSON */
    public $style_data;

    /** @var int Status */
    public $status;

    /** @var int Anonymous flag */
    public $anonymous;

    /** @var int Time created */
    public $timecreated;

    /** @var int Time modified */
    public $timemodified;

    /**
     * Constructor.
     *
     * @param int $id Card ID.
     * @throws \dml_exception If card not found.
     */
    public function __construct($id = 0) {
        if ($id) {
            $this->load($id);
        }
    }

    /**
     * Load card from database.
     *
     * @param int $id Card ID.
     * @return void
     * @throws \dml_exception If card not found.
     */
    public function load($id) {
        global $DB;

        $record = $DB->get_record('mosaic_cards', ['id' => $id], '*', MUST_EXIST);

        foreach ($record as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * Get card by ID.
     *
     * @param int $id Card ID.
     * @return card|null Card object or null if not found.
     */
    public static function get_by_id($id) {
        global $DB;

        if (!$DB->record_exists('mosaic_cards', ['id' => $id])) {
            return null;
        }

        return new self($id);
    }

    /**
     * Create a new card.
     *
     * @param \stdClass $data Card data.
     * @return card New card object.
     * @throws \dml_exception If creation fails.
     */
    public static function create($data) {
        global $DB, $USER;

        $record = new \stdClass();
        $record->boardid = $data->boardid;
        $record->userid = isset($data->userid) ? $data->userid : $USER->id;
        $record->section_id = isset($data->section_id) ? $data->section_id : null;
        $record->type = isset($data->type) ? $data->type : 'text';
        $record->title = isset($data->title) ? $data->title : '';
        $record->content = isset($data->content) ? $data->content : '';
        $record->media_data = isset($data->media_data) ? json_encode($data->media_data) : null;
        $record->position_data = isset($data->position_data) ? json_encode($data->position_data) : null;
        $record->style_data = isset($data->style_data) ? json_encode($data->style_data) : null;
        $record->status = 1;
        $record->anonymous = isset($data->anonymous) ? $data->anonymous : 0;
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;

        $id = $DB->insert_record('mosaic_cards', $record);

        return new self($id);
    }

    /**
     * Update the card.
     *
     * @param \stdClass $data Update data.
     * @return bool True on success.
     */
    public function update($data) {
        global $DB;

        if (isset($data->title)) {
            $this->title = $data->title;
        }
        if (isset($data->content)) {
            $this->content = $data->content;
        }
        if (isset($data->type)) {
            $this->type = $data->type;
        }
        if (isset($data->section_id)) {
            $this->section_id = $data->section_id;
        }
        if (isset($data->media_data)) {
            $this->media_data = json_encode($data->media_data);
        }
        if (isset($data->position_data)) {
            $this->position_data = json_encode($data->position_data);
        }
        if (isset($data->style_data)) {
            $this->style_data = json_encode($data->style_data);
        }

        $this->timemodified = time();

        return $DB->update_record('mosaic_cards', $this);
    }

    /**
     * Delete the card.
     *
     * @param bool $hard If true, permanently delete. Otherwise just mark as deleted.
     * @return bool True on success.
     */
    public function delete($hard = false) {
        global $DB;

        if ($hard) {
            // Delete reactions.
            $DB->delete_records('mosaic_reactions', ['cardid' => $this->id]);

            // Delete comments.
            $DB->delete_records('mosaic_comments', ['cardid' => $this->id]);

            // Delete card.
            return $DB->delete_records('mosaic_cards', ['id' => $this->id]);
        } else {
            // Soft delete - just mark as deleted.
            $this->status = 0;
            $this->timemodified = time();
            return $DB->update_record('mosaic_cards', $this);
        }
    }

    /**
     * Get all reactions for this card.
     *
     * @return array Array of reaction objects.
     */
    public function get_reactions() {
        global $DB;
        return $DB->get_records('mosaic_reactions', ['cardid' => $this->id]);
    }

    /**
     * Get all comments for this card.
     *
     * @return array Array of comment objects.
     */
    public function get_comments() {
        global $DB;
        return $DB->get_records('mosaic_comments', ['cardid' => $this->id], 'timecreated ASC');
    }

    /**
     * Add a reaction to this card.
     *
     * @param int $userid User ID.
     * @param string $reaction Reaction emoji/type.
     * @return int Reaction ID.
     */
    public function add_reaction($userid, $reaction) {
        global $DB;

        // Check if user already has this reaction.
        if ($DB->record_exists('mosaic_reactions', ['cardid' => $this->id, 'userid' => $userid, 'reaction' => $reaction])) {
            return false;
        }

        $record = new \stdClass();
        $record->cardid = $this->id;
        $record->userid = $userid;
        $record->reaction = $reaction;
        $record->timecreated = time();

        return $DB->insert_record('mosaic_reactions', $record);
    }

    /**
     * Remove a reaction from this card.
     *
     * @param int $userid User ID.
     * @param string $reaction Reaction emoji/type.
     * @return bool True on success.
     */
    public function remove_reaction($userid, $reaction) {
        global $DB;
        return $DB->delete_records('mosaic_reactions', ['cardid' => $this->id, 'userid' => $userid, 'reaction' => $reaction]);
    }

    /**
     * Add a comment to this card.
     *
     * @param int $userid User ID.
     * @param string $comment Comment text.
     * @param int $parentid Parent comment ID for threading.
     * @return int Comment ID.
     */
    public function add_comment($userid, $comment, $parentid = null) {
        global $DB;

        $record = new \stdClass();
        $record->cardid = $this->id;
        $record->userid = $userid;
        $record->parentid = $parentid;
        $record->comment = $comment;
        $record->timecreated = time();
        $record->timemodified = $record->timecreated;

        return $DB->insert_record('mosaic_comments', $record);
    }

    /**
     * Get media data as associative array.
     *
     * @return array Media data.
     */
    public function get_media_data() {
        if (empty($this->media_data)) {
            return [];
        }
        return json_decode($this->media_data, true) ?: [];
    }

    /**
     * Get position data as associative array.
     *
     * @return array Position data.
     */
    public function get_position_data() {
        if (empty($this->position_data)) {
            return [];
        }
        return json_decode($this->position_data, true) ?: [];
    }

    /**
     * Get style data as associative array.
     *
     * @return array Style data.
     */
    public function get_style_data() {
        if (empty($this->style_data)) {
            return [];
        }
        return json_decode($this->style_data, true) ?: [];
    }

    /**
     * Check if user can edit this card.
     *
     * @param int $userid User ID.
     * @param \context_module $context Module context.
     * @return bool True if user can edit.
     */
    public function can_edit($userid, $context) {
        // Owner can edit if they have editownpost capability.
        if ($this->userid == $userid && has_capability('mod/mosaic:editownpost', $context)) {
            return true;
        }

        // Moderators can edit any post.
        if (has_capability('mod/mosaic:moderate', $context)) {
            return true;
        }

        return false;
    }

    /**
     * Check if user can delete this card.
     *
     * @param int $userid User ID.
     * @param \context_module $context Module context.
     * @return bool True if user can delete.
     */
    public function can_delete($userid, $context) {
        // Owner can delete if they have deleteownpost capability.
        if ($this->userid == $userid && has_capability('mod/mosaic:deleteownpost', $context)) {
            return true;
        }

        // Moderators can delete any post.
        if (has_capability('mod/mosaic:moderate', $context)) {
            return true;
        }

        return false;
    }
}
