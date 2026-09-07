<?php
/**
 * Redirects for the pages the old site retired.
 *
 * /about/, /team/ and /contact/ were indexed and had inbound links, so leaving
 * them as 404s sends anyone arriving from a search result to a dead end. A 301
 * keeps that person, and tells Google where the content went instead of just
 * that it is gone.
 *
 * Kept in the theme rather than in a redirect plugin: it is three rules, and a
 * plugin for three rules is how the old site ended up with 29 of them.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map of retired paths to where they should now land.
 */
function sc_redirect_map() {
	return apply_filters(
		'sc_redirect_map',
		array(
			'/about/'             => '/#who',
			'/team/'              => '/#who',
			'/contact/'           => '/#contact',
			'/social-media/'      => '/#services',
			'/portfolio/'         => '/#work',
			'/portfolio-archive/' => '/#work',
			'/post-list/'         => '/',
			'/booking/'           => '/#contact',
		)
	);
}

/**
 * Send the 301.
 *
 * Only fires on a 404, so it can never shadow a real page that still exists --
 * if he later republishes /about/ this quietly stops interfering rather than
 * redirecting the live page away from itself.
 */
function sc_do_redirects() {
	if ( ! is_404() ) {
		return;
	}

	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path = strtok( $path, '?' );
	$path = trailingslashit( '/' . trim( $path, '/' ) );

	$map = sc_redirect_map();

	if ( isset( $map[ $path ] ) ) {
		wp_safe_redirect( home_url( $map[ $path ] ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'sc_do_redirects', 1 );
