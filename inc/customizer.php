<?php
/**
 * Customizer settings.
 *
 * Everything a non-developer might reasonably need to change. The phone number
 * and the enquiry address in particular must never require a developer -- the
 * last outage lasted weeks partly because nobody could see or change where the
 * forms were delivering.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the panel.
 */
function sc_customizer( $wp_customize ) {
	$wp_customize->add_section(
		'sc_details',
		array(
			'title'       => __( 'Smile Creative -- details', 'smilecreative' ),
			'priority'    => 20,
			'description' => __( 'Contact details, address and where enquiries are delivered.', 'smilecreative' ),
		)
	);

	$fields = array(
		'phone'        => array( __( 'Phone, as displayed', 'smilecreative' ), 'text', '' ),
		'phone_raw'    => array( __( 'Phone, for the tel: link', 'smilecreative' ), 'text', __( 'No spaces, with country code.', 'smilecreative' ) ),
		'street'       => array( __( 'Street address', 'smilecreative' ), 'text', '' ),
		'town'         => array( __( 'Town', 'smilecreative' ), 'text', '' ),
		'postcode'     => array( __( 'Postcode', 'smilecreative' ), 'text', '' ),
		'email'        => array( __( 'Published email address', 'smilecreative' ), 'email', __( 'The one shown on the site (studio@). Never appears readable in the page source -- it is assembled by JavaScript so bulk harvesters cannot read it.', 'smilecreative' ) ),
		'enquiry_to'   => array( __( 'Enquiries are delivered to', 'smilecreative' ), 'email', __( 'Never published anywhere. This is the only place it appears, so it cannot be harvested and does not need rotating.', 'smilecreative' ) ),
		'enquiry_from' => array( __( 'Enquiries are sent FROM', 'smilecreative' ), 'email', __( 'Must be an address on this domain. If this is set to the visitor\'s address instead, the mail fails authentication at the far end and is discarded with no bounce -- that is the most common cause of forms that silently stop delivering.', 'smilecreative' ) ),
		'founded'      => array( __( 'Trading since', 'smilecreative' ), 'text', '' ),
		'first_site'   => array( __( 'First website built', 'smilecreative' ), 'text', '' ),
		'trading'      => array( __( 'Trading status, for the footer', 'smilecreative' ), 'text', __( 'Printed small at the foot of every page, or left off entirely if this is blank. Example: "Smile Creative is a trading name of ... , registered in Northern Ireland, NI000000".', 'smilecreative' ) ),
	);

	foreach ( $fields as $key => $f ) {
		$wp_customize->add_setting(
			'sc_' . $key,
			array(
				'default'           => sc_opt( $key ),
				'sanitize_callback' => 'email' === $f[1] ? 'sanitize_email' : 'sanitize_text_field',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			'sc_' . $key,
			array(
				'label'       => $f[0],
				'description' => $f[2],
				'section'     => 'sc_details',
				'type'        => 'email' === $f[1] ? 'email' : 'text',
			)
		);
	}

	$wp_customize->add_setting(
		'sc_hero_network',
		array(
			'default'           => true,
			'sanitize_callback' => 'wp_validate_boolean',
			'transport'         => 'refresh',
		)
	);
	$wp_customize->add_control(
		'sc_hero_network',
		array(
			'label'       => __( 'Animated map in the hero', 'smilecreative' ),
			'description' => __( 'The cursor-reactive map of where the work is. Turn it off for a plain typographic hero -- the script is then not loaded at all.', 'smilecreative' ),
			'section'     => 'sc_details',
			'type'        => 'checkbox',
		)
	);
}
add_action( 'customize_register', 'sc_customizer' );
