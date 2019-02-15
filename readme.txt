=== Strong Testimonials ===
Contributors: machothemes,silkalns,cdillon27
Tags: testimonials, testimonial slider, testimonial form, star ratings
Requires at least: 4.6
Requires PHP: 5.6
Tested up to: 5.1
Stable tag: 2.36
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Simple yet powerful. Very customizable. Developer-friendly.

== Description ==

**Strong Testimonials** is a standalone plugin built, maintained & operated by the friendly folks over at [MachoThemes](https://www.machothemes.com/)

In just a few steps, you will be collecting and publishing your testimonials or reviews. Beginners and pros alike will appreciate the wealth of flexible features refined over 4 years from user feedback and requests.

### Primary Features

* No complicated shortcodes
* A front-end form
* Custom fields
* Star ratings
* Slider & carousel with several navigation options
* Grids and Masonry
* Ready for translation with [WPML](https://wpml.org/), [Polylang](https://wordpress.org/plugins/polylang/), and [WPGlobus](https://wordpress.org/plugins/wpglobus/)

### More Features

* Sort by oldest, newest, random, or menu order (drag-and-drop)
* Categories
* Excerpts and "Read more" links
* Featured Images (thumbnails) and Gravatars
* Pagination
* Embeds (YouTube, Twitter, Instagram, Facebook)
* Custom capabilities
* Developer-friendly (actions, filters, templates)

### Style

This plugin provides a few designs with only basic style options for background color and font color. Everything else will be inherited from your theme.

Some templates have light & dark versions and other options. If you want to customize things like fonts, margins and borders, you will need custom CSS.

### Testimonial Submission Form

This plugin provides one form with custom fields. Customize the form by adding or removing fields and changing properties like the order, label, and placeholder.

Anti-spam measures include honeypots and Captcha via these plugins:

* [Google Captcha (reCAPTCHA) by BestWebSoft](https://wordpress.org/plugins/google-captcha/) *recommended*
* [Captcha Pro](https://bestwebsoft.com/products/wordpress/plugins/captcha/)
* [Really Simple Captcha](https://wordpress.org/plugins/really-simple-captcha/)

Send custom notification emails to multiple admins.

Submit the form via Ajax for use with plugins like [Popup Maker](https://wordpress.org/plugins/popup-maker/).

### Displaying Testimonials

**Everything happens in a View**. Instead of learning multiple shortcodes with dozens of options, a View contains all the options in a simple, intuitive editor that no other testimonial plugin has.

Create unlimited views. For example, one view for a form, another for a static grid, another for a slideshow, and so on.

Display a view using a shortcode or the widget.

A variety of templates are included that work well in most themes.

For ultimate control and seamless integration, copy any template to your theme and customize it.

The template function will add a view to your custome theme templates:

`<?php if ( function_exists( 'strong_testimonials_view' ) ) {
    strong_testimonials_view( $id );
} ?>`

### Privacy and GDPR

By default, this plugin:

* does not store any user or visitor data,
* does not send any data to remote servers, act as a service or embed content,
* does not edit the comments form in any way.

The plugin also prevents displaying email addresses in your testimonials on the front end.

However, this plugin provides features that may involve private data.

* If you use the testimonial submission form, the data collected on that form will be stored in your database.
* If you enable the administrator notification email upon new testimonial submission, the data collected on your form, at your selection, may be included in that notification email.
* If you enable comments on testimonials, the plugin will use your theme's single post template and comment form.
* If you embed third-party posts such as Twitter, YouTube and FaceBook in your testimonials, you will be agreeing to the Terms of Use of those third-party sites.


### Try these plugins too

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

[testimonial_view] - To display your testimonials as a list or a slideshow, or to display the form. The first step is to create a **view** which manages all the options in an easy-to-use (some call it fun!) editor.

`[testimonial_view id=1]`

[testimonial_count] - To display the number of testimonials you have. For example:

`Read some of our [testimonial_count] testimonials!`

[testimonial_average_rating] - To display the average rating of all your testimonials. Includes stars!

= Can I show more than one testimonial in the slider (i.e. a carousel)? =

Yes. you can show 1, 2 or 3 at a time and you can scroll 1, 2 or 3 at a time.

= Can I add testimonials from YouTube, Twitter, Instagram and Facebook? =

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

Yes. This requires a lightbox so if your theme does not include one, you will need a lightbox plugin.

= Will it automatically use my existing testimonials? =

No. If you already have testimonials in another plugin or theme, you will have to re-enter them. Why? Because every theme and plugin stores data differently.

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

* See changelog.txt for previous versions.

== Upgrade Notice ==

= 2.32 =
New feature: Show multiple slides at the same time in a carousel.

= 2.32.1 =
* Fix bug in translation of slider text controls.

= 2.32.2 =
New adjustable responsive breakpoints for carousels.

= 2.32.3 =
Fix stretched slide height in carousel.

= 2.32.4 =
Improved compatibility with WordPress Custom HTML widgets.