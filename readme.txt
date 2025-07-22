=== Strong Testimonials ===
Contributors: wpchill,silkalns,cdillon27
Tags: testimonial plugin, testimonials slider, testimonial form, star ratings
Requires at least: 5.2
Requires PHP: 5.6
Tested up to: 6.8
Stable tag: 3.2.14
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==
## An easy-to-use testimonial plugin to collect and show customer feedback in WordPress 

Easily collect and publish testimonials in just a few steps. Packed with flexible features refined over the years, perfect for beginners and pros alike!

= SEE HOW EASY IT IS TO GET STARTED WITH STRONG TESTIMONIALS =
## Creating your first testimonials collection form
https://www.youtube.com/watch?v=_DmoHH6iE4w&list=PLM2tOjfhVrZcgMyoeC_M7yUii1QJW8nfH&index=3
## Displaying your testimonials
https://www.youtube.com/watch?v=zIb0RQv2-pY&list=PLM2tOjfhVrZcgMyoeC_M7yUii1QJW8nfH&index=1


## Premium features only available in Strong Testimonials - Paid version:

* [Import reviews from external sources](https://strongtestimonials.com/kb/testimonial-importer/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Import+reviews): With our dedicated 3-rd party importer, you can now automate importing, managing, and displaying your testimonials from
Google, Facebook, Booking, Airbnb, Yelp, Trustpilot, Capterra, and G2.
* [Custom Testimonial Collection Form Fields](https://strongtestimonials.com/kb/custom-fields-2/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Custom+form+fields): Easily assign custom fields to a testimonial view if you want to add extra fields to your testimonial submission forms and optionally display this alongside testimonials on your website.
The perfect example is with a car review website. Using the Custom Fields extension, you can gather detailed testimonials that include information such as car manufacturer, model, and horsepower.
* [Multiple Testimonial Collection Forms](https://strongtestimonials.com/kb/creating-multiple-forms/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Multiple+forms): You can now create multiple forms to collect testimonials from your website visitors and customers. You can easily create new forms to gather testimonials for different types of products on your website. There is no limit to the number of forms you can create or use.
* [Advanced Views](https://strongtestimonials.com/kb/fields-reordering/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Advanced+views): Easily define the display order of your testimonial fields. Re-order the name, image, URL, and testimonial content fields through drag & drop.
* [Properties](https://strongtestimonials.com/kb/properties/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Properties): If you are not a fan of calling your product reviews testimonials, then use this extension to replace the default text (testimonials) with another one such as reviews or ratings.
* [Captcha anti-spam form Protection](https://strongtestimonials.com/kb/adding-spam-control-to-your-forms/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Captcha): Protection for spam is something all websites need.
* [Templates](https://strongtestimonials.com/kb/pro-templates/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Templates): Beautifully designed & pixel-perfect templates, ready to use to showcase your testimonials. Don't lose clients with a poor design.
* [Infinite Scroll](https://strongtestimonials.com/kb/infinite-scroll/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Infinite+scroll): Using this extension you can control the number of testimonials that are visible on a pages first load. As the user starts scrolling down the page, more testimonials are brought into view using a continuous loading animation.
* [Enhanced emails](https://strongtestimonials.com/kb/enhanced-emails/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=Enhanced+emails): Send a thank you email to your client once their testimonial is approved. Increase brand loyalty by showing you really care about your clients. Keep your clients engaged and increase your chances of selling more.
* Priority email support.
* Support and updates for 12 months.

**[Learn more about Strong Testimonials - Paid version .](https://strongtestimonials.com/pricing/?utm_source=wordpress.org&utm_medium=link&utm_campaign=description&utm_term=ST+pro)**

### About the testimonials style

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
= 3.2.14 - 22.07.2025 -
- Fixed: Undefined key "color" if background preset was not chosen.
- Added: Accessibility controls for slider buttons.

= 3.2.13 - 15.07.2025 -
- Fixed: Star rating icons were not displaying correctly due to an escaping issue.

= 3.2.12 - 10.07.2025 -
- Fixed: Security issue.

= 3.2.11 - 04.06.2025 -
- Fixed: Compatibility with plugins that edit wp_kses_post allowed html list. eg. "The Post Grid".
- Fixed: Slider image lazy-load not working properly.
- Fixed: Pagination was not working on the front page.
- Update: jQuery Validate to the latest version.

= 3.2.10 - 28.05.2025 -
- Fixed: Compatibility issue with the average rating shortcode when using multiple category attributes.
- Added: Option to display the 'Read More' button inline with the testimonial content.
- Added: Submission date column for testimonials in the admin list table.

= 3.2.9 - 20.05.2025 -
- Fixed: Missing notification system files in wp.org deploy.

= 3.2.8 - 19.05.2025 -
- Fixed: Admin list table broken if the testimonial had a table as post content.
- Added: Added previously removed config update functions.
- Fixed: Prevent fatal error if wpmtst_custom_forms is not array.

= 3.2.7 - 19.03.2025 -
- Fixed: Ensured only valid social platform image URLs are inserted into testimonials, preventing broken images.

= 3.2.6 - 17.03.2025 -
- Added: Integrated WPChill Notification System.
- Fixed: Gravatar overwriting platform user images.

= 3.2.5 - 06.03.2025 -
- Fixed: Inline script not being loaded with admin view script registration. 

= 3.2.4 - 12.02.2025 -
- Fixed: Security issue

= 3.2.3 - 09.01.2025 -
- Fixed: Testimonial stars rating edit triggering js error. ( [#493](https://github.com/WPChill/strong-testimonials/issues/493) )

You can read the complete changelog [here](https://github.com/WPChill/strong-testimonials/blob/master/changelog.txt)

== Upgrade Notice ==

= 3.2.4 =
- This version introduced a fix for a reported security vulnerability.