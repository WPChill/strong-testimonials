<?php
/**
 * Template Name: Modern
 * Description: A modern template designed for slideshows or single testimonials. Looks great with manual or automatic excerpts.
 */


$continuous_slide = ( isset( $atts['slideshow_settings']['continuous_sliding'] ) && 1 === (int) $atts['slideshow_settings']['continuous_sliding'] ) ? 'true' : 'false';

do_action( 'wpmtst_before_view' );
?>
<div class="strong-view <?php wpmtst_container_class(); ?>"<?php wpmtst_container_data(); ?>>
	<?php do_action( 'wpmtst_view_header' ); ?>

	<div class="strong-content <?php wpmtst_content_class(); ?>">
		<?php do_action( 'wpmtst_before_content', $atts ); ?>

		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			?>
			<div class="<?php wpmtst_post_class( $atts ); ?>">
				<div class="wpmtst-testimonial-inner testimonial-inner">
					<?php do_action( 'wpmtst_before_testimonial' ); ?>

					<div <?php echo ( 'slideshow' === $atts['mode'] ) ? 'data-infinite-loop="' . esc_attr( $continuous_slide ) . '"' : ''; ?>  class="wpmtst-testimonial-content  testimonial-content">
						<?php wpmtst_the_title( 'h3', 'wpmtst-testimonial-heading testimonial-heading' ); ?>
											
						<?php wpmtst_the_content(); ?>
						<?php do_action( 'wpmtst_after_testimonial_content' ); ?>
					</div>

					<?php wpmtst_the_thumbnail(); ?>

					<?php wpmtst_the_client(); ?>

					<div class="clear"></div>

					<?php do_action( 'wpmtst_after_testimonial', $atts ); ?>
				</div>

			</div>
		<?php endwhile; ?>

		<?php do_action( 'wpmtst_after_content', $atts ); ?>
	</div>

	<?php do_action( 'wpmtst_view_footer' ); ?>
</div>

<?php do_action( 'wpmtst_after_view' ); ?>
