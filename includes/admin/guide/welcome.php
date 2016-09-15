<?php
$plugin_data    = get_plugin_data( WPMTST_DIR . 'strong-testimonials.php', false );
$plugin_version = $plugin_data['Version'];
$major_minor    = strtok( $plugin_version, '.' ) . '.' . strtok( '.' );
?>
<div class="wrap wpmtst welcome">

	<h3 class="large"><?php printf( __( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), $major_minor ); ?></h3>

	<?php do_action( 'wpmtst_guide_before_content' ); ?>

	<h4 class="large"><?php _e( 'Upgrading from version 1?' ); ?></h4>

	<p>The &#91;strong&#93;, &#91;wpmtst&#93; and &#91;read_more&#93; shortcodes and the original widget <span
			style="color: red;">were removed</span> in version 2.0. <a href="https://www.wpmission.com/strong-testimonials-version-2-coming-soon/" target="_blank">Here's why</a>. <em>I promise this is the last major change.</em></p>

	<p>Everything now happens in <a href="<?php echo admin_url('edit.php?post_type=wpm-testimonial&page=testimonial-guide&tab=views' ); ?>">Views</a>.</p>

	<h4 class="large"><?php _e( 'What\'s new' ); ?></h4>

	<p>Star ratings!</p>

	<p>Several features have been added to the form fields.</p>
	<ul>
		<li>Form preview makes it easier to see how the field labels, placeholders, etc. will look.</li>
		<li>Text fields can have a <strong>default form value</strong> to display on the form.</li>
		<li>Text fields can also have a <strong>default display value</strong> to display on the testimonial if that field is left blank on the form.</li>
		<li>Field labels are now optional.</li>
		<li>A field can contain a shortcode to display custom messages or other code snippets.</li>
		<li>The Help tab (in the upper right corner) now contains more helpful information.</li>
	</ul>

	<p>The form now collects the date upon submission. That date is displayed in the post editor "Publish" box and is available as a custom field in the View editor.</p>

	<p>The view editor has a button to copy the view's shortcode to the clipboard!</p>

	<?php do_action( 'wpmtst_guide_after_content' ); ?>

</div>
