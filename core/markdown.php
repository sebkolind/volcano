<?php

/**
 * Search for a Markdown file based on the URL.
 * Parse the contents of the file with Parsedown.
 *
 * Will look for files in PAGES first and then POSTS.
 * On 404 either use PAGES/404.md or PAGES/default.md in that order.
 *
 * @param array|boolean $filename false or an array specifying path and filename
 * @return void prints the parsed Markdown file
 */
function parse_markdown($filename = false) {
    $file = implode('/', $filename) . '.md';
    $directory = $filename && file_exists(PAGES . '/' . $file) ? PAGES : POSTS;
    $filepath = !$filename ? $directory . '/default.md' : $directory . '/' . $file;

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
