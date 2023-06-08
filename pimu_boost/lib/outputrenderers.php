<?php

use core\output\named_templatable;
use core_completion\cm_completion_details;
use core_course\output\activity_information;

defined('MOODLE_INTERNAL') || die();

/**
 * Simple base class for Moodle renderers.
 *
 * Tracks the xhtml_container_stack to use, which is passed in in the constructor.
 *
 * Also has methods to facilitate generating HTML output.
 *
 * @copyright 2009 Tim Hunt
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.0
 * @package core
 * @category output
 */
class renderer_base {
    public function somedata() {
        return 'asd';
    }
}
