<?php
/**
 * Header for the landing page.
 *
 * Logo and phone only. A visitor who arrived from an advert has one decision
 * to make, and every nav link is a way of not making it.
 *
 * @package smilecreative
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'is-lander' ); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'smilecreative' ); ?></a>

<header>
	<div class="wrap hbar">
		<a class="brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php
			$logo = get_theme_mod( 'custom_logo' );
			if ( $logo ) {
				echo wp_get_attachment_image( $logo, 'sc-logo', false, array( 'alt' => esc_attr( get_bloginfo( 'name' ) ) ) );
			} else {
				printf(
					'<img src="%s" alt="%s" width="320" height="120">',
					esc_url( SC_URI . '/assets/img/logo.png' ),
					esc_attr( get_bloginfo( 'name' ) )
				);
			}
			?>
		</a>
		<?php echo wp_kses_post( sc_tel_link( 'htel' ) ); ?>
	</div>
</header>

<main id="main">
