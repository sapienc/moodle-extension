<?php
defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/renderer.php');

class theme_pimu_boost_core_course_renderer extends core_course_renderer {

    public function frontpage() {
        // global $CFG, $SITE;

        // $output = '';

        // if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
        //     $frontpagelayout = $CFG->frontpageloggedin;
        // } else {
        //     $frontpagelayout = $CFG->frontpage;
        // }

        // foreach (explode(',', $frontpagelayout) as $v) {
        //     switch ($v) {
        //         // Display the main part of the front page.
        //         case FRONTPAGENEWS:
        //             if ($SITE->newsitems) {
        //                 // Print forums only when needed.
        //                 require_once($CFG->dirroot .'/mod/forum/lib.php');
        //                 if (($newsforum = forum_get_course_forum($SITE->id, 'news')) &&
        //                         ($forumcontents = $this->frontpage_news($newsforum))) {
        //                     $newsforumcm = get_fast_modinfo($SITE)->instances['forum'][$newsforum->id];
        //                     $output .= $this->frontpage_part('skipsitenews', 'site-news-forum',
        //                         $newsforumcm->get_formatted_name(), $forumcontents);
        //                 }
        //             }
        //             break;

        //         case FRONTPAGEENROLLEDCOURSELIST:
        //             $mycourseshtml = $this->frontpage_my_courses();
        //             // if (!empty($mycourseshtml)) {
        //                 $output .= $this->frontpage_part('skipmycourses', 'frontpage-course-list',
        //                     get_string('mycourses'), $mycourseshtml);
        //             // }
        //             break;

        //         case FRONTPAGEALLCOURSELIST:
        //             $availablecourseshtml = $this->frontpage_available_courses();
        //             $output .= $this->frontpage_part('skipavailablecourses', 'frontpage-available-course-list',
        //                 get_string('availablecourses'), $availablecourseshtml);
        //             break;

        //         case FRONTPAGECATEGORYNAMES:
        //             $output .= $this->frontpage_part('skipcategories', 'frontpage-category-names',
        //                 get_string('categories'), $this->frontpage_categories_list());
        //             break;

        //         case FRONTPAGECATEGORYCOMBO:
        //             $output .= $this->frontpage_part('skipcourses', 'frontpage-category-combo',
        //                 get_string('courses'), $this->frontpage_combo_list());
        //             break;

        //         case FRONTPAGECOURSESEARCH:
        //             $output .= $this->box($this->course_search_form(''), 'd-flex justify-content-center');
        //             break;

        //     }
        //     $output .= '<br />';
        // }

        
        /////////////////////////////////////////////////////////////////////
        // $output = $this->frontpage_my_courses();

        // error_log("Here is an output:");
        // error_log($output);

        // return $output . get_string('pimu_text_edit_frontpage_settings', 'theme_pimu_boost');

        /////////////////////////////////////////////////////////////////////
        return '<section class="pimu-frontpage-blocks">' . parent::frontpage() . '</section>';
    }
}
