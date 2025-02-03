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
 * Form for editing HTML block instances.
 *
 * @package   block_html_tutor
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Html tutor block
 */
class block_html_tutor extends block_base {

    /**
     * Init
     *
     * @return void
     */
    public function init() {
        $this->title = get_string('pluginname', 'block_html_tutor');
    }

    /**
     * has config
     *
     * @return boolean
     */
    public function has_config() {
        return true;
    }

    /**
     * Applicable formats
     *
     * @return array
     */
    public function applicable_formats() {
        return ['all' => true];
    }

    /**
     * Specialization
     *
     * @return void
     */
    public function specialization() {
        $this->title = isset($this->config->title)
            ? format_string($this->config->title)
            : format_string(get_string('newhtmltutorblock', 'block_html_tutor'));
    }

    /**
     * Allow multiple
     *
     * @return bool
     */
    public function instance_allow_multiple() {
        return true;
    }

    /**
     * Get content
     *
     * @return stdClass
     */
    public function get_content() {
        global $CFG, $USER;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // Fancy html allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        $department = $USER->department ?? '';
        if (isset($this->config->text) && ($department == 'academic' || is_siteadmin())) {
            // Rewrite url.
            $this->config->text = file_rewrite_pluginfile_urls(
                $this->config->text,
                'pluginfile.php',
                $this->context->id,
                'block_html_tutor',
                'content',
                null);
            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config.
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            $this->content->text = format_text($this->config->text, $format, $filteropt);
        } else {
            $this->content->text = '';
        }

        unset($filteropt);

        return $this->content;
    }


    /**
     * Serialize and store config data
     * @param stdClass $data
     * @param bool $nolongerused
     */
    public function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match.
        $config->text = file_save_draft_area_files(
            $data->text['itemid'],
            $this->context->id,
            'block_html_tutor',
            'content',
            0,
            ['subdirs' => true],
            $data->text['text']);
        $config->format = $data->text['format'];

        parent::instance_config_save($config, $nolongerused);
    }

    /**
     * Delete instance
     *
     * @return bool
     */
    public function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_html_tutor');
        return true;
    }

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid) {
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
        if (!$fs->is_area_empty($fromcontext->id, 'block_html_tutor', 'content', 0, false)) {
            $draftitemid = 0;
            file_prepare_draft_area($draftitemid,
                $fromcontext->id,
                'block_html_tutor',
                'content',
                0,
                ['subdirs' => true]);
            file_save_draft_area_files(
                $draftitemid,
                $this->context->id,
                'block_html_tutor',
                'content',
                0,
                ['subdirs' => true]);
        }
        return true;
    }

    /**
     * Content is trusted
     *
     * @return bool
     */
    public function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        // Find out if this block is on the profile page.
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // This is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here.
                return true;
            } else {
                // No JS on public personal pages, it would be a big security issue.
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /**
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    public function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_html_tutor_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }
}
