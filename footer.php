<?php
/**
 * Footer.
 *
 * The first version of this was a single copyright line. That was too thin for
 * a business site -- the footer is where people go for the phone number, the
 * address and the opening hours once they have decided to get in touch, and
 * making them scroll back up for it is a small tax on the one action the whole
 * page is asking for.
 *
 * What it deliberately does NOT do, unlike the one it replaces:
 *   - no stock photograph behind it
 *   - no readable email address (the guard assembles it at runtime)
 *   - no links to pages that no longer exist
 *   - the service list is the same array the Services section uses, so the two
 *     can never disagree
 *
 * @package smilecreative
 */

$sc_footer_services = array(
	'/website-design/'   => __( 'Website design & build', 'smilecreative' ),
	'/logo-design/'      => __( 'Logo & brand identity', 'smilecreative' ),
	'/print-marketing/'  => __( 'Print', 'smilecreative' ),
	'/search-marketing/' => __( 'Search marketing', 'smilecreative' ),
	'/video-production/' => __( 'Video production', 'smilecreative' ),
);
?>
</main>

<footer>
	<div class="wrap fgrid">

		<div class="fcol fcol-brand">
			<img class="flogo" src="<?php echo esc_url( SC_URI . '/assets/img/logo.png' ); ?>"
			     alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="320" height="104" loading="lazy">
			<p class="muted">
				<?php esc_html_e( 'Websites and print, built properly. A Belfast studio working for businesses across the UK and Ireland, and a few a good deal further.', 'smilecreative' ); ?>
			</p>
			<?php
			/*
			 * Samson and Goliath. Brendan's own illustration, sitting at 78% so it
			 * reads as a nod rather than competing with the gold section labels --
			 * his drawing is #f1b927, hue 43, against the site's #ffdb00 at hue 51,
			 * and at full strength the two yellows argue with each other.
			 *
			 * aria-hidden and empty alt on purpose: it says nothing a screen reader
			 * needs, and "Harland and Wolff cranes" read aloud in the footer of a
			 * web studio is noise.
			 */
			?>
			<img class="fcranes" src="<?php echo esc_url( SC_URI . '/assets/img/belfast-cranes.png' ); ?>"
			     alt="" aria-hidden="true" width="520" height="346" loading="lazy" decoding="async">
		</div>

		<div class="fcol">
			<span class="label"><?php esc_html_e( 'What we do', 'smilecreative' ); ?></span>
			<ul>
				<?php foreach ( $sc_footer_services as $sc_url => $sc_name ) : ?>
					<li><a href="<?php echo esc_url( home_url( $sc_url ) ); ?>"><?php echo esc_html( $sc_name ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="fcol">
			<span class="label"><?php esc_html_e( 'This site', 'smilecreative' ); ?></span>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/#work' ) ); ?>"><?php esc_html_e( 'Work', 'smilecreative' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#redesign' ) ); ?>"><?php esc_html_e( 'Free homepage redesign', 'smilecreative' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#reviews' ) ); ?>"><?php esc_html_e( 'Reviews', 'smilecreative' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/support/' ) ); ?>"><?php esc_html_e( 'Aftercare & hosting', 'smilecreative' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Contact', 'smilecreative' ); ?></a></li>
			</ul>
		</div>

		<div class="fcol">
			<span class="label"><?php esc_html_e( 'The studio', 'smilecreative' ); ?></span>
			<p><?php echo esc_html( sc_opt( 'street' ) ); ?><br>
			   <?php echo esc_html( trim( sc_opt( 'town' ) . ' ' . sc_opt( 'postcode' ) ) ); ?></p>
			<p class="fcontact"><?php echo wp_kses_post( sc_tel_link() ); ?></p>
			<?php
			/*
			 * Renders only once the published address exists, and never as
			 * readable text in the source -- the old footer printed
			 * office@smilecreative.agency in plain sight, which is the exact
			 * thing he has been trying to avoid for years.
			 */
			$sc_eml = sc_email_html();
			if ( $sc_eml ) :
				?>
				<p class="fcontact"><?php echo wp_kses_post( $sc_eml ); ?></p>
			<?php endif; ?>
			<p class="muted fhours"><?php echo esc_html( wp_strip_all_tags( sc_opt( 'hours' ) ) ); ?></p>
		</div>

	</div>

	<div class="wrap fbar">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>.</span>
		<span class="faint">
			<?php
			/*
			 * Only printed once there is something true to print. The first cut
			 * carried the words "Trading status line goes here" as a reminder to
			 * me, and went live saying exactly that -- a placeholder in a footer
			 * is a placeholder on every page of the site. Set sc_trading in the
			 * Customizer (e.g. the limited company name and number, or the sole
			 * trader line) and it appears.
			 */
			$sc_trading = trim( (string) get_theme_mod( 'sc_trading', '' ) );
			if ( '' !== $sc_trading ) :
				?>
				<?php echo esc_html( $sc_trading ); ?>
				&nbsp;·&nbsp;
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy', 'smilecreative' ); ?></a>
		</span>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
