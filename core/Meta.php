<?php

namespace V;

interface IMeta
{
    public static function getMeta(string $type, string|null $haystack): string|null;
}

/**
 * Class Meta
 *
 * Handles meta data on any supported file.
 *
 * @package V
 */
class Meta implements IMeta
{
    /**
     * @param string $type
     * @param string|null $haystack
     * @return string|null
     */
    public static function getMeta(string $type, string|null $haystack = null): string|null
    {
        $filepath = Filer::getCurrentFile();

        if (!is_null($haystack) && file_exists($haystack)) {
            $filepath = $haystack;
        }

        if (!file_exists($filepath)) {
            $filepath = PAGES . '/404.md';
        }

        $file = file_get_contents($filepath);

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $tokens = [];

        if ($extension === 'md') {
            if (str_contains($file, '<!--')) {
                $file = str_replace(["\r\n", "\r", "\n"], '', $file);
                preg_match('/<!--(.*)-->/', $file, $match);
                $tokens = explode('*', implode('', $match));
            }
        }

        if ($extension === 'php') {
            foreach (token_get_all($file) as $token) {
                if (is_array($token) && token_name($token[0]) === 'T_DOC_COMMENT') {
                    $tokens = explode('*', $token[1]);
                }
            }
        }

        // No meta data in the file currently working on.
        if (count($tokens) === 0) {
            return null;
        }

        foreach ($tokens as $t) {
            if (stripos($t, $type) !== false) {
                return trim(str_ireplace($type . ':', '', $t));
            }
        }
    }
}
