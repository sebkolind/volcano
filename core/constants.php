<?php

define('CORE', ROOT . '/core');

/**
 * `SITE` should be set from consumer projects.
 * Otherwise `./site` in volcano is used.
 */
if (!defined('SITE')) {
    define('SITE', ROOT . '/site');
}

define('PAGES', SITE . '/pages');
define('THEME', SITE . '/theme');
define('TEMPLATES', THEME . '/templates');
