<?php
/**
 * Header.
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
<body <?php body_class(); ?>>
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

		<nav aria-label="<?php esc_attr_e( 'Primary', 'smilecreative' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						/*
						 * Keep the <ul>. Stripping it with '%3$s' left bare
						 * <li> elements sitting directly inside <nav>, which
						 * browsers still draw as list items -- the live header
						 * showed a bullet before every link, and on the
						 * Elementor pages the list stacked vertically over the
						 * logo. The wrapper is styled in style.css instead.
						 */
						'items_wrap'     => '<ul class="nav-list">%3$s</ul>',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				/*
				 * Short labels on purpose. Six long ones plus the phone and the
				 * button wrapped the header onto two lines at 1440 -- adding a
				 * section re-tests the chrome.
				 */
				$fallback = array(
					'#work'     => __( 'Work', 'smilecreative' ),
					'#redesign' => __( 'Free redesign', 'smilecreative' ),
					'#reviews'  => __( 'Reviews', 'smilecreative' ),
					'#services' => __( 'Services', 'smilecreative' ),
					'#care'     => __( 'Aftercare', 'smilecreative' ),
					'#who'      => __( 'About', 'smilecreative' ),
					'#contact'  => __( 'Contact', 'smilecreative' ),
				);
				foreach ( $fallback as $href => $label ) {
					printf( '<a href="%s">%s</a>', esc_url( home_url( '/' ) . $href ), esc_html( $label ) );
				}
			}
			?>
		</nav>

		<?php echo wp_kses_post( sc_tel_link( 'htel' ) ); ?>
		<a class="btn" href="<?php echo esc_url( home_url( '/#redesign' ) ); ?>"><?php esc_html_e( 'Free redesign', 'smilecreative' ); ?></a>
	</div>
</header>

<main id="main">
