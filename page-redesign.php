<?php
/**
 * Template Name: Free homepage redesign (landing page)
 *
 * A distraction-free landing page for the free-redesign offer, for paid
 * traffic. Deliberately different from an ordinary page:
 *
 * - No navigation. Somebody who arrived from an advert has one decision to
 *   make, and every other link on the page is a way of not making it.
 * - The form is above the fold and repeated at the bottom, because a lander
 *   that makes you scroll back up to act loses the people it convinced.
 * - It is the SAME form as the rest of the site, so a submission is stored in
 *   the database before any mail is attempted. On a page carrying ad spend
 *   that matters more than anywhere else: a silent mail failure there is money
 *   spent to lose a lead twice over.
 *
 * Why this is a rebuild rather than a copy of the Next.js lander: that page
 * serves NO form markup at all -- not one <input> -- because its capture is
 * rendered client-side and posts to its own backend. A static copy would look
 * right and collect nothing.
 *
 * @package smilecreative
 */

get_header( 'lander' );

$sc_steps = array(
	array( __( 'Takes 2 minutes', 'smilecreative' ), __( 'Send us your address', 'smilecreative' ), __( 'Your current web address, and a line about what you would like to be better.', 'smilecreative' ) ),
	array( __( 'Usually within 24 hours', 'smilecreative' ), __( 'We design your homepage', 'smilecreative' ), __( 'A real concept — desktop and mobile, with the copy written for the new layout. Not a wireframe.', 'smilecreative' ) ),
	array( __( 'No strings', 'smilecreative' ), __( 'You keep it either way', 'smilecreative' ), __( 'Like it and we can talk about building it. Do not, and the concept is still yours.', 'smilecreative' ) ),
);
?>

<section class="lander-hero">
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'Free, no strings', 'smilecreative' ); ?></span>
			<h1><?php esc_html_e( 'We will redesign your homepage', 'smilecreative' ); ?> <em><?php esc_html_e( 'for free.', 'smilecreative' ); ?></em></h1>
			<p class="lead" style="margin-top:1.4rem">
				<?php esc_html_e( 'See exactly how your homepage could look. A real concept, usually back within 24 hours and always within two working days. No payment, no obligation, and it is yours to keep whatever you decide.', 'smilecreative' ); ?>
			</p>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'We would rather show you the work than tell you about it. If it is not right, you have lost nothing and we have lost a couple of days — which is the correct way round.', 'smilecreative' ); ?>
			</p>
			<div class="deets" style="margin-top:2rem">
				<span class="label"><?php esc_html_e( 'Or just ring', 'smilecreative' ); ?></span>
				<p><?php echo wp_kses_post( sc_tel_link() ); ?></p>
			</div>
		</div>
		<div>
			<?php echo sc_enquiry_form( array( 'id' => 'redesign', 'button' => __( 'Get my free redesign', 'smilecreative' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<section class="care">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'How it works', 'smilecreative' ); ?></span>
		<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Three steps, and one of them is ours.', 'smilecreative' ); ?></h2>
		<ol class="mk-steps" style="margin-top:2.4rem">
			<?php foreach ( $sc_steps as $i => $sc_step ) : ?>
				<li>
					<span class="n"><?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<div>
						<span class="when"><?php echo esc_html( $sc_step[0] ); ?></span>
						<h3><?php echo esc_html( $sc_step[1] ); ?></h3>
						<p class="muted"><?php echo esc_html( $sc_step[2] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<?php
// Proof, in the order that actually persuades: the work, then what customers said.
get_template_part( 'template-parts/work' );
get_template_part( 'template-parts/reviews' );
?>

<section id="contact">
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'Ready when you are', 'smilecreative' ); ?></span>
			<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Send us the address and we will get started.', 'smilecreative' ); ?></h2>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'No payment, no obligation, and the concept is yours to keep.', 'smilecreative' ); ?>
			</p>
			<div class="deets">
				<span class="label"><?php esc_html_e( 'The studio', 'smilecreative' ); ?></span>
				<p><?php echo esc_html( sc_address_line() ); ?></p>
				<span class="label"><?php esc_html_e( 'Direct', 'smilecreative' ); ?></span>
				<p><?php echo wp_kses_post( sc_tel_link() ); ?></p>
			</div>
		</div>
		<div>
			<?php echo sc_enquiry_form( array( 'id' => 'redesign2', 'button' => __( 'Get my free redesign', 'smilecreative' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<?php
get_footer( 'lander' );
