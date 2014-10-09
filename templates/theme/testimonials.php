<?php
/**
 * Testimonials Loop Template.
 *
 * For use in themes.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */

/**
 * For displaying testimonials using the [strong] shortcode (not a widget).
 * Copy this file into your (child) theme directory and modify as needed.
 * 
 * Default:
 * +----------------------------------------------+
 * | heading                                      |
 * +----------------------------------------------+
 * + .content ------------------------------------+ 
 * | photo (floated left)                         |
 * | content (entire, char_limit, or excerpt)     |
 * +----------------------------------------------+
 * + .client -------------------------------------+
 * | client name                                  |
 * | company name / URL                           |
 * +----------------------------------------------+
 */
?>

<div class="strong-container <?php echo $container_class_list; ?>">

	<div class="strong-content <?php echo $content_class_list; ?>">
	
		<?php /* Nested Loop */ ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		
		<div class="<?php echo $post_class_list; ?> post-<?php echo the_ID(); ?>">

			<div class="inner">

				<h3 class="heading"><?php the_title(); ?></h3>

				<?php /* The Content Block */ ?>
				<div class="content">

					<?php /* Thumbnail */ ?>
					<div class="photo"><?php the_post_thumbnail(); ?></div>

					<?php /* Content */ ?>
					<?php //the_content(); ?>
					<?php //the_excerpt(); ?>
					<?php wpmtst_field( 'truncated', array( 'char_limit' => 100 ) ); ?>

				</div><!-- .content -->

				<?php /* The Client Block */ ?>
				<div class="client">
				
					<?php /* Custom Fields */ ?>
					<div class="name"><?php wpmtst_field( 'client_name' ); ?></div>
					<div class="company"><a href="<?php wpmtst_field( 'company_website' ); ?>" target="_blank"><?php wpmtst_field( 'company_name'); ?></a></div>
					
					<?php /* OR */ ?>
					
					<?php /* Child Shortcodes Within [strong] Shortcode */ ?>
					<?php //wpmtst_field( 'client', array( 'content' => $shortcode_content ) ); ?>
					
				</div><!-- .client -->
				
				<div class="clear"></div>
				
			</div><!-- inner -->
			
		</div><!-- testimonial -->
			
		<?php endwhile; ?>
	
	</div><!-- .strong-content -->
	
</div><!-- .strong-container -->

