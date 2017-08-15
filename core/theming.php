<?php

/**
 * @desc - get a file in the theme folder
 * @param string $filename - the filename
 */
function get_theme_file($filename) {
	if (!$filename) return false;

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
 * @desc - get minified styles, stylesheet based on filename or default styles.css
 * @param string $filename - the filename
 */
function get_stylesheet($filename = false) {
	$filename = $filename ? $filename . '.css' : 'styles.css';

	if (USE_MINIFIED_CSS) {
		echo '<link rel="stylesheet" type="text/css" href="/site/minified.css.php">';
	} else {
		if (file_exists(ROOT . "/site/theme/$filename")) {
			echo '<link rel="stylesheet" type="text/css" href="/site/theme/' . $filename . '.css">';
		} else {
			die("No CSS file named $filename was found in /site/theme");
		}
	}
}

/**
 * @desc - get minified JS or JS based on filename
 * @param string $filename - the filename
 */
function get_scripts($filename = false) {
	if (USE_MINIFIED_JS) {
        echo '<script src="/site/minified.js.php"></script>';
	} else {
		if (file_exists(ROOT . "/site/theme/${filename}.js")) {
            echo '<script src="/site/theme/' . $filename . '.js"></script>';
			echo '<link rel="stylesheet" type="text/css" href="/site/theme/' . $filename . '.js">';
		} else {
			die("No JS file named ${filename}.js was found in /site/theme");
		}
	}
}

/**
 * @desc - get body classes. Used in the <body> tag
 */
function body_class() {
	if (is_home()) {
		echo 'is-home home';
	} else {
		echo 'is-page page-' . url_params()[0];
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
