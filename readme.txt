=== Strong Testimonials ===
Contributors: cdillon27
Tags: testimonials, testimonial widget, random testimonial, testimonial shortcode, testimonial slider
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: trunk
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Attract and convert new customers! Collect and display testimonials with a plugin that offers strong features and strong support.

== Description ==

Use your previous customers to bring in new ones with the strong social proof that testimonials provide.

How to get started: Add the submission form to a page to start collecting testimonials. Review and organize them into categories. Position them throughout your site using shortcodes, widgets or templates.

= TL;DR =

Categories, excerpts and Featured Images (thumbnails) are supported. Other display options include random order, pagination and slideshows. There's a customizable form with anti-spam options and a notification email. Shortcodes, widgets, and template files. A deep and wide demo site and a developer who's on your side.

[Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Demos](http://demos.wpmission.com/strong-testimonials/) | [Feature Requests](http://www.wpmission.com/feature-request)

= The Form =

Right out of the box, the testimonial submission form has fields for a name, an email address, a company name and website, a heading, a photo, and of course the testimonial content. Need to add a field? Want to remove one? No problem. Use the fields editor to **customize the form** to your specific situation, including the field order, the text before or after, the placeholder text, and required fields.

> **Example**: A dog trainer serving a wide geographical area added fields for the dog's name and the city to the form which encouraged people to upload pictures of their dog instead of themselves! Displaying the city demonstrated her willingness to travel near and far to help her customers.

The form also offer **anti-spam** options like honeypots and Captcha via these plugins: [Captcha](http://wordpress.org/plugins/captcha/), [Really Simple Captcha](http://wordpress.org/plugins/really-simple-captcha/), [Simple reCaptcha](http://wordpress.org/plugins/simple-recaptcha). The form messages for the required fields, the success and error messages, and even the Submit button are customizable too. It's pretty cool.

= Review, Organize, Publish =

Testimonials are a custom post type that uses the standard post editor, so no new UI to learn there. Just like posts, they can be organized into **categories** for easy segmentation. Categories are by far the best way to show the right testimonials on the right page and to spread them out across the breadth of your site.

Submitted testimonials will be in Pending status by default or they can be published immediately (use with caution!). You can send a **notification email** to the site administrator (or any email address) with submission details.

= Show Them Off =

Show the newest first, the oldest first or in random order. Show one, ten or all of them. Show the full testimonial, the excerpt or up to a specified length - which is great when you have both long and short testimonials. 

With the excerpt and short version options, you can add a "Read more" link to the full testimonial, which works well when you have long testimonials that really tell a story and when you have prospects that do their due diligence and read every word on your site.

*There are a ton of benefits to this approach. Viewing the full testimonial uses the single post template in your theme (or a custom version) so your site's look and feel remains the same, including the previous/next post links if used in your theme. That consistency reflects back on you and only adds to the (hopefully) positive experience your visitors have on your site. Being able to read them one at a time helps your visitors focus on the story, look for similiarities between your customer and themselves, and imagine themselves using the product or service being described.*

Many people sprinkle testimonials around their site and add a link to their full testimonials page. That's a great way to pique someone's interest without bombarding them with all your testimonials up front.

When you have more than ten or so testimonials, a popular approach is to **paginate them**, only showing 5-10 per page. The plugin offers simple pagination controls (1 2 3 ...) that can be placed above and below the testimonial group.

> **Example**: A computer repair guy in business for 15 years had hundreds(!) of wonderful testimonials. Fearing a mammoth page of testimonials would, at best, never be read and, at worst, turn people off, he organized them into categories by year and by service (hardware, network, mobile, etc.). Paginating them allowed people to take their time flipping through them. For the kicker, he compiled the top ten spanning all his years in business into another page so his prospects would quickly appreciate his longevity and experience. A nice balance of quick-and-to-the-point and here's-the-whole-story.

= Slideshow =

A well-thought-out slideshow can be a great selling aide and looks great on an otherwise static page or sidebar without being distracting. Use the **Excerpt** feature to craft quick and simple one-liners that tell prospects exactly what they need to hear from your previous customers. [Really make it sing](http://demos.wpmission.com/strong-testimonials/examples/excerpt-slideshow/) with the **Featured Image**.

Both the shortcode and the widget can be a slideshow. Multiple slideshows can be used on the same page by a shortcode or widget with different styles and speeds. *(Can other testimonial plugins say that?)*

= Style =

Testimonial plugins typically offer either no styling at all or a handful of predefined layouts that may or may not look good in your theme.

Strong Testimonials offers one layout for the shortcode and one for the widget inherited from the original version (GC Testimonials) that is just generic enough to look good in most cases with maybe a few tweaks (I always remove the gradient background ;)). 

Its stylesheets are largely structural and can be easily overridden by your theme or a plugin like [Simple Custom CSS](https://wordpress.org/plugins/simple-custom-css/). You can also skip loading each stylesheet (page, widget, form) and let your theme handle it from the ground up.

*In fact, for any plugin that affects output, I draw the line at around five CSS overrides. Any more than that and I skip its stylesheet and start from scratch. More work up front but easier to maintain long-term. Just my two cents.*

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

Learning a new plugin, especially one that makes heavy use of shortcodes and attributes, can seem like learning Klingon. If you have any trouble, take it one bite at a time with the [demo site](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/full-attribute-list/) as your guide. By all means, use the support forum for help or contact me directly. You will be fluent soon!

= Translations =

The plugin includes a POT file and Hebrew, Russian, Spanish and Swedish translations. It is currently undergoing testing by the generous folks at WPML who will recommend how to make it WPML-compatible.

= Support =

As a technical support veteran and seasoned developer, I am dedicated to your success. I will move mountains to fix bugs, to resolve conflicts with other plugins and themes, and to craft elegant solutions to fragile workarounds. Just ask [Grant](https://wordpress.org/support/topic/great-support-308?replies=2), [hnrocket](https://wordpress.org/support/topic/effective-great-support?replies=2) and [Anna](https://wordpress.org/support/topic/awesome-support-and-solid-plug-in?replies=2).

= Future =

As Strong Testimonials grows, I want it to remain simple enough for beginners yet robust enough for pros. I embrace the challenge.

There is a long list of feature requests and new ones come in every week. I love that! I have some of them organized over at [WP Mission](https://www.wpmission.com/project/strong-testimonials/) so if you have an idea on how to make Strong stronger, please head over there and browse the current feature requests first. Vote for the ones you like and submit new ones. The site is functional but still a little rough around the edges :)

Please keep in mind the original scope of this plugin: to showcase customer testimonials on your site. There are several variations of that and I'm open to hearing them all but I will not throw in everything but the kitchen sink. I believe in the [WordPress philosophy](https://wordpress.org/about/philosophy/).

= The Point =

Putting yourself in the shoes of your prospect may not always be easy but the dividends are clear: Well-planned, well-organized and well-placed testimonials will contribute much more to your site's overall message and hopefully will help convert more prospects into customers than simply throwing a spinning widget in sidebar.

**And that's my mission: to build a strong yet flexible tool to help you do that.**

[Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Demos](http://demos.wpmission.com/strong-testimonials/) | [Feature Requests](http://www.wpmission.com/feature-request)

= The Weaknesses =

The `[strong]` shortcode can be overwhelming at first. There's no shortcode builder thingy in the page editor. *A better solution is almost ready.*

The plugin may seem a bit disjointed; or maybe double-jointed is a better word. The `[strong]` shortcode was meant to replace the original `[wpmtst-*]` shortcodes but I haven't phased those out yet. 

The admin pages for the original shortcodes and settings are mixed in with the admin pages for `[strong]` and the newer features. It can be unclear which settings affect the new shortcode, the old shortcodes, or the widget.

The template files and functions can be vastly improved.

= Recommended =

These plugins work well with Strong Testimonials and add some nice features.

* [Admin Menu Post List](http://wordpress.org/plugins/admin-menu-post-list/) provides a list of your testimonials right there in the admin menu.
* [Debug This](http://wordpress.org/plugins/debug-this/) to peek under the hood when troubleshooting.
* [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) (multisite) ensures the plugin is properly activated in each site in your network.
* [Simple Custom CSS](http://wordpress.org/plugins/simple-custom-css/) is my go-to plugin for quick CSS fixes.
* [Wider Admin Menu](http://wordpress.org/plugins/wider-admin-menu/) lets your admin menu breathe.

= Known Conflicts =

* [Page Builder 1.x by SiteOrigin](https://wordpress.org/plugins/siteorigin-panels/) - The widget settings are not being saved properly in the drag-and-drop builder. Update to the excellent Page Builder 2.x if possible.
* [Warp Framework by YooTheme](https://yootheme.com/themes/warp-framework) - Custom templates for custom post types are not being found since the framework seemingly supplants the WordPress template hierarchy.

= Translations =

In version 1.13+:

* Hebrew (he_IL) - Haim Asher
* Russian (ru_RU) - Матвеев Валерий
* Spanish (es_ES) - Diego Ferrández
* Swedish (sv_SE) - Tom Stone

Many thanks to these translators. Can you help? [Contact me](http://www.wpmission.com/contact/).

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

For help, use the [support forum](http://wordpress.org/support/plugin/strong-testimonials) or [contact me](http://www.wpmission.com/contact/).


== Frequently Asked Questions ==

[Screenshots](http://wordpress.org/plugins/strong-testimonials/screenshots/) | [Demos](http://demos.wpmission.com/strong-testimonials/) | [Feature Requests](http://www.wpmission.com/feature-request)

= How can I change the "Read more" link text? =

For the widget, use the 'strong_widget_read_more_text' filter. For example, add this to your theme's `functions.php`:
`
/**
 * Change the widget "Read more" link text.
 */
function my_strong_widget_read_more_text( $text ) {
	return "More testimonials »";
}
add_filter( 'strong_widget_read_more_text', 'my_strong_widget_read_more_text', 10, 1 );
`

For the `[strong]` shortcode, use the `more_post` and `more_text` attributes like in [this demo](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/read-more-each/).


= I added the `[strong]` shortcode to a page but I don't see the name or company fields. =

The `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

If you're not familiar with HTML or nested shortcodes, it may feel like learning a foreign language. Please refer to the [demo site](http://demos.wpmission.com/strong-testimonials) or ask for help.


= How can I change which client fields appear below the testimonial? =

The `[strong]` shortcode has child shortcodes `[client]` and `[field]`. Here's a good [example](http://demos.wpmission.com/strong-testimonials/the-strong-shortcode/custom-fields/).

For the widget and the original `[wpmtst]` shortcodes, go to the Client Section tab on the Testimonials > Settings page. Follow the example to build shortcodes based on your custom fields. There is a shortcode for text fields (like a client's name) and a shortcode for links (like a client's website). When in doubt, use the default template provided.


= So the child/nested shortcodes for `[strong]` are different than the widget? Why? =

Short answer: I plan to build a single method soon. If the shortcode and the widget are cousins now, they will be more like brothers later.

Long answer: The Client Section shortcodes were a quick-n-simple way to add client fields to both the original `[wpmtst-*]` shortcodes and the widget AFTER custom fields were added in version 1.7. Then I developed the `[strong]` shortcode in order to bring all the options together (a form, a slideshow, multiple selection criteria) into a single shortcode. That opened up the door for child shortcodes to provide even more options for displaying client fields. The customization requests and questions like "Where do I edit the code to..." decreased significantly after that :). 

My plan is to build a tool that allows you to configure a testimonial display component, let's call it a view, and then add that view to a page using a shorter shortcode (!) like `[strong view="1"]` or to a widget using a dropdown selector. 


= Are there templates? How do I change the look of the testimonials? =

The `[strong]` shortcode uses a template file that can be copied into the top directory of your theme, e.g. `wp-content/themes/my-theme/testimonials.php`. You can create multiple template files and include them using a shortcode attribute; e.g. `template="my-template"`. Template functions are also available for adding testimonial fields to new or existing templates.

The original `[wpmtst]` shortcodes do not use template files, but the stylesheets are largely structural so you can add CSS in your theme. In fact, I like to skip loading the stylesheets (in `Testimonials > Settings`) to see how they look in the theme, then style up from there. 


= How can I change "testimonial" to "review", for example? =

Instructions are [here](https://wordpress.org/support/topic/how-to-change-the-slug).


= How can I reorder my testimonials? =

Until I can build this into the plugin, try [Post Types Order](https://wordpress.org/plugins/post-types-order/) by Nsp Code (which lists them on a separate admin page) or [Simple Custom Post Order](https://wordpress.org/plugins/simple-custom-post-order/) by Sameer Humagain (which works directly in the admin list).


= How can I change the fields on the form? =

On the Testimonials > Fields page, there is a field editor where you can add or remove fields, change field details, and drag-n-drop to reorder them. You can also restore the default fields. 

If you have ever used the Advanced Custom Fields or Custom Field Suite plugins, the editor will be very familiar. Here is a full [tutorial](http://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/).


= How do I add a Captcha to the form? =

Select one of the supported plugins on the Testimonials > Settings page. Use the link to each plugin's download page to install it.

If the currently selected Captcha plugin is deactivated, the setting will revert to "none".

If your site relies on a partner plugin like this, and that plugin becomes buggy or abandoned, I will adopt or fork it to keep your site running.

[Contact me](http://www.wpmission.com/contact) to recommend another method or plugin.


= Is this multisite compatible? =

Yes, but I highly recommend first installing the [Proper Network Activation](http://wordpress.org/plugins/proper-network-activation/) plugin when adding Strong Testimonials to a multisite installation. That plugin will deftly handle the plugin activation process, ensuring each site has the default settings.


= Will it import my existing testimonials? =

Not yet. If you have a ton of testimonials, you may want to wait.


= Can I make a donation? =

Thanks but I prefer a nice [review](https://wordpress.org/support/view/plugin-reviews/strong-testimonials). Casting a compatibility vote will tell others it works too. That feedback really helps me understand how people are using my plugins, what works well, and what still needs work.


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

= 1.15.6 - 2015-05-14 =
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

= 1.15.6 =
Fix RTL stylesheet. Prevent error if there are no widgets.

= 1.15.5 =
Improve compatibility with Page Builder by SiteOrigin, Avia Framework, Elegant Themes Page Builder, Make theme by Theme Foundry, Responsive theme by CyberChimps.

= 1.15.4 =
Fix bug in slideshows.

= 1.15.3 =
Fix bug in widget.

= 1.15.2 =
Fix handling of custom widget areas.

= 1.15.1 =
Fix bug in slideshow Javascript.

= 1.15 =
Now works better with Page Builder 2.0. Added some filters.

= 1.14.5 =
Fixed slideshow pause on hover.

= 1.14.4 =
RTL stylesheet not included by default.

= 1.14.3 =
Fixed JavaScript error in slideshow.

= 1.14.2 = 
Fixed honeypot bug. Add Hebrew translation and right-to-left stylesheets.

= 1.14.1 =
Definitely update if you are using the Spacious theme or Page Builder plugin.

= 1.14 =
On the notification email, you can now edit the subject line, the message, and the "From" name and email address.

= 1.13.4 =
Fixed conflict with Spacious theme.

= 1.13.3 =
Fixed conflict with iThemes Exchange plugin.

= 1.13.2 =
Minor fixes. Updated translation files.

= 1.13.1 =
Bug fix.

= 1.13 =
Option to change the labels and messages on the form. Improved POT file. Russian, Spanish, and Swedish translations.

= 1.12 =
One bug fixed, two conflicts prevented, two minor features.

= 1.11.5 =
Important update for WordPress 4.0.1 that fixes client shortcode problem.

= 1.11.4 =
Removed the fix for potential widget problem; needs more testing.

= 1.11.3 =
Fixed a conflict with Roki theme. Fixed a potential display problem in the widget for slow-loading pages.

= 1.11.2 =
SECURITY VULNERABILITY FIXED. PLEASE UPDATE! The file upload on the submission form is now restricted to image file types only (jpg|jpeg|jpe|gif|png).

= 1.11.1 =
Fixed a bug in [wpmtst-cycle] category query.

= 1.11 =
Testing new [strong] shortcode. Added template functions and a default template file for use in themes. Added `rel="nofollow"` option. Improved memory usage, queries, honeypots, and server-side form validation. Added a .pot file.

= 1.10 =
Added honeypot spam control. Added category parameter to form shortcode.

= 1.9.3 =
Added a guide to help new users.

= 1.9 =
The widget now has the same features as the cycle shortcode, and the cycle process has been made more compatible with other plugins and themes.

= 1.8.1 =
Fixed a minor bug in Internet Explorer 7 and 8.

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
