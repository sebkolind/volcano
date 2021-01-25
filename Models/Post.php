<?php

namespace Volcano\Models;

use Volcano\Volcano;

interface IPost
{
    public function link(): string;
    public function meta(string $type): string;
}

/**
 * Class Post 📧
 * The Post Model
 * @package Volcano\Models
 */
class Post extends Volcano implements IPost
{
    public function __construct(
        public string $filepath = '',
    ) {
    }

    /**
     * Generate a post link.
     * Removes the POSTS directory path and file extension.
     * @return string
     */
    public function link(): string
    {
        $noDirectoryName = explode('/', str_replace($this->getPath('posts'), '', $this->filepath));
        return str_replace('.md', '', str_replace('\\', '/', end($noDirectoryName)));
    }

    /**
     * Get meta data.
     * @param string $type
     * @return string
     */
    public function meta(string $type): string
    {
        return $this->getMeta($type, $this);
    }
}
