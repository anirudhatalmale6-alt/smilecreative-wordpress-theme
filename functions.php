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

define( 'SC_VERSION', '1.6.1' );
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
 * Is Elementor currently drawing its own editor?
 *
 * The editor loads the page into an iframe as an ordinary front-end request --
 * is_admin() is FALSE there -- so the shedding below stripped the very scripts
 * the editing canvas is built from, and Elementor reported "the preview could
 * not be loaded". My regression, introduced with the shedding on 7 Sep.
 *
 * Keyed on the preview parameters rather than on the user being logged in: an
 * administrator reading the site normally should still get the fast page.
 */
function sc_is_builder_editing() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['elementor-preview'] ) || isset( $_GET['preview_id'] ) || isset( $_GET['elementor_library'] ) ) {
		return true;
	}
	if ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) {
		return true;
	}
	// phpcs:enable
	if ( class_exists( '\Elementor\Plugin' )
		&& isset( \Elementor\Plugin::$instance->preview )
		&& method_exists( \Elementor\Plugin::$instance->preview, 'is_preview_mode' )
		&& \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
		return true;
	}
	return false;
}

/**
 * Is the current view drawn entirely by this theme's own templates?
 *
 * Used to decide when the page builder's assets are dead weight. The homepage
 * is the case that matters: WordPress still treats it as page #799, which was
 * built in Elementor, so Elementor still enqueues that page's generated CSS and
 * still adds its body classes -- even though front-page.php ignores the stored
 * content completely and draws its own markup.
 */
function sc_theme_owns_view() {
	return is_front_page() || is_404()
		|| is_page_template( 'page-redesign.php' )
		|| is_page_template( 'page-support.php' );
}

/**
 * Shed the page builder's front-end assets on the pages this theme draws.
 *
 * Not a tidy-up. Elementor's per-page stylesheet for the old homepage contains
 *
 *     body.elementor-page-799{background-color:#FFFFFF}
 *
 * which is one class more specific than `body{background:var(--ink)}`, so it
 * won on the live site: a white page with cream text on it, which is what
 * Brendan photographed. Stylesheets for a layout that is no longer rendered can
 * only ever do this kind of harm, so they come off.
 *
 * Matched on the registered FILE PATH, not on the handle. Handle prefixes were
 * the obvious way to do this and they measured wrong: Elementor's per-widget
 * styles register as `widget-heading`, `widget-image`, `e-swiper` and so on,
 * which share no prefix with anything, and a list of them would go stale with
 * every release. The path is the one thing that cannot lie about which plugin
 * a file came from.
 *
 * It also runs twice -- once after the normal enqueue pass, and again
 * immediately before the tags are printed -- because Elementor adds several of
 * these while the head is already being rendered, which is after
 * wp_enqueue_scripts has finished. Anything NOT in this list (the cookie
 * banner, analytics, the Kira widget) is left alone deliberately.
 */
function sc_shed_builder_assets() {
	if ( is_admin() || ! sc_theme_owns_view() || sc_is_builder_editing() ) {
		return;
	}

	$paths = array(
		'/plugins/elementor/',
		'/plugins/elementor-pro/',
		'/plugins/pro-elements/',
		'/plugins/elementskit-lite/',
		'/plugins/essential-addons-for-elementor-lite/',
		'/plugins/royal-elementor-addons/',
		'/plugins/header-footer-elementor/',
		'/plugins/premium-addons-for-elementor/',
		'/uploads/elementor/',   // generated per-page CSS and the Google fonts it copied
		'/plugins/formidable/',  // form plugins: this theme's enquiry form is its own
		'/plugins/ninja-forms/',
	);

	foreach ( array( wp_styles(), wp_scripts() ) as $reg ) {
		foreach ( (array) $reg->queue as $handle ) {
			$src = isset( $reg->registered[ $handle ] ) ? (string) $reg->registered[ $handle ]->src : '';
			if ( '' === $src ) {
				continue;
			}
			foreach ( $paths as $needle ) {
				if ( false !== strpos( $src, $needle ) ) {
					$reg->dequeue( $handle );
					break;
				}
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'sc_shed_builder_assets', 999 );
add_action( 'wp_print_styles', 'sc_shed_builder_assets', 0 );
add_action( 'wp_print_footer_scripts', 'sc_shed_builder_assets', 0 );

/**
 * Swallow shortcodes whose plugin is no longer switched on.
 *
 * WordPress prints an unregistered shortcode as literal text, so the moment the
 * booking plugin was deactivated the raw string
 * `[koalendar link="https://koalendar.com/e/..."]` appeared at the foot of the
 * page for every visitor. A page should never show its own plumbing, so any of
 * these that survives in old content now renders as nothing at all.
 */
function sc_neutralise_orphan_shortcodes() {
	foreach ( array( 'koalendar' ) as $tag ) {
		if ( ! shortcode_exists( $tag ) ) {
			add_shortcode( $tag, '__return_empty_string' );
		}
	}
}
add_action( 'init', 'sc_neutralise_orphan_shortcodes', 99 );

/**
 * Body classes used by the stylesheet.
 *
 * Also strips the builder's own body classes on the pages this theme draws, so
 * that a stylesheet arriving from somewhere I have not thought of -- a cached
 * copy, a plugin that inlines its critical CSS -- still has nothing to hook on
 * to. The dequeue above removes the stylesheet; this removes the target.
 */
function sc_body_class( $classes ) {
	if ( is_front_page() ) {
		$classes[] = 'is-home';
	}

	if ( sc_theme_owns_view() ) {
		$classes = array_values(
			array_filter(
				$classes,
				function ( $c ) {
					return 0 !== strpos( $c, 'elementor-' ) && 0 !== strpos( $c, 'ehf-' );
				}
			)
		);
	}

	return $classes;
}
add_filter( 'body_class', 'sc_body_class', 999 ); // 999: Elementor adds its own classes at the default priority, so a filter at 10 is overwritten a moment later.
