=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider
Requires at least: 3.5
Tested up to: 4.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A powerful testimonial manager.


== Description ==

Integrate testimonials in 3 steps:

1. Add a form to a page to **gather** them.
2. Use the editor to **review** and organize them.
3. **Display** them using shortcodes or a widget.

[Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Demos](http://demos.wpmission.com/strong-testimonials/) | [Feature Requests](http://www.wpmission.com)


**What's New**

The `[strong]` shortcode that does it all.

Template functions and a default template file.

An improved POT file for translations.


**Primary Features**

A submission form with custom fields and anti-spam options.

Support for excerpts, featured images, and categories.

Multiple shortcode options including Cycle, All, Random, and Single.

Multiple widget options including category selection and random order.


**Other Features**

Administrator notification upon new testimonial submission.

Skip loading the included stylesheets if you want to style it from the ground up.

Tested in many popular themes including the [problematic](http://chrislema.com/wordpress-obesity/) [Avada](http://www.fklein.info/2013/05/overloaded-theme-problems/).

Ready for translations (i18n).


**Spam Control**

Two methods are available:

1. Honeypot (built-in)
2. Captcha (via plugins)
    * [Captcha](http://wordpress.org/plugins/captcha/)
    * [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/)
    * [Simple reCaptcha](http://wordpress.org/plugins/simple-recaptcha)


**Recommended**

These plugins work well with Strong Testimonials and add some nice features.

* [Admin Menu Post List](http://wordpress.org/plugins/admin-menu-post-list/)
* [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) (multisite)
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/)
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/)


**Known Conflicts**

* [Page Builder by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/) - The widget settings are not being saved properly in the drag-and-drop builder.
* [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) - The administrator notification email may not be sent.


**Translations**

In version 1.13:

* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many thanks to these translators. Can you help? [Contact me](http://www.wpmission.com/contact/).


== Installation ==

Option A:

1. Go to `Plugins > Add New`.
1. Search for "strong testimonials".
1. Click "Install Now".

Option B: 

1. Download the zip file.
1. Unzip it on your hard drive.
1. Upload the `strong-testimonials` folder to the `/wp-content/plugins/` directory.

Option C:

1. Download the zip file.
1. Upload the zip file via `Plugins > Add New > Upload`.

Finally, activate the plugin.

<em>The default settings are designed to help you hit the ground running.</em>

* Add the **submission form shortcode** to a page to start gathering testimonials. 
* You can **change the fields** on the form in the fields editor. 
* Use **shortcodes and widgets** to display testimonials in various ways. 

View some examples on the [demo site](http://demos.wpmission.com/strong-testimonials/).

For help, use the [support forum](http://wordpress.org/support/plugin/strong-testimonials) or [contact me](http://www.wpmission.com/contact/).


== Frequently Asked Questions ==

[Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Demos](http://demos.wpmission.com/strong-testimonials/) | [Feature Requests](http://www.wpmission.com)

= Are there templates? How do I change the look of the testimonials? =

The new `[strong]` shortcode uses a template file that can be copied into your theme. You can create multiple template files and include them using a shortcode attribute; e.g. `template="my-template"`. Template functions are also available for adding testimonial fields to new or existing templates.

The original `[wpmtst]` shortcodes do not use template files, but the stylesheets are largely structural so you can add CSS in your theme or [custom CSS](http://wordpress.org/plugins/simple-custom-css/). In fact, I like to skip loading the stylesheets (in `Testimonials > Settings`) to see how they look in the theme, then style up from there. Future versions will have pre-built templates with adjustable colors, borders, etc.

= How can I change "testimonial" to "review", for example? =

Instructions are [here](https://wordpress.org/support/topic/how-to-change-the-slug).

= How can I reorder my testimonials? =

Until I can build this into the plugin, try [Post Types Order](https://wordpress.org/plugins/post-types-order/) by Nsp Code (which lists them on a separate admin page) or [Simple Custom Post Order](https://wordpress.org/plugins/simple-custom-post-order/) by Sameer Humagain (which works directly in the admin list). Or you can adjust each testimonial's publish date manually.

= How can I change the fields on the form? =

On the `Testimonials > Fields` page, there is a field editor where you can add or remove fields, change field details, and drag-n-drop to reorder them. You can also restore the default fields. 

If you have ever used the Advanced Custom Fields or Custom Field Suite plugins, the editor will be very familiar. Here is a full [tutorial](http://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/).

= How do I add a Captcha to the form? =

Select one of the supported plugins on the `Testimonials > Settings` page. Use the link to each plugin's download page to install it.

If the currently selected Captcha plugin is deactivated, the setting will revert to "none".

If your site relies on a partner plugin like this, and that plugin becomes buggy or abandoned, I will adopt it or integrate it to keep your site running.

[Contact me](http://www.wpmission.com/contact) to recommend another method or plugin.

= How can I change which client fields appear below the testimonial? =

The new `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

For the original `[wpmtst]` shortcodes, go to the `Client Section` tab on the `Testimonials > Settings` page. Follow the example to build shortcodes based on your custom fields. There is a shortcode for text fields (like a client's name) and a shortcode for links (like a client's website). When in doubt, use the default template provided.

= Is this multisite compatible? =

Yes, but I highly recommend first installing the [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin when adding Strong Testimonials to a multisite installation. That plugin will deftly handle the plugin activation process, ensuring each site has the default settings.

= Will it import my existing testimonials? =

Not yet. If you have a ton of testimonials, you may want to wait.

= Can I make a donation? =

Thanks but I prefer a nice [review](https://wordpress.org/support/view/plugin-reviews/strong-testimonials). Casting a compatibility vote will tell others it works too. That feedback really helps me understand how people are using my plugins, what works well, and what still needs work.


== Screenshots ==

1. The form with the default fields.
2. The form with custom fields.
3. The [all] shortcode with the default style.
4. The [all] shortcode with custom CSS.
5. The widget with a character limit.
6. The widget without a character limit.
7. The widget showing random excerpts.
8. the [cycle] shortcode showing excerpts.
9. The testimonials admin page.
10. Settings > General.
11. Settings > Cycle Shortcode.
12. Settings > Client Section.
13. The widget settings.
14. The custom fields editor.
15. The shortcode list.
16. The guide.


== Changelog ==

= 1.13.3 - 2014-12-14 =
* Fix conflict with iThemes Exchange plugin.

= 1.13.2 - 2014-12-8 =
* Minor fixes. Updated translation files.

= 1.13.1 - 2014-11-30 =
* Bug fix.

= 1.13 - 2014-11-30 =
* Ability to change labels and messages on the submission form.
* Add context and comments to POT file.
* Russian, Spanish, and Swedish translations.
* Improve category handling on form shortcode.
* Make form responsive.

= 1.12 - 2014-11-24 =
* Bug fix: empty custom field showed field name.
* Feature: Add `[strong]` shortcode to admin list.
* Feature: Make name column sortable in admin list.
* Conflict: Rename `wptexturize` filter.
* Conflict: De-couple some page-specific stylesheets to make plugin compatible with WPBakery's Visual Composer.

= 1.11.5 - 2014-11-21 =
* Add filter to prevent `[strong]` shortcode from `wptexturize()` in WordPress 4.0.1.

= 1.11.4 - 2014-11-14 =
* Remove the fix for potential problem on slow-loading pages; needs more testing.

= 1.11.3 - 2014-11-14 =
* Fix conflict with Roki theme.
* Fix a condition on slow-loading pages where the testimonials in the widget might appear stacked up before the slideshow script hides all but the first one.

= 1.11.2 - 2014-11-06 =
* IMPORTANT SECURITY UPDATE! 
* Restrict file upload types to jpg, jpeg, jpe, gif, png.

= 1.11.1 - 2014-10-23 =
* Fix bug in [wpmtst-cycle] category query.

= 1.11 - 2014-10-22 =
* New [strong] shortcode.
* New default template file and template functions for use in themes.
* Add `rel="nofollow"` option for links in client section.
* Improve conditional loading using `is_admin`.
* Use `tax_query` in shortcode queries instead of `get_terms`.
* Move honeypot CSS to page and generify hidden element.
* Fix server-side validation not adding error classes.
* Rename `char-limit` and `more-page` options to use underscores.
* Add POT file.
* Make the Guide page translation-ready.
* Drop `input type="url"` from the form until more themes adopt it.

= 1.10 - 2014-10-07 =
* Add honeypot spam control.
* Add category parameter to form shortcode.

= 1.9.3 =
* Fix bug.

= 1.9.1 =
* Add a guide to help new users.
* Improve Captcha plugin selector.

= 1.9 =
* Add widget features.
  * Post excerpt.
	* "Read more" link to post.
	* Show only selected parts.
* Minor fixes for cycle slide selection and container spacing.

= 1.8.1 =
* Fix trailing comma error in JavaScript for IE 7 and 8.

= 1.8 =
* New features in cycle shortcode: Excerpt and "Read more" link.
* Solve CSS width and float conflicts with some themes.
* Ready for translations.

= 1.7.3 = 
* Fix shortcode processing in widget content.

= 1.7.2 = 
* Fix the update process.

= 1.7.1 =
* Fix `Warning: Invalid argument supplied in foreach()` bugs.

= 1.7 =
* Custom fields on the testimonial submission form.
* Client fields underneath each testimonial via shortcodes.
* Improve activation/update process.
* Remove "Agree" checkbox from form.

= 1.6.2 =
* Fix conflict if jQuery Cycle plugin is enqueued by another plugin or theme.
* Fix conflict if using cycle shortcode and cycle widget on same page.
* All scripts local instead of via CDN.

= 1.6.1 =
* Bug fix where photo was not uploading with form submission.

= 1.6 =
* Add support for Really Simple Captcha plugin.

= 1.5.2 =
* Improve compatibility with earlier versions of PHP.

= 1.5.1 =
* Another bug fix for themes that set a width on the `content` class.

= 1.5 =
* Testimonial cycle shortcode.
* Improve reCaptcha error handling.
* Correct text domain use.
* Fix bug in widget character limit function.
* Fix widget text that flows outside of sidebar.
* Fix bug in script registered/queued check.
* Improve plugin update procedure.
* Finally settled on a commenting style :)
	
= 1.4.7 =
* Remove line breaks on long input elements.
* Consistent self-closing input tags.

= 1.4.6 =
* Fix bug: Copy-n-pasting shortcodes onto a page in Visual mode included `<code>` tags which fubar'd the page.

= 1.4.5 =
* Fix bug: The form shortcode was being rendered before any accompanying page content.

= 1.4.4 =
* New minimum WordPress version: 3.5.
* Add shims for `has_shortcode` and `shortcode_exists` for WordPress version 3.5.
* Change `save_post_{post-type}` to `save_post` for WordPress version 3.6.

= 1.4.3 =
* Improve compatibility with earlier versions of PHP.

= 1.4.2 =
* Fix bug: missing categories in admin testimonials table.

= 1.4.1 =
* Fix bug in category filter in the widget.

= 1.4 =
* Initial version, a fork of GC Testimonials 1.3.2.


== Upgrade Notice ==

= 1.13.3 =
Fix conflict with iThemes Exchange plugin.

= 1.13.2 =
Minor fixes. Updated translation files.

= 1.13.1 =
Bug fix.

= 1.13 =
Option to change the labels and messages on the form. Improved POT file. Russian, Spanish, and Swedish translations.

= 1.12 =
One bug fixed, two conflicts prevented, two minor features.

= 1.11.5 =
Important update for WordPress 4.0.1 that fixes client shortcode problem.

= 1.11.4 =
Removed the fix for potential widget problem; needs more testing.

= 1.11.3 =
Fixed a conflict with Roki theme. Fixed a potential display problem in the widget for slow-loading pages.

= 1.11.2 =
SECURITY VULNERABILITY FIXED. PLEASE UPDATE! The file upload on the submission form is now restricted to image file types only (jpg|jpeg|jpe|gif|png).

= 1.11.1 =
Fixed a bug in [wpmtst-cycle] category query.

= 1.11 =
Testing new [strong] shortcode. Added template functions and a default template file for use in themes. Added `rel="nofollow"` option. Improved memory usage, queries, honeypots, and server-side form validation. Added a .pot file.

= 1.10 =
Added honeypot spam control. Added category parameter to form shortcode.

= 1.9.3 =
Added a guide to help new users.

= 1.9 =
The widget now has the same features as the cycle shortcode, and the cycle process has been made more compatible with other plugins and themes.

= 1.8.1 =
Fixed a minor bug in Internet Explorer 7 and 8.

= 1.8 =
New features in cycle shortcode: Excerpt and "Read more" link. Solved CSS width and float conflicts with some themes. Ready for translations.

= 1.7.3 =
Fixed shortcode processing in widget content.

= 1.7.2 =
Fixed the update process.

= 1.7.1 =
Bugfix for `Warning: Invalid argument supplied in foreach()`.

= 1.7 =
Custom fields. Finally!

= 1.6.2 =
Fix conflicts with multiple uses of jQuery Cycle plugin.

= 1.6.1 =
Bug fix where photo was not uploading with form submission.

= 1.6 =
Added support for Really Simple Captcha plugin.

= 1.5.2 = 
Improved compatibility with earlier versions of PHP.

= 1.5.1 =
Another bug fix for themes that set a width on the `content` class.

= 1.5 =
New cycle shortcode. Bug fixes.

= 1.4.7 =
Improved code formatting to prevent a low-probability rendering problem.

= 1.4.6 =
Fixed a bug when copy-n-pasting a shortcode.

= 1.4.5 =
Fixed a bug where the form shortcode pushed other page content below.

= 1.4.4 =
Definitely update if you are running WordPress 3.5 or 3.6.

= 1.4.3 = 
Improved compatibility with earlier versions of PHP.

= 1.4.2 =
Fixed a minor bug that did not show multiple categories in the admin testimonials list.

= 1.4.1 =
Fixed a minor bug if a category was selected in the widget settings.
