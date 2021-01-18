<?php

namespace V;

interface IRouter
{
    public static function isHome(): bool;
    public static function isTemplate(): bool;
    public static function isDefault(): bool;
}

/**
 * Class Router
 * @package V
 */
class Router implements IRouter
{
    /**
     * Check if the current route resolves to a home template.
     *
     * @return bool
     */
    public static function isHome(): bool
    {
        return self::isDefault() !== null &&
            USE_HOME_TEMPLATE &&
            file_exists(TEMPLATES . '/home.php');
    }

    /**
     * Check if the current route resolvs to a custom template.
     *
     * @return bool
     */
    public static function isTemplate(): bool
    {
        return count(Url::getParameters()) > 0 &&
            Filer::getFile(Filer::getCurrentFilepath(), TEMPLATES) !== null;
    }

    /**
     * Check if the current route resolves to the default page.
     * TODO: Is this needed? Do we want to have a default fallback?
     *
     * @return bool
     */
    public static function isDefault(): bool
    {
        return count(Url::getParameters()) > 0;
    }
}
