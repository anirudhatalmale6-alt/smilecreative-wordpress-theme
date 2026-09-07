<?php
/**
 * Contact.
 *
 * ONE form, rendered by sc_enquiry_form(). The old site had twelve across
 * three plugins and none of them were delivering; "one form displayed in
 * multiple locations" is literally true here rather than four copies that
 * drift apart.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="contact">
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'Get in touch', 'smilecreative' ); ?></span>
			<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Tell us what you are trying to do.', 'smilecreative' ); ?></h2>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'Not sure what you need yet? That is a normal place to start. Ring, or send a couple of lines and we will come back to you.', 'smilecreative' ); ?>
			</p>

			<div class="deets">
				<span class="label"><?php esc_html_e( 'The studio', 'smilecreative' ); ?></span>
				<p>
					<?php echo esc_html( sc_opt( 'street' ) ); ?><br>
					<?php echo esc_html( trim( sc_opt( 'town' ) . ' ' . sc_opt( 'postcode' ) ) ); ?>
				</p>

				<span class="label"><?php esc_html_e( 'Direct', 'smilecreative' ); ?></span>
				<p><?php echo wp_kses_post( sc_tel_link() ); ?></p>

				<?php
				/*
				 * Only rendered once the published address exists. Nothing
				 * readable goes into the source -- it is assembled by JS, and
				 * with JS off the link falls back to the form rather than
				 * going dead.
				 */
				$sc_eml = sc_email_html();
				if ( $sc_eml ) :
					?>
					<span class="label"><?php esc_html_e( 'Email', 'smilecreative' ); ?></span>
					<p><?php echo wp_kses_post( $sc_eml ); ?></p>
				<?php endif; ?>

				<span class="label"><?php esc_html_e( 'Hours', 'smilecreative' ); ?></span>
				<p><?php echo esc_html( wp_strip_all_tags( sc_opt( 'hours' ) ) ); ?></p>
			</div>
		</div>

		<div>
			<?php echo sc_enquiry_form(); // phpcs:ignore WordPress.Security.EscapeOutput -- built from escaped parts. ?>
		</div>
	</div>
</section>
