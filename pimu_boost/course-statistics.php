<?php

// defined('MOODLE_INTERNAL') || die();

// Подключение необходимых файлов и настроек
require_once(dirname(dirname(dirname(__FILE__))) . '/config.php');
require_login();

header('Content-Type: text/html; charset=utf-8');

global $PAGE;
global $USER;
global $DB;


$incoming_course_id = required_param('id', PARAM_INT);
$course = get_course($incoming_course_id);
$courseid = $course->id;

$context = context_course::instance($courseid);
$PAGE->set_context(context_course::instance($courseid));
$PAGE->set_course($course);

// Redirect user if there is no capability to view the grades (which means also has no capability to view the statistics page)
if (!has_capability('moodle/grade:view', $context)) {
    $courseurl = new moodle_url('/course/view.php', array('id' => $courseid));
    redirect($courseurl);
    return;
}

$PAGE->set_url('/theme/pimu_boost/course-statistics.php', array('id' => $courseid)); // $PAGE->set_url('/course-statistics.php', array('id' => $courseid));

if (!isloggedin() || !is_enrolled($context, null, '', true)) {
    print_error( get_string('pimu_course_statistics_tab_page_get_data_error_message', 'theme_pimu_boost') , '', $course->viewurl);
}

$PAGE->set_title(
    get_string('pimu_course_statistics_tab', 'theme_pimu_boost') // "Статистика"
    // $course->fullname
);
$PAGE->set_heading($course->fullname . ': ' . get_string('pimu_course_statistics_tab_page_additional_header', 'theme_pimu_boost') );

echo $OUTPUT->header();

// Получаем ФИО пользователя
$user_full_name = $USER->firstname . ' ' . $USER->lastname;
$user_email = $USER->email;


// Получаем список оцениваемых элементов для курса
$gradeitems = grade_item::fetch_all(array('courseid' => $courseid));
$grade_items_marks = []; // итоговый массив с наименованиями оцениваемых элементов (subjLabel) и самой оценкой (data) (она представлена в массиве просто чтобы с удобством использоватб потом в chart.js при отображении графиков)

// Перебираем оцениваемые элементы и выводим информацию об оценках
foreach ($gradeitems as $gradeitem) {
    // Получаем объект оценки для каждого оцениваемого элемента
    $grade = new grade_grade(array('itemid' => $gradeitem->id, 'userid' => $USER->id));

   // Проверяем, есть ли оценка для данного элемента
    if ($grade->finalgrade != null) {
        // Получаем информацию о максимальном диапазоне оценки
        $grademax = $gradeitem->grademax;
        
        // Проверяем, является ли оцениваемый элемент процентным
        if ($gradeitem->gradetype == GRADE_TYPE_PERCENTAGE) {
            // Конвертируем оценку в 10-балльную шкалу
            $gradevalue = $grade->finalgrade * 0.1 * $grademax;
        } else {
            // Конвертируем оценку в 10-балльную шкалу
            $gradevalue = ($grade->finalgrade - $gradeitem->grademin) / ($gradeitem->grademax - $gradeitem->grademin) * 10;
        }

        $final_grade_value = ( intval($grade->finalgrade) >= 100 || intval($grade->finalgrade) >= 10 && intval($grade->finalgrade) < 11 ) ? 10.0 : round($gradevalue, 1);

        array_push($grade_items_marks,
            [
                "sujbLabel" => !$gradeitem->itemname ? get_string('pimu_course_statistics_tab_chart_final_course_mark', 'theme_pimu_boost') : $gradeitem->itemname,
                "data" => [$final_grade_value]
            ]
        );
        
        // Выводим информацию о фактической и посчитанной оценке
        // echo '<p>intval ' . intval($gradevalue) . ' | ' . '$final_grade_value:' . $final_grade_value .'</p>';
        // echo '<p>Оценка за элемент ' . $gradeitem->itemname . ': ' . $grade->finalgrade . '</p>';
        // echo '<p>Фактическая оценка: ' . $grade->finalgrade . '</p>';
        // echo '<p>Посчитанная оценка в 10-балльной шкале: ' . round($gradevalue, 1) . '</p>';
    } 
    // Если оценки нет, мы все-равно создадим элемент в массиве с названием оцениваемого элемента и отсутствием оценки за него
    else {
        array_push($grade_items_marks,
            [
                "sujbLabel" => !$gradeitem->itemname ? get_string('pimu_course_statistics_tab_chart_final_course_mark', 'theme_pimu_boost') : $gradeitem->itemname,
                "data" => [0]
            ]
        );
    }
}

