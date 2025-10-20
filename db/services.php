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
 * Web service definitions.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

defined('MOODLE_INTERNAL') || die();

$functions = [

    'mod_mosaic_get_board' => [
        'classname' => 'mod_mosaic\external\get_board',
        'methodname' => 'execute',
        'description' => 'Get board data including cards and sections.',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => 'mod/mosaic:view',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

    'mod_mosaic_create_card' => [
        'classname' => 'mod_mosaic\external\create_card',
        'methodname' => 'execute',
        'description' => 'Create a new card on a board.',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/mosaic:post',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

    'mod_mosaic_update_card' => [
        'classname' => 'mod_mosaic\external\update_card',
        'methodname' => 'execute',
        'description' => 'Update an existing card.',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/mosaic:editownpost',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

    'mod_mosaic_delete_card' => [
        'classname' => 'mod_mosaic\external\delete_card',
        'methodname' => 'execute',
        'description' => 'Delete a card.',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => 'mod/mosaic:deleteownpost',
        'services' => [MOODLE_OFFICIAL_MOBILE_SERVICE],
    ],

];
