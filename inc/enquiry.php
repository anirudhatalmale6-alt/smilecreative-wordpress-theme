<?php
/**
 * The one enquiry form.
 *
 * The old site had TWELVE forms across three systems -- seven Elementor Pro,
 * five Royal Elementor Addons, one Quiz Master Next -- and none of them were
 * delivering. Nobody noticed for weeks, and that is the part this file is
 * really designed around.
 *
 * Two rules follow from that failure:
 *
 * 1. EVERY submission is written to the database BEFORE any mail is attempted.
 *    If the mail fails, the enquiry is still on record. On the old site a mail
 *    failure meant the customer was simply gone, with nothing to find
 *    afterwards. Storage is what turns a silent revenue loss into something
 *    you can notice late and still act on.
 *
 * 2. The From address is ALWAYS on this domain, never the visitor's. Sending
 *    as the visitor makes the message fail SPF/DMARC at the receiving end and
 *    it is discarded with no bounce -- the single most common cause of exactly
 *    the symptom this site had. The visitor goes in Reply-To, so hitting reply
 *    still works the way everyone expects.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SC_ENQ_DB_VERSION', '1' );

/**
 * Create the submissions table.
 */
function sc_enquiry_install() {
	global $wpdb;

	if ( get_option( 'sc_enq_db_version' ) === SC_ENQ_DB_VERSION ) {
		return;
	}

	$table   = $wpdb->prefix . 'sc_enquiries';
	$collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE {$table} (
		id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
		created_at DATETIME NOT NULL,
		name VARCHAR(190) NOT NULL DEFAULT '',
		email VARCHAR(190) NOT NULL DEFAULT '',
		phone VARCHAR(60) NOT NULL DEFAULT '',
		subject VARCHAR(120) NOT NULL DEFAULT '',
		message TEXT NULL,
		source VARCHAR(190) NOT NULL DEFAULT '',
		ip VARCHAR(45) NOT NULL DEFAULT '',
		mail_sent TINYINT(1) NOT NULL DEFAULT 0,
		mail_error TEXT NULL,
		PRIMARY KEY (id),
		KEY created_at (created_at),
		KEY mail_sent (mail_sent)
	) {$collate};";

	require_once ABSPATH . 'wp-admin/includes/upgrade.php';
	dbDelta( $sql );

	update_option( 'sc_enq_db_version', SC_ENQ_DB_VERSION );
}
add_action( 'after_switch_theme', 'sc_enquiry_install' );
add_action( 'admin_init', 'sc_enquiry_install' );

/**
 * Render the form. One markup definition, used everywhere.
 *
 * Available as [smile_enquiry] in the editor and as sc_enquiry_form() in a
 * template, so "one form displayed in multiple locations" is literally true
 * rather than four copies that drift apart.
 */
