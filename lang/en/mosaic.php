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
 * English language strings for mod_mosaic.
 *
 * @package    mod_mosaic
 * @copyright  2025 David Kelly (https://davidkel.ly) <contact@davidkel.ly>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     David Kelly <contact@davidkel.ly>
 */

defined('MOODLE_INTERNAL') || die();

$string['modulename'] = 'Mosaic Board';
$string['modulenameplural'] = 'Mosaic Boards';
$string['modulename_help'] = 'The Mosaic Board activity enables collaborative content creation with rich media support, multiple layout options, and real-time interaction.';
$string['pluginname'] = 'Mosaic';
$string['pluginadministration'] = 'Mosaic Board administration';

// Capabilities.
$string['mosaic:addinstance'] = 'Add a new Mosaic Board';
$string['mosaic:deleteownpost'] = 'Delete own posts';
$string['mosaic:editownpost'] = 'Edit own posts';
$string['mosaic:exportboard'] = 'Export board content';
$string['mosaic:manage'] = 'Manage board settings';
$string['mosaic:moderate'] = 'Moderate posts and comments';
$string['mosaic:post'] = 'Create posts on board';
$string['mosaic:view'] = 'View Mosaic Board';
$string['mosaic:viewallposts'] = 'View all posts including anonymous';

// Settings.
$string['layout'] = 'Board layout';
$string['layout_help'] = 'Choose how cards will be arranged on the board:<br>
<strong>Wall:</strong> Pinterest-style cascading grid<br>
<strong>Grid:</strong> Uniform card arrangement<br>
<strong>Canvas:</strong> Free-positioning with connection lines<br>
<strong>Stream:</strong> Linear blog/social media style<br>
<strong>Timeline:</strong> Chronological with visual timeline';
$string['layoutsettings'] = 'Layout settings';
$string['layout_canvas'] = 'Canvas (free positioning)';
$string['layout_grid'] = 'Grid (uniform cards)';
$string['layout_stream'] = 'Stream (linear)';
$string['layout_timeline'] = 'Timeline (chronological)';
$string['layout_wall'] = 'Wall (masonry)';
$string['mosaicname'] = 'Board name';

// Board interface.
$string['addcard'] = 'Add card';
$string['addcomment'] = 'Add comment';
$string['addsection'] = 'Add section';
$string['anonymous'] = 'Anonymous';
$string['card'] = 'Card';
$string['cards'] = 'Cards';
$string['comment'] = 'Comment';
$string['comments'] = 'Comments';
$string['confirmdelete'] = 'Are you sure you want to delete this?';
$string['confirmdeletecard'] = 'Are you sure you want to delete this card?';
$string['deletecard'] = 'Delete card';
$string['editcard'] = 'Edit card';
$string['noposts'] = 'No posts yet.';
$string['postcountuser'] = '{$a} posts';
$string['posts'] = 'Posts';
$string['reaction'] = 'Reaction';
$string['reactions'] = 'Reactions';
$string['section'] = 'Section';
$string['sections'] = 'Sections';

// Card types.
$string['cardtype'] = 'Card type';
$string['cardtype_audio'] = 'Audio';
$string['cardtype_code'] = 'Code snippet';
$string['cardtype_file'] = 'File';
$string['cardtype_image'] = 'Image';
$string['cardtype_link'] = 'Link';
$string['cardtype_text'] = 'Text';
$string['cardtype_video'] = 'Video';

// Form elements.
$string['cardcontent'] = 'Content';
$string['cardtitle'] = 'Title';
$string['commenttext'] = 'Comment';
$string['sectioncolor'] = 'Section color';
$string['sectionname'] = 'Section name';

// Errors.
$string['erroraddingcard'] = 'Error adding card.';
$string['errorcannotpost'] = 'You do not have permission to post on this board.';
$string['errordeletingcard'] = 'Error deleting card.';
$string['errorinvalidcard'] = 'Invalid card.';
$string['errorupdatingcard'] = 'Error updating card.';

// Events.
$string['eventcardcreated'] = 'Card created';
$string['eventcarddeleted'] = 'Card deleted';
$string['eventcardupdated'] = 'Card updated';

// Privacy.
$string['privacy:metadata:mosaic_cards'] = 'Information about cards created by users.';
$string['privacy:metadata:mosaic_cards:anonymous'] = 'Whether the post is anonymous.';
$string['privacy:metadata:mosaic_cards:content'] = 'The content of the card.';
$string['privacy:metadata:mosaic_cards:timecreated'] = 'The time the card was created.';
$string['privacy:metadata:mosaic_cards:timemodified'] = 'The time the card was last modified.';
$string['privacy:metadata:mosaic_cards:title'] = 'The title of the card.';
$string['privacy:metadata:mosaic_cards:userid'] = 'The user who created the card.';
$string['privacy:metadata:mosaic_comments'] = 'Information about comments on cards.';
$string['privacy:metadata:mosaic_comments:comment'] = 'The comment text.';
$string['privacy:metadata:mosaic_comments:timecreated'] = 'The time the comment was created.';
$string['privacy:metadata:mosaic_comments:userid'] = 'The user who created the comment.';
$string['privacy:metadata:mosaic_reactions'] = 'Information about reactions to cards.';
$string['privacy:metadata:mosaic_reactions:reaction'] = 'The reaction emoji.';
$string['privacy:metadata:mosaic_reactions:timecreated'] = 'The time the reaction was created.';
$string['privacy:metadata:mosaic_reactions:userid'] = 'The user who added the reaction.';
