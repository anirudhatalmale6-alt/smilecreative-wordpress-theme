<?php
/**
 * Footer for the landing page. No link list -- same reason as the header.
 *
 * @package smilecreative
 */

?>
</main>
<footer>
	<div class="wrap fbar">
		<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( sc_address_line() ); ?>.</span>
		<span class="faint"><?php echo esc_html( wp_strip_all_tags( sc_opt( 'hours' ) ) ); ?></span>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
