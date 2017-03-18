=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider, testimonial form
Requires at least: 3.6
Tested up to: 4.7.3
Stable tag: 2.19
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

A full-featured testimonials plugin that works right out of the box for beginners and offers advanced features for pros, all backed by strong support.

== Description ==

All the features you need to quickly add and customize testimonials on your site.

> [Display Demos](http://demos.wpmission.com/strong-testimonials/group-1-display/) | [Slideshow Demos](http://demos.wpmission.com/strong-testimonials/group-2-slideshow/) | [Form Demos](http://demos.wpmission.com/strong-testimonials/group-3-form/)

= Is this the right plugin for you? =
It's important to understand this plugin's intended use case: A small business with up to a few hundred testimonials, maybe organized into multiple categories, and displaying them a few different ways like a full page of testimonials and a slideshow widget, plus a form for accepting new testimonials.

If that describes your situation, this plugin will work for you right out of the box with just a few steps.

= Primary Features =

* Star ratings
* A front-end form
* Custom form fields editor
* Categories
* Excerpts, both manual and automatic
* Featured Images (thumbnails)
* Slideshows with several navigation options
* Sort by oldest, newest, random, or menu order (drag-and-drop)
* Pagination
* Built-in templates with several layout options
* Template files and functions for deep customization
* Gravatar support
* Ready for translations
* Compatible with [WPML](https://wpml.org/), [Polylang](https://wordpress.org/plugins/polylang/) and [WPGlobus](https://wordpress.org/plugins/wpglobus/)

= The Form =

You can customize the form to your needs by adding or removing fields, or changing properties like the order, the text before or after, and the placeholder text.

The form also offers **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), and [Advanced noCaptcha reCaptcha](https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/).

**Notification emails** can be sent to multiple admins when new testimonials are submitted. New testimonials can also be assigned to a specific category.

The form can be submitted via Ajax so it's compatible with popular plugins like [Popup Maker](https://wordpress.org/plugins/popup-maker/).

= Display =

Everything happens in a **view**. Instead of learning multiple shortcodes with dozens of options, a view contains all the options in a simple, intuitive editor that no other testimonial plugin has. Display the view using a single shortcode or the widget.

You can create unlimited views. For example, one view for the form, another view for a list, another for a slideshow, and so on.

Some of the options for displaying testimonials in a **list** or **slideshow**:

* select which testimonials to show
* set the sort order
* select which fields to include
* extras like pagination
* layout and style options

= Style =

Strong Testimonials offers a handful of templates to try in your theme. The default template is just generic enough to look good in most cases with maybe a few tweaks.

Need help matching your theme? Got some weird spacing or floating? I'm here to help.

For ultimate control and seamless integration, any template can be copied to your theme and customized (a tutorial and more templates coming soon). There is also a template function that can display any view.

= Try these plugins too =

* [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/) to open thumbnails into full-size images. Nice!
* [Simple Custom CSS](https://wordpress.org/plugins/simple-custom-css/) for quick CSS tweaks.
* [Post State Tags](https://wordpress.org/plugins/post-state-tags/) helps quickly differentiate Published / Pending / Draft and more.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu b r e a t h e.

= Translations =

* Arabic (ar_AR) - Ahmad Yousef
* French (fr_FR) - Jean-Paul Radet
* German (de_DE) - Richard Hopp
* Hebrew (he_IL) - Haim Asher
* Persian (fa_IR) - Mahmoud Zooroofchi
* Portuguese (pt_BR) - Mauricio Richieri
* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many, many thanks to these translators.

== Installation ==

1. Go to Plugins > Add New.
1. Search for "strong testimonials".
1. Click "Install Now".

OR

1. Download the zip file.
1. Upload the zip file via Plugins > Add New > Upload.

Activate the plugin. Look for "Testimonials" in the admin menu.

== Frequently Asked Questions ==

= Can I change the fields on the form? =

Yes. There is a custom field editor where you can add or remove fields, change field details, and drag-and-drop to reorder them. You can also customize the form error & success messages and the Submit button.

= After the form has been submitted, can I redirect them to another page? =

Yes.

= I want to use categories. Can I add a category selector to the submission form? =

Yes. After setting up your categories, you can add a category dropdown or checklist to the form.

= Can I edit the notification email that goes out when a new testimonial has been submitted? =

Yes. You can completely customize the email and send it to multiple recipients, or turn it off altogether.

= Can I change which client fields appear below the testimonial? =

Yes. In views, these custom fields can be changed with a few clicks.

= My theme also includes testimonials. Will that be a problem? =

It depends. I have narrowed down the causes of many conflicts and addressed them in the plugin. If you encounter trouble, use the support form or contact me and we'll sort it out. If you want help disabling testimonials in your theme, even better :)

= I'm familiar with template files. Can I customize the template? =

Yes. With some HTML and CSS knowledge, you can copy any template to your theme and customize it as you see fit. You can also have multiple custom templates.

= Can I display a large version of the featured image in a lightbox? =

Yes. This requires a lightbox so if your theme does not include one, you will need a plugin like [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/).

== Screenshots ==

1. Slideshow
2. Default template
3. Default form
4. Admin list table
5. General settings
6. Form settings
7. Fields editor
8. View editor

== Changelog ==

= 2.19 - Mar 18, 2017 =
* Improve add-on integration.
* Prevent conflict between Advanced noCaptcha reCaptcha plugin and page builders.
* Add simple WP-CLI compatibility.
* Add option to clear star rating value.

= 2.18.2 - Feb 26, 2017 =
* Fix bug in conditional loading of star rating stylesheet.

= 2.18.1 - Feb 23, 2017 =
* Fix bug in form success/error scrolling.

= 2.18 - Feb 15, 2017 =
* Add form success redirect options.
* Add visual editor (TinyMCE) to the form success message.
* Process shortcodes in the form success message.
* Fix bug when slideshows are in hidden tabs.
* Fix bug in edit rating script.
* Improve Modern template responsive style.
* Minor refactoring.

= 2.17.5 - Jan 18, 2017 =
* Fix bug when page has multiple slideshows.

= 2.17.4 - Jan 17, 2017 =
* Fix bug in form Ajax.

= 2.17.3 - Dec 28, 2016 =
* Fix bug that allowed unsanitized custom field names.
* Improve admin UI.
* Update Spanish translation.

= 2.17.2 - Dec 14, 2016 =
* Fix incomplete loading of FontAwesome for slideshows.

= 2.17.1 - Dec 14, 2016 =
* Fix bug in plugin activation process.
* Improve category checklist style in default form template.

= 2.17 - Dec 13, 2016 =
* Add category checklist option to form fields.
* Fix bug that allowed double underscores in custom field name.
* Fix date column formatting in testimonials admin list.
* Improve admin UI in fields and view editors.
* Improve slideshow controls style.

= 2.16.5 - Dec 8, 2015 =
* Fix bug when [Max Mega Menu](https://wordpress.org/plugins/megamenu/) plugin installed.

= 2.16.4 - Dec 7, 2016 =
* Fix conflict with [Huge IT Slider](https://wordpress.org/plugins/slider-image/) plugin.
* Remove some unnecessary edge case functions.

= 2.16.3 - Dec 5, 2016 =
* Fix bug when loading form validation translation files.
* Fix bug that initialized slideshows before images are loaded.
* Improve admin UI.

= 2.16.2 - Dec 4, 2016 =
* Strip slashes from custom fields and submitted form values.

= 2.16.1 - Dec 2, 2016 =
* Fix bug in slideshow query.
* Fix conflict with Dream Theme themes.

= 2.16 - Dec 1, 2016 =
* Fix missing icons (start, quotes, pager buttons).
* Fix incorrect WPML string registration (read-more texts).
* Improve compatibility.

== Upgrade Notice ==

Improved compatibility. Add-on plugins available at strongplugins.com.
