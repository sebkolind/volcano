<?php

/**
 * Get meta from a .php or .md file.
 * Meta data is written in the comment section in the top of the file.
 *
 * @param string $needle get specific meta
 * @param boolean $print return or echo value
 * @param string $haystack give a specific file to work on
 *
 * @return string|array|null null if no data, or all meta data or specific
 */
function site_meta($needle = false, $print = false, $haystack = null) {
    $filename = implode('/', url_params());

    if (url_params() && file_exists(TEMPLATES . '/' . $filename . '.php')) {
        $filepath = TEMPLATES . '/' . $filename . '.php';
    } elseif (!url_params() && USE_HOME_TEMPLATE && file_exists(TEMPLATES . '/home.php')) {
        $filepath = TEMPLATES . '/home.php';
    } elseif (!url_params()) {
        $filepath = PAGES . '/default.md';
    } else {
        $directory = file_exists(PAGES . '/' . $filename . '.md') ? PAGES : POSTS;
        $filepath = $directory . '/' . $filename . '.md';
    }

    if (!is_null($haystack) && file_exists($haystack)) {
        $filepath = $haystack;
    }

    $extension = pathinfo($filepath, PATHINFO_EXTENSION);

    if (!file_exists($filepath)) {
        $filepath = PAGES . '/404.md';
    }

    $file = file_get_contents($filepath);

    $meta = [];
    $arr = [];

    if ($extension === 'md') {
        if (strpos($file, '<!--') !== false) {
            $file = str_replace(["\r\n", "\r", "\n"], '', $file);
            preg_match('/<!--(.*)-->/', $file, $match);

            $arr = explode('*', implode($match));
        }
    }

    if ($extension === 'php') {
        foreach (token_get_all($file) as $token) {
            if (is_array($token) && token_name($token[0]) === 'T_DOC_COMMENT') {
                $arr = explode('*', $token[1]);
            }
        }
    }

    // No meta data in the file currently working on.
    if (count($arr) === 0) {
        return;
    }

    foreach ($arr as $value) {
        if ($value === '') {
            continue;
        }

        // Title
        if (stripos($value, 'Title') !== false) {
            $meta['title'] = trim(str_ireplace('Title:', '', $value));
        }

        // Description
        if (stripos($value, 'Description') !== false) {
            $meta['description'] = trim(str_ireplace('Description:', '', $value));
        }

        // Written
        if (stripos($value, 'Written') !== false) {
            $meta['written'] = trim(str_ireplace('Written:', '', $value));
        }

        // Updated
        if (stripos($value, 'Updated') !== false) {
            $meta['updated'] = trim(str_ireplace('Updated:', '', $value));
        }

        // Author
        if (stripos($value, 'Author') !== false) {
            $meta['author'] = trim(str_ireplace('Author:', '', $value));
        }
    }

    if ($needle) {
        $needle = strtolower($needle);

        if (array_key_exists($needle, $meta)) {
            if ($print) {
                echo $meta[$needle];
            }
            return $meta[$needle];
        } else {
            return '';
        }
    }

    if ($print) {
        echo $meta;
    }

    return $meta;
}
