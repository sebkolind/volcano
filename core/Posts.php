<?php
namespace V;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

use V\Models\Post;

interface IPosts
{
    public static function getPosts(): array;
    public static function isPost(string $filepath): bool;
}

/**
 * Class Posts
 *
 * Handles everything with Posts.
 *
 * @package V
 */
class Posts extends Filer implements IPosts
{
    public function __construct()
    {
        if (!is_dir(POSTS)) {
            die(
                'Trying to get all posts but the /site/posts directory does not exist.'
            );
        }
    }

    /**
     * Recursively gets all posts in the POSTS directory.
     *
     * @return array
     */
    public static function getPosts(): array
    {
        $allPosts = function () {
            $dir = new RecursiveDirectoryIterator(POSTS);
            $iterator = new RecursiveIteratorIterator($dir);
            $result = new RegexIterator(
                $iterator,
                '/.*(.md)/',
                RegexIterator::MATCH
            );

            foreach ($result as $post) {
                yield $post;
            }
        };

        $posts = [];
        foreach ($allPosts() as $postPath) {
            array_push($posts, new Post($postPath));
        }
        return $posts;
    }

    /**
     * Checks if a given file is a Post
     *
     * @param string $filepath
     * @return bool
     */
    public static function isPost(string $filepath): bool
    {
        return file_exists($filepath);
    }
}
