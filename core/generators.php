<?php

/**
 * @param array $items
 * @param string $item_sep - the separator of each item. If empty, no separator.
 * @param string $item_tag - the HTML tag of each item
 * @param string $tag - the HTML tag for the wrapper
 */
function generate_nav($items, $item_sep = '', $item_tag = '', $tag = 'nav') {
	$total_items = count($items);
	$i = 1;

	echo '<' . $tag . '>';
	foreach ($items as $title => $link) {
		if ($item_tag) {
			echo '<' . $item_tag . '>';
		}

		echo '<a href="' . $link . '">' . $title . '</a>';

		if ($item_sep && $i < $total_items) {
			echo $item_sep;
		}

		if ($item_tag) {
			echo '</' . $item_tag . '>';
		}

		$i++;
	}
	echo '</' . $tag . '>';
}

?>