// Небольшой хак чтобы переместить самый первый элемент массива в конец (как правило это Итоговая оценка за курс)
$course_total_mark = array_shift($grade_items_marks);
array_push($grade_items_marks, $course_total_mark);

$json_stats_data = json_encode($grade_items_marks); // var_dump( $json_stats_data );


// Создаем объект completion_info для курса
$completion = new completion_info($courseid);


// Получаем общее количество оцениваемых элементов в курсе
$total_gradeitems = $DB->count_records('grade_items', array('courseid' => $courseid));

// Получаем количество завершенных оцениваемых элементов для текущего пользователя
$completed_gradeitems = $DB->count_records_sql("
    SELECT COUNT(*) 
    FROM {grade_grades} g
    INNER JOIN {grade_items} gi ON gi.id = g.itemid
    WHERE g.userid = :userid 
    AND gi.courseid = :courseid 
    AND g.finalgrade IS NOT NULL",
    array('userid' => $USER->id, 'courseid' => $courseid)
);

// Вычисляем процент завершенности курса
$progress_percentage = $total_gradeitems > 0 ? round(($completed_gradeitems / $total_gradeitems) * 100) : 0;

?>

<span style="display: none;" id="json_stats_data"><?php echo($json_stats_data); ?></span>
<span style="display: none;" id="grade_data"><?php echo($progress_percentage); ?></span>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid tertiary-navigation full-width-bottom-border border-0 p-0">

    <?php
        if(!count($grade_items_marks)) {
            echo '<h5 style="color: #878787;">Нет данных для отображения статистики</h5>';
        }
    ?>
    
    <!-- ========================================= [ CHART PLACE HEADING ] ========================================= -->
    <div class="d-flex flex-row align-items-center align-content-center px-5 pb-3 mb-3" style="border-bottom: 1px solid #dee2e6;">

        <div class="align-self-start">
            <div class="search-widget dropdown d-flex" data-searchtype="user">
                <div class="align-items-center d-flex">
                    <div class="selected-option-img d-block pr-2">
                        <span class="userinitials" style="width: 40px; height: 40px;">
                            <?php
                                echo $USER->firstname[0];
                                echo $USER->lastname[0];
                            ?>
                        </span>
                    </div>
                    <div class="selected-option-info d-block pr-3">
                        <span class="selected-option-text p-0 font-weight-bold">
                            <?php echo $user_full_name; ?>
                        </span>
                        <span class="d-block small">
                            <?php echo $user_email; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div style="width: 1px; height: 40px; background-color: #dee2e6;" class="mx-3 mr-4"></div>

        <div class="m-0">
            <h4 class="m-0">Статистика обучения студента</h4>
        </div>

        </div>
        <!-- ======================================== [ / CHART PLACE HEADING ] ======================================== -->

        <!-- ========================================= [ CHARTS ] ========================================= -->
        <div class="charts-row-place">
        <div class="chart-block" style="flex-shrink: 3;">

            <p class="chart-title">Общий прогресс по курсу</p>

            <div class="chart-container" style="max-width: 180px;max-height: 180px;">
                <canvas id="progressChart"></canvas>
                <p class="circle-progress-chart-center-text" id="progressChartText"></p>
            </div>

        </div>

        <!-- ======================================================================================================= -->

        <div class="chart-block" style="flex-shrink: 1;">

            <p class="chart-title">Оценки за оцениваемые элементы</p>

            <div class="chart-container" style="width: 100%;height: 100%;">
                <canvas id="scoresChart"></canvas>
            </div>

        </div>
    </div>
    <!-- ======================================== [ / CHARTS ] ======================================== -->
</div>


