<?php
/**
 * The homepage.
 *
 * Content comes from the Work / Client logos / Reviews post types where they
 * exist, and falls back to the approved concept copy where they do not -- so
 * the site looks finished from the moment the theme is switched on, rather
 * than demanding twenty projects be entered before it stops looking broken.
 *
 * @package smilecreative
 */

get_header();
?>

<section class="hero">
	<?php if ( sc_opt( 'hero_network' ) ) : ?>
		<?php
		/*
		 * The photograph layer. On the reference this hero was copied from, the
		 * node network is a faint overlay on a warm photograph and the PHOTO is
		 * doing all the emotional work -- the geometry on its own is cold. Until
		 * a real photograph exists this warm wash stands in for it, so the
		 * structure is right and only the asset is missing.
		 */
		$hero_img = get_theme_mod( 'sc_hero_image' );
		?>
		<div class="hero-photo"<?php echo $hero_img ? ' style="background-image:url(' . esc_url( $hero_img ) . ')"' : ''; ?>></div>
		<canvas id="field" aria-hidden="true"></canvas>
		<div class="hero-scrim"></div>
	<?php endif; ?>

	<div class="wrap">
		<div class="copy">
			<span class="label rise">
				<?php
				printf(
					/* translators: %s: year the first website was built */
					esc_html__( 'Web · Print · Brand — Belfast, since %s', 'smilecreative' ),
					esc_html( sc_opt( 'first_site' ) )
				);
				?>
			</span>
			<h1 class="rise"><?php esc_html_e( 'Websites and print,', 'smilecreative' ); ?> <em><?php esc_html_e( 'built properly.', 'smilecreative' ); ?></em></h1>
			<p class="lead rise">
				<?php
				printf(
					/* translators: %s: year the first website was built */
					esc_html__( 'A Belfast studio working for businesses across the UK and Ireland, and a few a good deal further — Antibes, Mauritius, Essex, Glasgow, Liverpool. First website built in %s. You deal with the person doing the work, and you own everything at the end of it.', 'smilecreative' ),
					esc_html( sc_opt( 'first_site' ) )
				);
				?>
			</p>
			<div class="cta rise">
				<?php /* The free concept leads. On a page arguing "we would rather lose
				         the job than wear you down", the primary CTA should be the
				         low-commitment one -- "Start a project" asks for a decision
				         the visitor has not got enough to make yet. */ ?>
				<a class="btn" href="#redesign"><?php esc_html_e( 'Get a free homepage redesign', 'smilecreative' ); ?></a>
				<a class="btn ghost" href="#work"><?php esc_html_e( 'See recent work', 'smilecreative' ); ?></a>
			</div>
		</div>
	</div>

	<?php if ( sc_opt( 'hero_network' ) ) : ?>
		<div class="readout">
			<div class="rbar" id="readout">
				<span class="idle"><?php esc_html_e( 'Nine places, one studio. Hover a point to see whose work is there.', 'smilecreative' ); ?></span>
			</div>
		</div>
	<?php endif; ?>
</section>

<?php get_template_part( 'template-parts/clients' ); ?>
<?php get_template_part( 'template-parts/work' ); ?>
<?php get_template_part( 'template-parts/mockup' ); ?>
<?php get_template_part( 'template-parts/reviews' ); ?>
<?php get_template_part( 'template-parts/services' ); ?>
<?php get_template_part( 'template-parts/care' ); ?>
<?php get_template_part( 'template-parts/who' ); ?>
<?php get_template_part( 'template-parts/nohustle' ); ?>
<?php get_template_part( 'template-parts/how' ); ?>
<?php get_template_part( 'template-parts/answers' ); ?>
<?php get_template_part( 'template-parts/contact' ); ?>

<?php
get_footer();
