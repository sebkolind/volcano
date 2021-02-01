<?php

namespace Volcano;

use Volcano\Models\Entry;

use Parsedown;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Volcano🌋
 * A micro flat-file blog and website constructor.
 * @package Volcano
 */
class Volcano
{
    /**
     * Holds all configuration options with a default value set for each.
     * @var array $configuration
     */
    private array $configuration = [
        'paths' => [
            'pages' => 'site/pages',
            'posts' => 'site/posts',
            'theme' => 'site/theme',
            'templates' => 'site/templates',
        ],
    ];

    public function __construct(?array $userConfiguration = null)
    {
        # A theme needs at least a "index.php" file.
        if (!file_exists($this->getFilePath($this->getPath('theme'), 'index.php'))) {
            die('index.php is a required theme file. You have to create it in ' . $this->getPath('theme'));
        }

        # Setup configurations and paths
        $this->configure($userConfiguration);

        # Resolve the app
        $this->resolve();
    }

    /**
     * "The Magic" 🧙‍♂️
     * Renders the Page or Post.
     * @return string
     */
    public function render(): string
    {
        $filepath = $this->getEntryPath();

        # Handle 404
        if (is_null($filepath)) {
            if (is_null($this->getFilePath($this->getPath('pages'), '404.md'))) {
                # Since no 404 page exists, set HTTP response to 404.
                header('Location: /', true, 404);
                die();
            }
            # Show custom 404 page.
            header('Location: /404', true);
            die();
        }

        $Parsedown = new Parsedown();

        $file = fopen($filepath, 'r');
        $content = fread($file, filesize($filepath));

        return $Parsedown->text($content);

        fclose($file);
    }

    /**
     * Recursively get all Entries based on type.
     * @param string $type 'posts'|'pages'. Default 'posts'.
     * @return Entry[]
     */
    public function getEntries(string $type = 'posts'): array
    {
        $allEntries = function ($type) {
            $dir = new RecursiveDirectoryIterator($this->getPath($type));
            $iterator = new RecursiveIteratorIterator($dir);
            $result = new RegexIterator(
                $iterator,
                '/.*(.md)/',
                RegexIterator::MATCH
            );

            foreach ($result as $entry) {
                yield $entry;
            }
        };

        $entries = [];
        foreach ($allEntries($type) as $entryPath) {
            array_push($entries, new Entry($entryPath));
        }
        return $entries;
    }

    /**
     * Get Meta data for an Entry or Template.
     * @param string $type
     * @param ?Entry $entry
     * @return string
     */
    public function getMeta(string $type, ?Entry $entry = null): string
    {
        /**
         * Since "resolvedRoute()" resolves to "site/theme/index.php" if The Route resolves to an Entry
         * we cannot use it for returning the Entry file (the Markdown file).
         */
        $filepath = $this->isEntry() ? $this->getEntryPath() : $this->resolvedRoute();

        /**
         * Set $filepath to an Entry's path if given.
         * This is typically used if rendering a list of Entry's.
         */
        if (
            !is_null($entry)
            && $entry->file !== ''
            && file_exists($entry->file)
        ) {
            $filepath = $entry->file;
        }

        if (!file_exists($filepath)) {
            $filepath = $this->getFilePath($this->getPath('pages'), '404.md');
        }

        $file = file_get_contents($filepath);

        $extension = pathinfo($filepath, PATHINFO_EXTENSION);
        $tokens = [];

        if ($extension === 'md') {
            if (str_contains($file, '<!--')) {
                $file = str_replace(["\r\n", "\r", "\n"], '', $file);
                preg_match('/<!--(.*)-->/', $file, $match);
                $tokens = explode('*', $match[1]);
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

        # It was not possible to resolve to any meta data.
        return '';
    }

    /**
     * Get a path in the $configuration array
     * @param string $needle
     * @return string
     */
    public function getPath(string $needle): string
    {
        return $this->configuration['paths'][$needle];
    }

    /**
     * Set all paths and override existing configuration options.
     * @param array $userConfiguration
     * @return void
     */
    private function configure(?array $userConfiguration): void
    {
        # Get consumer project root dir
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $consumerDir = dirname($reflection->getFileName(), 3);

        # Set default paths
        foreach ($this->configuration['paths'] as $key => $value) {
            $this->configuration['paths'][$key] = "$consumerDir/$value";
        }

        # Merge user configurations with default configurations
        $this->configuration = array_replace_recursive($this->configuration, $userConfiguration);
    }

    /**
     * Checks if a file exists and returns the concatenated path
     * @return string|null
     */
    private function getFilePath(string $directory, string $filename): ?string
    {
        if (file_exists("$directory/$filename")) {
            return "$directory/$filename";
        }
        return null;
    }

    /**
     * Get the path to the resolved Entry.
     * @return string
     */
    private function getEntryPath(): string
    {
        # Pages takes precedence over Posts
        $directory = $this->isPage() ? $this->getPath('pages') : $this->getPath('posts');
        return $this->getFilePath($directory, $this->route() . '.md');
    }

    /**
     * Check if The Route resolves to the home page
     * @return bool
     */
    private function isHome(): bool
    {
        # Make sure we are at the root of the website
        return $this->route() === ''
            && !is_null($this->getFilePath($this->getPath('templates'), 'home.php'));
    }

    /**
     * Check if The Route resolves to a Template
     * @return bool
     */
    private function isTemplate(): bool
    {
        return !is_null($this->getFilePath($this->getPath('templates'), $this->route() . '.php'));
    }

    /**
     * Check if The Route resolves to a Page
     * @return bool
     */
    public function isPage(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFilePath($this->getPath('pages'), $this->route() . '.md'));
    }

    /**
     * Check if The Route resolves to a Post
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFilePath($this->getPath('posts'), $this->route() . '.md'));
    }

    /**
     * Check if The Route resolves to an Entry.
     * @return bool
     */
    public function isEntry(): bool
    {
        return $this->isPage() || $this->isPost();
    }

    /**
     * "The Route"
     * @return string
     */
    private function route(): string
    {
        $strippedUrl = str_replace([$_SERVER['SERVER_NAME'], '//'], '', $_SERVER['REQUEST_URI']);

        # Convert to array so that we can remove empty values
        $tokenizedUrl = explode('/', $strippedUrl);
    
        # Remove all empty values from array
        $urlParameters = array_filter($tokenizedUrl);

        return implode('/', $urlParameters);
    }

    /**
     * "The Resolved Route"
     * @return string
     */
    private function resolvedRoute(): string
    {
        # The Route resolves to Home
        if ($this->isHome()) {
            return $this->getFilePath($this->getPath('templates'), 'home.php');
        }

        # The Route resolves to a custom template
        elseif ($this->route() !== '' && $this->isTemplate()) {
            return $this->getFilePath($this->getPath('templates'), $this->route() . '.php');
        }

        # The Route resolves to a Page or Post
        elseif ($this->isEntry()) {
            return $this->getFilePath($this->getPath('theme'), 'index.php');
        }

        # The Route resolved to 404
        elseif (!is_null($this->getFilePath($this->getPath('pages'), '404.md'))) {
            return header('Location: /404', true);
            die();
        }

        # The Route resolves to nothing.
        else {
            die('Volcano could not resolve your app. Make sure that you have the correct structure.');
        }
    }

    /**
     * "The End"
     * Handles resolving the correct Template or Entry we want.
     * In this order: home.php -> Template -> Page -> Post -> die()
     * @return void
     */
    private function resolve(): void
    {
        require_once $this->resolvedRoute();
    }
}
