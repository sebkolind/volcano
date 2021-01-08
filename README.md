# Volcano

An extendable & lightweight flat file blog and website constructor.

## Features

-   No database
-   Lightweight & fast
-   You write your content in beautiful markdown
-   Easy-to-use plugin environment
-   Great with custom designs

## Requirements

A server running PHP.

## Get started

```bash
mkdir ~/path/to/project
cd ~/path/to/project

touch index.php
echo "<?php require 'setup.php' ?>" >> index.php

composer require sebastianks/volcano

# IMPORTANT
# copy initial configuration and site to your project
# if you don't do this you *have* to set these things up manually.
cd ~/path/to/project/
cp vendor/sebastianks/volcano/config/.htaccess .
cp -R vendor/sebastianks/volcano/config/* .
```

## Theming

There are some minimum requirements for your theme to run. That is:

-   The `theme` folder in `/site/` (obviously)
-   Inside `/site/theme` you need the following files:
    -   header.php
    -   footer.php
    -   index.php
-   That's it!

### Templates

A template is a file that let's you create a custom layout for a specific page on your site.

Templates live in a folder in `/site/theme/` called `/templates`. A template file equals the page name.
Page `yoursite.com/about-me` requires a template file called `about-me.php`.

### Partials

In adition to templates you have partials. A partial is a piece of code that you find yourself reusing.

Partials live in a folder in `/site/theme` called `/partials`. Partial names should be a-Z, 0-9 and `.php` files.
To use a partial in your theme you call it by filename without `.php`. Example: `<?php get_partial('partial-name'); ?>`.

### CSS & JS

All `.css` and `.js` files in `/site/theme` and `/site/plugins` are automatically loaded.
The only thing you have to do is use `get_stylesheets()` and `get_scripts()`. Example:

```
<!DOCTYPE html>
<html>
  <head>
    <?php get_stylesheets(); ?>
  </head>
  <body>
    ...

    <?php get_scripts(); ?>
  </body>
</html>
```

Each file will be loaded and added to the DOM individually one after another.

## Plugins

Plugins in Volcano is easy to build and easy to use.

Plugins are basically a function that executes upon calling `plugin('plugin-name')` which could create a Facebook widget, a gallery or something else.

The requirements for a plugin is:

-   A folder inside `/site/plugins/` where your plugin lives. Example: `/site/plugins/google-analytics/`
-   Inside that folder you need at least `index.php`
-   `index.php` requires a function with the same name as the folder _but_ in camelCase. Example: `function googleAnalytics() { ... }`

To call a plugin from your theme files use:

`plugin('google-analytics');`

The `plugin()` function takes two arguments. First is the name. Second argument is passed to the plugin root function, like: `googleAnalytics($id)`. The second argument is used to pass options to your plugin. This could be a single value, like in this case, an id, or an array of options. `$options` is default to `false`, and is not needed if your plugin doesn't need it.

Your plugin can have `.css` and `.js` files, and will automatically be added to the front-end. Read the section "CSS & JS" for more information.
