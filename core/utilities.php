<?php

/**
 * @desc - pretty print array
 * @param array $array - the array
 * @param boolean $report - echo result or print with <pre>
 */
function pre($array, $report = false) {
    if ($report) {
        echo $array;
    }

    echo '<pre>' . print_r($array, true) . '</pre>';
}
