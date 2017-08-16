# Volcano
A lightweight flatfile CMS written in PHP.
Volcano was built to help you built fast and custom websites without headaches.

## Features
Volcano is lightweight but the features included makes it powerful enough to run small to medium websites.
Even though Volcano ships with some powerful core features, you're not limited to those because of the seamlessly plugin environment.

- No database
- Lightweight & fast
- You write content in beautiful markdown
- Seamlessly plugin environment
- Great with custom designs
- Built-in caching & minifying of JS and CSS

## Requirements
A server running PHP.

## Get started
Make a folder to run your site from. Example:

`mkdir ~path/to/site/`

`git clone https://github.com/sebastianks/volcano.git ~path/to/site/`

## Theming
You always start off with a very basic theme that let's you know Volcano is running. 
You can use this as a foundation for your theme, or you can delete everything and build your own.

There are some minimum requirements for your theme to run. That is:

- The `theme` folder in `/site/` (obviously)
- Inside `/site/theme` you need the following files:
	- header.php
	- footer.php
	- index.php
- That's it!

### Templates
A template is a `.php` file that let's you create a custom layout for a specific page on your site.

Templates live in a folder in `/site/theme/` called `/templates`. A template file equals the page name. 
Page `yoursite.com/about-me` requires a template file called `about-me.php`.

### Partials
In adition to templates you have partials. A partial is a piece of code that you find yourself reusing.

Partials live in a folder in `/site/theme` called `/partials`. Partial names should be a-Z, 0-9 and `.php` files.
To use a partial in your theme you call it by filename without `.php`. Example: `<?php get_partial('partial-name'); ?>`.

### CSS & JS
All `.css` and `.js` files in `/site/theme` and `/site/plugins/*` are automatically minified and cached.
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

_Both functions will only get minified if set to true in `/setup.php`, else they'll return multiple files._
