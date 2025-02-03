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
 * Html tutor block backup
 *
 * @package    block_html_tutor
 * @subpackage backup-moodle2
 * @copyright  2003 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Specialised backup task for the html block
 * (requires encode_content_links in some configdata attrs)
 *
 * TODO: Finish phpdocs
 */
class backup_html_tutor_block_task extends backup_block_task {

    /**
     * Settings
     *
     * @return void
     */
    protected function define_my_settings() {
    }

    /**
     * Steps
     *
     * @return void
     */
    protected function define_my_steps() {
    }

    /**
     * fileareas
     *
     * @return array
     */
    public function get_fileareas() {
        return ['content'];
    }

    /**
     * We need to encode some attrs in configdata
     *
     * @return array
     */
    public function get_configdata_encoded_attributes() {
        return ['text'];
    }

    /**
     * No special encoding of links
     *
     * @param string $content
     * @return void
     */
    public static function encode_content_links($content) {
        return $content;
    }
}

