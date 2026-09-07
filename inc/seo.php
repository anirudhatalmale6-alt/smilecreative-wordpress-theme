<?php
/**
 * Structured data.
 *
 * The old site published Organization + WebPage + WebSite and nothing else --
 * no street, no postcode, no hours, nothing a search engine could read as a
 * PLACE. It also duplicated its own name: "Smile Creative Smile Creative -
 * Web Design Belfast".
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Emit ProfessionalService on the front page.
 */
function sc_schema() {
	if ( ! is_front_page() ) {
		return;
	}

	$host = wp_parse_url( home_url(), PHP_URL_HOST );

	$schema = array(
		'@context'  => 'https://schema.org',
		'@type'     => 'ProfessionalService',
		'@id'       => home_url( '/#localbusiness' ),
		'name'      => wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ),
		'url'       => home_url( '/' ),
		'telephone' => sc_opt( 'phone_raw' ),
		'address'   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => sc_opt( 'street' ),
			'addressLocality' => sc_opt( 'town' ),
			'postalCode'      => sc_opt( 'postcode' ),
			'addressCountry'  => 'GB',
		),
		'openingHoursSpecification' => array(
			array(
				'@type'     => 'OpeningHoursSpecification',
				'dayOfWeek' => array( 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday' ),
				'opens'     => '09:00',
				'closes'    => '17:00',
			),
		),
		'areaServed' => array( 'Northern Ireland', 'United Kingdom', 'Ireland' ),
		'knowsAbout' => array(
			'Website design', 'WordPress', 'Logo design',
			'Print marketing', 'Search marketing', 'Video production',
		),
	);

	if ( sc_opt( 'founded' ) ) {
		$schema['foundingDate'] = sc_opt( 'founded' );
	}

	/*
	 * NO "email" key, on purpose.
	 *
	 * Structured data is plain text in the served source, so an address here
	 * would undo the obfuscation in the contact block -- it was the last
	 * readable address left on the Get It Framed build for exactly this reason.
	 * Google does not need it; the phone number and the form are enough.
	 *
	 * NO aggregateRating or Review nodes either. Google requires review markup
	 * to come from reviews you collected yourself; the ones on this page are
	 * copied from a Google profile, and marking them up is a policy breach that
	 * risks the whole rich result. Show them, do not mark them up.
	 */

	printf(
		'<script type="application/ld+json">%s</script>' . "\n",
		wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
	);
}
add_action( 'wp_head', 'sc_schema', 5 );

/**
 * Every page gets exactly one H1.
 *
 * Twenty-one of the old site's twenty-six pages had none at all, which is not
 * something a restyle fixes -- it is decided by the template. Kept as a filter
 * so the rule is visible rather than assumed.
 */
function sc_the_h1( $text ) {
	return $text;
}
