<?php
use function site_meta as post_meta;

/**
 * Recursively searches for Markdown files in the POSTS directory.
 *
 * @return generator yielding all posts
 */
function recursive_get_posts() {
    $dir = new RecursiveDirectoryIterator(POSTS);
    $iterator = new RecursiveIteratorIterator($dir);
    $all_posts = new RegexIterator($iterator, '/.*(.md)/', RegexIterator::MATCH);

    foreach ($all_posts as $value) {
        yield $value;
    }
}

/**
 * Get all posts in the POSTS directory.
 * Returns either an HTML list or an array.
 *
 * @param string|boolean tag specify what tag to render with, or false if none.
 *
 * @return array|void
 */
function get_posts($tag = 'ul') {
    if (!is_dir(POSTS)) {
        die('Trying to get all posts but the /site/posts directory does not exist.');
    }

    $all_posts = recursive_get_posts();

    if ($tag === false) {
        return $all_posts;
    }

    echo '<' . $tag . ' class="vo-list vo-posts">';
    foreach ($all_posts as $post) {
        $post_title = post_meta('title', false, $post);
        $post_written = post_meta('written', false, $post);
        $post_link = get_post_link($post);

        echo '<li><a href="' . $post_link . '">' . $post_title . $post_written . '</a></li>';
    }
    echo '</' . $tag . '>';
}

/**
 * Generate a post link.
 * Removes the POSTS directory path and file extension.
 *
 * @param string filename of post
 *
 * @return string link
 */
function get_post_link($post) {
    $no_dir_name = explode('/', str_replace(POSTS, '', $post));
    $link = str_replace('.md', '', str_replace('\\', '/', end($no_dir_name)));
    return $link;
}

/**
 * Checks if the current URL renders to a post.
 *
 * @return boolean
 */
function is_post() {
    $filepath = POSTS . '/' . implode('/', url_params()) . '.md';
    return file_exists($filepath);
}
