<?php

/**
 * This file handles some basic setup
 * mostly constants
 */


/**
 * @desc - use 404 page or not
 * 	if "false" redirect to default.md
 * 	default: true
 */
define('USE_404', true);

/**
 * @desc - use index.php or template as home
 * 	if "true" template has to be: site/templates/home.php
 */
define('USE_HOME_TEMPLATE', true);

/**
 * @desc - true: show errors
 * 		   false - don't show errors.
 * Should be false when your site is live
 */
define('IS_DEV', false);

/**
 * @desc - use minified CSS or not
 */
define('USE_MINIFIED_CSS', true);

/**
 * @desc - use minified JS or not
 */
define('USE_MINIFIED_JS', true);
