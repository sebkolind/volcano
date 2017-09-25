<?php

/**
 * @desc - get a plugin
 * @param string $name - the plugin folder name
 * @param string|array $options - options to give plugin
 */
function plugin($name, $options = false) {
	if (is_dir(SITE . "/plugins/$name")) {
		if (!file_exists(SITE . "/plugins/$name/index.php")) {
			die("The plugin \"$name\" does not have an index.php file");
		}

		include SITE . "/plugins/$name/index.php";

        // dash-case into camelCase
        $fnName = str_replace('-', '', ucwords($name, '-'));
        $fnName = lcfirst($fnName);

		if (function_exists($fnName)) {
			$fnName($options);
		} else {
			die("It seems like plugin '$name' misses: '$fnName()' function");
		}
	} else {
		die("No plugin named $name exists in /site/plugins - should be a directory");
	}
}

?>
