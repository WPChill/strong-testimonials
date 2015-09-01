=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider, reviews, testimonial form
Requires at least: 3.5
Tested up to: 4.3
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Manage your testimonials with a plugin that offers strong features and strong support.

== Description ==

Strong Testimonials by [WP Mission](https://www.wpmission.com) is a full-featured plugin that works right out of the box for beginners and offers advanced features for pros.

= Overview =

1. Categories, excerpts, and Featured Images (thumbnails) are supported.
1. Sorting options, pagination, and slideshows.
1. A testimonial form with customizable fields, anti-spam options, and a notification email.
1. Shortcodes, widgets, and template files.
1. A deep and wide demo site and a developer who's on your side.

[Check out the demos](http://demos.wpmission.com/strong-testimonials/)

= Collect =

The testimonial form has fields for a name, an email address, a company name and website, a heading, a photo, and of course the testimonial content. 

Need to add a field? Want to remove one? No problem. Use the fields editor to **customize the form** to your specific situation, including the field order, the text before or after, the placeholder text, and required fields.

The form also offer **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), and [Advanced noCaptcha reCaptcha](https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/). 

New testimonials will be in Pending status by default or they can be published immediately. You can send multiple **notification emails** to your admin(s).

= Display =

Show the newest first, the oldest first, or in random order. Drag and drop them into a new order in the admin list.

Show one or show all or any number in between. Show the full testimonial, the excerpt, or up to a specified length - which is great when you have both long and short testimonials. 

Just like posts, they can be organized into **categories** for easy segmentation. You can **paginate them**, only showing 5-10 per page, for eaxmple. The plugin offers simple page controls (1 2 3 ...) that can be placed above and below the testimonial group.

= Slideshow =

Both the shortcode and the widget can be a slideshow. Multiple slideshows can be used on the same page by a shortcode or widget with different styles and speeds. *(Can other testimonial plugins say that?)*

Use **excerpts** to craft quick and simple one-liners. [Really make it sing](http://demos.wpmission.com/strong-testimonials/examples/excerpt-slideshow/) using **Featured Images**.

= Style =

Testimonial plugins typically offer either no styling at all or a handful of predefined layouts that may or may not look good in your theme.

Strong Testimonials offers one layout for the shortcode and one for the widget inherited from the original version (GC Testimonials) that is just generic enough to look good in most cases with maybe a few tweaks. 

You can also skip loading each stylesheet (page, widget, form) and let your theme handle it from the ground up.

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
[strong slideshow show_for="5" effect_for="1" excerpt]
`

[Check out the demos](http://demos.wpmission.com/strong-testimonials/)

= Support =

As a technical support veteran, I am dedicated to your success. I will move mountains to fix bugs, to resolve conflicts with other plugins and themes, and to craft elegant solutions to fragile workarounds.

= Recommended =

* [Debug This](http://wordpress.org/plugins/debug-this/) to peek under the hood when troubleshooting.
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/) is my go-to plugin for quick CSS fixes.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu breathe.

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

Yes. You can edit the subject line, the message, who it's from, and who it's to (including multiple recipients).

= Can I change which client fields appear below the testimonial? =

Yes. The `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

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

= 1.20 =
* New shortcode options "more_page" and "date". Fix thumbnail support and reorder table striping.
