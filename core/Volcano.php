<?php

namespace V;

use Parsedown;

use V\Models\Post;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * To-do
 * 
 * - Make it possible to "extend" Volcano. Is plugins the way to go?
 * - Test that we can still do the old things.
 * - 
 */

/**
 * Volcano🌋
 * A lightweight, extendable and fast flat-file website and blog constructor.
 * @package V
 */
class Volcano
{
    /**
     * Holds all configuration options with a default value set for each.
     * @var array $configuration
     */
    private array $configuration = [
        'useHomeTemplate' => false,
        'use404' => true,
        'isDev' => false,
    ];

    public function __construct(?array $userConfiguration): void
    {
        $this->configure($userConfiguration);
        $this->checkRequiredFiles();
        $this->resolve();
    }

    /**
     * Renders the Page or Post.
     * TODO: Should we allow passing in a specific route to resolve?
     * @return string
     */
    public function render(): string
    {
        # Pages takes precedence over Posts
        $directory = $this->isPage() ? PAGES : POSTS;
        $filepath = $this->getFile($directory, $this->route());

        # Handle 404
        if (
            !is_null($filepath)
            && $this->setting('use404', true)
            && !is_null($this->getFile(PAGES, '404.md'))
        ) {
            $filepath = $this->getFile(PAGES, '404.md');
        } else {
            # TODO: Should we fail more gracefully?
            die('You have hit a wall.');
        }

        $Parsedown = new Parsedown();

        $file = fopen($filepath, 'r');
        $content = fread($file, filesize($filepath));

        return $Parsedown->text($content);

        fclose($file);
    }

    /**
     * Recursively gets all posts in the POSTS directory.
     * @return Post[]
     */
    public static function posts(): array
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
     * Overrides existing configuration options.
     * @param array $userConfiguration
     * @return void
     */
    private function configure(?array $userConfiguration): void
    {
        if (!is_null($userConfiguration)) {
            foreach ($userConfiguration as $key => $value) {
                if (array_key_exists($key, $this->configuration)) {
                    $this->configuration[$key] = $value;
                }
            }
        }
    }

    /**
     * Checks if all required files exist.
     * @return void
     */
    private function checkRequiredFiles(): void
    {
        $requiredThemeFiles = ['header', 'footer', 'index'];

        foreach ($requiredThemeFiles as $filename) {
            $file = $this->getFile(THEME, "$filename.php");
            if (is_null($file)) {
                die(
                    "${file}.php is a required theme file. You have to create it in /site/theme"
                );
            }
        }
    }

    /**
     * Checks if a file exists and returns the concatenated path
     * @return string|null
     */
    private function getFile(string $directory, string $filename): ?string
    {
        if (file_exists("$directory/$filename")) {
            return "$directory/$filename";
        }
    }

    /**
     * Check if The Route resolves to the home page
     * @return bool
     */
    private function isHome(): bool
    {
        return $this->setting('useHomeTemplate', true)
            # Make sure we are at the root of the website
            && $this->route() === ''
            && !is_null($this->getFile(TEMPLATES, 'home.php'));
    }

    /**
     * Check if The Route resolves to a Template
     * @return bool
     */
    private function isTemplate(): bool
    {
        return !is_null($this->getFile(TEMPLATES, $this->route() . '.php'));
    }

    /**
     * Check if The Route resolves to a Page
     * @return bool
     */
    private function isPage(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFile(PAGES, $this->route() . '.md'));
    }

    /**
     * Check if The Route resolves to a Post
     * @return bool
     */
    private function isPost(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFile(POSTS, $this->route() . '.md'));
    }

    /**
     * Gets and checks a configuration option.
     * @param string $needle
     * @param mixed $condition
     */
    private function setting(string $needle, mixed $condition): bool
    {
        return array_key_exists($needle, $this->configuration)
            && $this->configuration[$needle] === $condition;
    }

    /**
     * "The Route"
     * @return string
     */
    private function route(): string
    {
        $strippedUrl = str_replace(
            [$_SERVER['SERVER_NAME'], '//'],
            '',
            $_SERVER['REQUEST_URI']
        );

        # Convert to array so that we can remove empty values
        # TODO: Is this actually needed?
        $tokenizedUrl = explode('/', $strippedUrl);
    
        # Remove all empty values from array
        $urlParameters = array_filter($tokenizedUrl);

        return implode('/', $urlParameters);
    }

    /**
     * Handles resolving the correct Entry we want.
     * In this order: home.php -> custom template -> Page -> Post -> die()
     * @return void
     */
    private function resolve(): void
    {
        # The Route resolves to Home
        if ($this->isHome()) {
            require_once $this->getFile(TEMPLATES, 'home.php');
        }

        # The Route resolves to a custom template
        elseif ($this->route() !== '' && $this->isTemplate()) {
            require_once $this->getFile(TEMPLATES, $this->route() . '.php');
        }

        # The Route resolves to a Page or Post
        elseif ($this->isPage() || $this->isPost()) {
            require_once $this->getFile(THEME, 'index.php');
        }

        # The Route resolves to nothing.
        else {
            die('Volcano could not resolve your app. Make sure that you have the correct structure.');
        }
    }
}
