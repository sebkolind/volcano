<?php

require CORE . '/theming.php';

// Check if theme folder exists
if (!is_dir(SITE . '/theme')) {
	die('You need a "theme" folder in: /site/');
}

// Check if all required theme files exists
$req_theme_files = array(
	'header',
	'footer',
	'index'
);

foreach ($req_theme_files as $filename) {
	if (!file_exists(THEME . '/' . $filename . '.php')) {
		die("${filename}.php is a required theme file. You have to create it in /site/theme");
	}
}

/**
 * @desc - get theme file according to current page.
 */
if (
	url_params() &&
	file_exists(TEMPLATES . '/' . url_params()[0] . '.php')
) {
	include TEMPLATES . '/' . url_params()[0] . '.php';
}

// No page and custom home template
elseif (
	!url_params() &&
	USE_HOME_TEMPLATE
) {
	if (file_exists(TEMPLATES . '/home.php')) {
		include TEMPLATES . '/home.php';
	} else {
		die('Trying to use custom home template, but no template named "home.php" was found in /site/templates/');
	}
}

else {
	require THEME . "/index.php";
}

?>
