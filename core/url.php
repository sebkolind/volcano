<?php

/**
 * @desc - all things with the url
 * @param string $param - a specific parameter
 * @return - specific param or array of parameters
 */
function url_params($param = false) {
    $url = str_replace([$_SERVER['SERVER_NAME'], '//'], '', $_SERVER['REQUEST_URI']);

    $url_params = explode('/', $url);

    // Remove all empty values from array
    $url_params = array_filter($url_params);

    /**
     * Reset array keys
     * because if array_filter() removes values
     * the keys won't start from 0
     */
    $url_params = array_values($url_params);

    if ($param) {
        $key = array_search($param, $url_params);
        $param = $key !== false ? $url_params[$key] : '';
    } else {
        $param = $url_params;
    }

    return $param;
}
