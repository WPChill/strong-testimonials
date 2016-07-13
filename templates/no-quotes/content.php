<?php
/**
 * Template Name: No Quotes
 * Description: A version of the default template without the quote symbol in the heading.
 */
?>
<!-- Strong Testimonials: No Quotes Template -->
<div class="strong-view no-quotes <?php wpmtst_container_class(); ?>">
	<div class="strong-content <?php wpmtst_content_class(); ?>">

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">
				<div class="testimonial-inner">
					<?php do_action( 'wpmtst_before_testimonial' ); ?>
					<?php wpmtst_the_title( '<h3 class="testimonial-heading">', '</h3>' ); ?>
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
