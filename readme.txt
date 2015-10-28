=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider, reviews, testimonial form
Requires at least: 3.5
Tested up to: 4.3
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Add testimonials with a plugin that offers strong features and strong support.

== Description ==

Strong Testimonials by [WP Mission](https://www.wpmission.com) is a full-featured plugin that works right out of the box for beginners and offers advanced features for pros.

= What's New =

* Compatible with WPML and Polylang.
* Views are easy to configure.
* New template choices (and more on the way).
* More thumbnail sizes.
* Gravatar support.

= Primary Features =

* Configure a View or use the robust `[strong]` shortcode
* A front-end form with custom fields, anti-spam options, and notification emails
* Categories
* Excerpts
* Featured Images (thumbnails)
* Multiple slideshows
* Sort by oldest, newest, random, or menu order
* Simple pagination
* Custom "Read more" links
* Template files and functions for deep customization
* A developer who's on your side :)

[Check out the demos](http://demos.wpmission.com/strong-testimonials/)

= The Form =

By default, the testimonial form has fields for 
* a name, 
* an email address, 
* a company name and website, 
* a heading, 
* a photo, 
* and, of course, the testimonial content.

Need to add a field? Want to remove one? No problem. Use the fields editor to **customize the form** to your specific situation, including the field order, the text before or after, the placeholder text, and required fields.

The form also offer **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), and [Advanced noCaptcha reCaptcha](https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/).

New testimonials will be in Pending status by default or they can be published immediately. You can send multiple **notification emails** to your admin(s).

= Display =

Select the sort order and how many to show. Drag and drop them into a new order in the admin list.

Show the full testimonial, the excerpt, or up to a specified length - which is great when you have both long and short testimonials.

Organize them into **categories** for easy segmentation. You can **paginate them**, only showing 5-10 per page, for example. The plugin offers simple page controls that can be placed above and below the testimonial group.

= Slideshow =

Slideshows are easy to create. Multiple slideshows can be used on the same page with different styles and speeds. *(Can other testimonial plugins say that?)*

Use **excerpts** to craft quick and simple one-liners. [Really make it sing](http://demos.wpmission.com/strong-testimonials/examples/excerpt-slideshow/) using **Featured Images**.

= Style =

Strong Testimonials offers a handful of templates to try in your theme. The default template is just generic enough to look good in most cases with maybe a few tweaks.

Need help matching your theme? Got some weird spacing or floating? I'm here to help.

For ultimate control and seamless integration, any **template file** can be copied into your theme. (A tutorial and more templates coming soon.)

= Views =

Views are the future of this plugin. They bring all the options together into a single admin screen. Add a View to a page with the `[testimonial_view]` shortcode, or to a sidebar with a widget.

= Shortcodes =

The `[strong]` shortcode is the predecessor of Views. It is unique and versatile. Most attributes act as on/off switches. For example, `[strong title random]` means "show testimonials and their title in random order".

Other examples:

`
[strong form]
`
`
[strong title category="3" count="20" per_page="5"]
  [client]
    [field name="client_name" class="name"]
  [/client]
[/strong]
`
`
[strong slideshow show_for="5" effect_for="1" excerpt]
`

Use `[read_more]` after your testimonials to add a "Read more" link to another page.

[Check out the demos](http://demos.wpmission.com/strong-testimonials/)

= Support =

I will move mountains to fix bugs, to resolve conflicts with other plugins and themes, and to craft elegant solutions to fragile workarounds.

= Recommended =

* [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/) to open thumbnails into full-size images. Nice!
* [Debug This](http://wordpress.org/plugins/debug-this/) to peek under the hood when troubleshooting.
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/) is my go-to plugin for quick CSS fixes.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu b r e a t h e.

= Known Conflicts =

* [Meteor Slides](https://wordpress.org/plugins/meteor-slides/)
* [Unyson Framework by ThemeFuse](http://unyson.io/)
* [Warp Framework by YooTheme](https://yootheme.com/themes/warp-framework)

= Translations =

* Arabic (ar_AR) - Ahmad Yousef
* French (fr_FR) - Jean-Paul Radet
* Hebrew (he_IL) - Haim Asher
* Portuguese (pt_BR) - Mauricio Richieri
* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many, many thanks to these translators. Can you help? [Contact me](https://www.wpmission.com/contact/).

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

== Frequently Asked Questions ==

= Can I change the fields on the form? =

Yes. There is a custom field editor where you can add or remove fields, change field details, and drag-and-drop to reorder them. You can also restore the default fields. Here is a full [tutorial](https://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/).

= I want to use categories. Can I add a category selector to the submission form? =

Yes. After setting up your categories, you can add a category dropdown element to the form.

= Can I edit the notification email that goes out when a new testimonial has been submitted? =

Yes. You can edit the subject line, the message, the sender, and the recipient(s).

= Can I change which client fields appear below the testimonial? =

Yes. In Views, the client fields can be changed with a few clicks. The `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

= Can I change "testimonial" to "review", for example? =

Maybe. Instructions are [here](https://wordpress.org/support/topic/how-to-change-the-slug). However, this does not seem to work for all theme/plugin combinations, like themes that also include testimonials, so I plan to build this into the plugin soon.

= Will it import my existing testimonials? =

Not yet.

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

= 1.23 - 2015-10-28 =
* Fix option to show Gravatar only if found.
* Improve compatibility with Elegant Themes.
* Fix invalid HTML.
* Load custom stylesheet if using custom template; e.g. testimonials-custom.php & testimonials-custom.css.

= 1.22 - 2015-10-23 =
* Gravatar support in Views.

= 1.21.4 - 2015-10-21 =
* Fix bug with Polylang admin.
* New Spanish (es_ES) translation.

= 1.21.3 - 2015-10-15 =
* Fix bug in resetting post data after query.

= 1.21.2 - 2015-10-14 = 
* Fix conflict with WooCommerce.

= 1.21.1 - 2015-10-13 =
* Removed Simple Colorbox dependency. Any lightbox will do.

= 1.21 - 2015-10-12 =
* Views.
* New templates.
* WPML & Polylang compatible.
* All available thumbnail sizes including custom.
* Option to click thumbnail to open full-size image in lightbox.
* More hooks and filters.
* Better template functions.
* Prevent double posting of the form.
* Separate `[read_more]` shortcode.

= 1.20.2 - 2015-09-25 =
* Fix bug in custom shortcode.
* Fix bug in form class.
* Allow removal of content field in form.

= 1.20.1 - 2015-09-06 =
* Fix bug in updating default settings.

= 1.20 - 2015-09-01 =
* New shortcode option "more_page" to add a "Read more" to a page.
* New child shortcode "date" to display the post date.
* Fix thumbnail support.
* Fix reorder table striping.

= 1.19 - 2015-08-27 =
* Add option to change shortcode.
* Fix redundant admin notices.

= 1.18.5 - 2015-08-25 =
* Fix bug when restoring default fields.

= 1.18.4 - 2015-08-21 =
* Fix bug in notification email settings.

= 1.18.3 - 2015-08-18 =
* Fix bug when image is a required field on the form.

= 1.18.2 - 2015-08-17 =
* Fix bug in updating default options.

= 1.18.1 - 2015-08-17 =
* Fix bug when using site admin email for notifications.

= 1.18 - 2015-08-17 =
* Important update for WordPress 4.3!
* Option to send notification emails to multiple recipients.
* Option to add a category selector to the testimonial submission form.

= 1.17.2 - 2015-07-09 =
* Remove HTML comments from templates to increase theme compatibility.

= 1.17.1 - 2015-07-03 =
* Remove support for the Simple reCaptcha plugin.

= 1.17 - 2015-06-29 =
* Add support for the Advanced noCaptcha reCaptcha plugin.
* Add Arabic and Portuguese translations.
* Updated translation files for French and Spanish.
* Updated POT file.

== Upgrade Notice ==

Introducing Views. WPML & Polylang compatible. New templates. More thumbnail sizes. Gravatar support.
