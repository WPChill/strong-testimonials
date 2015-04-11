<?php
/**
 * Settings > News page.
 *
 * @since 1.15.5
 * @package Strong_Testimonials
 */
?>
<div class="wrap wpmtst strong-news">

<h2><?php _e( 'Strong Testimonials News', 'strong-testimonials' ); ?></h2>

<h3>Page Builder Integration Continues</h3>

<p>
Drag-and-drop page builder plugins and themes store content differently. This plugin has to find the <code>[strong]</code> shortcodes in each page in order to know which stylesheets and scripts to load. This is called <strong>conditional loading</strong> and it's a best practice that improves your site's speed. Without conditional loading, all the plugin's stylesheets and scripts, like the slideshow and pagination, would be loaded on <em>every page</em> even if they were not needed, resulting in slow, bloated pages.
</p>

<p><em>(I wonder how much internet bandwidth is used for sites without conditional loading?)</em></p>

<table class="news">
<tr>
<th>Works!</th>
<th>Conflict</th>
<th>Untested</th>
</tr>

<tr>
<td>
<ol>
<li><a href="https://wordpress.org/plugins/siteorigin-panels/" target="_blank" rel="nofollow">Page Builder by SiteOrigin</a></li>
<li><a href="http://www.kriesi.at/theme-overview" target="_blank" rel="nofollow">Avia Framework & Enfold theme</a></li>
<li><a href="https://thethemefoundry.com/wordpress-themes/make/" target="_blank" rel="nofollow">Make theme by Theme Foundry</a></li>
<li><a href="http://www.elegantthemes.com/gallery/divi/" target="_blank" rel="nofollow">Elegant Page Builder & Divi theme by Elegant Themes</a></li>
<li><a href="http://cyberchimps.com/responsive-theme/" target="_blank" rel="nofollow">Responsive theme by CyberChimps</a></li>
</ol>
</td>

<td>
<ol>
<li><a href="http://unyson.io/" target="_blank" rel="nofollow">Unyson Framework by ThemeFuse</a></li>
</ol>
</td>

<td>
<ol>
<li>Visual Composer</li>
<li>Velocity Page</li>
<li>Beaver Builder</li>
<li>Themify Builder</li>
<li>Live Composer</li>
<li>Fast Page Layout</li>
<li>Lasso</li>
</ol>
</td>
</tr>
</table>

<p><a href="https://www.wpmission.com/contact" target="_blank">Contact me</a> to suggest a page builder.</p>

<hr>

<h3>Version 1.16: Reorder & Random</h3>

<p>
This version offers drag-and-drop ordering in the testimonial list. It is <em>disabled by default</em> and can be enabled on the Testimonials Settings page.
</p>

<p>
That order is available in the widget and by using <code>menu_order</code> in the <code>[strong]</code> shortcode. The plugin actively prevents these post order plugins from accessing testimonials: <a href="https://wordpress.org/plugins/simple-custom-post-order/" target="_blank" rel="nofollow">Simple Custom Post Order</a>, <a href="https://wordpress.org/plugins/intuitive-custom-post-order/" target="_blank" rel="nofollow">Intuitive Custom Post Order</a>, and <a href="https://wordpress.org/plugins/post-types-order/" target="_blank" rel="nofollow">Post Types Order</a>.
</p>

<p><a href="https://www.wpmission.com/contact" target="_blank">Contact me</a> to suggest a post order plugin.</p>

<p>
This version also moves the random order function from SQL to PHP (from the database to the application) where it belongs. This is important if your web host denies SQL random like <a href="http://wpengine.com/" target="_blank" rel="nofollow">WP Engine</a>.
</p>

<h3>Version 1.17: Views</h3>

<p>
I have taken the feedback to heart. The <code>[strong]</code> shortcode is either too complex or half-baked, depending on your viewpoint.
</p>

<p>
Version 1.17 will introduce views which will replace the shortcode "language" with something like <code>[strong view="1"]</code> and all the settings will be in admin. This has been the goal from the start. It is a better approach for both you and me.
</p>
<p>
The <code>[strong]</code> shortcode will remain for those who like it.
</p>

<h3>Version 1.18</h3>

<p>This version will add a few minor features in preparation for 2.0.</p>

<h3>Version 2.0</h3>

<p>
Strong is my project for learning plugin development and is nowhere near mature. I do not believe in "if it ain't broke, don't fix it". I do not believe in "lite" versions. This plugin will continue to evolve. And I will continue to support you; as much as you allow, anyway.
</p>

<p>
Version 2.0 will use more modern development methods and allow deeper customization and integration with themes and plugins. It will be stronger.
</p>

<hr>

<p>
<em>If you have not already, please consider posting a review and casting a compatibility vote on <a href="https://wordpress.org/plugins/strong-testimonials/" target="_blank" rel="nofollow">wordpress.org</a>.</em>
</p>

<p>I also invite you to join <a href="https://www.wpmission.com/" target="_blank">WP Mission</a> to <a href="https://www.wpmission.com/feature-request" target="_blank">submit feature requests</a> and vote on the future of this plugin. Seriously, your input matters.</p>

<p>Thanks for choosing Strong Testimonials.</p>

<p>
<strong>Chris Dillon</strong><br>
Founder, <a href="https://www.wpmission.com" target="_blank">WP Mission</a><br>
</p>

</div>
