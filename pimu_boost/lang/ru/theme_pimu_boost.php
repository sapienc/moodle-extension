<?php
// Every file should have GPL and copyright in the header - we skip it in tutorials but you should not skip it for real.

// This line protects the file from being accessed by a URL directly.
defined('MOODLE_INTERNAL') || die();

// A description shown in the admin theme selector.
$string['choosereadme'] = 'Описание темы PIMU Boost, базирующейся на теме Boost. It adds the ability to upload background photos.';
// The name of our plugin.
$string['pluginname'] = 'ПИМУ Boost';
// We need to include a lang string for each block region.
$string['region-side-pre'] = 'Right';

// -------------------- PIMU Theme words: --------------------
// Строка, для отображения настроек так называемой `frontpage` (главной страницы).
$string['pimu_text_edit_frontpage_settings'] = 'Редактировать контент страницы';
$string['pimu_course_statistics_tab'] = 'Статистика';
$string['pimu_course_statistics_tab_page_additional_header'] = 'Статистика обучения пользователя';
$string['pimu_course_statistics_tab_page_get_data_error_message'] = 'Не удается получить данные...';
$string['pimu_course_statistics_tab_chart_final_course_mark'] = 'Итоговая оценка за курс';

// Flexsections plugin localisation
$string['addsection'] = 'Добавить раздел';
$string['addsections'] = 'Добавить разделы';
$string['addsubsection'] = 'Добавить подраздел';
$string['deletesection'] = 'Удалить раздел';
$string['editsection'] = 'Редактировать раздел';
$string['editsectionname'] = 'Редактировать наименование раздела';
$string['hidefromothers'] = 'Спрятать раздел';

// -------------------- Standard Boost Theme Settings words: --------------------

// The name of the second tab in the theme settings.
$string['advancedsettings'] = 'Advanced settings';
// The brand colour setting.
$string['brandcolor'] = 'Brand colour';
// The brand colour setting description.
$string['brandcolor_desc'] = 'The accent colour.';
// Name of the settings pages.
$string['configtitle'] = 'Настройки ПИМУ Boost';
// Name of the first settings tab.
$string['generalsettings'] = 'General settings';
// Preset files setting.
$string['presetfiles'] = 'Additional theme preset files';
// Preset files help text.
$string['presetfiles_desc'] = 'Preset files can be used to dramatically alter the appearance of the theme. See <a href=https://docs.moodle.org/dev/Boost_Presets>Boost presets</a> for information on creating and sharing your own preset files, and see the <a href=http://moodle.net/boost>Presets repository</a> for presets that others have shared.';
// Preset setting.
$string['preset'] = 'Theme preset';
// Preset help text.
$string['preset_desc'] = 'Pick a preset to broadly change the look of the theme.';
// Raw SCSS setting.
$string['rawscss'] = 'Raw SCSS';
// Raw SCSS setting help text.
$string['rawscss_desc'] = 'Use this field to provide SCSS or CSS code which will be injected at the end of the style sheet.';
// Raw initial SCSS setting.
$string['rawscsspre'] = 'Raw initial SCSS';
// Raw initial SCSS setting help text.
$string['rawscsspre_desc'] = 'In this field you can provide initialising SCSS code, it will be injected before everything else. Most of the time you will use this setting to define variables.';