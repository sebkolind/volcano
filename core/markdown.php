<?php

/**
 * @desc - parse markdown
 * @param string $filename - the filename
 * @param string $nested_path - nested path inside /site/pages
 */
function parse_markdown($filename = false, $nested_path = false) {
	$filepath = !$filename
				? PAGES . '/default.md'
				: PAGES . '/' . $filename . '.md';

	if ($nested_path) {
		if (!$filename) {
			die('When using nested path (second argument) you have to give filename as first argument');
		}

		$filepath = PAGES . $nested_path . '/' . $filename . '.md';
	} else {
		$filepath = !$filename
			? PAGES . '/default.md'
			: PAGES . '/' . $filename . '.md';
	}

	if (!file_exists($filepath)) {
		$filename = USE_404 && file_exists(PAGES . '/404.md') ? '404' : 'default';
		$filepath = PAGES . '/' . $filename . '.md';
	}

	$Parsedown = new Parsedown();

	$file = fopen($filepath, 'r');
	$content = fread($file, filesize($filepath));

	echo $Parsedown->text($content);

	fclose($file);
}
