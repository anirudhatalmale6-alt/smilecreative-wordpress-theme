<?php
/**
 * Client logo strip.
 *
 * The caption is not decoration. Most of these came for print rather than
 * websites, and saying so turns an implied claim into proof -- it also
 * protects him if a prospect actually rings one of them.
 *
 * @package smilecreative
 */

$logos = new WP_Query(
	array(
		'post_type'      => 'sc_client',
		'posts_per_page' => 18,
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	)
);

if ( ! $logos->have_posts() ) {
	return;
}
?>
<section class="clients">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Clients we have produced work for', 'smilecreative' ); ?></span>
		<p class="lead" style="margin-top:.9rem;font-size:1rem">
			<?php esc_html_e( 'Many of these came to us for print rather than websites, some of them years ago. We have left them in because the work was real — not to suggest we built all their sites.', 'smilecreative' ); ?>
		</p>
		<div class="cgrid">
			<?php
			while ( $logos->have_posts() ) :
				$logos->the_post();
				echo '<div class="clogo">';
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'sc-logo', array( 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) ) );
				}
				echo '</div>';
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
