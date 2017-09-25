<?php
/**
 * @desc - Add Facebook page plugin
 * @param array $options
 */

function fbPagePlugin($options = false) {
	$defaults = array(
		'href' => 'https://www.facebook.com/facebook',
		'width' => '340',
		'height' => '500',
		'tabs' => 'timeline',
		'hide_cover' => 'false',
		'show_facepile' => 'true',
		'hide_cta' => 'false',
		'small_header' => 'false',
		'adapt_container_width' => 'true'
	);

	/**
	 * Merge $options with $defaults
	 * allows user to actually set attributes.
	 * If no $options given, use defaults
	 */
	$options = $options 
		? array_merge($defaults, $options) 
		: $defaults;

	?>
		<div
			class="fb-page"
			data-href="<?php echo $options['href']; ?>"
			data-width="<?php echo $options['width']; ?>"
			data-height="<?php echo $options['height']; ?>"
			data-hide-cover="<?php echo $options['hide_cover']; ?>"
			data-show-facepile="<?php echo $options['show_facepile']; ?>"
			data-hide-cta="<?php echo $options['hide_cta']; ?>"
			data-small-header="<?php echo $options['small_header']; ?>"
			data-adapt-container-width="<?php echo $options['adapt_container_width']; ?>">
		</div>
	<?
}
