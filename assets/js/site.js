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

  /* ---- Scroll the confirmation into view -------------------------------
     After a post the page reloads at the top and the "thank you" is halfway
     down, so it reads as though nothing happened. */
  var msg = document.querySelector('.formmsg');
  if (msg && !window.location.hash) {
    msg.scrollIntoView({ block: 'center' });
  }
})();
