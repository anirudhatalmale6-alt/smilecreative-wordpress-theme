<?php
/**
 * Shared helpers: theme options, the email guard, and the places data.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme option with a sensible default.
 *
 * Everything the client might reasonably want to change lives here rather than
 * being typed into a template, so he never needs a developer to correct a
 * phone number.
 */
function sc_opt( $key ) {
	$defaults = array(
		'phone'        => '+44 (28) 9099 7004',
		'phone_raw'    => '+442890997004',
		'street'       => 'Moat House, 54 Bloomfield Avenue',
		'town'         => 'Belfast',
		'postcode'     => 'BT5 5AD',
		'hours'        => 'Monday to Friday, 9am – 5pm',   // real en dash: this value is printed through esc_html(), so an entity would render literally
		'email'        => '',            // studio@ -- set in the Customizer when created.
		'enquiry_to'   => '',            // Where the form delivers. Never published.
		'enquiry_from' => '',            // Envelope sender, must be on this domain.
		'founded'      => '2008',
		'first_site'   => '2001',
		'hero_network' => true,
	);

	$val = get_theme_mod( 'sc_' . $key, null );

	if ( null === $val || '' === $val ) {
		return isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
	}

	return $val;
}

/**
 * Print an email address that a harvester cannot read in the page source.
 *
 * Same approach as the Get It Framed build, and for the same reason he gave me
 * himself: he has been wary of publishing live addresses for years, and the old
 * site published brendan@ and office@ as raw mailto links with no protection.
 *
 * Nothing readable is served. The address is base64 in a data attribute and
 * assembled by JS at runtime. With JavaScript off the link falls back to the
 * enquiry form rather than going dead.
 *
 * This is not unbreakable -- anything that runs JS can still read it. It stops
 * the bulk harvesters, which is the whole of what obfuscation ever does, and it
 * is worth being straight about that rather than calling it protection.
 */
function sc_email_html( $text = '', $attrs = array() ) {
	$address = (string) sc_opt( 'email' );

	if ( '' === $address || ! is_email( $address ) ) {
		return '';
	}

	$fallback = apply_filters( 'sc_email_fallback_url', '#enquiry-form' );
	$label    = '' !== $text ? $text : __( 'Email the studio', 'smilecreative' );

	$attr = '';
	foreach ( $attrs as $k => $v ) {
		$attr .= sprintf( ' %s="%s"', esc_attr( $k ), esc_attr( $v ) );
	}

	return sprintf(
		'<a class="smile-eml" href="%1$s" data-eml="%2$s"%3$s>%4$s</a>',
		esc_url( $fallback ),
		esc_attr( base64_encode( $address ) ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$attr,
		esc_html( $label )
	);
}

/**
 * The full address as one line, for schema and the footer.
 */
function sc_address_line() {
	return trim( sprintf( '%s, %s %s', sc_opt( 'street' ), sc_opt( 'town' ), sc_opt( 'postcode' ) ), ', ' );
}

/**
 * Places for the hero canvas.
 *
 * Every point is a real client location. Laid out as a stylised map rather than
 * a true projection -- Port Louis is 10,000km away and a real projection would
 * squash the UK into a thumbnail.
 *
 * Filterable so the list can grow without touching the template.
 */
function sc_places() {
	return apply_filters(
		'sc_places',
		array(
			array(
				'x'    => 0.42,
				'y'    => 0.30,
				'n'    => __( 'Belfast', 'smilecreative' ),
				'w'    => __( 'The studio', 'smilecreative' ),
				'home' => true,
				'r'    => 5.4,
			),
			array(
				'x' => 0.37,
				'y' => 0.20,
				'n' => __( 'Ballymena', 'smilecreative' ),
				'w' => __( 'Logan&rsquo;s Removals, Inner Beauty, Ahoghill Playgroup', 'smilecreative' ),
				'r' => 4.4,
			),
			array(
				'x' => 0.30,
				'y' => 0.13,
				'n' => __( 'Glasgow', 'smilecreative' ),
				'w' => __( 'Client work across Scotland', 'smilecreative' ),
				'r' => 3.4,
			),
			array(
				'x' => 0.36,
				'y' => 0.44,
				'n' => __( 'Liverpool', 'smilecreative' ),
				'w' => __( 'Client work in the north west', 'smilecreative' ),
				'r' => 3.4,
			),
			array(
				'x' => 0.52,
				'y' => 0.58,
				'n' => __( 'London', 'smilecreative' ),
				'w' => __( 'Client work in the capital', 'smilecreative' ),
				'r' => 3.4,
			),
			array(
				'x' => 0.58,
				'y' => 0.52,
				'n' => __( 'Essex', 'smilecreative' ),
				'w' => __( 'Lead-generation websites', 'smilecreative' ),
				'r' => 3.4,
			),
			array(
				'x' => 0.62,
				'y' => 0.62,
				'n' => __( 'Colchester', 'smilecreative' ),
				'w' => __( 'Client work', 'smilecreative' ),
				'r' => 3.2,
			),
			array(
				'x' => 0.72,
				'y' => 0.40,
				'n' => __( 'Antibes', 'smilecreative' ),
				'w' => __( 'Riviera Massage &mdash; France', 'smilecreative' ),
				'r' => 4.0,
			),
			array(
				'x' => 0.86,
				'y' => 0.76,
				'n' => __( 'Port Louis', 'smilecreative' ),
				'w' => __( 'Moollan &amp; Moollan &mdash; Mauritius', 'smilecreative' ),
				'r' => 4.6,
			),
		)
	);
}

/**
 * Escaped, spaced-out phone link.
 */
function sc_tel_link( $class = '' ) {
	return sprintf(
		'<a class="%1$s" href="tel:%2$s">%3$s</a>',
		esc_attr( $class ),
		esc_attr( sc_opt( 'phone_raw' ) ),
		esc_html( sc_opt( 'phone' ) )
	);
}
