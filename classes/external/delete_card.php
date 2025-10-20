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
 * Delete card web service.
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
 * Delete card web service class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class delete_card extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'cardid' => new external_value(PARAM_INT, 'Card ID'),
        ]);
    }

    /**
     * Delete a card.
     *
     * @param int $cardid Card ID.
     * @return array Delete result.
     */
    public static function execute($cardid) {
        global $USER;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'cardid' => $cardid,
        ]);

        // Load card.
        $card = new card($params['cardid']);

        // Load board and get context.
        $board = new board($card->boardid);
        $context = $board->get_context();
        self::validate_context($context);

        // Check if user can delete this card.
        if (!$card->can_delete($USER->id, $context)) {
            throw new \moodle_exception('errordeletingcard', 'mod_mosaic');
        }

        // Delete the card (soft delete by default).
        $card->delete(false);

        // Trigger event.
        $event = \mod_mosaic\event\card_deleted::create([
            'objectid' => $card->id,
            'context' => $context,
            'other' => [
                'boardid' => $board->id,
            ],
        ]);
        $event->trigger();

        return [
            'success' => true,
            'cardid' => $card->id,
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
            'cardid' => new external_value(PARAM_INT, 'Deleted card ID'),
        ]);
    }
}
