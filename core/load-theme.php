<?php

use V\Filer;
use V\Url;

require CORE . '/theming.php';

// Check if theme folder exists
if (!is_dir(SITE . '/theme')) {
    die('You need a "theme" folder in: /site/');
}

// Check if all required theme files exists
$req_theme_files = ['header', 'footer', 'index'];

foreach ($req_theme_files as $filename) {
    if (!file_exists(THEME . '/' . $filename . '.php')) {
        die(
            "${filename}.php is a required theme file. You have to create it in /site/theme"
        );
    }
}

/**
 * @desc - get theme file according to current page.
 */

// If on a page, and template exists
if (Url::getParameters() && Filer::getFile(Filer::getCurrentFilepath() . '.php', TEMPLATES)) {
    include TEMPLATES . '/' . Filer::getCurrentFilepath() . '.php';
}

// No page, but custom home template
elseif (!Url::getParameters() && USE_HOME_TEMPLATE) {
    if (file_exists(TEMPLATES . '/home.php')) {
        include TEMPLATES . '/home.php';
    } else {
        die(
            'Trying to use custom home template, but no template named "home.php" was found in /site/templates/'
        );
    }
}

// Else - require index.php
else {
    require THEME . '/index.php';
}
