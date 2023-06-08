<?php
namespace theme_pimu_boost\output;

use theme_boost\output\core_renderer as base;


class theme_pimu_boost_core_renderer extends base {
    // Define a custom function to add data to the template
    public function get_custom_data() {
        return "This is my custom data";
    }
}