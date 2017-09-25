<?php

/**
 * @desc - get meta from a .php or .md file. Meta data is written in a
  	comment section of the file.
 * @param string $needle - get specific meta
 * @param boolean $print - return or echo value
 * @return - all meta data or specific
 */
function site_meta($needle = false, $print = false) {
	if (
		url_params() &&
		file_exists(TEMPLATES . '/' . url_params()[0] . '.php')
	) {
		$filepath = TEMPLATES . '/' . url_params()[0] . '.php';

	} elseif (
		!url_params() &&
		USE_HOME_TEMPLATE &&
		file_exists(TEMPLATES . '/home.php')
	) {
		$filepath = TEMPLATES . '/home.php';

	} elseif (!url_params()) {
		$filepath = PAGES . '/default.md';

	} else {
		$filepath = PAGES . '/' . url_params()[0] . '.md';
	}

	$extension = pathinfo($filepath, PATHINFO_EXTENSION);

	if (!file_exists($filepath)) {
		$filepath = PAGES . '/404.md';
	}

	$file = file_get_contents($filepath);

	$meta = array();

	if ($extension === 'md') {
		if (strpos($file, '<!--') !== false) {
			$file = str_replace(array("\r\n", "\r", "\n"), '', $file);
			preg_match('/<!--(.*)-->/', $file, $match);

			$arr = explode('*', implode($match));
		}
	}

	if ($extension === 'php') {
		foreach (token_get_all($file) as $token) {
			if (
				is_array($token) &&
				token_name($token[0]) === 'T_DOC_COMMENT'
			) {
				$arr = explode('*', $token[1]);
			}
		}
	}

	foreach ($arr as $value) {
		if ($value === '') {
			continue;
		}

		// Title
		if (
			stripos($value, 'Title') !== false
		) {
			$meta['title'] =
			trim(
				str_ireplace(
					'Title:',
					'',
					$value
				)
			);
		}

		// Description
		if (
			stripos($value, 'Description') !== false
		) {
			$meta['description'] =
			trim(
				str_ireplace(
					'Description:',
					'',
					$value
				)
			);
		}
	}

	if ($needle) {
		$needle = strtolower($needle);

		if (array_key_exists($needle, $meta)) {
			if ($print) {
				echo $meta[$needle];
			}
			return $meta[$needle];
		} else {
			return '';
		}
	}

	if ($print) {
		echo $meta;
	}

	return $meta;
}
