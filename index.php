<?php
/**
 * Fallback template. WordPress requires this file to exist.
 *
 * @package smilecreative
 */

get_header();
?>
<section class="pagehead">
	<div class="wrap"><h1><?php echo esc_html( wp_get_document_title() ); ?></h1></div>
</section>
<section>
	<div class="wrap entry">
		<?php
		if ( have_posts() ) {
			while ( have_posts() ) {
				the_post();
				printf(
					'<h2><a href="%s">%s</a></h2>',
					esc_url( get_permalink() ),
					esc_html( get_the_title() )
				);
				the_excerpt();
			}
		} else {
			esc_html_e( 'Nothing here yet.', 'smilecreative' );
		}
		?>
	</div>
</section>
<?php
get_footer();
