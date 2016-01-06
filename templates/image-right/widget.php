<?php
/**
 * Template Name: Image on Right Widget
 * Description: A version of the default widget template with the image on the right and client fields on the left.
 * Force: view-layout-normal
 */
?>
<!-- Strong Testimonials: Image on Right Widget Template -->
<div class="strong-view strong-widget image-right-widget <?php wpmtst_container_class(); ?>">
	<div class="strong-content <?php wpmtst_content_class(); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">
				<div class="testimonial-inner">
					<?php wpmtst_the_title( '<h5 class="testimonial-heading">', '</h5>' ); ?>
					<div class="testimonial-content">
						<?php wpmtst_the_thumbnail(); ?>
						<div class="maybe-clear"></div>
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
