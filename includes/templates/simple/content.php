<?php
/**
 * Template Name: Simple
 * Description: A simple template.
 */
?>
<?php do_action( 'wpmtst_before_view' ); ?>

<div class="strong-view <?php wpmtst_container_class(); ?>"<?php wpmtst_container_data(); ?>>
	<?php do_action( 'wpmtst_view_header' ); ?>

	<div class="strong-content <?php wpmtst_content_class(); ?>">
		<?php do_action( 'wpmtst_before_content' ); ?>

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php wpmtst_post_class(); ?>">

				<div class="testimonial-inner">
					<?php do_action( 'wpmtst_before_testimonial' ); ?>

					<?php wpmtst_the_title( '<h3 class="testimonial-heading">', '</h3>' ); ?>

					<div <?php $view = new Strong_View_Slideshow( $atts );
					echo($view->atts['slideshow_settings']['continuous_sliding'] == 1 ) ? esc_attr__('data-infinite-loop=false') : esc_attr__('data-infinite-loop="true"') ; ?>  class="testimonial-content">
						<?php wpmtst_the_thumbnail(); ?>
						<div class="maybe-clear"></div>
						<?php wpmtst_the_content(); ?>
						<?php do_action( 'wpmtst_after_testimonial_content' ); ?>
					</div>

					<?php wpmtst_the_client(); ?>

					<div class="clear"></div>

					<?php do_action( 'wpmtst_after_testimonial' ); ?>
				</div>

			</div>
		<?php endwhile; ?>

		<?php do_action( 'wpmtst_after_content' ); ?>
	</div>

	<?php do_action( 'wpmtst_view_footer' ); ?>
</div>

<?php do_action( 'wpmtst_after_view' ); ?>
