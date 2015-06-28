=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider
Requires at least: 3.5
Tested up to: 4.2.2
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Collect and display testimonials with a plugin that offers strong features and strong support.

== Description ==

Strong Testimonials by [WP Mission](https://www.wpmission.com) is a full-featured plugin that works right out of the box for beginners and offers advanced features for pros.

= Overview =

1. Categories, excerpts, and Featured Images (thumbnails) are supported.
1. Multiple sorting options, pagination, and slideshows.
1. A testimonial submission form with customizable fields, anti-spam options, and a notification email.
1. Shortcodes, widgets, and template files.
1. A deep and wide demo site and a developer who's on your side.

[Demos](http://demos.wpmission.com/strong-testimonials/) | [Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Feature Requests](https://www.wpmission.com/project/strong-testimonials/?target=feature-requests)

= Collect =

`
[strong form]
`

Right out of the box, the testimonial submission form has fields for a name, an email address, a company name and website, a heading, a photo, and of course the testimonial content. 

Need to add a field? Want to remove one? No problem. Use the fields editor to **customize the form** to your specific situation, including the field order, the text before or after, the placeholder text, and required fields.

The form also offer **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), [Simple reCaptcha](http://wordpress.org/plugins/simple-recaptcha). The form messages for the required fields, the success and error messages, and even the Submit button are customizable too. It's pretty cool.

= Admin =

Testimonials are a custom post type that uses the standard post editor, so no new UI to learn there. Just like posts, they can be organized into **categories** for easy segmentation. Categories are by far the best way to show the right testimonials on the right page and to spread them out across the breadth of your site.

Submitted testimonials will be in Pending status by default or they can be published immediately. You can send a **notification email** to the site administrator (or any email address) with submission details.

= Display =

`
[strong title thumbnail]
`

Show the newest first, the oldest first or in random order. Drag and drop them into a new order in the admin list.

Show one, ten or all of them. Show the full testimonial, the excerpt, or up to a specified length - which is great when you have both long and short testimonials. 

With the excerpt and short version options, you can add a "Read more" link to the full testimonial, which works well when you have long testimonials that really tell a story and when you have prospects that do their due diligence and read every word on your site.

When you have more than ten or so testimonials, a popular approach is to **paginate them**, only showing 5-10 per page. The plugin offers simple page controls (1 2 3 ...) that can be placed above and below the testimonial group.

= Slideshow =

`
[strong slideshow]
`

A well-thought-out slideshow can be a great selling aide and looks great on an otherwise static page or sidebar without being distracting. Use the **Excerpt** feature to craft quick and simple one-liners that tell prospects exactly what they need to hear from your previous customers. [Really make it sing](http://demos.wpmission.com/strong-testimonials/examples/excerpt-slideshow/) with the **Featured Image**.

Both the shortcode and the widget can be a slideshow. Multiple slideshows can be used on the same page by a shortcode or widget with different styles and speeds. *(Can other testimonial plugins say that?)*

= Style =

Testimonial plugins typically offer either no styling at all or a handful of predefined layouts that may or may not look good in your theme.

Strong Testimonials offers one layout for the shortcode and one for the widget inherited from the original version (GC Testimonials) that is just generic enough to look good in most cases with maybe a few tweaks (I always remove the gradient gray background). 

Its stylesheets are largely structural and can be easily overridden by your theme or a plugin like [Simple Custom CSS](https://wordpress.org/plugins/simple-custom-css/). You can also skip loading each stylesheet (page, widget, form) and let your theme handle it from the ground up.

Need help matching your theme? Got some weird spacing or floating? I'm here to help.

For ultimate control and seamless integration with your theme, the plugin also offers **template files**. (Tutorial coming soon.)

= One shortcode to rule them all. =

The `[strong]` shortcode is unique and versatile. Most attributes act as on/off switches. For example, `[strong title random]` means "show testimonials and their title in random order".

Other shortcode examples:

`
[strong title category="3" count="20" per_page="5"]
  [client]
    [field name="client_name" class="name"]
  [/client]
[/strong]
`
`
[strong slideshow show_for="5" effect_for="1" length="200"]
`
`
[strong thumbnail random excerpt more_post more_text="more..."
 count="3" class="three-across"]
`

Learning a new plugin, especially one that makes heavy use of shortcodes, can seem like learning [Klingon](http://en.wikipedia.org/wiki/Klingon_language). If you have any trouble, take it one bite at a time with the [demo site](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/full-attribute-list/) as your guide. By all means, use the support forum for help or contact me directly. You will be fluent soon!

= Translations =

The plugin includes a POT file and French, Hebrew, Russian, Spanish and Swedish translations. It is currently undergoing testing by the generous folks at WPML who will recommend how to make it WPML-compatible.

= Support =

As a technical support veteran, I am dedicated to your success. I will move mountains to fix bugs, to resolve conflicts with other plugins and themes, and to craft elegant solutions to fragile workarounds. Just ask [Grant](https://wordpress.org/support/topic/great-support-308?replies=2), [hnrocket](https://wordpress.org/support/topic/effective-great-support?replies=2), and [Anna](https://wordpress.org/support/topic/awesome-support-and-solid-plug-in?replies=2).

[Demos](http://demos.wpmission.com/strong-testimonials/) | [Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Feature Requests](https://www.wpmission.com/project/strong-testimonials/?target=feature-requests)

= The Weaknesses =

The `[strong]` shortcode can be overwhelming at first. There's no shortcode builder thingy in the page editor. *A better solution is almost ready.*

The plugin may seem a bit disjointed; or maybe double-jointed is a better word. The `[strong]` shortcode was meant to replace the original `[wpmtst]` shortcodes but I haven't phased those out yet.

The settings pages for the original shortcodes are mixed in with the settings pages for `[strong]` and the newer features. It can be unclear which settings affect the new shortcode, the old shortcodes, or the widget.

The template files and functions can be vastly improved.

= Recommended =

These plugins work well with Strong Testimonials and add some nice features.

* [Admin Menu Post List](http://wordpress.org/plugins/admin-menu-post-list/) provides a list of your testimonials right there in the admin menu.
* [Debug This](http://wordpress.org/plugins/debug-this/) to peek under the hood when troubleshooting.
* [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) (multisite) ensures the plugin is properly activated in each site in your network.
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/) is my go-to plugin for quick CSS fixes.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu breathe.

= Known Conflicts =

* [Html Social Share Buttons](https://wordpress.org/plugins/html-social-share-buttons/) - It's buggy, sorry.
* [Meteor Slides](https://wordpress.org/plugins/meteor-slides/)
* [Unyson Framework by ThemeFuse](http://unyson.io/)
* [Warp Framework by YooTheme](https://yootheme.com/themes/warp-framework) - Custom templates for custom post types are not being found since the framework seemingly supplants the WordPress template hierarchy.
* [Page Builder 1.x by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/) - The widget settings are not being saved properly in the drag-and-drop builder. Update to the excellent Page Builder 2 if possible.

= Translations =

* French (fr_FR) - Jean-Paul Radet
* Hebrew (he_IL) - Haim Asher
* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many thanks to these translators. Can you help? [Contact me](https://www.wpmission.com/contact/).

== Installation ==

Option A:

1. Go to Plugins > Add New.
1. Search for "strong testimonials".
1. Click "Install Now".

Option B: 

1. Download the zip file.
1. Unzip it on your hard drive.
1. Upload the `strong-testimonials` folder to the `/wp-content/plugins/` directory.

Option C:

1. Download the zip file.
1. Upload the zip file via Plugins > Add New > Upload.

Finally, activate the plugin.

<em>The default settings are designed to help you hit the ground running.</em>

View some examples on the [demo site](http://demos.wpmission.com/strong-testimonials/).

For help, use the [support forum](http://wordpress.org/support/plugin/strong-testimonials) or [contact me](https://www.wpmission.com/contact/).


== Frequently Asked Questions ==

[Demos](http://demos.wpmission.com/strong-testimonials/) | [Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Feature Requests](https://www.wpmission.com/feature-request)


= My web host does not allow the SQL RAND() random function. Will your plugin still work? =

Yes. The random routine has been moved from SQL to PHP (from the database to the application). Thanks to [Eric Hoanshelt](http://wpmigration.guru/) at [WPEngine](http://wpengine.com/) for showing me the light.


= Can I change which client fields appear below the testimonial? =

Yes. The `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

For the widget and the original `[wpmtst]` shortcodes, there are other child shortcodes for your custom fields; one for text fields (like a client's name) and one for links (like a client's website).


= Are there templates? Can I change the look of the testimonials? =

Yes. The `[strong]` shortcode uses a template file that can be copied into the top directory of your theme, e.g. `wp-content/themes/my-theme/testimonials.php`. You can create multiple template files and include them using a shortcode attribute; e.g. `template="my-template"`. Template functions are also available for adding testimonial fields to new or existing templates.

The original `[wpmtst]` shortcodes do not use template files, but the stylesheets are largely structural so you can add CSS in your theme. In fact, I like to skip loading the stylesheets (in `Testimonials > Settings`) to see how they look in the theme, then style up from there. 


= Can I change "testimonial" to "review", for example? =

Maybe. Instructions are [here](https://wordpress.org/support/topic/how-to-change-the-slug). However, this does not seem to work for all theme/plugin combinations, like themes that also include testimonials, so I plan to build this into the plugin soon.


= Can I reorder my testimonials? =

Yes. Two ways: (1) drag-and-drop ordering in the admin list and (2) manually changing the Order field (usually in the right-hand column in the post editor *or* in Quick Edit in the post list).

Drag and drop is **disabled by default** and can be enabled in `Testimonials > Settings > General`.

To avoid conflicts, the plugin proactively **denies** these plugins from reordering testimonials: [Post Types Order](https://wordpress.org/plugins/post-types-order/) by Nsp Code, [Simple Custom Post Order](https://wordpress.org/plugins/simple-custom-post-order/) by Sameer Humagain, and [Intuitive Custom Post Order](https://wordpress.org/plugins/intuitive-custom-post-order/) by Hijiri.

That manual order field is also available in the widget.


= Can I change the fields on the form? =

Yes. There is a custom field editor where you can add or remove fields, change field details, and drag-and-drop to reorder them. You can also restore the default fields.

If you have ever used the Advanced Custom Fields or Custom Field Suite plugins, the editor will be very familiar. Here is a full [tutorial](https://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/).


= Can I add Captcha to the form? =

Yes. Install one of the supported Captcha plugins, configure it, and select it on the Settings page.

If your site relies on a partner plugin like this, and that plugin becomes buggy or abandoned, I will adopt or fork it to keep your site running.

[Contact me](https://www.wpmission.com/contact) to recommend another method or plugin.


= Is this multisite compatible? =

Yes, but I highly recommend first installing the [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin when adding Strong Testimonials to a multisite installation. That plugin will deftly handle the plugin activation process, ensuring each site has the default settings.


= Will it import my existing testimonials? =

Not yet.


= Can I make a donation? =

Thanks but I prefer a nice [review](https://wordpress.org/support/view/plugin-reviews/strong-testimonials). Casting a compatibility vote will tell others it works too. That feedback really helps me understand how people are using my plugins, what works well, and what still needs work.


== Screenshots ==

1. The form with the default fields.
2. An example of custom fields on the form.
3. A [strong] shortcode example.
4. The widget with a character limit.
5. The widget settings.
6. The testimonials admin list.
7. Settings > General.
8. Settings > Form.
9. The custom fields editor.
10. The shortcode guide.


== Changelog ==

= 1.16.14 - 2015-06-27 =
* Fix conflict with Black Studio TinyMCE plugin.

= 1.16.13 - 2015-06-10 =
* Add html_entity_decode to shortcode preprocessing for GoodLayers themes.

= 1.16.12 - 2015-06-10 =
* Move localize scripts to wp_footer.

= 1.16.11 - 2015-06-01 =
* Fix conflict with NextGEN Gallery.

= 1.16.10 - 2015-05-29 =
* Fix bug when interacting with Simple Custom Post Order and Intuitive Custom Post Order plugins.

= 1.16.9 - 2015-04-16 =
* Really fix conflict with Jetpack Slideshow shortcode.

= 1.16.8 - 2015-04-15 =
* Fix conflict with Jetpack Slideshow shortcode.

= 1.16.7 - 2015-04-14 =
* Fix bug in slideshow pause-on-hover.

= 1.16.6 - 2015-04-13 =
* Fix bug in [wpmtst-random] shortcode.

= 1.16.5 - 2015-04-13 =
* Fix bug in admin post list date column filter (WooCommerce compat).

= 1.16.4 - 2015-04-09 =
* Fix bug in shortcode post count.

= 1.16.3 - 2015-04-09 =
* Fix bug on reactivate.

= 1.16.2 - 2015-04-09 =
* Fix bug in random order.

= 1.16.1 - 2015-04-09 =
* Fix bug in random order.

= 1.16 - 2015-04-09 =
* Add menu order to post list.
* Add `menu_order` attribute to `[strong]` shortcode.
* Add menu order option to widget and `[wpmtst-cycle]` shortcode.
* Add drag-and-drop reordering in post list.
* Move random selection from SQL to PHP.
* Remove `no_stylesheet` shortcode attribute.
* Add language files for the form validation script.
* Add French translation.

= 1.15.14 - 2015-04-02 =
* Fix bug when truncating content.

= 1.15.13 - 2015-03-29 =
* Order by menu order instead of post date in query.

= 1.15.12 - 2015-03-24 =
* Improve compatibility with WooCommerce and Visual Composer.
* Fix mobile screen orientation change CSS.
* Strip tags when truncating content.

= 1.15.11 - 2015-03-19 =
* Preprocess post meta fields for shortcodes.

= 1.15.10 - 2015-03-17 =
* Fix category view page. 

= 1.15.9 - 2015-03-17 =
* Fix possible early return when preprocessing widgets.

= 1.15.8 - 2015-03-16 =
* Fix pass-by-reference error.

= 1.15.7 - 2015-03-16 =
* Fix pass-by-reference error.
* Add `wpmtst_get_field` function.

= 1.15.6 - 2015-03-14 =
* Add missing element to RTL stylesheet.
* Prevent error if no widgets have been defined.

= 1.15.5 - 2015-03-13 =
* Process shortcode in widgets.
* Preprocess nested shortcodes.
* Add filter 'strong_widget_read_more_text' for the widget "Read more" link text.
* Add "News" tab to Settings page.
* Remove Guide icon.
* Add more version info to HTML comment.
* Fix `<label>` mismatch in widget form.

= 1.15.4 - 2015-03-10 =
* Fix bug in slideshows.

= 1.15.3 - 2015-03-09 =
* Fix bug in widget.

= 1.15.2 - 2015-03-07 =
* Fix handling of custom widget areas.

= 1.15.1 - 2015-03-07 =
* Fix bug in slideshow JavaScript.

= 1.15 - 2015-03-07 =
* Improved style and script enqueueing for better compatibility with page builder plugins.
* Add filter for the widget "Read more" link.
* Add filter for `[strong]` shortcode HTML output.
* Add default slideshow attributes.

= 1.14.5 - 2015-02-02 =
* Fix slideshow pause on hover to work with either version of Cycle.

= 1.14.4 - 2015-01-28 =
* RTL stylesheet not included by default.

= 1.14.3 - 2015-01-27 =
* Fix JavaScript error in slideshow.

= 1.14.2 - 2015-01-27 =
* Fix honeypot bug.
* Add Hebrew translation and right-to-left stylesheets.

= 1.14.1 - 2015-01-23 =
* Fix conflict with Spacious theme and others that also use the jQuery Cycle slideshow plugin.
* Add `jquery.cycle2.js.map` to prevent error in Chrome Developer Console.
* Improve compatibility with Page Builder plugin version 2.x.

= 1.14 - 2015-01-22 =
* New notification email options.

= 1.13.4 - 2015-01-21 =
* Fix conflict with Spacious theme.

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

= 1.16.14 =
Fix conflict with Black Studio TinyMCE plugin.
