<?php

/**
 * @desc - If no url params - return default.md
 * 	else try to show page by first param in url.
 * 	Can only *try* because parse_markdown() checks if file exists.
 * 	If it does not exist return default.md
 *
 * @return parsed markdown as html
 */
function content() {
    if (!url_params()) {
        parse_markdown();
    } else {
        parse_markdown(url_params()[0]);
    }
}
