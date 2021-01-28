<?php

/**
 * Common helper functions of Dikkha Application
 */
if (!function_exists('get_weekdays')) {
    /**
     * Get weekday information
     *
     * @param int $day [ optional ]
     * @return boolean|string|array
     */
    function get_weekdays($day = -1) {
        $weekdays = [
          0 => 'Sunday',
          1 => 'Monday',
          2 => 'Tuesday',
          3 => 'Wednesday',
          4 => 'Thursday',
          5 => 'Friday',
          6 => 'Saturday'
        ];
        if (is_int($day) && $day >= 0) {
            if ($day < 7) {
                return $weekdays[$day];
            } else {
                return false;
            }
        }

        return $weekdays;
    }

}

if (!function_exists('get_available_times')) {
    /**
     * Get teacher available time information
     *
     * @param string $time [ optional ]
     * @return boolean|string|array
     */
    function get_available_times($time = '') {
        $available_times = [
          'morning' => 'Morning(7am-12pm)',
          'noon' => 'Noon(12pm-5pm)',
          'night' => 'Night(5pm-12am)'
        ];
        if (array_key_exists($time, $available_times)) {
            return $available_times[$time];
        }

        return $available_times;
    }

}

if (!function_exists('get_academic_degrees')) {
    /**
     * Get academy degree list
     *
     * @param int $degree [ optional ]
     * @return boolean|string|array
     */
    function get_academic_degrees($degree = -1) {
        $academic_degrees = [
          1 => 'Masters',
          2 => 'Bachelor/Honors',
          3 => 'Doctoral',
          4 => 'Diploma',
          5 => 'Higher Secondary'
        ];
        if (is_int($degree) && $degree > 0 ) {
            if ($degree < 6) {
                return $academic_degrees[$degree];
            } else {
                return false;
            }
        }

        return $academic_degrees;
    }

}

if (!function_exists('get_project_statuses')) {

}

if (!function_exists('get_project_statuses')) {

    function get_project_statuses() {
        return array('Ongoing', 'Support', 'Completed');
    }

}

if (!function_exists('get_method_types')) {
    /**
     * Get HTTP Method list
     *
     * @param bool $json [ optional ]
     * @return boolean|string|array
     */
    function get_method_types($json = false) {
        $array = array('GET', 'POST', 'PUT', 'DELETE');
        if ($json) {
            $array[] = 'JSON';
        }
        return $array;
    }

}

if (!function_exists('get_param_data_types')) {

    function get_param_data_types() {
        return array('int', 'float', 'string', 'array', 'file');
    }

}

if (!function_exists('get_date_difference')) {

    function get_date_difference($datetime = '') {
        $format = 'Y-m-d H:i:s';
        $d = DateTime::createFromFormat($format, $datetime);
        if ($d && $d->format($format) == $datetime) {


            $datetime1 = new DateTime($datetime);
            $datetime2 = new DateTime(date($format));
            $oDiff = $datetime1->diff($datetime2);

//        if ($oDiff->days > 30) {
//            $publishTime = $oDiff->m . ' month' . ($oDiff->m > 1 ? 's' : '') . ' ago';
//        } else {
//            $publishTime = ($oDiff->days > 0) ? $oDiff->days . ' day' . (($oDiff->days > 1) ? 's' : '') . ' ago' : ($oDiff->h == 0 ? $oDiff->i . ' Mins ago' : $oDiff->h . ' Hours ago ');
//        }
//        echo $oDiff->y . ' Years <br/>';
//        echo $oDiff->m . ' Months <br/>';
//        echo $oDiff->d . ' Days <br/>';
//        echo $oDiff->h . ' Hours <br/>';
//        echo $oDiff->i . ' Minutes <br/>';
//        echo $oDiff->s . ' Seconds <br/>';
//        echo $oDiff->days . ' Total Days <br/>';
            return array(
              'days' => $oDiff->days,
              'months' => $oDiff->m,
              'years' => $oDiff->y,
            );
        }
        return '';
    }

}

if (!function_exists('debug')) {

    function debug($data, $print = false, $exit = TRUE) {
        if ($print) {
            echo $data;
        } else {
            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
        if ($exit) {
            exit();
        }
    }

}
