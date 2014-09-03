<?php
/**
 * Strong Testimonials - Guide page
 */

/*
 * The Guide page
 * @since 1.9.1
 */ 
function wpmtst_guide() {
	?>
	<div class="wrap wpmtst guide">
		<h2>Strong Testimonials Guide</h2>

		<?php $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'getting-started'; ?>
		<h2 class="nav-tab-wrapper">
			<a href="?post_type=wpm-testimonial&page=guide" class="nav-tab <?php echo $active_tab == 'getting-started' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Getting Started', 'strong-testimonials' ); ?></a>
			<a href="?post_type=wpm-testimonial&page=guide&tab=simple" class="nav-tab <?php echo $active_tab == 'simple' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Simple', 'strong-testimonials' ); ?></a>
			<a href="?post_type=wpm-testimonial&page=guide&tab=advanced" class="nav-tab <?php echo $active_tab == 'advanced' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Advanced', 'strong-testimonials' ); ?></a>
			<a href="?post_type=wpm-testimonial&page=guide&tab=future" class="nav-tab <?php echo $active_tab == 'future' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Future', 'strong-testimonials' ); ?></a>
		</h2>

		<?php
		if( 'future' == $active_tab ) {
			wpmtst_guide_future();
		}
		elseif( 'advanced' == $active_tab ) {
			wpmtst_guide_advanced();
		}
		elseif( 'simple' == $active_tab ) {
			wpmtst_guide_simple();
		}
		else {  // 1st tab
			wpmtst_guide_start();
		}
		?>
	</div>
	<?php
}


function wpmtst_guide_start() {
	?>
	<div class="guide-content start">
	
		<p><em>View some examples on the <a href="http://demos.wpmission.com/strong-testimonials/" target="_blank">demo site</a>.</em></p>
		
		<h3>A testimonial is like a blog post.</h3>
		<p>They have many of the same fields and features:</p>
		<ul>
			<li><strong>A Title</strong></li>
			<li><span class="permalink">a permalink</a></li>
			<li>&ldquo;Some content.&rdquo;</li>
			<li><em>...an excerpt...</em></li>
			<li><div class="dashicons dashicons-format-image"></div> a featured image (thumbnail)</li>
			<li>custom fields <span class="help">(like client name and email)</span></li>
			<li>categories</li>
		</ul>
		
		<h3>How do you want to use testimonials?</h3>
		<p>The <strong><a href="edit.php?post_type=wpm-testimonial&page=guide&tab=simple">Simple</a></strong> tab shows you how to quickly start adding testimonials to your site.</p>
		<p>The <strong><a href="edit.php?post_type=wpm-testimonial&page=guide&tab=advanced">Advanced</a></strong> tab describes more features like custom fields and excerpts.</p>
		
		<h3>You are invited to participate.</h3>
		<p>Coming soon, the direction of this plugin will be in your hands as you will be able to vote on feature requests.</p>
		
	</div>
	<?php
}


function wpmtst_guide_simple() {
	?>
	<div class="guide-content simple">

		<p><em>View some examples on the <a href="http://demos.wpmission.com/strong-testimonials/" target="_blank">demo site</a>.</em></p>

		<h3>Add testimonials to your site in three simple steps.</h3>
		<p>Use the default settings to hit the ground running.</p>
		<table class="guide" cellspacing="0">
			<tr>
				<th><span>1</span></th>
				<td class="step">Gather</td>
				<td>To accept new testimonials, add the shortcode [wpmtst-form] to a page.</td>
			</tr>
			<tr>
				<th><span>2</span></th>
				<td class="step">Publish</td>
				<td>Review and publish new submissions and/or enter existing testimonials manually.</td>
			</tr>
			<tr>
				<th><span>3</span></th>
				<td class="step">Display</td>
				<td>Add a shortcode like [wpmtst-all] to a page and/or add a widget to a sidebar.</td>
			</tr>
		</table>
		
	</div>
	<?php
}


function wpmtst_guide_advanced() {
	?>
	<div class="guide-content">
	
		<p><em>View some examples on the <a href="http://demos.wpmission.com/strong-testimonials/" target="_blank">demo site</a>.</em></p>

		<h3>Make your testimonials sing using advanced features.</h3>
		<table class="guide">
			<tr>
				<th><span>1</span></th>
				<td class="step">Gather</td>
				<td class="features">
					<ul class="features">
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=fields">Fields</a></strong> - Customize the form to meet your needs. <a href="http://www.wpmission.com/tutorials/customize-the-form-in-strong-testimonials/" target="_blank">Here</a> is a full tutorial.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial">Featured Images</a></strong> - AKA thumbnails. Allow your clients to include a photo or a logo with their testimonial.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=settings">Anti-spam</a></strong> - Add a Captcha to the form using a plugin.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=settings">Notification</a></strong> - Automatically send an email to the site administrator when a new testimonial has been submitted and is pending review.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th><span>2</span></th>
				<td class="step">Publish</td>
				<td class="features">
					<ul class="features">
						<li><strong><a href="edit-tags.php?taxonomy=wpm-testimonial-category&post_type=wpm-testimonial">Categories</a></strong> - Organize your testimonials by year, salesperson, event, etc.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial">Excerpts</a></strong> - Available in the post editor, use excerpts to create attention-grabbing one-liners for use in widgets and the cycle shortcode.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=settings">Skip Loading Stylesheets</a></strong> - The included stylesheets include a default layout. Skip loading the stylesheets if you want to style everything in your theme or <a href="http://wordpress.org/plugins/simple-custom-css/">custom CSS</a>.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th><span>3</span></th>
				<td class="step">Display</td>
				<td class="features">
					<ul class="features">
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=shortcodes">Shortcodes</a></strong> - Several ways to show your testimonials.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=settings&tab=cycle">Cycle Shortcode</a></strong> - Add a testimonial slider to any page.</li>
						<li><strong><a href="widgets.php">Widgets</a></strong> - The widget has the same features as the cycle shortcode. Multiple widgets can used on the same page.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial&page=settings&tab=client">Client Section</a></strong> - Select which fields (including your custom fields) appear below the testimonial content.</li>
						<li><strong><a href="edit.php?post_type=wpm-testimonial">As Posts</a></strong> - Since a testimonial is a type of post, it can be displayed using your theme's default post template or a custom template, included pagination.</li>
					</ul>
				</td>
			</tr>
		</table>
		
	</div>
	<?php
}


function wpmtst_guide_future() {
	?>
	<div class="guide-content future">
	
		<h3>Here's some of what we have planned for upcoming versions.</h3>
		<ol>
			<li>A client section editor similar to the custom fields editor.</li>
			<li>More anti-spam options.</li>
			<li>Auto-publish option (bypass administrator review).</li>
			<li>Manual reordering of testimonials.</li>
			<li>Hooks and filters for deeper customization.</li>
			<li>Microdata for SEO benefits.</li>
			<li>Improved pagination options.</li>
			<li>Star rankings (i.e. 0 - 5 stars)</li>
			<li>Import existing testimonials from a variety of other plugins.</li>
			<li>More layout and style options.</li>
		<ol>
		
	</div>
	<?php
}