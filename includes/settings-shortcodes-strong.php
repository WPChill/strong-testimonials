<?php
/**
 * Settings > Shortcodes Page > Strong Shortcode Tab
 *
 * @since 1.11.0
 * @package Strong_Testimonials
 */
?>

<h3 class="gutter"><code>[strong]</code> <?php _e( 'The new shortcode that does it all.', 'strong-testimonials' ); ?></h3>

<div id="tabs">

	<ul>
		<li><a href="#tabs-notes"><?php _e( 'Notes', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-form"><?php _e( 'The Form', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-display"><?php _ex( 'Display', 'verb', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-slideshow"><?php _e( 'Slideshow', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-readmore"><?php _e( 'Read More', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-child"><?php _e( 'Child Shortcodes', 'strong-testimonials' ); ?></a></li>
		<li><a href="#tabs-examples"><?php _e( 'Examples', 'strong-testimonials' ); ?></a></li>
	</ul>

	<?php include( 'tabs/notes.php' ); ?>
	<?php include( 'tabs/form.php' ); ?>
	<?php include( 'tabs/display.php' ); ?>
	<?php include( 'tabs/slideshow.php' ); ?>
	<?php include( 'tabs/readmore.php' ); ?>
	<?php include( 'tabs/child.php' ); ?>
	<?php include( 'tabs/examples.php' ); ?>

</div><!-- #tabs -->
