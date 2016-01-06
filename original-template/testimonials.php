<?php
/**
 * Template Name: Original Template
 *
 * @author Chris Dillon chris@wpmission.com
 * @package Strong_Testimonials
 * @since 1.11.0
 *
 * This template is used by the [strong] shortcode when it has attributes like "title", "thumbnail", etc.
 *
 * With [testimonial_view], use the /templates/default/testimonials.php template instead. It's much better :)
 */
?>
<div class="strong-container <?php echo $container_class_list; ?>">
	<div class="strong-content <?php echo $content_class_list; ?>">
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php global $post; ?>
			<div class="<?php echo $post_class_list; ?> post-<?php echo $post->ID; ?>">
				<div class="inner">
					<?php if ( $title && $post->post_title ) : ?>
						<h3 class="heading"><?php echo $post->post_title; ?></h3>
					<?php endif; ?>
					<div class="content">
						<?php if ( $thumbnail && has_post_thumbnail( $post->ID ) ) : ?>
							<div class="photo"><?php echo get_the_post_thumbnail( $post->ID, $thumbnail_size ); ?></div>
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
					</div>
					<?php if ( $show_client ) : ?>
						<div class="client">
							<?php echo do_child_shortcode( $parent_tag, $shortcode_content ); ?>
						</div>
					<?php endif; ?>
					<?php if ( $more_post && ( $excerpt || $length ) ) : ?>
						<div class="readmore"><a href="<?php echo get_permalink( $post ); ?>"><?php echo $more_text; ?></a></div>
					<?php endif; ?>
					<?php if ( $more_page ) : ?>
						<?php
						if ( !is_numeric( $more_page ) ) {
							$page = get_page_by_path( $more_page );
							$more_page = $page->ID;
						}
						?>
						<div class="readmore"><a href="<?php echo get_permalink( $more_page ); ?>"><?php echo $more_text; ?></a></div>
					<?php endif; ?>
					<div class="clear"></div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>
</div>
