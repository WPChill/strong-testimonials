=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial slider, testimonial form, reviews, star ratings
Requires at least: 3.6
Tested up to: 4.8.1
Stable tag: 2.26.10
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Simple yet powerful. Very customizable. Developer-friendly. Free support.

== Description ==

A flexible testimonials plugin that works right out of the box for beginners with advanced features for pros, backed by strong support.

**[Go Demo](https://strongdemos.com/strong-testimonials/)** | **[Knowledge Base](https://support.strongplugins.com/article-category/strong-testimonials/)** | **[Add-ons](https://strongplugins.com/plugins/category/strong-testimonials/)**

### Is this the right plugin for you?

If you are a small business with up to several hundred testimonials or reviews, maybe using categories for different products or services, that needs flexible display options and a customizable form for accepting new testimonials, this plugin will work in just a few steps.

### Primary Features

* No complicated shortcode "language"
* A front-end form with custom fields
* Star ratings
* Slider with several navigation options
* Grid, columns, and Masonry
* Ready for translation with [WPML](https://wpml.org/), [Polylang](https://wordpress.org/plugins/polylang/), and [WPGlobus](https://wordpress.org/plugins/wpglobus/)
* A developer who's on your side :)

### More Features

* Sort by oldest, newest, random, or menu order (drag-and-drop)
* Categories
* Excerpts and "Read more" links
* Featured Images (thumbnails) and Gravatars
* Pagination
* Developer-friendly

### Testimonial Submission Form

Customize the form by adding or removing fields and changing properties like the order, label, and placeholder.

**Anti-spam** measures include honeypots and Captcha via these plugins:

* [Advanced noCaptcha reCaptcha](https://wordpress.org/plugins/advanced-nocaptcha-recaptcha/)
* [Captcha](https://wordpress.org/plugins/captcha/) and [Captcha Pro](https://bestwebsoft.com/products/wordpress/plugins/captcha/)
* [Really Simple Captcha](https://wordpress.org/plugins/really-simple-captcha/)

Send custom **notification emails** to multiple admins.

Submit the form via Ajax to use with popular plugins like [Popup Maker](https://wordpress.org/plugins/popup-maker/).

### Displaying Testimonials

Everything happens in a **view**. Instead of learning multiple shortcodes with dozens of options, a view contains all the options in a simple, intuitive editor that no other testimonial plugin has.

Display the view using a single shortcode or the widget.

Create unlimited views. For example, one view for a form, another for a static grid, another for a slideshow, and so on.

### Style

Strong Testimonials offers a handful of templates to try in your theme. The default template is just generic enough to look good in most cases with maybe a few tweaks.

Need help matching your theme? Got some weird spacing or floating? I'm here to help.

For ultimate control and seamless integration, copy any template to your theme and customize it. There is also a template function `<?php strong_testimonials_view( $id ); ?>`.

### Pro Add-ons

#### Review Markup

Testimonials are essentially five-star reviews. Adding review markup will encourage search engines to display rich snippets in search results. [Learn more](https://strongplugins.com/plugins/strong-testimonials-review-markup/)

#### Multiple Forms

Need more forms for different products or services? No problem. Create unlimited forms. [Learn more](https://strongplugins.com/plugins/strong-testimonials-multiple-forms/)

#### Properties

Want to rename "testimonials" to "reviews"? Want to change the permalink structure? [Learn more](https://strongplugins.com/plugins/strong-testimonials-properties/)

### Support

I will [move mountains](https://strongplugins.com/testimonials/) to help you get the most out of this plugin.

### Try these plugins too

* [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/) to open thumbnails into full-size images. Nice!
* [Simple Custom CSS](https://wordpress.org/plugins/simple-custom-css/) still works great for quick CSS tweaks.
* [Wider Admin Menu](https://wordpress.org/plugins/wider-admin-menu/) lets your admin menu b r e a t h e.

== Installation ==

1. Go to Plugins > Add New.
1. Search for "strong testimonials".
1. Click "Install Now".

OR

1. Download the zip file.
1. Upload the zip file via Plugins > Add New > Upload.

Activate the plugin. Look for "Testimonials" in the admin menu.

== Frequently Asked Questions ==

= What are the shortcodes? =

[testimonial_view] - To display your testimonials as a list or a slideshow, or to display the form. There are no complicated shortcode options. Instead, a **view** manages all the options with an easy-to-use (some call it fun!) editor for creating unlimited views.

`[testimonial_view id=1]`

[testimonial_count] - To display the number of testimonials you have. For example:

`Read some of our [testimonial_count] testimonials!`

= Can I add testimonials from YouTube, Twitter, and Instagram? =

Yes. The plugin supports the [WordPress embed](https://codex.wordpress.org/Embeds) feature for inserting testimonials from [these sources](https://codex.wordpress.org/Embeds#Does_This_Work_With_Any_URL.3F).

= Can I change the fields on the form? =

Yes. There is a custom fields editor to add or remove fields, change field details, and drag-and-drop to reorder them.

= After the form has been submitted, can I redirect them to another page or display a custom message? =

Yes and yes.

= Can I set the status of the newly submitted testimonial? =

Yes, either pending or published.

= Can I reorder my testimonials by drag and drop? =

Yes.

= Can I change the fields that appear below the testimonial? =

Yes. In views, change these custom fields in a few clicks.

= Can I display a large version of the featured image in a popup? =

Yes. This requires a lightbox so if your theme does not include one, you will need a plugin like [Simple Colorbox](https://wordpress.org/plugins/simple-colorbox/).

= Will it automatically use my existing testimonials? =

No. If you already have testimonials in another plugin or theme, you will have to re-enter them. Why? Because every theme and plugin stores data differently.

= Can I import my existing testimonials? =

It depends. The plugin does not provide an import tool because every situation is different. With some technical skills, you may be able to successfully export your existing testimonials to a CSV file and import them into Strong Testimonials. Contact me if you want help with that. Otherwise, it may be simpler and easier to migrate them manually.

= Is it true that including a link to my site in my support requests really helps you troubleshoot problems? =

Undeniably, yes.

This [screenshot](http://www.screencast.com/t/TPMRWM0yug) shows where I immediately start looking for clues before asking for more information and potentially waiting hours or days for a response (it happens).

I can determine what theme you're using, what plugins are active, whether you're using any caching/minification/optimization (do you need to clear your cache?), if there are any JavaScript errors in your theme or another plugin (more common than you may think), and somewhat how the testimonial view is configured.

If you prefer not to post your URL publicly, start a private support ticket at [support.strongplugins.com](https://support.strongplugins.com).

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

= 2.26.10 - Sep 12, 2017 =
* Fix compatibility with Captcha by simplywordpress 4.3.4+.
* Fix compatibility with Polylang string translations when user's admin language is not English.
* Improve flex grid style when last row is less than full.
* Improve rendering of shortcode field type.
* Add post ID of newly submitted testimonial to action hook.

= 2.26.9 - Sep 2, 2017 =
* Fix integration with Polylang 2.1+.
* Indent subcategories in the category selector on the form.

= 2.26.8 - Aug 26, 2017 =
* Fix form whitespace problem in some themes.

= 2.26.7 - Aug 15, 2017 =
* Fix compatibility with Captcha plugin version 4.3.1.

= 2.26.6 - Aug 15, 2017 =
* Trim leading and trailing spaces on form input values.

= 2.26.5 - Aug 2, 2017 =
* Improve adding thumbnail support.

= 2.26.4 - July 31, 2017 =
* Fix bug in WPML String Translations.

= 2.26.3 - July 27, 2017 =
* Fix bug when displaying empty rating in post editor.

= 2.26.2 - July 24, 2017 =
* Fix WPGlobus compatibility.

= 2.26.1 - July 17, 2017 =
* Fix bug in saving checkbox field.

= 2.26 - July 6, 2017 =
* Improve excerpt handling.
* Add option for linking title to testimonial post.
* Improve featured image responsive style.
* Minor UI improvements in view editor.

= 2.25.2 - June 28, 2017 =
* Fix conflict with OptimizePress page builder.
* Remove defer on admin scripts.

= 2.25.1 - June 26, 2017 =
* Fix bug when adding star rating to single template and category archive.

= 2.25 - June 23, 2017 =
* Improve form validation.
  * Update jQuery Validation plugin (1.16.0).
  * Add tabindex.
  * Add custom validation to star rating field.
  * Improve form error indicator style.
* Fix bug that was printing duplicate script variables.
* Make checkbox field text translatable.
* Add hooks and filters in form submission.
* Add option to disable "* Required Field" notice.
* Update link to wordpress.org review form.
* Update style for Review Markup add-on.
* Lint and compress JavaScript files.

See changelog.txt for previous versions.

== Upgrade Notice ==

Fixes minor bugs and compatibility issues.
