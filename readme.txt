=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider
Requires at least: 3.5
Tested up to: 4.0
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


**Anti-Spam**

Instead of reinventing the anti-spam wheel, Strong integrates other plugins to do the heavy lifting. Because spam is heavy and modular is stronger.

Currently supported:

* [Captcha](http://wordpress.org/plugins/captcha/)
* [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/)
* [Simple reCaptcha](http://wordpress.org/plugins/simple-recaptcha)

Notes

* More options coming soon.
* If your site relies on a partner plugin like this, and that plugin becomes buggy or abandoned, I will adopt it or integrate it to keep your site running.
* [Contact me](http://www.wpmission.com/contact) if you have a plugin recommendation.


**Recommended**

These plugins work well with Strong Testimonials and add some nice features.

* [Admin Menu Post List](http://wordpress.org/plugins/admin-menu-post-list/)
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/)
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/)


**Known Conflicts**

* [Page Builder by SiteOrigin](http://wordpress.org/support/plugin/siteorigin-panels) - The widget settings are not being saved properly in the drag-and-drop builder.
* [WP Mail SMTP](https://wordpress.org/plugins/wp-mail-smtp/) - The administrator notification email may not be sent.


**Translations**

Can you help? [Contact me](http://www.wpmission.com/contact/).


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

= How do I add a Captcha to the form? =

Select one of the supported plugins on the `Testimonials > Settings` page. Use the link to each plugin's download page to install it.

If the currently selected Captcha plugin is deactivated, the setting will revert to "none".

= How can I change the fields on the form? =

On the `Testimonials > Fields` page, there is a field editor where you can add or remove fields, change field details, and drag-n-drop to reorder them. You can also restore the default fields. 

If you have ever used the Advanced Custom Fields or Custom Field Suite plugins, the editor will be very familiar. Here is a full [tutorial](http://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/).

= How can I change which client fields appear below the testimonial? =

On the `Client Section` tab on the `Testimonials > Settings` page. Follow the example to build shortcodes based on your custom fields. There is a shortcode for text fields (like a client's name) and a shortcode for links (like a client's website). When in doubt, use the default template provided.

I admit it can greatly improved but I needed to build something quickly to include in version 1.7 with custom fields. This will be replaced with a field editor in an upcoming version.

= Is this multisite compatible? =

Yes, but I highly recommend first installing the [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin when adding Strong Testimonials to a multisite installation. That plugin will deftly handle the plugin activation process, ensuring each site has the default settings.

= Are there templates? How do I change the look of the testimonials? =

Except for the default template, which is rather plain and so 2011, the stylesheets are largely structural so you can add on CSS in your theme or [custom CSS](http://wordpress.org/plugins/simple-custom-css/). In fact, I like to skip loading the stylesheets (in `Testimonials > Settings`) to see how they look in the theme, then style up from there. Future versions will have pre-built templates with adjustable colors, borders, etc.

= What about Akismet for the submission form? =

I plan to integrate Akismet and more soon.

= Will it import my existing testimonials? =

Not yet. If you have a ton of testimonials, you may want to wait.

= Can I make a donation? =

Thanks but I prefer a nice [review](https://wordpress.org/support/view/plugin-reviews/strong-testimonials). Casting a compatibility vote will tell others it works too. That feedback really helps me understand how people are using my plugins, what works well, and what still needs work.

= Does anybody read these things? =

That's *my* frequently asked question ;) As a reward for reading this far, leave a message [here](http://www.wpmission.com/contact) with "I read the FAQs!" in the subject line to receive one hour of WordPress support absolutely free.


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

= 1.9.3 =
* Fix bug.

= 1.9.1 =
* Added a guide to help new users.
* Improved Captcha plugin selector.

= 1.9 =
* Add widget features.
  * Post excerpt.
	* "Read more" link to post.
	* Show only selected parts.
* Minor fixes for cycle slide selection and container spacing.

= 1.8.1 =
* Fixed trailing comma error in JavaScript for IE 7 and 8.

= 1.8 =
* New features in cycle shortcode: Excerpt and "Read more" link.
* Solved CSS width and float conflicts with some themes.
* Ready for translations.

= 1.7.3 = 
* Fixed shortcode processing in widget content.

= 1.7.2 = 
* Fixed the update process.

= 1.7.1 =
* Fix for `Warning: Invalid argument supplied in foreach()` bugs.

= 1.7 =
* Custom fields on the testimonial submission form.
* Client fields underneath each testimonial via shortcodes.
* Improved activation/update process.
* Removed "Agree" checkbox from form.

= 1.6.2 =
* Fix conflict if jQuery Cycle plugin is enqueued by another plugin or theme.
* Fix conflict if using cycle shortcode and cycle widget on same page.
* All scripts local instead of via CDN.

= 1.6.1 =
* Bug fix where photo was not uploading with form submission.

= 1.6 =
* Added support for Really Simple Captcha plugin.

= 1.5.2 =
* Improved compatibility with earlier versions of PHP.

= 1.5.1 =
* Another bug fix for themes that set a width on the `content` class.

= 1.5 =
* Testimonial cycle shortcode.
* Improved reCaptcha error handling.
* Corrected text domain use.
* Fixed bug in widget character limit function.
* Fix for widget text that flows outside of sidebar.
* Fix bug in script registered/queued check.
* Improved plugin update procedure.
* Finally settled on a commenting style :)
	
= 1.4.7 =
* Removed line breaks on long input elements.
* Consistent self-closing input tags.

= 1.4.6 =
* Fixed bug: Copy-n-pasting shortcodes onto a page in Visual mode included `<code>` tags which fubar'd the page.

= 1.4.5 =
* Fixed bug: The form shortcode was being rendered before any accompanying page content.

= 1.4.4 =
* New minimum WordPress version: 3.5.
* Added shims for `has_shortcode` and `shortcode_exists` for WordPress version 3.5.
* Changed `save_post_{post-type}` to `save_post` for WordPress version 3.6.

= 1.4.3 =
* Improved compatibility with earlier versions of PHP.

= 1.4.2 =
* Fixed bug: missing categories in admin testimonials table.

= 1.4.1 =
* Fixed bug in category filter in the widget.

= 1.4 =
* Initial version, a fork of GC Testimonials 1.3.2.


== Upgrade Notice ==

= 1.9.3 =
Added a guide to help new users.

= 1.9 =
The widget now has the same features as the cycle shortcode, and the cycle process has been made more compatible with other plugins and themes.

= 1.8.1 =
Fixed a minor bug in Internet Explorer 7 or 8.

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
