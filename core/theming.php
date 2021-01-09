<?php

/**
 * @desc - get a file in the theme folder
 * @param string $filename - the filename
 */
function get_theme_file($filename) {
    if (!$filename) {
        return false;
    }

    if (file_exists(THEME . '/' . $filename)) {
        require THEME . '/' . $filename;
    } else {
        die("get_theme_file() trying to get $filename but it does not exist");
    }
}

function get_header() {
    get_theme_file('header.php');
}

function get_footer() {
    get_theme_file('footer.php');
}

function get_sidebar() {
    get_theme_file('sidebar.php');
}

/**
 * @desc - get styles in site and plugins
 */
function get_stylesheets() {
    foreach (glob('{site/theme/*.css,site/plugins/*/*.css}', GLOB_BRACE) as $file) {
        echo '<link rel="stylesheet" type="text/css" href="' . $file . '">';
    }
}

/**
 * @desc - get JS in site and plugins
 */
function get_scripts() {
    foreach (glob('{site/theme/*.js,site/plugins/*/*.js}', GLOB_BRACE) as $file) {
        echo '<script type="text/javascript" src="' . $file . '"></script>';
    }
}

/**
 * @desc - get body classes. Used in the <body> tag
 */
function body_class() {
    if (is_home()) {
        echo 'is-home home';
    } else {
        $page_or_post = is_post() ? 'is-post post-' : 'is-page page-';
        echo $page_or_post . implode('-', url_params());
    }
}

/**
 * @desc - checks if current page in home
 */
function is_home() {
    if (url_params()) {
        return false;
    }

    return true;
}

/**
 * @desc - checks if current page _is_ a page
 * 	or if current page is specific page given by $name
 */
function is_page($name = false) {
    if ($name) {
        if (url_params($name)) {
            return true;
        }
    } else {
        if (url_params()) {
            return true;
        }
    }

    return false;
}

/**
 * @desc - get a theme partial
 * @param string $name - name of partial
 */
function get_partial($name) {
    if (is_dir(THEME . '/partials')) {
        if (file_exists(THEME . '/partials/' . $name . '.php')) {
            include THEME . '/partials/' . $name . '.php';
        } else {
            die("Partial ${name}.php doesn't exist");
        }
    } else {
        die("Trying to get partial ${name}.php, but no /partials folder found");
    }
}
