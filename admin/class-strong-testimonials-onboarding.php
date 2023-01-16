<?php
if(!class_exists('WP_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
if(!class_exists('WP_Posts_List_Table')){
	require_once( ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php' );
}

class WPMTST_Onboarding extends WP_Posts_List_Table
{

	// Your custom list table is here
	public function display() {

		$new_gal_url = admin_url('post-new.php?post_type=wpm-testimonial');
		?>
		<div class="wpmtst-onboarding-wrapper">

			<div class="wpmtst-onboarding-title">
				<img src="<?php echo esc_url( WPMTST_ADMIN_URL ) .'img/onboarding/WPChill-Onboarding-Wave.png';?>" class="wpmtst-onboarding-title-icon" /> <span><?php esc_html_e( 'Hi, there!', 'strong-testimonials' ); ?></span>
			</div>
			<div class="wpmtst-onboarding-text-wrap">
				<p><?php esc_html_e( 'In just a few steps, you will be collecting and publishing your testimonials or reviews. Beginners and pros alike will appreciate the wealth of flexible features refined over 4 years from user feedback and requests.', 'strong-testimonials' ); ?></p>
			</div>
			<div class="wpmtst-onboarding-banner-wrap">
				<img src="<?php echo esc_url( WPMTST_ADMIN_URL ) .'img/onboarding/st-banner.png';?>" class="wpmtst-onboarding-banner" />
			</div>
			<div class="wpmtst-onboarding-button-wrap">
				<a href="<?php echo esc_url( $new_gal_url ); ?>" class="wpmtst-onboarding-button"><?php esc_html_e( 'Create your first testimonial', 'strong-testimonials' ); ?></a>
			</div>
			<div class="wpmtst-onboarding-doc-wrap">
				<p class="wpmtst-onboarding-doc" ><?php echo sprintf( esc_html__( 'Need help? Check out %s our documentation%s.', 'strong-testimonials' ),  '<a href="https://strongtestimonials.com/docs/" target="_blank">','</a>' ); ?></p>
			</div>
		</div>
		<?php
	}
}

