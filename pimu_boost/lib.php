<?php

// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.                                                               
defined('MOODLE_INTERNAL') || die();

// We will add callbacks here as we add features to our theme.

// -------------------- Get Main SCSS Content function --------------------
function theme_pimu_boost_get_main_scss_content($theme) {
    global $CFG;

    $scss = '';
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    $fs = get_file_storage();

    $context = context_system::instance();
    if ($filename == 'default.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    } else if ($filename == 'plain.scss') {
        // We still load the default preset files directly from the boost theme. No sense in duplicating them.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/plain.scss');

    } else if ($filename && ($presetfile = $fs->get_file($context->id, 'theme_pimu_boost', 'preset', 0, '/', $filename))) {
        // This preset file was fetched from the file area for theme_pimu_boost and not theme_boost (see the line above).
        $scss .= $presetfile->get_content();
    } else {
        // Safety fallback - maybe new installs etc.
        $scss .= file_get_contents($CFG->dirroot . '/theme/boost/scss/preset/default.scss');
    }
    // Pre CSS - this is loaded AFTER any prescss from the setting but before the main scss.
    $pre = file_get_contents($CFG->dirroot . '/theme/pimu_boost/scss/pre.scss');
    // Post CSS - this is loaded AFTER the main scss but before the extra scss from the setting.
    $post = file_get_contents($CFG->dirroot . '/theme/pimu_boost/scss/post.scss');
    // Combine them together.
    $result = $pre . "\n" . $scss . "\n" . $post;
    return $result;
}


function theme_pimu_boost_extend_navigation_course($navigation, $course, $context) {
    $url = new moodle_url('/theme/pimu_boost/course-statistics.php', array('id'=>$course->id));
    $node = navigation_node::create(
        get_string('pimu_course_statistics_tab', 'theme_pimu_boost'), // 'My tab',
        $url,
        navigation_node::TYPE_ROOTNODE,
        null,
        'a-custom-tab',
        null // new pix_icon('i/custom-tab', '')
    );

    $node->showinflatnavigation = true;
    $node->set_force_into_more_menu(false);
    $node->set_show_in_secondary_navigation(true);

    // If the user has the capability to view the grades, this means he also can view statistics tab
    if (has_capability('moodle/grade:view', $context)) {
        $mynode = $navigation->add_node($node, "coursereports");
    }

}
