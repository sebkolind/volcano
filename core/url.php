<?php

namespace V;

interface IUrl
{
    public static function getParameters(): array;
    public static function checkIfPage(string $needle): bool;
}

/**
 * Class Url
 *
 * Handles everything with the URL.
 *
 * @package V
 */
class Url implements IUrl
{
    /**
     * Get all URL parameters
     *
     * @return array
     */
    public static function getParameters(): array
    {
        $strippedUrl = str_replace(
            [$_SERVER['SERVER_NAME'], '//'],
            '',
            $_SERVER['REQUEST_URI']
        );

        $tokenizedUrl = explode('/', $strippedUrl);
    
        // Remove all empty values from array
        $urlParameters = array_filter($tokenizedUrl);

        /**
         * Reset array keys because if array_filter() removes values
         * the keys won't start from 0
         */
        return array_values($urlParameters);
    }

    /**
     * Check if a given page
     * TODO: Is this needed?
     *
     * @param string $needle
     * @return bool
     */
    public static function checkIfPage(string $needle): bool
    {
        return array_search($needle, self::getParameters()) !== false;
    }
}
