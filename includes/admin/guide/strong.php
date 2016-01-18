<div class="guide-content strong-shortcode">

	<div class="update-nag strong">
		<h2>The [strong] shortcode has reached the end of its capacity and will be removed soon.</h2>
		<p>All future development will be in <b><a href="<?php echo admin_url( 'edit.php?post_type=wpm-testimonial&page=views'); ?>">Views</a></b>. Take a few minutes to convert your shortcodes now and reap the rewards later.</p>
		<p>This decision was not made lightly and I believe it is necessary to move forward. ~ <i><a href="http://www.wpmission.com/contact" target="_blank">Chris</a></i></p>
	</div>

	<section>
		<h3><?php _e( 'The [strong] Shortcode', 'strong-testimonials' ); ?></h3>
		<p><?php _e( 'The <code>[strong]</code> shortcode is unique and versatile. Most attributes act as on/off switches. Think of it as constructing a sentence.', 'strong-testimonials' ); ?></p>
		<p><?php _e( 'If a page has multiple <code>[strong]</code>\'s, each one must be closed; e.g. <code>[strong &hellip; /]</code> or <code>[strong &hellip;][/strong]</code>.', 'strong-testimonials' ); ?></p>
		<table class="reference first wide">
			<?php include 'form.php'; ?>
			<?php include 'display.php'; ?>
			<?php include 'style.php'; ?>
			<?php include 'slideshow.php'; ?>
		</table>
		<table class="reference alternate wide">
			<?php include 'child.php'; ?>
		</table>
		<table class="reference wide">
			<?php include 'read_more.php'; ?>
		</table>
	</section>
</div>
