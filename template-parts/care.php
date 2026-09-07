<?php
/**
 * Aftercare, and the real care-plan prices.
 *
 * The audit said "zero prices anywhere across 26 pages". That was wrong: the
 * old Support page published a full list. The problem was that the only page
 * carrying a number was one nothing linked to.
 *
 * @package smilecreative
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<section id="care" class="care">
  <div class="wrap">
    <span class="label">Looking after it</span>
    <h2 class="big" style="margin-top:.8rem">A website is not finished when it launches.</h2>
    <p class="lead" style="margin-top:1.2rem">Most of the trouble we get called about is on
      sites somebody built and walked away from. Every site we look after gets the same
      baseline, and we would rather tell you plainly what that means than call it a
      &ldquo;care plan&rdquo;.</p>
    <div class="carelist">
      <div class="careitem">
        <h3>Kept up to date</h3>
        <p>WordPress, plugins and PHP kept current, checked after every update rather than
          set to auto and hoped for.</p>
      </div>
      <div class="careitem">
        <h3>Backed up and monitored</h3>
        <p>Off-site backups, malware scanning, and a firewall that is actually configured.
          If something breaks we usually know before you do.</p>
      </div>
      <div class="careitem">
        <h3>Spam kept out of your inbox</h3>
        <p>Contact forms filtered on four layers, and anything blocked is quarantined rather
          than deleted, so a real enquiry can never vanish silently.</p>
      </div>
    </div>
    <div class="pricerow">
      <span><b>&pound;9.97</b> a month &mdash; hosting, domain and backups only</span>
      <span><b>&pound;87</b> a month &mdash; the same, with updates, monitoring and support</span>
      <span class="faint">Full plans on the Support page</span>
    </div>
  </div>
</section>
