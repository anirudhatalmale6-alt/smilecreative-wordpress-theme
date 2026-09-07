<?php
/**
 * The free homepage redesign offer.
 *
 * This is the strongest thing on the page and it is placed AFTER the work
 * gallery on purpose. "Free homepage redesign" read cold is a gimmick; read
 * straight after six real client sites it is confidence. The proof has to land
 * before the offer or the offer devalues the proof.
 *
 * It is also the whole positioning made physical. The page argues we would
 * rather lose the job than wear anyone down -- handing over a finished concept
 * before asking for a penny IS that argument, rather than another claim about
 * it. His own lander says it better than I could: "We show our work before
 * asking for anything."
 *
 * The form is the SAME form as everywhere else, pre-set to the redesign
 * subject. No second form, no second inbox, no second thing to break.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sc_steps = array(
	array( __( 'Takes 2 minutes', 'smilecreative' ), __( 'Send us your address', 'smilecreative' ), __( 'Your current web address and a line about what you would like to be better.', 'smilecreative' ) ),
	array( __( 'Within 48 hours', 'smilecreative' ), __( 'We design your homepage', 'smilecreative' ), __( 'A real concept, desktop and mobile, with copy written for the new layout. Not a wireframe.', 'smilecreative' ) ),
	array( __( 'No strings', 'smilecreative' ), __( 'You keep it either way', 'smilecreative' ), __( 'Like it and we can talk about building it. Do not, and the concept is still yours.', 'smilecreative' ) ),
);
?>
<section id="redesign" class="care mockup">
	<div class="wrap">
		<div class="mk-head">
			<div>
				<span class="label"><?php esc_html_e( 'Before you spend anything', 'smilecreative' ); ?></span>
				<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'We will redesign your homepage for free.', 'smilecreative' ); ?></h2>
				<p class="lead" style="margin-top:1.2rem">
					<?php esc_html_e( 'Send us your address and we will send back a proper concept of your homepage within 48 hours — desktop and mobile, with the copy written. No payment, no obligation, and it is yours to keep whatever you decide.', 'smilecreative' ); ?>
				</p>
				<p class="lead" style="margin-top:1.2rem">
					<?php esc_html_e( 'We would rather show you the work than tell you about it. If the concept is not right, you have lost nothing and we have lost a couple of days — which is the correct way round.', 'smilecreative' ); ?>
				</p>
				<div class="cta">
					<a class="btn" href="#contact"><?php esc_html_e( 'Get my free redesign', 'smilecreative' ); ?></a>
				</div>
			</div>

			<ol class="mk-steps">
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
	</div>
</section>
