<?php
/**
 * Enquiries admin screen.
 *
 * Storing submissions is worth nothing if there is no way to look at them, and
 * the failure this replaces was invisible precisely because nothing surfaced
 * it. So this screen does two jobs: list the enquiries, and shout when mail is
 * failing rather than waiting for someone to notice the phone has gone quiet.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Menu entry, with a bubble showing how many recent sends failed.
 */
function sc_enquiry_menu() {
	$failed = sc_enquiry_failed_count();
	$label  = __( 'Enquiries', 'smilecreative' );

	if ( $failed ) {
		$label .= sprintf(
			' <span class="awaiting-mod"><span class="pending-count">%d</span></span>',
			(int) $failed
		);
	}

	add_menu_page(
		__( 'Enquiries', 'smilecreative' ),
		$label,
		'edit_pages',
		'sc-enquiries',
		'sc_enquiry_screen',
		'dashicons-email-alt',
		26
	);
}
add_action( 'admin_menu', 'sc_enquiry_menu' );

/**
 * How many of the last 50 enquiries failed to email.
 */
function sc_enquiry_failed_count() {
	global $wpdb;

	$table = $wpdb->prefix . 'sc_enquiries';

	// phpcs:disable WordPress.DB.DirectDatabaseQuery
	$exists = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $table ) );
	if ( $exists !== $table ) {
		return 0;
	}

	return (int) $wpdb->get_var(
		"SELECT COUNT(*) FROM (SELECT mail_sent FROM {$table} ORDER BY id DESC LIMIT 50) t WHERE mail_sent = 0"
	);
	// phpcs:enable
}

/**
 * A dashboard-wide warning when delivery is broken.
 *
 * This is the notice that would have saved the last several weeks. The old
 * setup had no SMTP plugin, so WordPress sent through PHP mail() with no log
 * of any kind -- a total failure looked exactly like nobody getting in touch.
 */
function sc_enquiry_notice() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return;
	}

	$failed = sc_enquiry_failed_count();
	if ( ! $failed ) {
		return;
	}

	printf(
		'<div class="notice notice-error"><p><strong>%s</strong> %s <a href="%s">%s</a></p></div>',
		esc_html__( 'Enquiry email is not being delivered.', 'smilecreative' ),
		esc_html(
			sprintf(
				/* translators: %d: number of failed sends */
				_n(
					'%d recent enquiry was saved but could not be emailed. Nothing has been lost -- it is all on the Enquiries screen -- but the notification is broken and needs fixing.',
					'%d recent enquiries were saved but could not be emailed. Nothing has been lost -- they are all on the Enquiries screen -- but the notification is broken and needs fixing.',
					$failed,
					'smilecreative'
				),
				$failed
			)
		),
		esc_url( admin_url( 'admin.php?page=sc-enquiries' ) ),
		esc_html__( 'Open Enquiries', 'smilecreative' )
	);
}
add_action( 'admin_notices', 'sc_enquiry_notice' );

/**
 * The list screen.
 */
function sc_enquiry_screen() {
	global $wpdb;

	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'You do not have permission to view enquiries.', 'smilecreative' ) );
	}

	$table = $wpdb->prefix . 'sc_enquiries';
	$per   = 30;
	$page  = isset( $_GET['paged'] ) ? max( 1, absint( $_GET['paged'] ) ) : 1; // phpcs:ignore WordPress.Security.NonceVerification
	$off   = ( $page - 1 ) * $per;

	// phpcs:disable WordPress.DB.DirectDatabaseQuery
	$total = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );
	$rows  = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$table} ORDER BY id DESC LIMIT %d OFFSET %d", $per, $off ) );
	// phpcs:enable

	echo '<div class="wrap"><h1>' . esc_html__( 'Enquiries', 'smilecreative' ) . '</h1>';

	echo '<p class="description">' . esc_html__( 'Every enquiry is written here before any email is attempted, so a mail problem can never lose one again.',
		'smilecreative'
	) . '</p>';

	if ( ! $rows ) {
		echo '<p>' . esc_html__( 'No enquiries yet.', 'smilecreative' ) . '</p></div>';
		return;
	}

	echo '<table class="widefat striped"><thead><tr>';
	foreach ( array( 'When', 'Name', 'Email', 'Phone', 'About', 'Message', 'Emailed' ) as $h ) {
		echo '<th>' . esc_html( $h ) . '</th>';
	}
	echo '</tr></thead><tbody>';

	foreach ( $rows as $r ) {
		echo '<tr>';
		echo '<td>' . esc_html( mysql2date( 'j M Y, H:i', $r->created_at ) ) . '</td>';
		echo '<td><strong>' . esc_html( $r->name ) . '</strong></td>';
		printf( '<td><a href="mailto:%1$s">%1$s</a></td>', esc_attr( $r->email ) );
		echo '<td>' . esc_html( $r->phone ) . '</td>';
		echo '<td>' . esc_html( $r->subject ) . '</td>';
		echo '<td>' . nl2br( esc_html( wp_trim_words( (string) $r->message, 40 ) ) ) . '</td>';

		if ( $r->mail_sent ) {
			echo '<td><span style="color:#1a7f37">&#10003;</span></td>';
		} else {
			printf(
				'<td><span style="color:#b32d2e" title="%s">&#10007; %s</span></td>',
				esc_attr( (string) $r->mail_error ),
				esc_html__( 'failed', 'smilecreative' )
			);
		}
		echo '</tr>';
	}

	echo '</tbody></table>';

	$pages = (int) ceil( $total / $per );
	if ( $pages > 1 ) {
		echo '<div class="tablenav"><div class="tablenav-pages">';
		echo wp_kses_post(
			paginate_links(
				array(
					'base'    => add_query_arg( 'paged', '%#%' ),
					'format'  => '',
					'current' => $page,
					'total'   => $pages,
				)
			)
		);
		echo '</div></div>';
	}

	echo '</div>';
}

/**
 * CSV export, so the record is portable and he is never locked in to this
 * theme to get at his own leads.
 */
function sc_enquiry_export() {
	if ( ! isset( $_GET['sc_export'] ) || ! current_user_can( 'edit_pages' ) ) {
		return;
	}
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_key( $_GET['_wpnonce'] ), 'sc_export' ) ) {
		return;
	}

	global $wpdb;
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery
	$rows = $wpdb->get_results( "SELECT created_at,name,email,phone,subject,message,source,mail_sent FROM {$wpdb->prefix}sc_enquiries ORDER BY id DESC", ARRAY_A );

	nocache_headers();
	header( 'Content-Type: text/csv; charset=utf-8' );
	header( 'Content-Disposition: attachment; filename=smile-enquiries.csv' );

	$out = fopen( 'php://output', 'w' );
	fputcsv( $out, array( 'Date', 'Name', 'Email', 'Phone', 'About', 'Message', 'Page', 'Emailed' ) );
	foreach ( (array) $rows as $r ) {
		fputcsv( $out, $r );
	}
	fclose( $out );
	exit;
}
add_action( 'admin_init', 'sc_enquiry_export' );
