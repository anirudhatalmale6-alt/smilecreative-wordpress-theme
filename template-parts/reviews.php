<?php
/**
 * Google reviews.
 *
 * Deliberately NOT a carousel: a slider hides four of the five and makes the
 * reader work for the proof, and proof should be the one thing you cannot miss.
 *
 * Deliberately NOT marked up as schema either -- Google requires review markup
 * to come from reviews you collected yourself, and these are copied from a
 * Google profile. Displaying them is fine; marking them up risks the whole
 * rich result. See inc/seo.php.
 *
 * @package smilecreative
 */

$revs = new WP_Query(
	array(
		'post_type'      => 'sc_review',
		'posts_per_page' => 8,
		'orderby'        => 'menu_order date',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( ! $revs->have_posts() ) {
	return;
}
?>
<section id="reviews" class="reviews">
	<div class="wrap">
		<div class="rhead">
			<div>
				<span class="label"><?php esc_html_e( 'What customers actually said', 'smilecreative' ); ?></span>
				<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Unedited, and not written by us.', 'smilecreative' ); ?></h2>
			</div>
			<div class="rscore">
				<span class="num"><?php echo esc_html( get_theme_mod( 'sc_rating', '4.9' ) ); ?></span>
				<span class="of">
					<?php
					printf(
						/* translators: %s: total number of Google reviews */
						esc_html__( 'out of 5, from %s Google reviews', 'smilecreative' ),
						esc_html( get_theme_mod( 'sc_rating_count', '8' ) )
					);
					?>
					<br><?php esc_html_e( 'none below 4 stars in nine years', 'smilecreative' ); ?>
				</span>
			</div>
		</div>

		<p class="lead" style="margin-top:1.6rem">
			<?php
			printf(
				/* translators: 1: number shown, 2: total reviews */
				esc_html__( '%1$s of the %2$s, the ones people took the time to write something in. Word for word.', 'smilecreative' ),
				esc_html( ucfirst( number_format_i18n( $revs->post_count ) ) ),
				esc_html( get_theme_mod( 'sc_rating_count', '8' ) )
			);
			?>
		</p>

		<div class="rgrid">
			<?php
			while ( $revs->have_posts() ) :
				$revs->the_post();
				?>
				<figure class="rcard">
					<div class="stars" aria-label="<?php esc_attr_e( '5 out of 5', 'smilecreative' ); ?>">&#9733;&#9733;&#9733;&#9733;&#9733;</div>
					<blockquote><?php echo wp_kses_post( wpautop( get_the_content() ) ); ?></blockquote>
					<figcaption>
						<strong><?php echo esc_html( get_post_meta( get_the_ID(), 'sc_who', true ) ? get_post_meta( get_the_ID(), 'sc_who', true ) : get_the_title() ); ?></strong>
						<span class="faint"><?php echo esc_html( get_post_meta( get_the_ID(), 'sc_what', true ) ); ?></span>
					</figcaption>
				</figure>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
