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
 * Mosaic board AMD module loader.
 *
 * @module     mod_mosaic/loader
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

define(['jquery'], function($) {
    return {
        /**
         * Initialize the Mosaic board application.
         *
         * @param {Object} config Configuration object.
         */
        init: function(config) {
            // Log initialization.
            // eslint-disable-next-line no-console
            console.log('Mosaic board initializing...', config);

            // Wait for Vue app bundle to be loaded.
            // For now, just display the board ID.
            var container = $('#mosaic-board-app');
            if (container.length) {
                container.find('.mosaic-loading').html(
                    '<p>Mosaic Board ' + config.boardid + ' will load here.</p>' +
                    '<p>Vue.js app coming soon!</p>'
                );
            }
        }
    };
});
