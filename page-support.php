<?php
/**
 * Template Name: Aftercare & hosting
 *
 * The care-plan page, rebuilt in the theme.
 *
 * It was the last page still drawing its own look from Elementor, so once the
 * new header and footer went on it stood out badly -- white panels, green
 * ticks, a teal block and two paragraphs rendering as pale text on white.
 *
 * The copy is Brendan's, moved across as he wrote it. Two things did NOT come
 * across, both deliberate:
 *
 *   - The old page's yearly table disagreed with its monthly table about what
 *     each plan includes (weekly vs 2-weekly backups, 5GB vs 500MB bandwidth).
 *     A plan is one thing; only the price changes with the billing period. So
 *     the features live in ONE array here and both prices point at it, which
 *     means the two can never drift apart again.
 *   - Three of the four yearly plans published a bullet reading "Pricing table
 *     list item" -- an Elementor placeholder row nobody filled in. Gone.
 *
 * @package smilecreative
 */

get_header();

/*
 * Prices as they stand on the live site, monthly and yearly. The yearly figures
 * are a flat 10% off twelve months for the three support plans; Just Host is
 * £110 against £119.64, which is 8%. Not my arithmetic to correct -- these are
 * the numbers he charges.
 */
$sc_plans = array(
	array(
		'name'     => __( 'Just Host', 'smilecreative' ),
		'sub'      => __( 'No support', 'smilecreative' ),
		'monthly'  => '9.97',
		'yearly'   => '110.00',
		'features' => array(
			__( '.co.uk domain name', 'smilecreative' ),
			__( 'Website hosting', 'smilecreative' ),
			__( 'Monthly backups', 'smilecreative' ),
			__( '500 MB bandwidth', 'smilecreative' ),
			__( '5 × 1GB email addresses', 'smilecreative' ),
			__( 'Immunify malware protection', 'smilecreative' ),
		),
	),
	array(
		'name'     => __( 'Basic', 'smilecreative' ),
		'sub'      => __( 'Support', 'smilecreative' ),
		'monthly'  => '44.00',
		'yearly'   => '475.00',
		'features' => array(
			__( '.co.uk domain name', 'smilecreative' ),
			__( 'Website hosting', 'smilecreative' ),
			__( 'Weekly backups', 'smilecreative' ),
			__( '5 GB bandwidth', 'smilecreative' ),
			__( '10 × 1GB email addresses', 'smilecreative' ),
			__( 'Immunify malware protection', 'smilecreative' ),
			__( '1 hour of content updates', 'smilecreative' ),
			__( 'Site security hardening', 'smilecreative' ),
			__( 'Core, plugin and theme updates', 'smilecreative' ),
			__( 'Uptime monitoring', 'smilecreative' ),
			__( 'Performance scans', 'smilecreative' ),
		),
	),
	array(
		'name'     => __( 'Standard', 'smilecreative' ),
		'sub'      => __( 'Support', 'smilecreative' ),
		'monthly'  => '87.00',
		'yearly'   => '940.00',
		'popular'  => true,
		'features' => array(
			__( '.co.uk domain name', 'smilecreative' ),
			__( 'Website hosting', 'smilecreative' ),
			__( 'Daily backups', 'smilecreative' ),
			__( '20 GB bandwidth', 'smilecreative' ),
			__( '20 × 1GB email addresses', 'smilecreative' ),
			__( 'Immunify malware protection', 'smilecreative' ),
			__( '2 hours of content updates', 'smilecreative' ),
			__( 'Site security hardening', 'smilecreative' ),
			__( 'Core, plugin and theme updates', 'smilecreative' ),
			__( 'Uptime monitoring', 'smilecreative' ),
			__( 'Performance scans', 'smilecreative' ),
			__( 'Phone support', 'smilecreative' ),
			__( 'Email deliverability monitoring', 'smilecreative' ),
		),
	),
	array(
		'name'     => __( 'Premium', 'smilecreative' ),
		'sub'      => __( 'Support', 'smilecreative' ),
		'monthly'  => '175.00',
		'yearly'   => '1890.00',
		'features' => array(
			__( '.co.uk domain name', 'smilecreative' ),
			__( 'Website hosting', 'smilecreative' ),
			__( 'Daily backups', 'smilecreative' ),
			__( '50 GB bandwidth', 'smilecreative' ),
			__( '50 × 1GB email addresses', 'smilecreative' ),
			__( 'Immunify malware protection', 'smilecreative' ),
			__( '5 hours of content updates', 'smilecreative' ),
			__( 'Site security hardening', 'smilecreative' ),
			__( 'Core, plugin and theme updates', 'smilecreative' ),
			__( 'Uptime monitoring', 'smilecreative' ),
			__( 'Performance scans', 'smilecreative' ),
			__( 'Phone support', 'smilecreative' ),
			__( 'Email deliverability monitoring', 'smilecreative' ),
		),
	),
);

