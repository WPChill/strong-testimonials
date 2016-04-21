<div class="guide-content start">

	<?php do_action( 'wpmtst_guide_before_content' ); ?>

	<section>
		<h3><?php _e( 'How to display your testimonials', 'strong-testimonials' ); ?></h3>

		<p><?php _e( '1. Enter any existing testimonials and optional categories.', 'strong-testimonials' ); ?></p>

		<p><?php _e( '2. Create a new <b>View</b> to display them in a list or a slideshow.', 'strong-testimonials' ); ?></p>

		<p><?php _e( '3. Display the View using its <b>shortcode</b> or a <b>widget</b>.', 'strong-testimonials' ); ?></p>

		<p><?php
			$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-guide&tab=views' );
			printf( wp_kses( __( 'Views provide many features. Learn more on the <a href="%s">Views</a> tab or jump right in!', 'strong-testimonials' ), $tags ), esc_url( $url ) );
			?></p>

		<h3><?php _e( 'How to add a testimonial submission form', 'strong-testimonials' ); ?></h3>

		<p><?php _e( '1. Create a new View and select <b>Form</b> mode.', 'strong-testimonials' ); ?></p>

		<p><?php _e( '2. Display the View using its <b>shortcode</b> or a <b>widget</b>.', 'strong-testimonials' ); ?></p>

		<p><?php
			$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-fields' );
			printf( wp_kses( __( '3. Go to the <a href="%s">Fields</a> screen if you want to customize the form fields.', 'strong-testimonials' ), $tags ), esc_url( $url ) );
		?></p>

		<p><?php
			$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form' );
			printf( wp_kses( __( 'The form has more options like messages and anti-spam on the <a href="%s">Settings</a> screen.', 'strong-testimonials' ), $tags ), esc_url( $url ) );
			?></p>

	</section>

</div>
