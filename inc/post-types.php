<?php
/**
 * Content types.
 *
 * The work grid is a real post type rather than hard-coded markup, so adding a
 * project is a normal WordPress job. That matters more than usual here: the
 * argument for this rebuild is partly "you can still show a client the admin
 * and hand them a login", and a portfolio only a developer can edit undercuts
 * it immediately.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Projects, clients and reviews.
 */
function sc_post_types() {
	register_post_type(
		'sc_project',
		array(
			'labels'        => array(
				'name'               => __( 'Work', 'smilecreative' ),
				'singular_name'      => __( 'Project', 'smilecreative' ),
				'add_new_item'       => __( 'Add project', 'smilecreative' ),
				'edit_item'          => __( 'Edit project', 'smilecreative' ),
				'search_items'       => __( 'Search work', 'smilecreative' ),
				'not_found'          => __( 'No projects yet', 'smilecreative' ),
			),
			'public'        => true,
			'has_archive'   => 'work',
			'menu_icon'     => 'dashicons-portfolio',
			'menu_position' => 24,
			'rewrite'       => array( 'slug' => 'work' ),
			'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);

	register_post_type(
		'sc_client',
		array(
			'labels'       => array(
				'name'          => __( 'Client logos', 'smilecreative' ),
				'singular_name' => __( 'Client logo', 'smilecreative' ),
				'add_new_item'  => __( 'Add client logo', 'smilecreative' ),
			),
			'public'       => false,
			'show_ui'      => true,
			'menu_icon'    => 'dashicons-awards',
			'menu_position' => 25,
			'supports'     => array( 'title', 'thumbnail', 'page-attributes' ),
			'show_in_rest' => true,
		)
	);

	/**
	 * Reviews are stored, not marked up.
	 *
	 * Google's guidelines require review structured data to come from reviews
	 * you collected yourself. These are copied from a Google profile, so they
	 * are displayed on the page and deliberately kept OUT of the schema -- see
	 * inc/seo.php. Marking them up risks the rich result entirely.
	 */
	register_post_type(
		'sc_review',
		array(
			'labels'        => array(
				'name'          => __( 'Reviews', 'smilecreative' ),
				'singular_name' => __( 'Review', 'smilecreative' ),
				'add_new_item'  => __( 'Add review', 'smilecreative' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-star-filled',
			'menu_position' => 25,
			'supports'      => array( 'title', 'editor', 'page-attributes' ),
			'show_in_rest'  => true,
		)
	);
}
add_action( 'init', 'sc_post_types' );

/**
 * Project meta: the live URL and what was actually done.
 */
function sc_project_meta_box() {
	add_meta_box(
		'sc_project_meta',
		__( 'Project details', 'smilecreative' ),
		'sc_project_meta_render',
		'sc_project',
		'normal',
		'high'
	);
	add_meta_box(
		'sc_review_meta',
		__( 'Review details', 'smilecreative' ),
		'sc_review_meta_render',
		'sc_review',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'sc_project_meta_box' );

/**
 * Project fields.
 */
function sc_project_meta_render( $post ) {
	wp_nonce_field( 'sc_meta', 'sc_meta_nonce' );

	$fields = array(
		'sc_did'    => array( __( 'What we did', 'smilecreative' ), __( 'e.g. Website design &amp; build', 'smilecreative' ) ),
		'sc_place'  => array( __( 'Who and where', 'smilecreative' ), __( 'e.g. Family removals firm, Ahoghill', 'smilecreative' ) ),
		'sc_domain' => array( __( 'Live domain', 'smilecreative' ), 'e.g. logansremovals.co.uk' ),
		'sc_url'    => array( __( 'Link to the live site', 'smilecreative' ), 'https://' ),
	);

	echo '<table class="form-table">';
	foreach ( $fields as $key => $meta ) {
		printf(
			'<tr><th><label for="%1$s">%2$s</label></th><td><input type="text" class="large-text" id="%1$s" name="%1$s" value="%3$s"><p class="description">%4$s</p></td></tr>',
			esc_attr( $key ),
			esc_html( $meta[0] ),
			esc_attr( (string) get_post_meta( $post->ID, $key, true ) ),
			esc_html( wp_strip_all_tags( $meta[1] ) )
		);
	}
	echo '</table>';

	printf(
		'<p class="description">%s</p>',
		esc_html__( 'The featured image should be a screenshot of the live site at full width — no desk mockup, no frame, no watermark. The old portfolio used the same stock iMac photograph for all 55 projects and the screen was too small to read any of them.', 'smilecreative' )
	);
}

/**
 * Review fields.
 */
function sc_review_meta_render( $post ) {
	wp_nonce_field( 'sc_meta', 'sc_meta_nonce' );

	printf(
		'<p><label for="sc_who"><strong>%s</strong></label><br><input type="text" class="large-text" id="sc_who" name="sc_who" value="%s"></p>',
		esc_html__( 'Who left it', 'smilecreative' ),
		esc_attr( (string) get_post_meta( $post->ID, 'sc_who', true ) )
	);
	printf(
		'<p><label for="sc_what"><strong>%s</strong></label><br><input type="text" class="large-text" id="sc_what" name="sc_what" value="%s"></p>',
		esc_html__( 'What the job was', 'smilecreative' ),
		esc_attr( (string) get_post_meta( $post->ID, 'sc_what', true ) )
	);
	printf(
		'<p class="description">%s</p>',
		esc_html__( 'Reproduce the review word for word, typos and all. Tidying a customer\'s own sentence is what makes a real review start reading like an invented one.', 'smilecreative' )
	);
}

/**
 * Save meta.
 */
function sc_save_meta( $post_id ) {
	if ( ! isset( $_POST['sc_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['sc_meta_nonce'] ), 'sc_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	foreach ( array( 'sc_did', 'sc_place', 'sc_domain', 'sc_who', 'sc_what' ) as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
	if ( isset( $_POST['sc_url'] ) ) {
		update_post_meta( $post_id, 'sc_url', esc_url_raw( wp_unslash( $_POST['sc_url'] ) ) );
	}
}
add_action( 'save_post', 'sc_save_meta' );
