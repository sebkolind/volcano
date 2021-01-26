# Volcano 🌋

An lightweight flat-file blog and website constructor.

## Features

- No database
- Lightweight, extendable & fast
- You write your content in beautiful Markdown
- Great with custom designs

## Requirements

- PHP 8.0.0 or higher
- Support for `mod_rewrite` ([how to set up](https://www.digitalocean.com/community/tutorials/how-to-set-up-mod_rewrite))

## Getting Started

```bash
cd ~/path/to/project

composer require sebastianks/volcano

# IMPORTANT
# Copy a slim starter template bundled with Volcano.
cp -r vendor/sebastianks/volcano/setup/* .
```

Volcano will now serve your app from `/site/theme/index.php` which at it's lightest can be as slim as:

```php
<?php

# Get our App instance
$app = require __DIR__ . '/../../app.php';

# This is where the magic happens 🧙‍♂️
echo $app->render();
```

That is Volcano at it's barebones. A more realistic real-life example can be found [in the Wiki](https://github.com/sebastianks/volcano/wiki/The-Simple-Starter).

## What's next?

You just created your first app with Volcano🎉 Now you can go ahead and make a beautiful theme, start composing some content or something else you fancy. It's all up to you.

A couple of resources if you need some information and/or inspiration:

- Read the [Wiki](https://github.com/sebastianks/volcano/wiki). It's short and will get you started in no time.
- Volcano has a well-documented codebase. Reading it will give you everything there is to know about Volcano.
- Creating a blog? Try out the official [Blog](https://github.com/sebastianks/volcano-blog-template) template and start blogging right away.
- Need help with anything? Feel free to reach out [on Discord](https://discord.gg/pujumPht).
