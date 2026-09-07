SMILE CREATIVE -- WordPress theme v1.0.0
=========================================

WHAT THIS IS
A hand-built WordPress theme. No page builder, no addon plugins, no licence
key. Still WordPress -- you keep the admin, the editor, the media library, and
you can still show a client a login. What you lose is Elementor, the six addon
plugins, the 47 stylesheets, and the CSS-regeneration fault that took the old
site down twice in August.

INSTALL
1. Appearance > Themes > Add New > Upload Theme > choose this zip > Install.
2. DO NOT activate yet. Read the next section first.

BEFORE YOU ACTIVATE
Activating changes the live site immediately. If you want to see it first,
use a theme-preview: Appearance > Themes > hover the theme > Live Preview.
That renders it against your real content without any visitor seeing it, and
nothing changes until you click Activate.

AFTER YOU ACTIVATE -- do these four, in this order
1. Appearance > Customize > "Smile Creative -- details".
   Set: "Enquiries are delivered to"  -> 2026@smilecreative.agency
        "Enquiries are sent FROM"     -> an address on THIS domain
        "Published email address"     -> studio@ (leave blank until it exists)

   The FROM address matters more than it looks. If a form sends as the
   visitor's address the message fails authentication at the far end and is
   discarded with no bounce. That is the most likely explanation for the old
   forms going quiet, and it survives a rebuild unless you set this.

2. Settings > Reading > set the homepage to a static page.
3. Appearance > Menus > create a menu, assign it to "Primary navigation".
   If you skip this the theme falls back to sensible section links.
4. Send yourself ONE test enquiry and confirm it arrives.

THE FORM
There is ONE form. It renders in the contact section automatically, and you
can drop it on any page with the shortcode:  [smile_enquiry]

Every submission is written to the database BEFORE any email is attempted.
If the mail fails, the enquiry is still on record under "Enquiries" in the
admin sidebar, and a red warning appears across the dashboard telling you
delivery is broken. On the old site a mail failure meant the customer was
simply gone with nothing to find afterwards -- that cannot happen now.

There is also a CSV export, so your leads are portable and you are never
locked into this theme to get at them.

STILL WORTH DOING (not theme work)
Install an SMTP plugin and send through authenticated SMTP rather than PHP
mail(). You already have Brevo verified against the domain -- free tier,
WordPress plugin, and it gives you a delivery log. Without it there is no
record of a send at all, which is how the last failure ran for weeks unseen.

CONTENT
Work         -- add projects. Featured image must be a live screenshot at full
                width. No desk mockup, no frame, no watermark.
Client logos -- the strip under the hero.
Reviews      -- the Google reviews. Reproduce them word for word, typos and
                all. They are deliberately NOT marked up as schema: Google
                requires review markup to come from reviews you collected
                yourself, and marking up copied ones risks the rich result.

REDIRECTS
/about/, /team/, /contact/, /portfolio/ and a few others 301 to the relevant
section of the new homepage. They only fire on a 404, so if you ever
republish one of those pages the redirect stops interfering by itself.
Edit the list in inc/redirects.php.

THE ANIMATED HERO
Appearance > Customize > "Animated map in the hero". Turn it off for a plain
typographic hero -- the script is then not loaded at all.
Every point is a real client location. Edit the list in inc/helpers.php
(sc_places). Points where you can name the business are worth more than the
ones that just say "client work".

The warm wash behind it is standing in for a PHOTOGRAPH. That is the layer
the reference site uses and it is where the warmth comes from -- the geometry
on its own is cold. It is waiting for one of the images we discussed.

-------------------------------------------------------------------------
v1.1.0

THE FREE HOMEPAGE REDESIGN OFFER is now on the homepage, as its own band
straight after the work gallery, and it is the primary call to action in
both the hero and the header.

It is placed AFTER the work on purpose. "Free homepage redesign" read cold
is a gimmick; read straight after six real client sites it is confidence.

It uses the SAME form, with "A free homepage redesign" added as a subject.
No second form and no second inbox.

LEGACY ANCHORS. Your old menu pointed at /#Contact with a capital C, and
fragment identifiers are case-sensitive -- "#Contact" does nothing against
a section with id="contact". Any old Facebook post, bookmark or directory
link carrying one would have landed at the top of the page and looked
broken. site.js now matches case-insensitively, and maps the sections that
were folded into others: #Team and #About go to the About block,
#Portfolio to the work, #Industries to services, #Booking to contact,
#Support to aftercare.
