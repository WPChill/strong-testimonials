<?php
/**
 * Testimonials loop template.
 *
 * Used by the plugin for the [strong] shortcode. Not for use in a theme.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */
?>
<!-- Strong Testimonials default template -->

<div class="strong-container <?php echo $container_class_list; ?>">

	<div class="strong-content <?php echo $content_class_list; ?>">
	
		<?php /* Nested Loop */ ?>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
		<?php global $post; ?>
		
		<div class="<?php echo $post_class_list; ?> post-<?php echo $post->ID; ?>">
		
			<div class="inner">
			
				<?php if ( $title && $post->post_title ) : ?>
				<h3 class="heading"><?php echo $post->post_title; ?></h3>
				<?php endif; ?>
				
				<div class="content">
				
					<?php if ( $thumbnail && has_post_thumbnail( $post->ID ) ) : ?>
					<div class="photo"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?></div>
					<?php endif; ?>
					
					<?php 
					if ( $excerpt ) : // excerpt overrides length 
						$show_content = $post->post_excerpt;
					elseif( $length ) : // truncated
						$show_content = wpmtst_truncate( $post->post_content, $length );
					else : // entire
						$show_content = wpautop( $post->post_content );
					endif;
					echo do_shortcode( $show_content ); 
					?>

				</div><!-- .content -->
				
				<?php if ( $show_client ) : ?>
				<div class="client">
				<?php echo do_child_shortcode( $parent_tag, $shortcode_content ); ?>
				</div><!-- .client -->
				<?php endif; ?>
				
				<?php if ( $more_post && ( $excerpt || $length ) ) : ?>
				<div class="readmore"><a href="<?php echo get_permalink( $post ); ?>"><?php echo $more_text; ?></a></div>
				<?php endif; ?>

				<div class="clear"></div>
				
			</div><!-- .inner -->
		
		</div><!-- .testimonial -->
		
		<?php endwhile; ?>
	
	</div><!-- .strong-content -->
	
</div><!-- .strong-container -->
