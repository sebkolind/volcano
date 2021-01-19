<?php

/**
 * To-do
 *
 * - Make it possible to "extend" Volcano. Is plugins the way to go?
 */

/**
 * Volcano🌋
 * A lightweight, extendable and fast flat-file website and blog constructor.
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
        'paths' => [
            'site' => 'site',
            'pages' => 'site/pages',
            'posts' => 'site/posts',
            'theme' => 'site/theme',
            'templates' => 'site/theme/templates',
        ],
    ];

    public function __construct(?array $userConfiguration)
    {
        # A theme needs at least a "index.php" file which calls "render()"
        if (!file_exists($this->getFilePath($this->getPath('theme'), 'index.php'))) {
            die("index.php is a required theme file. You have to create it in /site/theme");
        }

        # Setup configurations and paths
        $this->configure($userConfiguration);

        # Resolve the app
        $this->resolve();
    }

    /**
     * Renders the Page or Post.
     * TODO: Should we allow passing in a specific route to resolve?
     * @return string
     */
    public function render(): string
    {
        $filepath = $this->getEntryPath();

        # Handle 404
        if (
            is_null($filepath)
            && $this->setting('use404', true)
        ) {
            # `use404` is set to true, but no 404.md file exists in the PAGES directory
            if (is_null($this->getFilePath($this->getPath('pages'), '404.md'))) {
                die('We are trying to show 404.md, but it does not exist. We have to exit.');
            }
            $filepath = $this->getFilePath($this->getPath('pages'), '404.md');
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
    public function posts(): array
    {
        require_once 'Models/Post.php';

        $allPosts = function () {
            $dir = new RecursiveDirectoryIterator($this->getPath('posts'));
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
     * Get Meta data for a Post or Page
     * @param string $type
     * @param string $filepath
     * @return ?string
     */
    public function getMeta(string $type): ?string
    {
        $filepath = $this->getEntryPath();
        
        if (!file_exists($filepath)) {
            $filepath = $this->getPath('pages') . '/404.md';
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

        # It was not possible to resolve to any meta data.
        return null;
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
        $reflection = new \ReflectionClass(\Composer\Autoload\ClassLoader::class);
        $consumerDir = dirname($reflection->getFileName(), 3);

        # Set paths if not given in the user configuration
        foreach ($this->configuration['paths'] as $key => $value) {
            if (!array_key_exists($key, $userConfiguration)) {
                $this->configuration['paths'][$key] = "$consumerDir/$value";
            }
        }

        # Override existing configurations if any
        if (!is_null($userConfiguration)) {
            foreach ($userConfiguration as $key => $value) {
                if (array_key_exists($key, $this->configuration)) {
                    $this->configuration[$key] = $value;
                }
            }
        }
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
     * Get the path to the resolved Entry. That is a Post or a Page.
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
        return $this->setting('useHomeTemplate', true)
            # Make sure we are at the root of the website
            && $this->route() === ''
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
    private function isPage(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFilePath($this->getPath('pages'), $this->route() . '.md'));
    }

    /**
     * Check if The Route resolves to a Post
     * @return bool
     */
    private function isPost(): bool
    {
        return $this->route() !== ''
            && !is_null($this->getFilePath($this->getPath('posts'), $this->route() . '.md'));
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
        elseif ($this->isPage() || $this->isPost()) {
            return $this->getFilePath($this->getPath('theme'), 'index.php');
        }

        # The Route resolves to nothing.
        else {
            die('We could not resolve your app. Make sure that you have the correct structure.');
        }
    }

    /**
     * Handles resolving the correct Entry we want.
     * In this order: home.php -> custom template -> Page -> Post -> die()
     * @return void
     */
    private function resolve(): void
    {
        require_once $this->resolvedRoute();
    }
}
