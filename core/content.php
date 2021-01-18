<?php

use V\Url;

/**
 * Will take care of rendering a page or post based on the URL.
 * More information is found in parse_markdown() in markdown.php.
 *
 * @return string parsed markdown as html
 */
function content()
{
    if (!Url::getParameters()) {
        parse_markdown();
    } else {
        parse_markdown(Url::getParameters());
    }
}
