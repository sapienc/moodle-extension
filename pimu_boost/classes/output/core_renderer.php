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
 * Version details
 *
 * @package    theme_pimu_boost
 * @copyright  2015-2019 Jeremy Hopkins (Coventry University)
 * @copyright  2015-2019 Fernando Acedo (3-bits.com)
 * @copyright  2017-2019 Manoj Solanki (Coventry University)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */

defined('MOODLE_INTERNAL') || die();

namespace theme_pimu_boost\output;

use theme_boost\output\core_renderer as core_renderer_base;

/**
 * Renderers to align Moodle's HTML with that expected by Bootstrap
 *
 * Note: This class is required to avoid inheriting Boost's core_renderer
 *
 * @copyright Copyright (c) 2017 Manoj Solanki (Coventry University)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// [PIMU]
class core_renderer extends core_renderer_base {

    /**
     * To decide whether we need to display PIMU style logo or not
     *
     * @return boolean to display PIMU style logo or not
     */
    public function pimu_logo_text_mode() {
        return true;
    }

    // =======================================================================================================

    /**
     * Get site's `fullname` for PIMU style logo
     *
     * @return string Site's Full Name
     */
    public function site_full_name() {
        $site = get_site(); // here could also be a string, reached from the `get_string('key', 'theme_name');` method
        return $site->fullname;
    }

    // =======================================================================================================

    /**
     * Get site's `shortname` for PIMU style logo
     *
     * @return string Site's Short Name
     */
    public function site_short_name() {
        $site = get_site();
        return $site->fullname;
    }

    // =======================================================================================================

    // -------------------- [ OLD SOLUTION OF CUSTOMIZATION, WHICH WAS DECLINED ] --------------------
    /**
     * Overriding the original function to decide whether to hide the main header on the Home Page or not.
     * We'll call the original `full_header()` function if we don't need to hide it.
     * The result will be reflected via `{{{ output.full_header }}}` in `drawers.mustache` file
     *
     * @return string HTML to display the main header or a custom content to hide the main header on the Home Page
     */
    // public function full_header() {
    //     global $PAGE;

    //     // This constant below will help us to find the header which is only on Home Page
    //     $home_page_type_constant = 'site-index'; // originally it was defined in `lib/outputrenderers.php` file in condition like: `else if ($homepage == HOMEPAGE_SITE)`, therefore, we'll also take it as a reference point
    //     $pagetype = $this->page->pagetype; // getting the current page's type
    //     $is_home_page = $pagetype == $home_page_type_constant; // alternative: if ($PAGE->url->get_path() === '/')

    //     /**
    //      * In order to not override `drawers.php` and `drawers.mustache` files, it was decided to just create an own HTML element,
    //      * based on which we will hide `secondarymoremenu` via CSS (search for: `.header-hidden-secondarymoremenu ~ .secondary-navigation` rule)
    //      */
    //     $custom_content = '<div class="d-none header-hidden-secondarymoremenu"></div>'; // ''; // it was just an empty string earlier

    //     $result = $is_home_page ? $custom_content : parent::full_header();

    //     return $result;
    // }

    // =======================================================================================================

    public function pimu_edit_frontpage_content_text() {
        return '!!!';
    }

    // =======================================================================================================

    /**
     * Outputs contents for frontpage as configured in $CFG->frontpage or $CFG->frontpageloggedin
     *
     * @return string
     */
    // public function frontpage() {
    //     // global $CFG, $SITE;

    //     // $output = '';

    //     // if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    //     //     $frontpagelayout = $CFG->frontpageloggedin;
    //     // } else {
    //     //     $frontpagelayout = $CFG->frontpage;
    //     // }

    //     // foreach (explode(',', $frontpagelayout) as $v) {
    //     //     switch ($v) {
    //     //         // Display the main part of the front page.
    //     //         case FRONTPAGENEWS:
    //     //             if ($SITE->newsitems) {
    //     //                 // Print forums only when needed.
    //     //                 require_once($CFG->dirroot .'/mod/forum/lib.php');
    //     //                 if (($newsforum = forum_get_course_forum($SITE->id, 'news')) &&
    //     //                         ($forumcontents = $this->frontpage_news($newsforum))) {
    //     //                     $newsforumcm = get_fast_modinfo($SITE)->instances['forum'][$newsforum->id];
    //     //                     $output .= $this->frontpage_part('skipsitenews', 'site-news-forum',
    //     //                         $newsforumcm->get_formatted_name(), $forumcontents);
    //     //                 }
    //     //             }
    //     //             break;

    //     //         case FRONTPAGEENROLLEDCOURSELIST:
    //     //             $mycourseshtml = $this->frontpage_my_courses();
    //     //             if (!empty($mycourseshtml)) {
    //     //                 $output .= $this->frontpage_part('skipmycourses', 'frontpage-course-list',
    //     //                     get_string('mycourses'), $mycourseshtml);
    //     //             }
    //     //             break;

    //     //         case FRONTPAGEALLCOURSELIST:
    //     //             $availablecourseshtml = $this->frontpage_available_courses();
    //     //             $output .= $this->frontpage_part('skipavailablecourses', 'frontpage-available-course-list',
    //     //                 get_string('availablecourses'), $availablecourseshtml);
    //     //             break;

    //     //         case FRONTPAGECATEGORYNAMES:
    //     //             $output .= $this->frontpage_part('skipcategories', 'frontpage-category-names',
    //     //                 get_string('categories'), $this->frontpage_categories_list());
    //     //             break;

    //     //         case FRONTPAGECATEGORYCOMBO:
    //     //             $output .= $this->frontpage_part('skipcourses', 'frontpage-category-combo',
    //     //                 get_string('courses'), $this->frontpage_combo_list());
    //     //             break;

    //     //         case FRONTPAGECOURSESEARCH:
    //     //             $output .= $this->box($this->course_search_form(''), 'd-flex justify-content-center');
    //     //             break;

    //     //     }
    //     //     $output .= '<br />';
    //     // }

    //     $output = '';

    //     $output = parent::frontpage();

    //     error_log("Here is an output:");
    //     error_log($output);

    //     return $output . '!!!!!!!!!!!!!!!!!!!!!!!!';
    // }

    // =======================================================================================================

    // public function course_content_header($onlyifnotcalledbefore = false) {
    //     // global $CFG;
    //     // static $functioncalled = false;
    //     // if ($functioncalled && $onlyifnotcalledbefore) {
    //     //     // we have already output the content header
    //     //     return '';
    //     // }

    //     // // Output any session notification.
    //     // $notifications = \core\notification::fetch();

    //     // $bodynotifications = '';
    //     // foreach ($notifications as $notification) {
    //     //     $bodynotifications .= $this->render_from_template(
    //     //             $notification->get_template_name(),
    //     //             $notification->export_for_template($this)
    //     //         );
    //     // }

    //     // $output = html_writer::span($bodynotifications, 'notifications', array('id' => 'user-notifications'));

    //     // if ($this->page->course->id == SITEID) {
    //     //     // return immediately and do not include /course/lib.php if not necessary
    //     //     return $output;
    //     // }

    //     // require_once($CFG->dirroot.'/course/lib.php');
    //     // $functioncalled = true;
    //     // $courseformat = course_get_format($this->page->course);
    //     // if (($obj = $courseformat->course_content_header()) !== null) {
    //     //     $output .= html_writer::div($courseformat->get_renderer($this->page)->render($obj), 'course-content-header');
    //     // }

    //     $output = parent::course_content_header($onlyifnotcalledbefore);

    //     error_log($output);

    //     $output .= 'MY OWN CONTENT';

    //     return $output;
    // }

    // =======================================================================================================

    // public function main_content() {
    //     $site = get_site();
    //     return $site->fullname;
    // }

    // public function show_password() {
    //     // return password_hash("teacher", PASSWORD_DEFAULT);
    //     return hash_internal_user_password("teacher");
    // }
}
