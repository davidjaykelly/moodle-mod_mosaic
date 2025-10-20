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
 * Get board web service.
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
use external_multiple_structure;
use mod_mosaic\board;

/**
 * Get board web service class.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */
class get_board extends external_api {

    /**
     * Returns description of method parameters.
     *
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters([
            'boardid' => new external_value(PARAM_INT, 'Board ID'),
        ]);
    }

    /**
     * Get board data including cards and sections.
     *
     * @param int $boardid Board ID.
     * @return array Board data.
     */
    public static function execute($boardid) {
        global $DB, $USER;

        // Validate parameters.
        $params = self::validate_parameters(self::execute_parameters(), [
            'boardid' => $boardid,
        ]);

        // Load board.
        $board = new board($params['boardid']);

        // Check permissions.
        $context = $board->get_context();
        self::validate_context($context);
        require_capability('mod/mosaic:view', $context);

        // Get cards.
        $cards = $board->get_cards(true);

        // Format cards with additional data.
        $cardsdata = [];
        foreach ($cards as $card) {
            // Get reactions.
            $reactions = $DB->get_records('mosaic_reactions', ['cardid' => $card->id]);
            $reactionsdata = [];
            foreach ($reactions as $reaction) {
                $reactionsdata[] = [
                    'id' => $reaction->id,
                    'userid' => $reaction->userid,
                    'reaction' => $reaction->reaction,
                    'timecreated' => $reaction->timecreated,
                ];
            }

            // Get comment count.
            $commentcount = $DB->count_records('mosaic_comments', ['cardid' => $card->id]);

            // Get user info if not anonymous.
            $userinfo = null;
            if (!$card->anonymous) {
                $user = $DB->get_record('user', ['id' => $card->userid]);
                $userinfo = [
                    'id' => $user->id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'fullname' => fullname($user),
                ];
            }

            $cardsdata[] = [
                'id' => $card->id,
                'boardid' => $card->boardid,
                'userid' => $card->userid,
                'section_id' => $card->section_id,
                'type' => $card->type,
                'title' => $card->title,
                'content' => $card->content,
                'media_data' => $card->media_data,
                'position_data' => $card->position_data,
                'style_data' => $card->style_data,
                'status' => $card->status,
                'anonymous' => $card->anonymous,
                'timecreated' => $card->timecreated,
                'timemodified' => $card->timemodified,
                'reactions' => $reactionsdata,
                'commentcount' => $commentcount,
                'user' => $userinfo,
            ];
        }

        // Get sections.
        $sections = $board->get_sections();
        $sectionsdata = [];
        foreach ($sections as $section) {
            $sectionsdata[] = [
                'id' => $section->id,
                'boardid' => $section->boardid,
                'name' => $section->name,
                'color' => $section->color,
                'position' => $section->position,
                'timecreated' => $section->timecreated,
            ];
        }

        // Check user permissions.
        $permissions = [
            'canview' => has_capability('mod/mosaic:view', $context),
            'canpost' => has_capability('mod/mosaic:post', $context),
            'canmoderate' => has_capability('mod/mosaic:moderate', $context),
            'canmanage' => has_capability('mod/mosaic:manage', $context),
        ];

        return [
            'board' => [
                'id' => $board->id,
                'course' => $board->course,
                'name' => $board->name,
                'intro' => $board->intro,
                'introformat' => $board->introformat,
                'layout' => $board->layout,
                'theme_config' => $board->theme_config ?: '{}',
                'settings' => $board->settings ?: '{}',
                'timecreated' => $board->timecreated,
                'timemodified' => $board->timemodified,
            ],
            'cards' => $cardsdata,
            'sections' => $sectionsdata,
            'permissions' => $permissions,
            'currentuser' => [
                'id' => $USER->id,
                'fullname' => fullname($USER),
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
            'board' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'Board ID'),
                'course' => new external_value(PARAM_INT, 'Course ID'),
                'name' => new external_value(PARAM_TEXT, 'Board name'),
                'intro' => new external_value(PARAM_RAW, 'Introduction'),
                'introformat' => new external_value(PARAM_INT, 'Introduction format'),
                'layout' => new external_value(PARAM_TEXT, 'Layout type'),
                'theme_config' => new external_value(PARAM_RAW, 'Theme configuration JSON'),
                'settings' => new external_value(PARAM_RAW, 'Settings JSON'),
                'timecreated' => new external_value(PARAM_INT, 'Time created'),
                'timemodified' => new external_value(PARAM_INT, 'Time modified'),
            ]),
            'cards' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Card ID'),
                    'boardid' => new external_value(PARAM_INT, 'Board ID'),
                    'userid' => new external_value(PARAM_INT, 'User ID'),
                    'section_id' => new external_value(PARAM_INT, 'Section ID', VALUE_OPTIONAL),
                    'type' => new external_value(PARAM_TEXT, 'Card type'),
                    'title' => new external_value(PARAM_TEXT, 'Card title', VALUE_OPTIONAL),
                    'content' => new external_value(PARAM_RAW, 'Card content', VALUE_OPTIONAL),
                    'media_data' => new external_value(PARAM_RAW, 'Media data JSON', VALUE_OPTIONAL),
                    'position_data' => new external_value(PARAM_RAW, 'Position data JSON', VALUE_OPTIONAL),
                    'style_data' => new external_value(PARAM_RAW, 'Style data JSON', VALUE_OPTIONAL),
                    'status' => new external_value(PARAM_INT, 'Status'),
                    'anonymous' => new external_value(PARAM_INT, 'Anonymous flag'),
                    'timecreated' => new external_value(PARAM_INT, 'Time created'),
                    'timemodified' => new external_value(PARAM_INT, 'Time modified'),
                    'reactions' => new external_multiple_structure(
                        new external_single_structure([
                            'id' => new external_value(PARAM_INT, 'Reaction ID'),
                            'userid' => new external_value(PARAM_INT, 'User ID'),
                            'reaction' => new external_value(PARAM_TEXT, 'Reaction type'),
                            'timecreated' => new external_value(PARAM_INT, 'Time created'),
                        ])
                    ),
                    'commentcount' => new external_value(PARAM_INT, 'Number of comments'),
                    'user' => new external_single_structure([
                        'id' => new external_value(PARAM_INT, 'User ID'),
                        'firstname' => new external_value(PARAM_TEXT, 'First name'),
                        'lastname' => new external_value(PARAM_TEXT, 'Last name'),
                        'fullname' => new external_value(PARAM_TEXT, 'Full name'),
                    ], 'User info', VALUE_OPTIONAL),
                ])
            ),
            'sections' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT, 'Section ID'),
                    'boardid' => new external_value(PARAM_INT, 'Board ID'),
                    'name' => new external_value(PARAM_TEXT, 'Section name'),
                    'color' => new external_value(PARAM_TEXT, 'Section color', VALUE_OPTIONAL),
                    'position' => new external_value(PARAM_INT, 'Position'),
                    'timecreated' => new external_value(PARAM_INT, 'Time created'),
                ])
            ),
            'permissions' => new external_single_structure([
                'canview' => new external_value(PARAM_BOOL, 'Can view'),
                'canpost' => new external_value(PARAM_BOOL, 'Can post'),
                'canmoderate' => new external_value(PARAM_BOOL, 'Can moderate'),
                'canmanage' => new external_value(PARAM_BOOL, 'Can manage'),
            ]),
            'currentuser' => new external_single_structure([
                'id' => new external_value(PARAM_INT, 'User ID'),
                'fullname' => new external_value(PARAM_TEXT, 'Full name'),
            ]),
        ]);
    }
}
