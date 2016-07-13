<?php
/**
 * Template Name: Unstyled
 * Description: A completely unstyled template for CSS experts. If using this template in a slideshow, you will probably need to add some structural CSS &ndash; use the default template as a guide.
 */
?>
<!-- Strong Testimonials: Unstyled Template -->
<div class="strong-view unstyled <?php wpmtst_container_class(); ?>">
	<div class="strong-content <?php wpmtst_content_class(); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">
				<div class="testimonial-inner">
					<?php do_action( 'wpmtst_before_testimonial' ); ?>
					<?php wpmtst_the_title( '<h3 class="testimonial-heading">', '</h3>' ); ?>
					<div class="testimonial-content">
						<?php wpmtst_the_thumbnail(); ?>
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
