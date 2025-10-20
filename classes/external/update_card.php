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
 * Update card web service.
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
 * Update card web service class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class update_card extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'cardid' => new external_value(PARAM_INT, 'Card ID'),
            'title' => new external_value(PARAM_TEXT, 'Card title', VALUE_DEFAULT, null),
            'content' => new external_value(PARAM_RAW, 'Card content', VALUE_DEFAULT, null),
            'type' => new external_value(PARAM_TEXT, 'Card type', VALUE_DEFAULT, null),
        ]);
    }

    /**
     * Update a card.
     *
     * @param int $cardid Card ID.
     * @param string $title Card title.
     * @param string $content Card content.
     * @param string $type Card type.
     * @return array Update result.
     */
    public static function execute($cardid, $title, $content, $type) {
        global $USER;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'cardid' => $cardid,
            'title' => $title,
            'content' => $content,
            'type' => $type,
        ]);

        // Load card.
        $card = new card($params['cardid']);

        // Load board and get context.
        $board = new board($card->boardid);
        $context = $board->get_context();
        self::validate_context($context);

        // Check if user can edit this card.
        if (!$card->can_edit($USER->id, $context)) {
            throw new \moodle_exception('errorcannotpost', 'mod_mosaic');
        }

        // Prepare update data.
        $data = new \stdClass();
        if ($params['title'] !== null) {
            $data->title = $params['title'];
        }
        if ($params['content'] !== null) {
            $data->content = $params['content'];
        }
        if ($params['type'] !== null) {
            $data->type = $params['type'];
        }

        // Update the card.
        $card->update($data);

        // Trigger event.
        $event = \mod_mosaic\event\card_updated::create([
            'objectid' => $card->id,
            'context' => $context,
            'other' => [
                'boardid' => $board->id,
            ],
        ]);
        $event->trigger();

        return [
            'success' => true,
            'card' => [
                'id' => $card->id,
                'title' => $card->title,
                'content' => $card->content,
                'type' => $card->type,
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
                'title' => new external_value(PARAM_TEXT, 'Card title'),
                'content' => new external_value(PARAM_RAW, 'Card content'),
                'type' => new external_value(PARAM_TEXT, 'Card type'),
                'timemodified' => new external_value(PARAM_INT, 'Time modified'),
            ]),
        ]);
    }
}
