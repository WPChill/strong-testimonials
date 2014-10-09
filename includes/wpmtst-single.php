<?php
/**
 * Default single testimonial template.
 *
 * Used by [wpmtst-*] shortcodes, not for use in a theme.
 * Will be deprecated when [strong] shortcode is fully functional.
 *
 * @package Strong_Testimonials
 * @since 1.11.0
 */
?>
<div class="testimonial <?php echo $content; ?>">

	<div class="inner">

		<?php // The title ?>
		<?php if ( $title && $post->post_title ) : ?>
		<h3 class="heading"><?php echo $post->post_title; ?></h3>
		<?php endif; ?>

		<?php // The content block ?>
		<div class="content">

			<?php // Thumbnail ?>
			<?php if ( $images && isset( $post->thumbnail_id ) ) : ?>
			<div class="photo"><?php echo get_the_post_thumbnail( $post->ID, 'thumbnail' ); ?></div>
			<?php endif; ?>

			<?php
			// Excerpt, Character limit, or Entire content
			if ( 'excerpt' == $content ) :
				echo $post->post_excerpt;
			elseif( 'truncated' == $content ) :
				echo wpmtst_truncate( $post->post_content, $char_limit );
			else :
				echo wpautop( $post->post_content );
			endif;
			?>

		</div><!-- .content -->

		<?php // The client block (client shortcodes have already been processed) ?>
		<?php if ( $client ) : ?>
		<div class="client"><?php echo $client_info; ?></div>
		<?php endif; ?>

		<?php // The "Read more" link ?>
		<?php if ( 2 == $more ) : ?>
		<div class="readmore"><a href="<?php get_permalink( $more_page ); ?>">Read more</a></div>
		<?php elseif ( 1 == $more ) : ?>
		<div class="readmore"><a href="<?php get_permalink( $post ); ?>">Read more</a></div>
		<?php endif; ?>

		<div class="clear"></div>
		
	</div><!-- inner -->
	
</div><!-- testimonial -->
