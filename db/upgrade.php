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
 * Plugin upgrade steps.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute mod_mosaic upgrade from the given old version.
 *
 * @param int $oldversion The old version of the plugin.
 * @return bool True on success.
 */
function xmldb_mosaic_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    // Automatically generated Moodle v4.0.0 release upgrade line.
    // Put any upgrade step following this.

    // For future upgrades, add version checks and upgrade steps here.
    // Example:
    // if ($oldversion < 2025012001) {
    //     // Upgrade steps here.
    //     upgrade_mod_savepoint(true, 2025012001, 'mosaic');
    // }

    return true;
}
