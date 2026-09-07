<?php
/**
 * Services list and the price ladder.
 *
 * Both are ARRAYS, not markup. The heading count is derived from the list it
 * describes, because it read "Six things" above a list of five for a round
 * after social media was dropped -- a number in a heading that describes the
 * list beneath it should never be a literal. Flattening this to static HTML
 * reintroduced exactly that bug, which is why it is data again.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sc_services = apply_filters(
	'sc_services',
	array(
		array(
			'name' => __( 'Website design &amp; build', 'smilecreative' ),
			'url'  => '/website-design/',
			'desc' => __( 'Built to be quick, to work on a phone, and to be edited by you afterwards.', 'smilecreative' ),
		),
		array(
			'name' => __( 'Logo &amp; brand identity', 'smilecreative' ),
			'url'  => '/logo-design/',
			'desc' => __( 'A mark you own outright, supplied in every format you will ever be asked for.', 'smilecreative' ),
		),
		array(
			'name' => __( 'Print', 'smilecreative' ),
			'url'  => '/print-marketing/',
			'desc' => __( 'Brochures, signage, vehicle graphics, stationery. Still a third of what we do, and some of these clients have been with us since before the websites.', 'smilecreative' ),
		),
		array(
			'name' => __( 'Search marketing', 'smilecreative' ),
			'url'  => '/search-marketing/',
			'desc' => __( 'Getting found for the things people actually type. No lock-in, no jargon.', 'smilecreative' ),
		),
		array(
			'name' => __( 'Video production', 'smilecreative' ),
			'url'  => '/video-production/',
			'desc' => __( 'Filmed, edited and captioned here. Useful on a website, not just on Instagram.', 'smilecreative' ),
		),
	)
);

/*
 * A LADDER, not a comparison table. Three tiers side by side with tick lists
 * make people shop the columns and pick the cheapest; a starting figure per
 * thing makes them work out which conversation they are in.
 *
 * The £45 is the one earning its place -- everything else asks for a project
 * commitment, and that one gives somebody who is not ready for a website
 * something to say yes to today.
 */
$sc_prices = apply_filters(
	'sc_prices',
	array(
		array( '&pound;45', __( 'A digital contact page', 'smilecreative' ), __( 'One page, one tap to ring, message or find you. Through Contact Local, which is ours.', 'smilecreative' ) ),
		array( '&pound;395', __( 'A website, from', 'smilecreative' ), __( 'Designed and built properly, yours outright. Fixed price once we have talked.', 'smilecreative' ) ),
		array( '&pound;9.97', __( 'Hosting and backups, a month', 'smilecreative' ), __( 'Domain, hosting, backups. No support included at this level.', 'smilecreative' ) ),
		array( '&pound;87', __( 'Looked after, a month', 'smilecreative' ), __( 'The same, plus updates, monitoring, security and someone at the end of the phone.', 'smilecreative' ) ),
	)
);

$sc_words = array( 1 => 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight' );
$sc_count = count( $sc_services );
$sc_word  = isset( $sc_words[ $sc_count ] ) ? $sc_words[ $sc_count ] : (string) $sc_count;
?>
<section id="services">
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'What we do', 'smilecreative' ); ?></span>
			<h2 class="big" style="margin-top:.8rem">
				<?php
				printf(
					/* translators: %s: number of services, spelled out */
					esc_html__( '%s things, done by the same people.', 'smilecreative' ),
					esc_html( $sc_word )
				);
				?>
			</h2>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'Most firms here need two or three of these and nobody to project-manage the gaps between them. That is the whole idea.', 'smilecreative' ); ?>
			</p>
		</div>
		<div class="svc">
			<?php foreach ( $sc_services as $sc_s ) : ?>
				<a href="<?php echo esc_url( home_url( $sc_s['url'] ) ); ?>">
					<div>
						<h3><?php echo wp_kses_post( $sc_s['name'] ); ?></h3>
						<p class="muted"><?php echo wp_kses_post( $sc_s['desc'] ); ?></p>
					</div>
					<span class="go"><?php esc_html_e( 'See more', 'smilecreative' ); ?> &#8594;</span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="wrap">
		<span class="label" style="margin-top:3.4rem"><?php esc_html_e( 'What things cost', 'smilecreative' ); ?></span>
		<div class="pgrid">
			<?php foreach ( $sc_prices as $sc_p ) : ?>
				<div class="pcard">
					<span class="fig"><?php echo wp_kses_post( $sc_p[0] ); ?></span>
					<h3><?php echo wp_kses_post( $sc_p[1] ); ?></h3>
					<p class="muted"><?php echo wp_kses_post( $sc_p[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<p class="lead" style="margin-top:1.8rem;font-size:.97rem">
			<?php esc_html_e( 'Every one of those is a starting figure, not a quote. What you get told at the first conversation is a fixed price for a described piece of work — and if your budget will not stretch to what you have described, you will hear that then rather than three weeks in.', 'smilecreative' ); ?>
		</p>
	</div>
</section>
