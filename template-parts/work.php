<?php
/**
 * The work gallery + the plain list of everything else.
 *
 * @package smilecreative
 */

$sc_q = new WP_Query(
	array(
		'post_type'      => 'sc_project',
		'posts_per_page' => 6,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);
?>
<section id="work">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Our work', 'smilecreative' ); ?></span>
		<h2 class="big"><?php esc_html_e( 'Real sites, shown as they actually are.', 'smilecreative' ); ?></h2>
		<p class="lead" style="margin-top:1rem">
			<?php esc_html_e( 'Every image below is a live screenshot of the site as it stands today. No mockups, no watermark, nothing staged. Click through and judge them yourself.', 'smilecreative' ); ?>
		</p>
	</div>

	<div class="wrap">
		<?php if ( $sc_q->have_posts() ) : ?>
			<div class="gallery" id="gal">
				<?php
				while ( $sc_q->have_posts() ) :
					$sc_q->the_post();
					$url = get_post_meta( get_the_ID(), 'sc_url', true );
					$tag = $url ? 'a' : 'article';
					?>
					<<?php echo esc_attr( $tag ); ?> class="card"<?php echo $url ? ' href="' . esc_url( $url ) . '" target="_blank" rel="noopener"' : ''; ?>>
						<div class="shot">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail(
									'sc-card',
									array(
										'loading' => 'lazy',
										'alt'     => sprintf(
											/* translators: %s: project name */
											esc_attr__( '%s website', 'smilecreative' ),
											esc_attr( get_the_title() )
										),
									)
								);
							}
							?>
						</div>
						<div class="cardmeta">
							<span class="label"><?php echo esc_html( get_post_meta( get_the_ID(), 'sc_did', true ) ); ?></span>
							<h3><?php the_title(); ?></h3>
							<p class="muted"><?php echo esc_html( get_post_meta( get_the_ID(), 'sc_place', true ) ); ?></p>
							<p class="dom"><?php echo esc_html( get_post_meta( get_the_ID(), 'sc_domain', true ) ); ?></p>
						</div>
					</<?php echo esc_attr( $tag ); ?>>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<div class="arrows">
				<button type="button" data-gal="prev" aria-label="<?php esc_attr_e( 'Previous', 'smilecreative' ); ?>">&#8249;</button>
				<button type="button" data-gal="next" aria-label="<?php esc_attr_e( 'Next', 'smilecreative' ); ?>">&#8250;</button>
			</div>
		<?php else : ?>
			<p class="lead" style="margin-top:2rem">
				<?php esc_html_e( 'Add projects under Work in the admin and they appear here. Use a live screenshot at full width as the featured image.', 'smilecreative' ); ?>
			</p>
		<?php endif; ?>

		<?php
		// Everything the gallery has no room for, named plainly.
		$more = new WP_Query(
			array(
				'post_type'      => 'sc_project',
				'posts_per_page' => 20,
				'offset'         => 6,
				'orderby'        => 'menu_order date',
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		if ( $more->have_posts() ) :
			?>
			<h3 style="margin-top:3.2rem;font-size:1.15rem"><?php esc_html_e( 'Also on the go, or recently finished', 'smilecreative' ); ?></h3>
			<ul class="morework">
				<?php
				while ( $more->have_posts() ) :
					$more->the_post();
					printf(
						'<li><span class="mw-n">%s</span><span class="mw-d">%s</span></li>',
						esc_html( get_the_title() ),
						esc_html( get_post_meta( get_the_ID(), 'sc_did', true ) )
					);
				endwhile;
				wp_reset_postdata();
				?>
			</ul>
		<?php endif; ?>
	</div>
</section>
