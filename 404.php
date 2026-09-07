<?php
/**
 * Not found.
 *
 * Retired URLs are 301'd in inc/redirects.php before this ever renders, so
 * anyone who lands here has followed a genuinely broken link. Give them the
 * two things that recover the visit: the work, and a way to make contact.
 *
 * @package smilecreative
 */

get_header();
?>
<section class="pagehead">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Not found', 'smilecreative' ); ?></span>
		<h1><?php esc_html_e( 'That page is not here any more.', 'smilecreative' ); ?></h1>
		<p class="lead" style="margin-top:1.4rem">
			<?php esc_html_e( 'It may have moved during the rebuild. The work and the contact form are both a click away, or ring and we will find it for you.', 'smilecreative' ); ?>
		</p>
		<div class="cta">
			<a class="btn" href="<?php echo esc_url( home_url( '/#work' ) ); ?>"><?php esc_html_e( 'See the work', 'smilecreative' ); ?></a>
			<a class="btn ghost" href="<?php echo esc_url( home_url( '/#contact' ) ); ?>"><?php esc_html_e( 'Get in touch', 'smilecreative' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
