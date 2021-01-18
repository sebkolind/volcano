<?php

namespace V;

interface IFiler
{
    public static function getFile(string $filename, string $directory): string|null;
    public static function getCurrentFilepath(): string;
    public static function getCurrentFile(): string|null;
}

/**
 * Class Filer
 *
 * Handles everything that has to do with files.
 *
 * @package V
 */
class Filer implements IFiler
{
    /**
     * Attempt to get a file by directory and filename.
     *
     * @param string $filename
     * @param string $directory
     * @return string|null
     */
    public static function getFile(string $filename, string $directory): string|null
    {
        if (!file_exists($directory . '/' . $filename)) {
            return null;
        }
        return $directory . '/' . $filename;
    }

    /**
     * Get current filepath based on URL parameters.
     *
     * @return string
     */
    public static function getCurrentFilepath(): string
    {
        return implode('/', Url::getParameters());
    }

    /**
     * Get current file based on current Route.
     *
     * @return string|null
     */
    public static function getCurrentFile(): string|null
    {
        $filename = self::getCurrentFilepath();

        if (Router::isTemplate()) {
            $filepath = self::getFile($filename . '.php', TEMPLATES);
        } elseif (Router::isHome()) {
            $filepath = self::getFile('home.php', TEMPLATES);
        } elseif (Router::isDefault()) {
            $filepath = self::getFile('default.md', PAGES);
        } else {
            // PAGES takes precedence over POSTS.
            $directory = self::getFile($filename . '.md', PAGES) !== null
                ? PAGES
                : POSTS;
            $filepath = self::getFile($filename . '.md', $directory);
        }

        return $filepath;
    }
}