function sc_enquiry_form( $args = array() ) {
	$args = wp_parse_args(
		$args,
		array(
			'id'     => 'enquiry',
			'button' => __( 'Send enquiry', 'smilecreative' ),
			'steps'  => false,   // true = the two-step redesign form
		)
	);

	$state = sc_enquiry_state();
	$old   = isset( $state['old'] ) ? $state['old'] : array();
	$val   = function ( $k ) use ( $old ) {
		return isset( $old[ $k ] ) ? esc_attr( $old[ $k ] ) : '';
	};

	ob_start();
	?>
	<div class="formwrap" id="<?php echo esc_attr( $args['id'] ); ?>-form">
		<?php if ( ! empty( $state['message'] ) ) : ?>
			<p class="formmsg <?php echo 'ok' === $state['type'] ? 'ok' : 'err'; ?>" role="status">
				<?php echo esc_html( $state['message'] ); ?>
			</p>
		<?php endif; ?>

		<form class="form" method="post" action="<?php echo esc_url( sc_enquiry_action_url() ); ?>#<?php echo esc_attr( $args['id'] ); ?>-form" novalidate>
			<?php wp_nonce_field( 'sc_enquiry', 'sc_enquiry_nonce' ); ?>
			<input type="hidden" name="sc_enquiry" value="1">
			<input type="hidden" name="sc_source" value="<?php echo esc_attr( sc_current_url() ); ?>">
			<?php /* Time trap: a human cannot read and fill this in under 3 seconds. */ ?>
			<input type="hidden" name="sc_t" value="<?php echo esc_attr( time() ); ?>">

			<?php /* Honeypot. Hidden in CSS, not with hidden/display:none inline, because
			         some bots skip fields the browser would not render at all. */ ?>
			<div class="hp" aria-hidden="true">
				<label for="sc_website">Leave this empty</label>
				<input type="text" id="sc_website" name="sc_website" tabindex="-1" autocomplete="off">
			</div>

			<?php if ( $args['steps'] ) : ?>
			<?php
			/*
			 * Step one asks for nothing personal. That order is the whole point:
			 * a visitor who has already ticked what is wrong with their site has
			 * invested something, and finishes far more often than one met with
			 * a name-and-email box on arrival. It is also the answer that makes
			 * the concept worth building -- it says what to fix.
			 */
			?>
			<fieldset class="step" data-step="1">
				<span class="step-of"><?php esc_html_e( 'Step 1 of 2', 'smilecreative' ); ?></span>
				<h3 class="step-q"><?php esc_html_e( 'What are you struggling most with on your current website?', 'smilecreative' ); ?></h3>
				<p class="muted step-hint"><?php esc_html_e( 'Tick anything that applies.', 'smilecreative' ); ?></p>

				<div class="pains">
					<?php foreach ( sc_enquiry_pains() as $sc_i => $sc_pain ) : ?>
						<label class="pain">
							<input type="checkbox" name="sc_pain[]" value="<?php echo esc_attr( $sc_pain ); ?>">
							<span><?php echo esc_html( $sc_pain ); ?></span>
						</label>
					<?php endforeach; ?>
				</div>

				<div class="field" style="margin-top:1.4rem">
					<label for="<?php echo esc_attr( $args['id'] ); ?>-u"><?php esc_html_e( 'Your web address', 'smilecreative' ); ?></label>
					<input id="<?php echo esc_attr( $args['id'] ); ?>-u" name="sc_url" type="text"
						placeholder="yourbusiness.co.uk" value="<?php echo $val( 'url' ); ?>">
				</div>

				<div class="step-nav">
					<button class="btn" type="button" data-step-next><?php esc_html_e( 'Continue', 'smilecreative' ); ?> &rarr;</button>
				</div>
			</fieldset>

			<fieldset class="step" data-step="2">
				<span class="step-of"><?php esc_html_e( 'Step 2 of 2', 'smilecreative' ); ?></span>
				<h3 class="step-q"><?php esc_html_e( 'Where should we send your redesign?', 'smilecreative' ); ?></h3>
				<p class="muted step-hint"><?php esc_html_e( 'Usually back within 24 hours, always within two working days.', 'smilecreative' ); ?></p>
			<?php endif; ?>

			<div class="field">
				<label for="<?php echo esc_attr( $args['id'] ); ?>-n"><?php esc_html_e( 'Your name', 'smilecreative' ); ?> <span class="req">*</span></label>
				<input id="<?php echo esc_attr( $args['id'] ); ?>-n" name="sc_name" type="text" required value="<?php echo $val( 'name' ); ?>">
			</div>
			<div class="field">
				<label for="<?php echo esc_attr( $args['id'] ); ?>-e"><?php esc_html_e( 'Email', 'smilecreative' ); ?> <span class="req">*</span></label>
				<input id="<?php echo esc_attr( $args['id'] ); ?>-e" name="sc_email" type="email" required value="<?php echo $val( 'email' ); ?>">
			</div>
			<div class="field">
				<label for="<?php echo esc_attr( $args['id'] ); ?>-p"><?php esc_html_e( 'Phone', 'smilecreative' ); ?></label>
				<input id="<?php echo esc_attr( $args['id'] ); ?>-p" name="sc_phone" type="tel" value="<?php echo $val( 'phone' ); ?>">
			</div>
			<?php if ( ! $args['steps'] ) : ?>
			<div class="field">
				<label for="<?php echo esc_attr( $args['id'] ); ?>-s"><?php esc_html_e( 'What is it about?', 'smilecreative' ); ?></label>
				<select id="<?php echo esc_attr( $args['id'] ); ?>-s" name="sc_subject">
					<?php foreach ( sc_enquiry_subjects() as $s ) : ?>
						<option<?php selected( isset( $old['subject'] ) ? $old['subject'] : '', $s ); ?>><?php echo esc_html( $s ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="field">
				<label for="<?php echo esc_attr( $args['id'] ); ?>-m"><?php esc_html_e( 'Your message', 'smilecreative' ); ?></label>
				<textarea id="<?php echo esc_attr( $args['id'] ); ?>-m" name="sc_message"><?php echo esc_textarea( isset( $old['message'] ) ? $old['message'] : '' ); ?></textarea>
			</div>
			<?php else : ?>
				<input type="hidden" name="sc_subject" value="<?php echo esc_attr( sc_enquiry_subjects()[0] ); ?>">
			<?php endif; ?>

			<div class="step-nav">
				<?php if ( $args['steps'] ) : ?>
					<button class="btn ghost" type="button" data-step-back>&larr; <?php esc_html_e( 'Back', 'smilecreative' ); ?></button>
				<?php endif; ?>
				<button class="btn" type="submit"><?php echo esc_html( $args['button'] ); ?></button>
			</div>

			<?php if ( $args['steps'] ) : ?>
			</fieldset>
			<p class="faint step-foot">
				<?php esc_html_e( 'Your details are only used to send your free redesign concept. No spam, and we do not pass them on.', 'smilecreative' ); ?>
			</p>
			<?php endif; ?>
		</form>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'smile_enquiry', 'sc_enquiry_form' );

/**
 * The "what are you struggling with" options on the redesign form.
 *
 * Deliberately the same six Brendan already runs on the Meta funnel, because
 * the answers arrive in the lead record and the wording is what the ad audience
 * has already seen. Filterable so a campaign can ask something else.
 */
function sc_enquiry_pains() {
	return apply_filters(
		'sc_enquiry_pains',
		array(
			__( 'Outdated design', 'smilecreative' ),
			__( 'Not enough leads or enquiries', 'smilecreative' ),
			__( "It doesn't sound like us", 'smilecreative' ),
			__( 'Nobody finds us on Google', 'smilecreative' ),
			__( 'It looks wrong on phones', 'smilecreative' ),
			__( "Other issue (we'll check for you)", 'smilecreative' ),
		)
	);
}

/**
 * The subject options.
 */
function sc_enquiry_subjects() {
	return apply_filters(
		'sc_enquiry_subjects',
		array(
			__( 'A free homepage redesign', 'smilecreative' ),
			__( 'A new website', 'smilecreative' ),
			__( 'An existing website that needs work', 'smilecreative' ),
			__( 'Logo or branding', 'smilecreative' ),
			__( 'Print', 'smilecreative' ),
			__( 'Search marketing', 'smilecreative' ),
			__( 'Video', 'smilecreative' ),
			__( 'A digital contact page', 'smilecreative' ),
			__( 'Something else', 'smilecreative' ),
		)
	);
}

/**
 * Where the form posts to -- the current page, so a failure redisplays in place.
 */
function sc_enquiry_action_url() {
	return sc_current_url();
}

/**
 * Current URL, without query noise.
 */
function sc_current_url() {
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '/';
	$path = strtok( $path, '?' );
	return home_url( $path );
}

/**
 * Handle the post.
 *
 * Runs on template_redirect so the theme is loaded but nothing has been sent.
 */
function sc_enquiry_handle() {
	if ( empty( $_POST['sc_enquiry'] ) ) {
		return;
	}

	$fail = function ( $msg, $keep = array() ) {
		sc_enquiry_state(
			array(
				'type'    => 'err',
				'message' => $msg,
				'old'     => $keep,
			)
		);
	};

	if ( ! isset( $_POST['sc_enquiry_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['sc_enquiry_nonce'] ), 'sc_enquiry' ) ) {
		// Almost always an expired page rather than an attack, so say that.
		$fail( __( 'That page had been open a while and the form expired. Please send it again.', 'smilecreative' ) );
		return;
	}

	$data = array(
		'name'    => isset( $_POST['sc_name'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_name'] ) ) : '',
		'email'   => isset( $_POST['sc_email'] ) ? sanitize_email( wp_unslash( $_POST['sc_email'] ) ) : '',
		'phone'   => isset( $_POST['sc_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_phone'] ) ) : '',
		'subject' => isset( $_POST['sc_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['sc_subject'] ) ) : '',
		'message' => isset( $_POST['sc_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['sc_message'] ) ) : '',
		'source'  => isset( $_POST['sc_source'] ) ? esc_url_raw( wp_unslash( $_POST['sc_source'] ) ) : '',
	);

	/*
	 * The two-step redesign form asks for the web address and the pain points
	 * before it asks who they are. Both are folded into the message rather than
	 * given columns of their own: the table, the CSV export and the notification
	 * email then carry them with no migration and nothing else to keep in step.
	 */
	$prefix = array();

	$site = isset( $_POST['sc_url'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['sc_url'] ) ) ) : '';
	if ( '' !== $site ) {
		if ( ! preg_match( '~^https?://~i', $site ) ) {
			$site = 'https://' . $site;   // people type their address without the scheme
		}
		$prefix[] = sprintf( 'Website: %s', esc_url_raw( $site ) );
	}

	$pains = isset( $_POST['sc_pain'] ) ? (array) wp_unslash( $_POST['sc_pain'] ) : array();
	$pains = array_values( array_intersect( array_map( 'sanitize_text_field', $pains ), sc_enquiry_pains() ) );
	if ( $pains ) {
		$prefix[] = sprintf( 'Struggling with: %s', implode( '; ', $pains ) );
	}

	if ( $prefix ) {
		$data['message'] = implode( "\n", $prefix )
			. ( '' !== $data['message'] ? "\n\n" . $data['message'] : '' );
	}

	// Spam gates. Both are silent -- a bot is told it succeeded so it stops
	// retrying, and nothing is written or sent.
	$hp   = isset( $_POST['sc_website'] ) ? trim( wp_unslash( $_POST['sc_website'] ) ) : '';
	$then = isset( $_POST['sc_t'] ) ? absint( $_POST['sc_t'] ) : 0;
	if ( '' !== $hp || ( $then && ( time() - $then ) < 3 ) ) {
		sc_enquiry_state(
			array(
				'type'    => 'ok',
				'message' => sc_enquiry_thanks(),
			)
		);
		return;
	}

	/*
	 * Third gate, added after one got through on 8 Sep: "Williamknoli", a
	 * telegra.ph jackpot link and nothing else. The honeypot and the time trap
	 * both passed it, because a bot driving a real browser engine leaves the
	 * hidden field alone and takes longer than three seconds.
	 *
	 * So this one reads the message instead of watching the behaviour. It is
	 * deliberately narrow -- a link shortener or a paste site, or a message that
	 * is little more than a wall of links. A genuine enquiry naming its own
	 * website is not caught by either.
	 *
	 * It STORES the enquiry either way. Nothing decides on its own that a
	 * customer does not exist; it only declines to put it in the inbox, and
	 * marks it so it is obvious on the Enquiries screen.
	 */
	$spam_hosts = apply_filters(
		'sc_enquiry_spam_hosts',
		array( 'telegra.ph', 't.me', 'bit.ly', 'tinyurl.com', 'cutt.ly', 'is.gd', 'rb.gy', 'shorturl.at' )
	);
	$haystack = strtolower( $data['message'] . ' ' . $data['name'] );
	$links    = preg_match_all( '~https?://~i', $data['message'] );
	$suspect  = $links >= 3;
	foreach ( $spam_hosts as $host ) {
		if ( false !== strpos( $haystack, strtolower( $host ) ) ) {
			$suspect = true;
			break;
		}
	}

	if ( '' === $data['name'] || ! is_email( $data['email'] ) ) {
		$fail( __( 'Please give a name and an email address we can reply to.', 'smilecreative' ), $data );
		return;
	}

	// ---- Store FIRST. This is the whole point of the file. ----
	if ( $suspect ) {
		$data['name'] = '[SPAM?] ' . $data['name'];
	}
	$id = sc_enquiry_store( $data );

	if ( $suspect ) {
		// Kept, visible in the admin, but not put in front of him. Told he
		// succeeded so he stops retrying.
		sc_enquiry_state(
			array(
				'type'    => 'ok',
				'message' => sc_enquiry_thanks(),
			)
		);
		return;
	}

	// ---- Then try to send. A mail failure no longer loses the enquiry. ----
	$sent  = false;
	$error = '';
	$to    = sc_enquiry_recipient();

	if ( $to ) {
		$from    = sc_enquiry_from();
		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			sprintf( 'From: %s <%s>', wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $from ),
			sprintf( 'Reply-To: %s <%s>', $data['name'], $data['email'] ),
		);

		$body = sprintf(
			"%s\n\nName:    %s\nEmail:   %s\nPhone:   %s\nAbout:   %s\n\n%s\n\n---\nSent from %s\nStored as enquiry #%d — it is in the site database whether or not this email arrives.\n",
			__( 'New enquiry from the website.', 'smilecreative' ),
			$data['name'],
			$data['email'],
			'' !== $data['phone'] ? $data['phone'] : '-',
			'' !== $data['subject'] ? $data['subject'] : '-',
			'' !== $data['message'] ? $data['message'] : '(no message)',
			$data['source'],
			$id
		);

		// Capture the reason rather than just the false, so a failure is
		// diagnosable later instead of being another silent drop.
		$catch = function ( $err ) use ( &$error ) {
			$error = is_wp_error( $err ) ? $err->get_error_message() : 'unknown';
		};
		add_action( 'wp_mail_failed', $catch );

		$sent = wp_mail(
			$to,
			sprintf(
				/* translators: 1: sender name, 2: subject */
				__( 'Website enquiry — %1$s (%2$s)', 'smilecreative' ),
				$data['name'],
				'' !== $data['subject'] ? $data['subject'] : __( 'general', 'smilecreative' )
			),
			$body,
			$headers
		);

		remove_action( 'wp_mail_failed', $catch );
	} else {
		$error = 'no recipient configured';
	}

	sc_enquiry_mark( $id, $sent, $error );

	sc_enquiry_state(
		array(
			'type'    => 'ok',
			'message' => sc_enquiry_thanks(),
		)
	);
}
add_action( 'template_redirect', 'sc_enquiry_handle' );

/**
 * The confirmation shown to the visitor.
 *
 * Deliberately identical whether or not the mail sent. The enquiry IS safely
 * recorded either way, so telling a customer "something went wrong" would be
 * both untrue and a reason for them to go elsewhere.
 */
function sc_enquiry_thanks() {
	return __( 'Thank you — that has come through. We will come back to you, usually the same working day.', 'smilecreative' );
}

/**
 * Write the row.
 */
function sc_enquiry_store( $data ) {
	global $wpdb;

	$wpdb->insert(
		$wpdb->prefix . 'sc_enquiries',
		array(
			'created_at' => current_time( 'mysql' ),
			'name'       => $data['name'],
			'email'      => $data['email'],
			'phone'      => $data['phone'],
			'subject'    => $data['subject'],
			'message'    => $data['message'],
			'source'     => $data['source'],
			'ip'         => sc_enquiry_ip(),
			'mail_sent'  => 0,
		),
		array( '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d' )
	);

	return (int) $wpdb->insert_id;
}

/**
 * Record whether the mail went, and why not.
 */
function sc_enquiry_mark( $id, $sent, $error ) {
	global $wpdb;

	if ( ! $id ) {
		return;
	}

	$wpdb->update(
		$wpdb->prefix . 'sc_enquiries',
		array(
			'mail_sent'  => $sent ? 1 : 0,
			'mail_error' => $sent ? null : substr( (string) $error, 0, 2000 ),
		),
		array( 'id' => $id ),
		array( '%d', '%s' ),
		array( '%d' )
	);
}

/**
 * Truncated IP, for spam triage only. The last octet is dropped so this is not
 * a personal identifier sitting in the table forever.
 */
function sc_enquiry_ip() {
	$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	if ( false !== strpos( $ip, '.' ) ) {
		$parts = explode( '.', $ip );
		if ( 4 === count( $parts ) ) {
			$parts[3] = '0';
			return implode( '.', $parts );
		}
	}
	return $ip;
}

/**
 * Where enquiries go. Set in the Customizer; falls back to the admin address
 * so a misconfiguration still lands somewhere real.
 */
function sc_enquiry_recipient() {
	$to = sc_opt( 'enquiry_to' );
	if ( ! is_email( $to ) ) {
		$to = get_option( 'admin_email' );
	}
	return is_email( $to ) ? $to : '';
}

/**
 * The envelope sender. Always on this domain -- see the file header.
 */
function sc_enquiry_from() {
	$from = sc_opt( 'enquiry_from' );
	if ( is_email( $from ) ) {
		return $from;
	}
	$host = wp_parse_url( home_url(), PHP_URL_HOST );
	$host = preg_replace( '/^www\./i', '', (string) $host );
	return 'no-reply@' . $host;
}

/**
 * Per-request form state.
 */
function sc_enquiry_state( $set = null ) {
	static $state = array(
		'type'    => '',
		'message' => '',
		'old'     => array(),
	);
	if ( null !== $set ) {
		$state = wp_parse_args( $set, $state );
	}
	return $state;
}
