<?php
function wpmtst_welcome() {
	?>
	<div class="wrap wpmtst welcome">
		
		<h1><?php _e( 'Thanks for updating Strong Testimonials', 'strong-testimonials' ); ?></h1>
		
		<h2>Here's what's new:</h2>
		
		<h3>Views</h3>
		
		<p>Views combine the simplicity of the widget with the power of the <code>[strong]</code> shortcode.</p>
		
		<p>Views also include new templates, and better template functions allow easier customization.</p>
		
		<p>You can select a size for the Featured Image (thumbnail) or use a custom size. You can open the full-size image in a lightbox.</p>
		
		<h3>Multilingual</h3>
		
		<p>The plugin is now compatible with WPML and Polylang, so the form can be translated into multiple languages.</p>
		
		<h3><a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=guide&tab=views' ); ?>">Learn more in the new Guide</a></h3>
	</div>
	<?php
}