<script>

    window.onload = function () {

        let json_stats_data_elem = document.getElementById('json_stats_data');
        let grade_data_elem = document.getElementById('grade_data');
    
        if(json_stats_data_elem) {
            let json_stats_data = JSON.parse(json_stats_data_elem.innerText);
            json_stats_data_elem.remove(); // удаляем элемент из DOM после того как прочитали данные

            console.log("json_stats_data:", json_stats_data);

            const periodColors = [
                'rgba(236, 112, 99, 0.8)', // 0 - 3.5
                'rgba(255, 205, 86, 0.8)', // 3.5 - 5.5
                'rgba(54, 162, 235, 0.8)', // 6.6 - 7.5
                'rgba(102, 187, 106, 1)', // 7.5 - 10
            ];

            function getColorByData(data, periodColors) {
                for (let i = 0; i < periodColors.length; i++) {
                    const color = periodColors[i];
                    const range = i === 0 ? [0, 3.5] : i === 1 ? [3.5, 5.5] : i === 2 ? [5.5, 7.5] : [7.5, 10];
                    if (data >= range[0] && data <= range[1]) {
                        return color;
                    }
                }
                return 'rgba(35,35,35,0.8)'; //null; // Если переданный data не попадает ни в один диапазон, то вернуть null
            }


            // ====================================== PROGRESS CHART ======================================
            // Определяем данные для диаграммы
            const passed = parseInt(grade_data_elem.innerText);// 89;
            grade_data_elem.remove(); // удаляем элемент из DOM после того как прочитали данные
            const notPassed = Math.abs(100 - passed);
            const progressChart_data = {
                datasets: [{
                    data: [
                        passed, // сколько пройдено
                        notPassed, // сколько не пройдено
                    ],
                    backgroundColor: [
                        '#66bb6a', // цвет сколько пройдено
                        '#e9ecef' // цвет сколько не пройдено
                    ]
                }]
            };

            // Определяем параметры для отображения диаграммы
            const progressChart_options = {
                responsive: true,
                tooltips: {
                    enabled: true, // false
                },
            };

            // Создаем объект диаграммы
            const progressChart = new Chart('progressChart', {
                type: 'doughnut',
                data: progressChart_data,
                options: progressChart_options
            });

            const progressChartTextElem = document.getElementById('progressChartText');
            progressChartTextElem.innerText = passed + '%';


            // ====================================== SCORES CHART ======================================
            let marks = json_stats_data || [
                // ---------- Mocked data for testing ----------
                // {
                //     sujbLabel: "Практическое задание",
                //     data: [3.3],
                //     // backgroundColor: 'rgba(75, 192, 192, 0.8)'
                // },
                // {
                //     sujbLabel: "Тест: Структуры печени в норме",
                //     data: [5.2],
                //     // backgroundColor: 'rgba(255, 205, 86, 0.8)',
                // },
                // {
                //     sujbLabel: "Итоговый тест",
                //     data: [6.8],
                //     // backgroundColor: 'rgba(255, 205, 86, 0.8)',
                // },
                // {
                //     sujbLabel: "Итоговая оценка за весь курс",
                //     data: [9.4],
                //     // backgroundColor: 'rgba(52,52,52,0.8)', //'rgba(255, 99, 132, 0.8)',   // красный
                // },
            ];
            console.log("marks:", marks);
            
            const dataSets = [{
                label: "Оценка",
                data: marks.map(item => item.data[0]),
                backgroundColor: marks.map(item => item?.backgroundColor ? item?.backgroundColor : getColorByData(item.data[0], periodColors)),
                borderWidth: 0
            }]

            console.log(dataSets);

            let scoresChart_data = {
                labels: marks.map(item => item.sujbLabel),
                datasets: dataSets,
            };

            // Define the options for the chart
            let scoresChart_options = {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                elements: {
                    bar: {
                        borderWidth: 16,
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                }
            };

            // Create the chart object
            var myChart = new Chart('scoresChart', {
                type: "bar",
                data: scoresChart_data,
                options: scoresChart_options
            });
        }

    }
</script>



<?php

echo $OUTPUT->footer();