$sc_why = array(
	array(
		__( 'Based in Northern Ireland, hosted in the UK', 'smilecreative' ),
		__( 'A local team serving Belfast and Northern Ireland, on hosting in Coventry. Local service, national performance.', 'smilecreative' ),
	),
	array(
		__( 'No tech stress — we handle it all', 'smilecreative' ),
		__( 'Plugin updates, broken features, malware. Leave it with us and it gets dealt with.', 'smilecreative' ),
	),
	array(
		__( 'Fixed monthly pricing, no surprises', 'smilecreative' ),
		__( 'Clear pricing with no hidden fees. A minor update or an urgent fix, you pay the same rate either way.', 'smilecreative' ),
	),
	array(
		__( 'No lock-in contracts', 'smilecreative' ),
		__( 'We would rather earn it every month. Cancel any time, with no pressure and no exit fee.', 'smilecreative' ),
	),
);

$sc_included = array(
	__( 'Regular WordPress core, theme and plugin updates', 'smilecreative' ),
	__( 'Malware monitoring and security hardening', 'smilecreative' ),
	__( 'Daily site backups', 'smilecreative' ),
	__( 'Uptime and performance tracking', 'smilecreative' ),
	__( 'Priority technical support', 'smilecreative' ),
);

$sc_faq = array(
	array(
		__( 'What is WordPress maintenance, and why do I need it?', 'smilecreative' ),
		__( 'It is the regular, proactive management and updating of your website so it stays safe and runs properly. You need it because it is what stops the site going offline, getting hacked, or developing the sort of technical fault that gets in the way of your business.', 'smilecreative' ),
	),
	array(
		__( 'What happens if I do not maintain the site?', 'smilecreative' ),
		__( 'It can crash, be hacked, or develop technical faults. That costs you traffic and conversions, and it costs your reputation, which is the harder one to get back.', 'smilecreative' ),
	),
	array(
		__( 'Can I do the maintenance myself?', 'smilecreative' ),
		__( 'Yes. It takes time and a certain amount of expertise. If you are busy running the business, or the technical side is not your ground, it is worth handing over.', 'smilecreative' ),
	),
	array(
		__( 'What are your hours and response times?', 'smilecreative' ),
		__( 'Monday to Friday, 9am to 5pm. We answer every request within six working hours and usually much faster, and we aim to resolve helpdesk requests within eight working hours. Larger improvement requests are typically done within twenty-four.', 'smilecreative' ),
	),
	array(
		__( 'Why Smile Creative rather than anyone else?', 'smilecreative' ),
		__( 'Because you get on with your business while we keep the site secure, updated and quick — and because you deal with the person doing the work rather than a ticket queue.', 'smilecreative' ),
	),
	array(
		__( 'What is actually included?', 'smilecreative' ),
		__( 'Software updates, security monitoring, backups, troubleshooting and the content updates included in your plan. If you are not sure which plan fits, ask and we will tell you honestly — including if the answer is the cheapest one.', 'smilecreative' ),
	),
);
?>

<section class="hero" style="padding-bottom:clamp(2rem,4vw,3rem)">
	<div class="wrap">
		<div class="copy">
			<span class="label"><?php esc_html_e( 'Aftercare & hosting', 'smilecreative' ); ?></span>
			<h1 style="max-width:20ch"><?php esc_html_e( 'Someone keeping an eye on it,', 'smilecreative' ); ?> <em><?php esc_html_e( 'every week.', 'smilecreative' ); ?></em></h1>
			<p class="lead" style="margin-top:1.6rem">
				<?php esc_html_e( 'A website is not finished when it goes live. Plugins go out of date, certificates expire, forms quietly stop delivering. We look after WordPress and WooCommerce sites for businesses in Belfast, across Northern Ireland, and well beyond — updates, backups, security and the small changes you need doing, on a fixed monthly price.', 'smilecreative' ); ?>
			</p>
			<div class="cta">
				<a class="btn" href="#plans"><?php esc_html_e( 'See the plans', 'smilecreative' ); ?></a>
				<a class="btn ghost" href="#ask"><?php esc_html_e( 'Ask a question first', 'smilecreative' ); ?></a>
			</div>
		</div>
	</div>
</section>

