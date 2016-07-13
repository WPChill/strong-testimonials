<?php
/**
 * Template Name: Default Widget
 * Description: The default widget template.
 * Force: view-layout-normal
 */
?>
<!-- Strong Testimonials: Default Widget Template -->
<div class="strong-view strong-widget default-widget <?php wpmtst_container_class(); ?>">
	<div class="strong-content <?php wpmtst_content_class(); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">
				<div class="testimonial-inner">
					<?php do_action( 'wpmtst_before_testimonial' ); ?>
					<?php wpmtst_the_title( '<h5 class="testimonial-heading">', '</h5>' ); ?>
					<div class="testimonial-content">
						<?php wpmtst_the_thumbnail(); ?>
						<div class="maybe-clear"></div>
						<?php wpmtst_the_content(); ?>
					</div>
					<div class="testimonial-client">
						<?php wpmtst_the_client(); ?>
					</div>
					<div class="clear"></div>
					<?php do_action( 'wpmtst_after_testimonial' ); ?>
				</div>
			</div>
		<?php endwhile; ?>

	</div><!-- .strong-content -->
	<?php wpmtst_read_more_page(); ?>
</div><!-- .strong-view -->
