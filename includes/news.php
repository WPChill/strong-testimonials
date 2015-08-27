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

	<section>
		<h3>The road ahead: In clay, not stone.</h3>
		
		<h4>Version 1.18</h4>
	
		<p>This was an important update for compatibility with WordPress 4.3.</p>
	
		<p>This version introduced two highly requested features: <strong>multiple email notifications</strong> when
			new testimonials have been submitted, and <strong>a category selector</strong> that can be added to the
			submission form.</p>
	
		
		<h4>Version 1.19 <em style="color: #CD0000;">(current)</em></h4>
	
		<p>This version improves compatibility with themes and other plugins by allowing you to <strong>customize the shortcode tag</strong>. For example, you can change it from <code>[strong]</code> to <code>[my_testimonials]</code>, <code>[reviews]</code>, or even a single character like <code>[T]</code>.</p>
	
		<p>WordPress does not handle shortcode collisions at all. So if your theme also provides a <code>[strong]</code> shortcode (for <strong>bolding</strong> text) it will override this plugin's shortcode, as themes are loaded <em>after</em> plugins. Customizing the shortcode will resolve that conflict. <a href="https://www.wpmission.com/contact" target="_blank">Contact me</a> for help if you would prefer to disable a shortcode in your theme.</p>
		
		<h4>Version 1.20</h4>
	
		<p>This version will add a few minor but highly requested features.</p>
	
		
		<h4>Version 1.21</h4>
	
		<p>I have taken the feedback to heart. The <code>[strong]</code> shortcode is either too complex or half-baked, depending on your viewpoint.</p>
	
		<p>This version will introduce <strong>views</strong> which will replace the shortcode "language" with something like <code>[strong view="1"]</code> and all the settings will be in admin. This has been the goal from the start. It is a better approach for both you and me.</p>
	
		<p>The <code>[strong]</code> shortcode and all its options will remain for those who like it.</p>
		
	
		<h4>Version 2.0</h4>
	
		<p>Strong is my project for learning plugin development and is nowhere near mature. I do not believe in "if it ain't broke, don't fix it". I do not believe in "lite" versions. This plugin will continue to evolve. And I will continue to support you; as much as you allow, anyway.</p>
	
		<p>Version 2.0 will use more modern development methods and allow deeper customization and integration with themes and plugins. It will be stronger.</p>
	</section>
	
	<section>
		<h3>Page Builder Integration Continues</h3>

		<p>Drag-and-drop page builder plugins and themes store content differently. This plugin has to find the <code>[strong]</code> shortcodes in each page in order to know which stylesheets and scripts to load. This is called <strong>conditional loading</strong> and it's a best practice that improves your site's speed. Without conditional loading, all the plugin's stylesheets and scripts, like the slideshow and pagination, would be loaded on <em>every page</em> even if they were not needed, resulting in slow, bloated pages.</p>
	
		<p><em>(I wonder how much internet bandwidth is used for sites without conditional loading?)</em></p>
	
		<p><a href="https://www.wpmission.com/contact" target="_blank">Contact me</a> to suggest a page builder.</p>
	
		<table class="news">
			<tr>
				<th>Works!</th>
				<th>Conflict</th>
				<th>Requested</th>
			</tr>
	
			<tr>
				<td>
					<ol>
						<li><a href="http://www.kriesi.at/theme-overview" target="_blank" rel="nofollow">Avia Framework &
								Enfold theme</a></li>
						<li><a href="https://wordpress.org/plugins/black-studio-tinymce-widget/" target="_blank"
						       rel="nofollow">Black Studio TinyMCE Widget</a></li>
						<li><a href="http://www.elegantthemes.com/gallery/divi/" target="_blank" rel="nofollow">Elegant Page
								Builder & Divi theme by Elegant Themes</a></li>
						<li><a href="http://goodlayers.com/" target="_blank" rel="nofollow">GoodLayers themes</a>
						<li><a href="https://thethemefoundry.com/wordpress-themes/make/" target="_blank" rel="nofollow">Make
								theme by Theme Foundry</a></li>
						<li><a href="https://wordpress.org/plugins/siteorigin-panels/" target="_blank" rel="nofollow">Page
								Builder by SiteOrigin</a></li>
						<li><a href="http://cyberchimps.com/responsive-theme/" target="_blank" rel="nofollow">Responsive
								theme by CyberChimps</a></li>
						<li><a href="http://vc.wpbakery.com/" target="_blank" rel="nofollow">Visual Composer by WPBakery</a>
						</li>
					</ol>
				</td>
	
				<td>
					<ol>
						<li><a href="http://unyson.io/" target="_blank" rel="nofollow">Unyson Framework by ThemeFuse</a>
						</li>
					</ol>
				</td>
	
				<td>
					<ol>
						<li>Beaver Builder</li>
						<li>Fast Page Layout</li>
						<li>Lasso</li>
						<li>Live Composer</li>
						<li>Layers by Obox</li>
						<li>Themify Builder</li>
						<li>Velocity Page</li>
						<li>Upfront by WPMU Dev</li>
					</ol>
				</td>
			</tr>
		</table>
	</section>

	<section>
		<h3>Thanks for choosing Strong Testimonials.</h3>
	
		<p>If you have not already, please consider posting a review and casting a compatibility vote on <a href="https://wordpress.org/plugins/strong-testimonials/" target="_blank" rel="nofollow">wordpress.org</a>.</em></p>
	
		<p>I also invite you to join <a href="https://www.wpmission.com/" target="_blank">WP Mission</a> to <a href="https://www.wpmission.com/feature-request" target="_blank">submit feature requests</a> and vote on the future of this plugin. Seriously, your input matters.</p>
	
		<p>
			<strong>Chris Dillon</strong><br>
			Founder, <a href="https://www.wpmission.com" target="_blank">WP Mission</a><br>
		</p>
	</section>

</div>
