<?php

/**
 * @desc - path for site.
 * should be set, otherwise content will be served from volcano/site internally.
 * default: volcano/site
 */
define('SITE', dirname(__FILE__) . '/site');

/**
 * @desc - use 404 page or not
 * if "false" redirect to default.md
 * default: true
 */
define('USE_404', true);

/**
 * @desc - use index.php or template as home
 * if `true` template has to be: site/templates/home.php
 * if `false` it will be index.php
 * default: true
 */
define('USE_HOME_TEMPLATE', false);

/**
 * @desc - whether or not to show errors.
 * default: false
 *
 * Should be false when your site is live
 *
 * By setting to true, you'll get error messages.
 */
define('IS_DEV', false);

// require volcano
require './vendor/sebastianks/volcano/index.php';
