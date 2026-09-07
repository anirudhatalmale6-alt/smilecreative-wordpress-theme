/**
 * Smile Creative -- the whole of the site's JavaScript.
 *
 * The old homepage loaded 25 separate scripts. This is the replacement, and it
 * is deliberately small enough to read in one sitting.
 */
(function () {
  'use strict';

  /* ---- Email guard ------------------------------------------------------
     Nothing readable sits in the served HTML. The address is base64 in a data
     attribute and assembled here at runtime. With JS off the link stays
     pointed at the enquiry form rather than going dead.
     Stops bulk harvesters, not anything that runs JS -- which is all any
     obfuscation has ever done. */
  document.querySelectorAll('.smile-eml[data-eml]').forEach(function (el) {
    try {
      var a = atob(el.getAttribute('data-eml'));
      el.setAttribute('href', 'mailto:' + a);
      el.textContent = a;
      el.removeAttribute('data-eml');
    } catch (e) {
      /* Leave the form fallback in place rather than showing a broken link. */
    }
  });

  /* ---- Work gallery ---------------------------------------------------- */
  var gal = document.getElementById('gal');
  document.querySelectorAll('[data-gal]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!gal) return;
      var card = gal.querySelector('.card');
      if (!card) return;
      var gap = parseFloat(getComputedStyle(gal).gap) || 16;
      var step = card.getBoundingClientRect().width + gap;
      gal.scrollBy({ left: (btn.getAttribute('data-gal') === 'next' ? 1 : -1) * step, behavior: 'smooth' });
    });
  });

  /* ---- Form: stop a double submit --------------------------------------
     A second click posts the enquiry twice. Cheap to prevent, and it keeps
     the stored record clean. */
  document.querySelectorAll('form.form').forEach(function (form) {
    form.addEventListener('submit', function () {
      var btn = form.querySelector('button[type=submit]');
      if (btn) {
        btn.disabled = true;
        btn.textContent = btn.getAttribute('data-sending') || 'Sending…';
      }
    });
  });

  /* ---- Legacy anchors -------------------------------------------------
     The old site used capitalised ids -- the main menu pointed at
     "/#Contact", and there are Facebook posts, bookmarks and third-party
     links out there carrying it. Fragment identifiers are CASE-SENSITIVE, so
     "#Contact" silently does nothing against a section with id="contact":
     the page loads at the top and the visitor assumes the link is broken.
     Match case-insensitively and scroll there instead. */
  var lastJump = -1;
  function resolveHash() {
    var raw = window.location.hash.replace(/^#/, '');
    if (!raw) return;
    if (document.getElementById(raw)) return;         // exact match, nothing to do
    /* Sections the old site had that this one folded into another. Same idea
       as the 301s in inc/redirects.php, for the in-page equivalent. */
    var alias = { team: 'who', about: 'who', portfolio: 'work', industries: 'services',
                  booking: 'contact', support: 'care' };
    var want = raw.toLowerCase();
    if (alias[want]) want = alias[want];
    var hit = null;
    var all = document.querySelectorAll('[id]');
    for (var i = 0; i < all.length; i++) {
      if (all[i].id.toLowerCase() === want) { hit = all[i]; break; }
    }
    if (!hit) return;
    // Only re-scroll if the visitor has not moved since OUR last jump --
    // otherwise a late retry yanks the page out from under someone reading.
    if (lastJump > -1 && Math.abs(window.scrollY - lastJump) > 4) return;
    hit.scrollIntoView();
    lastJump = Math.round(window.scrollY);
  }
  /* Run repeatedly, not once. This script is deferred, so the first call
     happens before the lazy images below the fold have laid out -- measured
     610px short on the first attempt, because the page grew underneath the
     jump. Retrying settles it, and the guard above means a visitor who has
     started scrolling is left alone. */
  resolveHash();
  window.addEventListener('load', resolveHash);
  [250, 750, 1500].forEach(function (ms) { setTimeout(resolveHash, ms); });
  window.addEventListener('hashchange', function () { lastJump = -1; resolveHash(); });

  /* ---- Scroll the confirmation into view -------------------------------
     After a post the page reloads at the top and the "thank you" is halfway
     down, so it reads as though nothing happened. */
  var msg = document.querySelector('.formmsg');
  if (msg && !window.location.hash) {
    msg.scrollIntoView({ block: 'center' });
  }
})();

/* ---------------------------------------------------------------------------
   Aftercare page: monthly / yearly prices.

   The numbers for both periods are already in the HTML as data attributes, so
   this only swaps which one is displayed -- with JavaScript off the page still
   shows the monthly price and every plan and feature is still readable. The old
   page hid its yearly table behind a script and showed nothing at all without
   it.
   --------------------------------------------------------------------------- */
(function () {
  var bills = document.querySelectorAll('.bill-btn');
  if (!bills.length) { return; }

  var nums = document.querySelectorAll('.plan-num');
  var pers = document.querySelectorAll('.plan-per');

  Array.prototype.forEach.call(bills, function (btn) {
    btn.addEventListener('click', function () {
      var yearly = btn.getAttribute('data-bill') === 'yearly';

      Array.prototype.forEach.call(bills, function (b) { b.classList.remove('is-on'); });
      btn.classList.add('is-on');

      Array.prototype.forEach.call(nums, function (n) {
        var v = n.getAttribute(yearly ? 'data-yearly' : 'data-monthly');
        if (v) { n.textContent = v; }
      });
      Array.prototype.forEach.call(pers, function (p) {
        p.textContent = yearly ? '/year' : '/month';
      });
    });
  });

  /* Carry the chosen plan into the enquiry form, so an enquiry from this page
     arrives saying which plan they clicked rather than "general". */
  Array.prototype.forEach.call(document.querySelectorAll('[data-plan]'), function (a) {
    a.addEventListener('click', function () {
      var msg = document.querySelector('#ask textarea');
      var plan = a.getAttribute('data-plan');
      if (msg && !msg.value) {
        msg.value = 'I am interested in the ' + plan + ' plan. ';
      }
    });
  });
}());
