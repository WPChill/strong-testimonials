<?php
/**
 * Template Name: Large Widget
 * Description: A big bold widget template. Great for slideshows with excerpts & featured images. Try a solid background color.
 * Force: view-layout-normal
 */
?>
<!-- Strong Testimonials: Large Widget Template -->
<div class="strong-view strong-widget large-widget <?php wpmtst_container_class(); ?>">
	<div class="strong-content <?php wpmtst_content_class(); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">
				<div class="testimonial-inner">
					<div class="testimonial-content">
						<?php wpmtst_the_thumbnail(); ?>
						<?php wpmtst_the_content(); ?>
					</div>
					<div class="testimonial-client">
						<?php wpmtst_the_client(); ?>
					</div>
					<?php wpmtst_read_more(); ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php endwhile; ?>

	</div>
</div>
