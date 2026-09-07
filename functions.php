<?php
/**
 * Smile Creative -- theme bootstrap.
 *
 * Deliberately dependency-free. No page builder, no addon plugins, no licence
 * key. The design lives in style.css rather than in generated per-page CSS
 * files, which is what removes the fault that took the old site down twice in
 * August: Elementor deleted its generated stylesheets after an update while
 * LiteSpeed kept serving HTML that asked for them, and a cache HIT never runs
 * PHP so they could never rebuild.
 *
 * Written to PHP 7.4 so it runs wherever the site ends up.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SC_VERSION', '1.3.0' );
define( 'SC_DIR', get_template_directory() );
define( 'SC_URI', get_template_directory_uri() );

require_once SC_DIR . '/inc/helpers.php';
require_once SC_DIR . '/inc/post-types.php';
require_once SC_DIR . '/inc/customizer.php';
require_once SC_DIR . '/inc/enquiry.php';
require_once SC_DIR . '/inc/enquiry-admin.php';
require_once SC_DIR . '/inc/seo.php';
require_once SC_DIR . '/inc/redirects.php';

/**
 * Theme supports.
 */
function sc_setup() {
	load_theme_textdomain( 'smilecreative', SC_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support(
		'html5',
		array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
	);

	// Work cards are a fixed landscape crop; the feature panel is full-bleed.
	add_image_size( 'sc-card', 1120, 760, true );
	add_image_size( 'sc-feature', 2000, 1100, true );
	add_image_size( 'sc-logo', 400, 200, false );

	register_nav_menus(
		array(
			'primary' => __( 'Primary navigation', 'smilecreative' ),
			'footer'  => __( 'Footer links', 'smilecreative' ),
		)
	);
}
add_action( 'after_setup_theme', 'sc_setup' );

/**
 * Front-end assets.
 *
 * One stylesheet and one small script. The old site loaded 47 stylesheets and
 * 25 scripts on the homepage for 4.7 MB; that number is the whole reason this
 * theme exists, so it is worth keeping this function boring.
 */
function sc_assets() {
	wp_enqueue_style( 'sc-fonts', SC_URI . '/assets/fonts/fonts.css', array(), SC_VERSION );
	wp_enqueue_style( 'sc-main', get_stylesheet_uri(), array( 'sc-fonts' ), SC_VERSION );

	wp_enqueue_script( 'sc-main', SC_URI . '/assets/js/site.js', array(), SC_VERSION, true );

	// The canvas hero is only loaded on the page that actually draws one.
	if ( is_front_page() && sc_opt( 'hero_network' ) ) {
		wp_enqueue_script( 'sc-network', SC_URI . '/assets/js/network.js', array(), SC_VERSION, true );

		/*
		 * wp_add_inline_script rather than wp_localize_script: localize casts
		 * every value to a STRING, which would turn the coordinates and radii
		 * into "0.42" and "5.4". They still work by coercion, right up until
		 * one of them does not. Send real JSON instead.
		 */
		wp_add_inline_script(
			'sc-network',
			'window.SC_PLACES=' . wp_json_encode( array( 'places' => sc_places() ) ) . ';',
			'before'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'sc_assets' );

/**
 * Preload the two fonts that render above the fold.
 *
 * Without this the headline reflows once the woff2 arrives, which is the most
 * visible part of a slow first paint.
 */
function sc_preload_fonts() {
	$faces = array( 'fraunces-latin-600.woff2', 'archivo-latin-400.woff2' );
	foreach ( $faces as $face ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( SC_URI . '/assets/fonts/' . $face )
		);
	}
}
add_action( 'wp_head', 'sc_preload_fonts', 1 );

/**
 * Strip the things WordPress prints that this site does not use.
 *
 * Each of these is a request, a header or a disclosure that costs something
 * and buys nothing here.
 */
function sc_trim_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'sc_trim_head' );

/**
 * Drop the block-library CSS on the front end.
 *
 * Nothing here is a block-editor layout, so it is ~90 KB of stylesheet for
 * rules nothing matches. Guarded so it never fires in the editor.
 */
function sc_drop_block_css() {
	if ( is_admin() ) {
		return;
	}
	wp_dequeue_style( 'wp-block-library' );
	wp_dequeue_style( 'wp-block-library-theme' );
	wp_dequeue_style( 'global-styles' );
	wp_dequeue_style( 'classic-theme-styles' );
}
add_action( 'wp_enqueue_scripts', 'sc_drop_block_css', 100 );

/**
 * Editor styles, so what he types in the admin resembles what ships.
 */
function sc_editor_assets() {
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor.css' );
}
add_action( 'after_setup_theme', 'sc_editor_assets' );

/**
 * Body classes used by the stylesheet.
 */
function sc_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-home';
	}
	return $classes;
}
add_filter( 'body_class', 'sc_body_class' );
