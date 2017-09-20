<?php
/**
 * Outputs all JS as minified
 * Kindly inspired by https://ikreativ.com/combine-minify-css-with-php/
 */

header('Content-type: text/javascript');
if (!IS_DEV) {
	header('Cache-Control: max-age=604800, public'); # cache for 1 week
}

ob_start("compress");

function compress($minify) {
	// Remove comments
	$minify = preg_replace('!/*[^*]*+([^/][^*]*+)*/!', '', $minify);

	// Remove tabs, spaces, newlines, etc.
	$minify = str_replace(array("\rn", "\r", "\n", "\t", '  ', '    ', '    '), '', $minify);

	return $minify;
}

/**
 * Getting all JS files in plugins
 */
foreach (glob('plugins/*/*.js') as $file) {
	if (filesize($file) > 0) {
		include $file;
	}
}

ob_end_flush();
