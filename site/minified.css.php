<?php
/**
 * Outputs all CSS as minified
 * Kindly from https://ikreativ.com/combine-minify-css-with-php/
 */

header('Content-type: text/css');
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

include './theme/styles.css';

/**
 * Getting all styles in plugins
 */
foreach (glob('plugins/*/*.css') as $file) {
	if (filesize($file) > 0) {
		include $file;
	}
}

ob_end_flush();