<section class="care">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Why us', 'smilecreative' ); ?></span>
		<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Four reasons, and none of them is a discount.', 'smilecreative' ); ?></h2>
		<div class="why4">
			<?php foreach ( $sc_why as $sc_i => $sc_w ) : ?>
				<div class="why4-item">
					<span class="why4-n"><?php echo esc_html( str_pad( (string) ( $sc_i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<h3><?php echo esc_html( $sc_w[0] ); ?></h3>
					<p class="muted"><?php echo esc_html( $sc_w[1] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section>
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'What you get', 'smilecreative' ); ?></span>
			<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Safe, quick, and up to date.', 'smilecreative' ); ?></h2>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'Your website or shop is usually the first impression the business makes. Left alone it gets vulnerable — old plugins, security holes, things that stop working with each other — and that costs traffic, customers and sales before anybody notices.', 'smilecreative' ); ?>
			</p>
		</div>
		<div>
			<ul class="ticks">
				<?php foreach ( $sc_included as $sc_item ) : ?>
					<li><?php echo esc_html( $sc_item ); ?></li>
				<?php endforeach; ?>
			</ul>
			<p class="muted" style="margin-top:1.6rem">
				<?php esc_html_e( 'Whether you are in Derry, Newry, Lisburn, Bangor or anywhere across the UK and Ireland, it is the same service and the same phone number.', 'smilecreative' ); ?>
			</p>
		</div>
	</div>
</section>

<section id="plans" class="reviews">
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Plans', 'smilecreative' ); ?></span>
		<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Pick the one that fits. Change it whenever.', 'smilecreative' ); ?></h2>
		<p class="lead" style="margin-top:1rem">
			<?php esc_html_e( 'Every price on this page is the price. Paying yearly saves you roughly ten per cent, and there is no contract either way.', 'smilecreative' ); ?>
		</p>

		<div class="billing" role="group" aria-label="<?php esc_attr_e( 'Billing period', 'smilecreative' ); ?>">
			<button type="button" class="bill-btn is-on" data-bill="monthly"><?php esc_html_e( 'Monthly', 'smilecreative' ); ?></button>
			<button type="button" class="bill-btn" data-bill="yearly"><?php esc_html_e( 'Yearly', 'smilecreative' ); ?></button>
		</div>

		<div class="plans">
			<?php foreach ( $sc_plans as $sc_p ) : ?>
				<div class="plan<?php echo ! empty( $sc_p['popular'] ) ? ' plan-pop' : ''; ?>">
					<?php if ( ! empty( $sc_p['popular'] ) ) : ?>
						<span class="plan-flag"><?php esc_html_e( 'Most chosen', 'smilecreative' ); ?></span>
					<?php endif; ?>
					<h3><?php echo esc_html( $sc_p['name'] ); ?></h3>
					<span class="plan-sub"><?php echo esc_html( $sc_p['sub'] ); ?></span>
					<p class="plan-price">
						<span class="plan-cur">£</span><span class="plan-num"
							data-monthly="<?php echo esc_attr( $sc_p['monthly'] ); ?>"
							data-yearly="<?php echo esc_attr( $sc_p['yearly'] ); ?>"><?php echo esc_html( $sc_p['monthly'] ); ?></span><span class="plan-per"><?php esc_html_e( '/month', 'smilecreative' ); ?></span>
					</p>
					<ul class="ticks">
						<?php foreach ( $sc_p['features'] as $sc_f ) : ?>
							<li><?php echo esc_html( $sc_f ); ?></li>
						<?php endforeach; ?>
					</ul>
					<a class="btn<?php echo empty( $sc_p['popular'] ) ? ' ghost' : ''; ?>" href="#ask"
						data-plan="<?php echo esc_attr( $sc_p['name'] ); ?>"><?php esc_html_e( 'Choose this plan', 'smilecreative' ); ?></a>
				</div>
			<?php endforeach; ?>
		</div>

		<p class="faint" style="margin-top:1.8rem;font-size:.85rem">
			<?php esc_html_e( 'Already have hosting elsewhere? We can look after the site where it sits — ask and we will price it.', 'smilecreative' ); ?>
		</p>
	</div>
</section>

<section>
	<div class="wrap">
		<span class="label"><?php esc_html_e( 'Questions', 'smilecreative' ); ?></span>
		<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'The ones we are actually asked.', 'smilecreative' ); ?></h2>
		<div class="qa" style="margin-top:2.4rem">
			<?php foreach ( $sc_faq as $sc_q ) : ?>
				<details>
					<summary><?php echo esc_html( $sc_q[0] ); ?></summary>
					<p class="muted"><?php echo esc_html( $sc_q[1] ); ?></p>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section id="ask">
	<div class="wrap split">
		<div>
			<span class="label"><?php esc_html_e( 'Talk to us', 'smilecreative' ); ?></span>
			<h2 class="big" style="margin-top:.8rem"><?php esc_html_e( 'Tell us what the site is and we will tell you what it needs.', 'smilecreative' ); ?></h2>
			<p class="lead" style="margin-top:1.2rem">
				<?php esc_html_e( 'Including if the answer is the cheapest plan, or none of them. We would rather you were on the right one than the dearest one.', 'smilecreative' ); ?>
			</p>
			<div class="deets">
				<span class="label"><?php esc_html_e( 'Direct', 'smilecreative' ); ?></span>
				<p><?php echo wp_kses_post( sc_tel_link() ); ?></p>
				<span class="label"><?php esc_html_e( 'Hours', 'smilecreative' ); ?></span>
				<p class="muted"><?php echo esc_html( wp_strip_all_tags( sc_opt( 'hours' ) ) ); ?></p>
			</div>
		</div>
		<div>
			<?php echo sc_enquiry_form( array( 'id' => 'support', 'button' => __( 'Send enquiry', 'smilecreative' ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
		</div>
	</div>
</section>

<?php
get_footer();
