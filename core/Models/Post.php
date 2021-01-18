<?php

namespace V\Models;

interface IPost {
    public function link(): string;
}

/**
 * Class Post
 *
 * The Post Model
 *
 * @package V\Models
 */
class Post implements IPost
{
    public function __construct(
        protected string $filepath = '',
    )
    {}

    /**
     * Generate a post link.
     * Removes the POSTS directory path and file extension.
     *
     * @return string
     */
    public function link(): string
    {
        $noDirectoryName = explode('/', str_replace(POSTS, '', $this->filepath));
        return str_replace('.md', '', str_replace('\\', '/', end($noDirectoryName)));
    }
}