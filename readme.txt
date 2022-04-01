=== Strong Testimonials ===
Contributors: wpchill,silkalns,cdillon27
Tags: testimonials, testimonial slider, testimonial form, star ratings
Requires at least: 5.2
Requires PHP: 5.6
Tested up to: 5.9
Stable tag: 2.51.9
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Simple yet powerful. Very customizable. Developer-friendly.

== Description ==

In just a few steps, you will be collecting and publishing your testimonials or reviews. Beginners and pros alike will appreciate the wealth of flexible features refined over 4 years from user feedback and requests.

= SEE HOW EASY IT IS TO GET STARTED WITH STRONG TESTIMONIALS =
https://www.youtube.com/watch?v=3nyvRvoxMxY

> **Premium features only available in Strong Testimonials - Paid version:**
>
> * Import reviews from: Facebook, Google My Business, Yelp, Zomato and WooCommerce
> - _With our dedicated 3-rd party importer, you can now automate importing, managing and displaying of your testimonials._
> * Custom Testimonial Collection Form Fields
> - _The perfect example is with a car review website. Using the Custom Fields extension, you can gather detailed testimonials that include information such as car manufacturer, model, and horsepower._
> * Multiple Testimonial Collection Forms
> - _You can now create multiple forms to collect testimonials from your website visitors and customers. You can easily create new forms to gather testimonials for different types of products on your website. There is no limit to the number of forms you can create or use._
> * Schema.org Markup
> - _The extension that will help you get **** in your Google search results for your business._
> * Advanced Views
> - _ For example, one view to display your testimonials, another view for the testimonial submission form, another view for a slideshow widget._
> * Properties
> - _You are not a fan of calling your product reviews testimonials? Then use this extension to replace the default text (testimonials) with another one such as reviews or ratings._
> * Captcha anti-spam form Protection
> - _Protection for spam is something all websites need_
> * PRO templates
> - _Beautifully designed & pixel perfect templates, ready to use with to showcase your testimonials. Don't loose clients with a poor design._
> * Priority email support
> * Support and updates for 12 months.
>
>**[Learn more about Strong Testimonials - Paid version .](https://strongtestimonials.com/pricing?utm_source=wordpress.org&utm_medium=web&utm_campaign=lite)**

### Style

This plugin provides a few designs with only basic style options for background color and font color. Everything else will be inherited from your theme.

Some templates have light & dark versions and other options. If you want to customize things like fonts, margins and borders, you will need custom CSS.

### Testimonial Submission Form

This plugin provides one form with custom fields. Customize the form by adding or removing fields and changing properties like the order, label, and placeholder.

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

== 3rd party or external service disclaimer ==

The plugin connects to our website through an API call (https://strongtestimonials/wp-json/mt/v1/get-all-extensions) in order to request a list of available extensions.

IT DOES NOT SEND ANY DATA NOR DO WE COLLECT INFORMATION FROM THE REQUEST

Our privacy policy can be found at this URL https://strongtestimonials.com/privacy-policy/

== Installation ==

1. Go to Plugins > Add New.
1. Search for "strong testimonials".
1. Click "Install Now".

OR

1. Download the zip file.
1. Upload the zip file via Plugins > Add New > Upload.

Activate the plugin. Look for "Testimonials" in the admin menu.

== Frequently Asked Questions ==

= How to Add the Form ? = 		

  1. Check the custom fields. The default set of fields are designed to suit most situations. Add or remove fields as you see fit.		

  2. Create a view. Select Form mode.		

  3. Add the view to a page or sidebar using its unique shortcode or the Strong Testimonials widget.		

  = How to Display Your Testimonials ? =		

  1. Enter your testimonials if necessary. The plugin will not read existing testimonials from another plugin or theme. It will not import testimonials.		

  2. Create a view. Select Display mode.		

  3. Add the view to a page or sidebar using its unique shortcode or the Strong Testimonials widget.		

  = How to Add a Slideshow ? =		

  1. Enter your testimonials if necessary. The plugin will not read existing testimonials from another plugin or theme. It will not import testimonials.		

  2. Create a view. Select Slideshow mode.		

  3. Add the view to a page or sidebar using its unique shortcode or the Strong Testimonials widget.		

  = How to Translate ? =		

  Strong Testimonials is compatible with WPML, Polylang and WP Globus.		

  In WPML and Polylang, domains are added to the String Translation pages. Those domains encompass the form fields, the form messages, the notification email, and the "Read more" link text in your views. They are updated automatically when any of those settings change.
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

* See [changelog.txt](https://github.com/WPChill/strong-testimonials/blob/dev/changelog.txt) for previous versions.

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
