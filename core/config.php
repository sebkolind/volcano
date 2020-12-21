<?php

date_default_timezone_set('Europe/Copenhagen');

require_once 'vendor/Parsedown.php';

require './setup.php';

if (IS_DEV) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

require 'constants.php';
require 'url.php';
require 'meta.php';
require 'utilities.php';
require 'markdown.php';
require 'content.php';
require 'plugins.php';
require 'generators.php';

if (!file_exists(PAGES . '/default.md')) {
    die('You need at least a "default.md" file in your /site/pages folder');
}

require 'load-theme.php';

?>
