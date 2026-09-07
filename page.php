<?php
/**
 * A single page.
 *
 * Every page gets exactly one H1, from the template. Twenty-one of the old
 * site's twenty-six pages had none at all.
 *
 * @package smilecreative
 */

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<section class="pagehead">
		<div class="wrap">
			<h1><?php the_title(); ?></h1>
		</div>
	</section>

	<section>
		<div class="wrap entry">
			<?php the_content(); ?>
		</div>
	</section>
	<?php
endwhile;

get_footer();
