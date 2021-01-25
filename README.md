# Volcano 🌋

An extendable & lightweight flat-file blog and website constructor.

## Features

- No database
- Lightweight, extendable & fast
- You write your content in beautiful Markdown
- Great with custom designs

## Requirements

A server running PHP 7 or higher.

_Might run on older PHP versions but those are not tested._

## Getting Started

```bash
mkdir ~/path/to/project
cd ~/path/to/project

composer require sebastianks/volcano

# IMPORTANT
# copy initial configuration and site to your project
# if you don't do this you *have* to set these things up manually.
# NOTE: The copy method might vary from OS
cd ~/path/to/project/
cp -r vendor/sebastianks/volcano/setup/* .
```

### You are almost there!

Volcano will now serve your app from `/site/theme/index.php` which at it's lightest can be as slim as:

```php
<?php
use Volcano\Volcano;

# Spin up Volcano by creating an instance of the Volcano class.
$app = new Volcano();

# `Render` takes care of rendering the Page, Post or Template matching the current route. This is essential for Volcano to do it's work.
echo $app->render();
```

This is really just the bare minimum to get up and running with Volcano. A real world example might require a bit more work:

```php
<?php
use Volcano\Volcano;
$app = new Volcano();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>My site &mdash; <?php echo $app->getMeta('title');?></title>
  <meta name="description" content="<?php echo $app->getMeta('description');?>">
</head>
<body>
  <?php echo $app->render(); ?>
</body>
</html>
```

Now, to make life a bit easier we could move the instantiation of Volcano (the `$app = new Volcano()` part) in it's own file which we could then call upon when needed:

```bash
# File structure of your app
my-site
  - site
    - pages
    - posts
      my-first-post.md
    - templates
    - theme
      index.php
    app.php
  index.php
```

Where `app.php` would contain:

```php
<?php
use Volcano\Volcano;
return new Volcano();
```

This allows us in a theme file to do something like this:

```php
# site/theme/index.php
<?php
# Get the instance of Volcano we created in previous step.
$app = require __DIR__ . '/../app.php';
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    # Get the "meta data" title
    <title>My site &mdash; <?php echo $app->getMeta('title');?></title>
    # and description
    <meta name="description" content="<?php echo $app->getMeta('description');?>">
</head>
<body>
  # $app->render() will take care of resolving your app and show your content from either a Page, Post or Template.
  <?php echo $app->render(); ?>
</body>
</html>
```

## What's next?

You just created your first app with Volcano🎉 Now you can go ahead and make a beautiful theme, start composing some content or something else you fancy. It's all up to you.

A couple of resources if you need some information and/or inspiration:

- Read the [Wiki](https://github.com/sebastianks/volcano/wiki). It's short and will get you started in no time.
- Volcano has a well-documented codebase. Reading it will give you everything there is to know about Volcano.
- Creating a blog? Try out the official [Blog](https://github.com/sebastianks/volcano-blog-template) template and start blogging right away.
- Need help with anything? Feel free to reach out [on Discord](https://discord.gg/pujumPht).
