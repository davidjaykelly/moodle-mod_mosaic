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
 * Create card web service.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

namespace mod_mosaic\external;

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use mod_mosaic\board;
use mod_mosaic\card;

/**
 * Create card web service class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class create_card extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'boardid' => new external_value(PARAM_INT, 'Board ID'),
            'type' => new external_value(PARAM_TEXT, 'Card type', VALUE_DEFAULT, 'text'),
            'title' => new external_value(PARAM_TEXT, 'Card title', VALUE_DEFAULT, ''),
            'content' => new external_value(PARAM_RAW, 'Card content', VALUE_DEFAULT, ''),
            'section_id' => new external_value(PARAM_INT, 'Section ID', VALUE_DEFAULT, null),
            'anonymous' => new external_value(PARAM_BOOL, 'Post anonymously', VALUE_DEFAULT, false),
        ]);
    }

    /**
     * Create a new card.
     *
     * @param int $boardid Board ID.
     * @param string $type Card type.
     * @param string $title Card title.
     * @param string $content Card content.
     * @param int $sectionid Section ID.
     * @param bool $anonymous Anonymous flag.
     * @return array Card data.
     */
    public static function execute($boardid, $type, $title, $content, $sectionid, $anonymous) {
        global $USER;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'boardid' => $boardid,
            'type' => $type,
            'title' => $title,
            'content' => $content,
            'section_id' => $sectionid,
            'anonymous' => $anonymous,
        ]);

        // Load board and validate context.
        $board = new board($params['boardid']);
        $context = $board->get_context();
        self::validate_context($context);

        // Check permission.
        require_capability('mod/mosaic:post', $context);

        // Create card data object.
        $data = new \stdClass();
        $data->boardid = $params['boardid'];
        $data->type = $params['type'];
        $data->title = $params['title'];
        $data->content = $params['content'];
        $data->section_id = $params['section_id'];
        $data->anonymous = $params['anonymous'] ? 1 : 0;

        // Create the card.
        $card = card::create($data);

        // Trigger card created event.
        $event = \mod_mosaic\event\card_created::create([
            'objectid' => $card->id,
            'context' => $context,
            'other' => [
                'boardid' => $board->id,
            ],
        ]);
        $event->trigger();

        // Return card data.
        return [
            'success' => true,
            'card' => [
                'id' => $card->id,
                'boardid' => $card->boardid,
                'userid' => $card->userid,
                'section_id' => $card->section_id,
                'type' => $card->type,
                'title' => $card->title,
                'content' => $card->content,
                'status' => $card->status,
                'anonymous' => $card->anonymous,
                'timecreated' => $card->timecreated,
                'timemodified' => $card->timemodified,
            ],
        ];
    }

    /**
     * Returns description of method result value.
     *
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure([
            'success' => new external_value(PARAM_BOOL, 'Success status'),
            'card' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Card ID'),
                'boardid' => new external_value(PARAM_INT, 'Board ID'),
                'userid' => new external_value(PARAM_INT, 'User ID'),
                'section_id' => new external_value(PARAM_INT, 'Section ID', VALUE_OPTIONAL),
                'type' => new external_value(PARAM_TEXT, 'Card type'),
                'title' => new external_value(PARAM_TEXT, 'Card title'),
                'content' => new external_value(PARAM_RAW, 'Card content'),
                'status' => new external_value(PARAM_INT, 'Status'),
                'anonymous' => new external_value(PARAM_INT, 'Anonymous flag'),
                'timecreated' => new external_value(PARAM_INT, 'Time created'),
                'timemodified' => new external_value(PARAM_INT, 'Time modified'),
            ]),
        ]);
    }
}
