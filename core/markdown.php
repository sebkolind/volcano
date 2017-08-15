<?php

/**
 * @desc - parse markdown
 * @param string $filename - the filename
 */
function parse_markdown($filename = null) {
	$filepath = !$filename
				? PAGES . '/default.md'
				: PAGES . '/' . $filename . '.md';

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

?>
