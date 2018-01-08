<?php

/**
 * Simple Colorbox settings
 *
 * @param $colorbox_settings
 *
 * @return mixed
 */
function wpmtst_colorbox_settings( $colorbox_settings ) {
	$colorbox_settings['rel'] = 'nofollow';
	$colorbox_settings['returnFocus'] = 0;

	return $colorbox_settings;
}

/**
 * Check if Simple Colorbox is active.
 */
function wpmtst_colorbox_filter() {
	if ( defined( 'SIMPLECOLORBOX_VERSION' ) ) {
		add_filter( 'simple_colorbox_settings', 'wpmtst_colorbox_settings' );
	}
}

add_action( 'init', 'wpmtst_colorbox_filter' );

/**
 * Add class for FooBox lightbox.
 *
 * @param $class_array
 *
 * @since 2.29.0
 *
 * @return array
 */
function wpmtst_foobox_class( $class_array ) {
	if ( defined( 'FOOBOX_BASE_VERSION' ) ) {
		$class_array[] = 'foobox';
	}

	return $class_array;
}

add_filter( 'wpmtst_thumbnail_link_class', 'wpmtst_foobox_class' );
