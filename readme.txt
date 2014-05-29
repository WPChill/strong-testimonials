=== Strong Testimonials ===
Contributors: cdillon27
Donate link: http://www.wpmission.com/donate
Tags: testimonials
Requires at least: 3.0.1
Tested up to: 3.9.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Collect and display testimonials from your customers. This is a fork of GC Testimonials that adds many new features.

== Description ==

**Show testimonials**

* with a variety of shortcodes
* in a widget with transition effects

**Collect testimonials**

* through a form with selectable anti-spam measures

**Manage testimonials**

* using the standard WordPress post editor, including Featured Images
* organize into categories

This is based on the very popular [GC Testimonials](http://wordpress.org/plugins/gc-testimonials/). I have been very active in that plugin's support forum because I like its simplicity, I used it on many sites, and I love to fix things.

Strong Testimonials aims to pick up where GC Testimonials left off while maintaining its simplicity.

= New Features and Fixes =

* General
	* Numerous code optimizations and PHP notice & error fixes.
	* Using native WordPress functions and style, a best practice that makes it faster and futureproof and helps it play well with others.
	* Multisite compatible with [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin. See FAQ below.
* Display
	* Simplified CSS.
	* Process shortcodes in content (i.e. nested shortcodes).
	* Thumbnail theme support specifically for testimonials, not all posts.
* Admin
	* Settings retained upon plugin deactivation.
	* Allow client's name and email to be blanked out.
	* More efficient options storage means it's faster & easier to upgrade.
	* Improved settings & shortcodes pages.
	* Added category counts.
* Submission form
	* Updated [jQuery validation plugin](http://jqueryvalidation.org/), conditionally loaded from CDN (speed!).
	* Client-side validation *plus* server-side validation, a best practice.
	* CAPTCHA options (more info below).
* Pagination
	* Scrolls to the top when a new page is selected.
* Widget
	* Updated [jQuery cycle plugin](http://jquery.malsup.com/cycle2/), conditionally loaded from CDN (speed!).
	* Break on word-boundary, not mid-word (e.g. "John is an assuring . . ." not "John is an ass . . .").
	* Improved widget settings.
	* Order by: Random, Newest first, Oldest first.
	* Load stylesheet in standard order instead of in the footer to allow your theme's stylesheet to take precedence.

= Anti-spam =

I prefer a modular approach. Instead of reinventing the anti-spam wheel, I decided to integrate other plugins to do the heavy lifting. Because spam is heavy.

To add CAPTCHA to the testimonial submission form:

1. install one of these supported plugins,
1. select that plugin on the `Testimonials > Settings` page.

Currently supported CAPTCHA plugins:

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
1. I simplifed the CSS so it will inherit as much from your theme as your other content and widgets, so you may be able to trim down any custom CSS.
1. I will gladly help you sort it out.

== Screenshots ==

1. A sample page of three testimonials including photos.
2. The widget with a character limit of 200.
3. The widget without a character limit.
4. The testimonial submission form without CAPTCHA.
5. The math CAPTCHA option at the end of the form.
6. The reCAPTCHA option at the end of the form.
7. The settings page.
8. All the shortcode options.
9. The testimonials admin page.
10. Adding or editing a testimonial.
11. Adding or editing categories.
12. The widget settings in Cycle mode.
13. The same widget in Static mode.

== Changelog ==

= 1.4.3 =
* Improved compatibility with earlier versions of PHP.

= 1.4.2 =
* Fixed bug: missing categories in admin testimonials table.

= 1.4.1 =
* Fixed bug in category filter in the widget.

= 1.4 =
* Initial version, a fork of GC Testimonials 1.3.2.

== Upgrade Notice ==

= 1.4.3 = 
Improved compatibility with earlier versions of PHP.

= 1.4.2 =
Fixed a minor bug that did not show multiple categories in the admin testimonials list.

= 1.4.1 =
Fixed a minor bug if a category was selected in the widget settings.
