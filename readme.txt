=== GC Testimonials ===
Contributors: Erin Garscadden
Donate link: http://tinyurl.com/knmhaj9
Tags: testimonials, testimonial manager, user testimonials, testimonial form, manage testimonials
License: GPLv2
Requires at least: 3.0
Tested up to: 3.4.1
Stable tag: trunk

GC Testimonials is an easy to use Testimonial Manager that allows you to easily gather and display testimonials on your WordPress site. 

== Description ==

GC Testimonials is an easy to use Testimonial Manager that allows you to easily gather and display testimonials on your WordPress site.

It has been built around shortcodes so it is easy to display what you want, where you want, on your website. 

You can add, edit and delete testimonials, with the option to add photos to your testimonials.

There is an included submission form shortcode to allow you to display a submission form on your website for your visitors to submit their own testimonials. When a user submits their own testimonial, the status is set to 'Pending' ready for you to review and publish.

= Main Features =

* Easy to use and manage your testimonials
* Add photos to your testimonials
* Use of shortcodes for flexibility of display
* Testimonial Submission form
* Email Notification on public testimonial submissions
* Widget to show testimonials in a sidebar
* Custom pagination amount on full page display

= The widget allows you to =

* Select a testimonial category to display testimonials from.
* Choose to show your testimonials as static, or cycle through them with jQuery
* Enter the number of testimonials to show
* Show or Hide testimonial photos
* Add a "Read More" link and a page to send users to

Please see the <a href="http://wordpress.org/extend/plugins/gc-testimonials/faq/">FAQ's</a> for shortcode usage.

= To our loyal users =

Thanks for all the great reviews and support!

We are currently setting up a support system to help with questions & answers for this plugin. The support has not been so great and we do apologize, but we are addressing this now and actively working on the plugin. 

= Watch this space… something is coming… =

== Installation ==

1. 	Upload the 'gc-testimonials' folder to '/wp-content/plugins' directory.
1.	Activate the plugin through the 'Plugins' menu in WordPress.
1.  Place shortcodes in your page or post where you would like to display the testimonial, or use the widget to display testimonials in the sidebar.

== Screenshots ==

1. Add the submission form to a page or post for your users to submit their own testimonials.
2. Edit your testimonials the same as you would your posts and pages.
3. All of your testimonials are kept in one place for ease of use.
4. Add the shortcode [ full-testimonials ] to a page to display a full testimonials page with inbuilt pagination.
5. Add the widget to your sidebar to show more testimonials!
6. Look for the handy 'quick reference' shortcodes displayed, ready for you to copy and paste into your page.
7. Email notification settings for when a user submits a testimonial from your site.

== Frequently Asked Questions ==

= How do I add a single testimonial? =

You can add a single testimonial by using the shortcode: [single-testimonial id="xx"]

This is used to show a single specific testimonial on your page or post. The shortcode requires the ID number of the testimonial. 

You will find this shortcode on the testimonials screen next to each of your posts.

= How do I add a random testimonial? =

You can add a random testimonial by using the shortcode: [random-testimonial category="xx" limit="x"]

This can take an optional category ID for when you want to display testimonials from a specific category. You can also optionally set how many testimonials you would like to show by changing the "limit" attribute. Default is 1.

= How do I show a full page of testimonials? =

You can add the full page testimonial display by using the shortcode: [full-testimonials category="xx"]

This is used to show a page with all your testimonials displayed. The shortcode can take an optional category ID for when you want to display testimonials from a specific category. You will find this shortcode on the categories screen next to each of your Testimonials Categories.

Full testimonials are displayed in a list of 5 per page, with built in pagination.

= How do I add the Submission Form? =

You can add the Testimonial Submission Form by using the shortcode: [testimonial-form]

This shortcode is used to show a form on your site where a user can submit their own testimonial. Once a testimonial has been submitted, the status will be set to "Pending" and will need to be approved by an administrator before it is shown publicly.

== Changelog == 

= V1.3.2 - 03.10.2013 =

* Bug fix for conflict with some plugins (using the the_content filter)

= V1.3.1 - 02.10.2013 =

* General bug fixes done to main plugin code
* Localization added (Thanks to Tom Stone)
* Settings Panel added
* Pagination - custom per page amount added
* Email notifications added
* Custom Character count added to widget
* Formatting bug fixed on widget

= V1.3 - 26.09.2012 =

* Added word limit (50) for the widget
* Excluded testimonials from search

= V1.2 - 14.08.2012 =

* Fixed CSS issue for random/single testimonial
* Changed loading of JS/CSS to pages the shortcode & widget are used only.

= V1.1 - 11.08.2012 =

* Fixed issue with widget categories not working & cycle option not saving.
* Admin icon now showing correctly.
* Added shortcode to show random testimonial.

= V1.0 - 31.07.2012 =
* First Release