=== Strong Testimonials ===
Contributors: cdillon27
Donate link: http://wpmission.com/donate
Tags: testimonials
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: 1.4
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Collect and display testimonials from your customers. This is a fork of GC Testimonials that adds many new features.

== Description ==

Show testimonials

* with a variety of shortcodes
* in a widget with slide effects

Collect testimonials

* through a form with anti-spam measures

Manage testimonials

* using the standard WordPress post editor, including Featured Images
* organize into categories

This is based on the very popular [GC Testimonials](http://wordpress.org/plugins/gc-testimonials/). I have been very active in that plugin's support forum because I like it's simplicity, I used it on many sites, and I love to fix things.

Strong Testimonials aims to pick up where GC Testimonials left off while maintaining its simplicity.

= New Features =

Numerous code optimizations and PHP notice & error fixes.

Simplified CSS.

Process shortcodes in content (i.e. nested shortcodes).

Thumbnail theme support specifically for testimonials, not all posts.

Using native WordPress functions and style, a best practice that makes it faster and futureproof and helps it play well with others.

Multisite compatible with [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin. See FAQ below.

Admin

* settings retained upon plugin deactivation
* allow client's name and email to be blanked out
* more efficient options storage means it's faster & easier to upgrade
* improved settings & shortcodes pages
* added category counts

Submission form

* updated [jQuery validation plugin](http://jqueryvalidation.org/), conditionally loaded from CDN (speed!)
* client-side validation *plus* server-side validation, a best practice
* CAPTCHA options (more info below)

Pagination

* scrolls to the top when a new page is selected

Widget

* updated [jQuery cycle plugin](http://jquery.malsup.com/cycle2/), conditionally loaded from CDN (speed!)
* break on word-boundary, not mid-word (e.g. "John is an assuring . . ." not "John is an ass . . .")
* improved widget settings
* order by: Random, Newest first, Oldest first
* loading stylesheet in standard order instead of in the footer to allow your theme's stylesheet to take precedence

= Anti-spam =

I prefer a modular approach. Instead of reinventing the anti-spam wheel, I decided to integrate other plugins to do the heavy lifting. Because spam is heavy.

To add CAPTCHA to the testimonial submission form:

1. install one of these supported plugins,
1. select that plugin on the `Testimonials > Settings` page.

CAPTCHA plugins supported:

* [Captcha](http://wordpress.org/plugins/captcha/) by BestWebSoft
* [Simple reCAPTCHA](http://wordpress.org/plugins/simple-recaptcha) by WPMission (that's me!)

Notes

* If the currently selected CAPTCHA plugin is deactivated, the setting will revert to "None".
* I plan to add an option for Akismet soon.
* [Contact me](http://www.wpmission.com/contact) if you have a plugin recommendation.

= Future =

This plugin is under active development and all ideas and feedback are welcome.

= Translations =

Can you help? [Contact me](http://www.wpmission.com/contact/).

== Installation ==

The normal way.

* Upload `/strong-testimonials` to the `/wp-content/plugins/` directory.

or

* Search for "Strong Testimonials" on your `Plugins > Add New` page.

then

* Activate the plugin through the `Plugins` menu.

Grab a shortcode from the `Testimonials > Shortcodes` page.

== Frequently Asked Questions ==

= Is this multisite compatible? =

Yes, but I highly recommend first installing the [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin when adding Strong Testimonials to a multisite installation. That plugin will deftly handle the plugin activation process, thus ensuring each site has the default settings.

= Will it import my existing testimonials? =

Not yet, but the next version will have an import function. If you have a ton of testimonials, you may want to wait.

= What about Akismet for the submission form? =

I plan to integrate Akismet soon.

= I modified my copy of GC Testimonials and I want to keep my features. =

I will gladly help add your modifications to a custom version of Strong Testimonials. In reality, I will likely steal your features and add them to a new version. You have been warned.

= I spent a lot of time adjusting the CSS to get GC Testimonials to work with my theme. Can I expect to do the same with this version? =

(a) You're not alone.

(b) I simplifed the CSS so it will inherit as much from your theme as your other content and widgets. So you may be able to trim down any custom CSS.

(c) I will gladly help you sort it out.

== Changelog ==

= 1.4 =
* Initial version, a fork of GC Testimonials 1.3.2.
