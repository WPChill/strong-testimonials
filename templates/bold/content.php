<?php
/**
 * Template Name: Large Widget
 * Description: A big bold widget template. Great for slideshows with excerpts and featured images. Try a solid background color.
 * Force: view-layout-normal
 */
?>
<?php 
do_action( 'wpmtst_before_view' ); 
$view = new Strong_View_Slideshow( $atts );

if( class_exists( 'Strong_Testimonials_Pro') && 1 == $view->atts['st_pro_filters'] && 'display' == $view->atts['mode'] ) {
	$class_check = '';
}else {
	$class_check = "wpmtst_post_class";
}

?>

<div class="strong-view strong-widget <?php wpmtst_container_class(); ?>"<?php wpmtst_container_data(); ?>>
	<?php do_action( 'wpmtst_view_header' ); ?>

	<div class="strong-content <?php wpmtst_content_class(); ?>">
		<?php do_action( 'wpmtst_before_content',$view ) ?>
		

		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<div class="<?php ('wpmtst_post_class' == $class_check) ?  $class_check() : $class_check ?>">
		
			<?php do_action('wpmtst_before_testimonial_inner', $view, $post) ?>
			<div class="testimonial-inner">
				<?php do_action( 'wpmtst_before_testimonial' ); ?>

				<div class="testimonial-content">
					<?php wpmtst_the_thumbnail(); ?>
					<?php wpmtst_the_content(); ?>
					<?php do_action( 'wpmtst_after_testimonial_content' ); ?>
				</div>

				<?php wpmtst_the_client(); ?>
			
				<div class="clear"></div>
				<?php do_action( 'wpmtst_after_testimonial' ,$view); ?>
			 </div>
		</div>
		<?php endwhile; ?>
		
		<?php do_action( 'wpmtst_after_content',$view ) ?>
	</div>

	<?php do_action( 'wpmtst_view_footer' ); ?>
</div>

<?php do_action( 'wpmtst_after_view' ); ?>
