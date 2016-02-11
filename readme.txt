=== Strong Testimonials ===
Contributors: cdillon27
Donate link: https://www.wpmission.com/donate/
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider, testimonial form
Requires at least: 3.5
Tested up to: 4.4.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A full-featured testimonials plugin that works right out of the box for beginners and offers advanced features for pros, all backed by strong support.

== Description ==

All the features you need to quickly add and customize testimonials on your site.

**[See demos of various templates, slideshows, and forms here!](http://demos.wpmission.com/strong-testimonials/)**

= Is this the right plugin for you? =
It's important to understand this plugin's intended use case: A small business with up to a few hundred testimonials, maybe organized into 5-10 categories, and displaying them a few different ways like a full page of testimonials and some slideshows, plus allowing customers to submit new testimonials.

If that describes your situation, this plugin will work for you right out of the box with just a few steps.

= Primary Features =

* A front-end form with custom fields, anti-spam options, and notification emails
* Categories
* Excerpts
* Featured Images (thumbnails)
* Multiple slideshows
* Sort by oldest, newest, random, or menu order
* Simple pagination
* Built-in templates with layout options for columns, grids, Masonry, and backgrounds
* Template files and functions for deep customization
* Custom "Read more" links
* Gravatar support
* Compatible with WPML and Polylang

Strong Testimonials [plays well with other plugins and themes](https://www.wpmission.com/theme-plugin-compatibility-fund/), and I strive to adhere to WordPress coding standards and best practices.

= The Form =

You can customize the form to your needs by adding or removing fields, or changing properties like the order, the text before or after, and the placeholder text.

The form also offers **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), and [Advanced noCaptcha reCaptcha](https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/).

**Notification emails** can be sent to multiple admins when new testimonials are submitted. New testimonials can also be assigned to a specific category.

The form can be submitted via Ajax which makes it compatible with the popular [Popup Maker](https://wordpress.org/plugins/popup-maker/) plugin.

= Display =

Everything happens in a **View**. Instead of learning multiple shortcodes with dozens of options, a View contains all the options in a simple, intuitive editor that no other testimonial plugin has. Display the View using a single shortcode or the widget.

Some of the options for displaying testimonials in a **list** or **slideshow**:

* select which testimonials to show
* set the sort order
* select which fields to include
* extras like pagination
* layout and style options

Check out the [screenshots](https://wordpress.org/plugins/strong-testimonials/screenshots/) to see the View editor.

= Style =

Strong Testimonials offers a handful of templates to try in your theme. The default template is just generic enough to look good in most cases with maybe a few tweaks.

Need help matching your theme? Got some weird spacing or floating? I'm here to help.

For ultimate control and seamless integration, any template can be copied to your theme and customized (a tutorial and more templates coming soon). There is also a template function that can display any View.

= Try these plugins too =

* [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/) to open thumbnails into full-size images. Nice!
* [Modular Custom CSS](https://wordpress.org/plugins/modular-custom-css/) for quick CSS tweaks right there in the Customizer.
* [Post State Tags](https://wordpress.org/plugins/post-state-tags/) helps quickly differentiate Published / Pending / Draft and more.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu b r e a t h e.

= Translations =

* Arabic (ar_AR) - Ahmad Yousef
* French (fr_FR) - Jean-Paul Radet
* German (de_DE) - Richard Hopp
* Hebrew (he_IL) - Haim Asher
* Portuguese (pt_BR) - Mauricio Richieri
* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many, many thanks to these translators.

Did you know you can help by [translating phrases here](https://translate.wordpress.org/projects/wp-plugins/strong-testimonials)? Even just a few phrases would help.

[Contact me](https://www.wpmission.com/contact/) to contribute a full translation.

== Installation ==

1. Go to Plugins > Add New.
1. Search for "strong testimonials".
1. Click "Install Now".

OR

1. Download the zip file.
1. Upload the zip file via Plugins > Add New > Upload.

Finally, activate the plugin.

== Frequently Asked Questions ==

= Can I change the fields on the form? =

Yes. There is a custom field editor where you can add or remove fields, change field details, and drag-and-drop to reorder them. You can also customize the form error & success messages and the Submit button.

= I want to use categories. Can I add a category selector to the submission form? =

Yes. After setting up your categories, you can add a category dropdown element to the form.

= Can I edit the notification email that goes out when a new testimonial has been submitted? =

Yes. You can edit the subject line, the message, the sender, and the recipient(s). You can also turn it off altogether.

= Can I change which client fields appear below the testimonial? =

Yes. In Views, these custom fields can be changed with a few clicks.

= Can I change "testimonial" to "review", for example? =

Maybe. Instructions are [here](https://wordpress.org/support/topic/how-to-change-the-slug). However, this does not seem to work for all theme/plugin combinations so I plan to build this into the plugin soon.

= My theme also includes testimonials. Will that be a problem? =

It depends. I have narrowed down the causes of many conflicts and addressed them in the plugin. If you encounter trouble, use the support form or contact me and we'll sort it out. If you want help disabling testimonials in your theme, even better :)

= I'm familiar with template files. Can I customize the template? =

Yes. With some HTML and CSS knowledge, you can copy any template to your theme and customize it as you see fit. You can also have multiple custom templates. (A tutorial is coming soon.)

= Can I make some suggestions? =

I'm all ears! Many of the improvements over the last two years are the result of feedback and ideas from people using the plugin in a variety of sites.

= Will it import my existing testimonials? =

Not yet.

== Screenshots ==

1. The default View settings and the default template.
2. Another View and the Simple template.
3. The form with default fields plus Captcha.
4. The testimonial admin list.
5. The testimonial form settings.
6. The View editor.
7. The custom fields editor.
8. Template files and template functions.

== Changelog ==

= 2.2.3 - February 11, 2016 =
* Fix bug in category selector in WPML.

= 2.2.2 - February 10, 2016 =
* Fix bug in category selector custom field when localized.

= 2.2.1 - February 8, 2016 =
* Fix bug in View category selector.
* Prevent potential class name conflict.

= 2.2 - February 8, 2016 =
* Improve category selector in View editor.
* Add category filter to testimonial admin list.
* Make category column sortable in testimonial admin list.
* Add German translation.

= 2.1 - February 5, 2016 =
* Add ability to duplicate a View.
* Fix pagination/slideshow show/hide behavior in View editor.
* Add framework for add-on plugins.

= 2.0.1 - February 3, 2016 =
* Fix bug that restored default custom fields.

= 2.0 - February 2, 2016 =
* Remove `[strong]`, `[wpmtst]`, `[read_more]` shortcodes.
* Remove original widget.
* Add content area to View widget.
* Improve handling of special characters in Fields editor.
* Fix bug in failed Captcha when submitting form via Ajax.
* Compatible with [Profit Builder](http://wpprofitbuilder.com/) plugin.

= 1.25.7 =
* Minor admin UI fixes.

= 1.25.6 =
* Fix `[strong]` slideshow script.
* Fix View list table column sorting.
* Fix single category selection in View editor.
* Compatible with [Taxonomy Terms Order](https://wordpress.org/plugins/taxonomy-terms-order/) plugin by Nsp-Code.
* Remove unused files.

= 1.25.5 =
* Fix bug in gradient CSS with two Views on one page.

= 1.25.4 =
* Fix loss of template selection when upgrading Views.
* Fix bug in submission form.

= 1.25.3 =
* Fix conflict with [Post Types Order](https://wordpress.org/plugins/post-types-order/) plugin.
* Fix localization of form error messages.
* Fix conflict with [Page Builder by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/).
* Don't show welcome page on bugfix updates - sorry :)

= 1.25.2 =
* Fix bug in `[strong form]`.

= 1.25.1 =
* Fix bug in Internet Explorer and Safari.

= 1.25 - 2016-01-06 =
* More layout and background options in Views.
* Improve template directory structure.
* New template function: `strong_testimonial_view()`
* Improve responsiveness.
* Fix long-standing slideshow CSS problems.
* Improve WPML compatibility.
* Compatible with Beaver Builder, Search Exclude, and Popup Maker.
* Preparing for version 2.0.

= 1.24.4 - 2015-12-07 =
* Remove debugging.

= 1.24.3 - 2015-12-06 =
* Fix bug in `more_page` option.

= 1.24.2 - 2015-11-10 =
* Fix bug in Gravatar display.

= 1.24.1 - 2015-11-09 =
* Fix bug in post list order.

= 1.24 - 2015-11-04 =
* Option to change custom field link text.
* Fix admin UI bugs.
* Add JavaScript and stylesheet versioning to force browser reload.

= 1.23.1 - 2015-10-29 =
* Fix bug in localization of form validation messages.

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

== Upgrade Notice ==

The Category selector is improved in the View editor and admin list. Adding a German translation.
