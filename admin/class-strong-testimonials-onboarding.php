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
				<img src="<?php echo esc_url( WPMTST_ADMIN_URL ) .'img/onboarding/WPChill Onboarding Wave.png';?>" class="wpmtst-onboarding-title-icon" /> <span><?php esc_html_e( 'Hi, there!', 'strong-testimonials' ); ?></span>
			</div>
			<div class="wpmtst-onboarding-text-wrap">
				<p><?php esc_html_e( 'With its intuitive interface and wide range of features, wpmtst makes it easy to create a professional-looking gallery that showcases your photos and videos in the best light. Whether you want to share your work with friends, family, or potential clients, wpmtst can help you create a gallery that impresses and engages.', 'strong-testimonials' ); ?></p>
			</div>
			<div class="wpmtst-onboarding-banner-wrap">
				<img src="<?php echo esc_url( WPMTST_ADMIN_URL ) .'img/onboarding/WPChill onboarding Banner.png';?>" class="wpmtst-onboarding-banner" />
			</div>
			<div class="wpmtst-onboarding-button-wrap">
				<a href="<?php echo esc_url( $new_gal_url ); ?>" class="wpmtst-onboarding-button"><?php esc_html_e( 'Create your first testimonial', 'strong-testimonials' ); ?></a>
			</div>
			<div class="wpmtst-onboarding-doc-wrap">
				<p class="wpmtst-onboarding-doc" ><?php echo sprintf( esc_html__( 'Need help? Check out %s our documentation%s.', 'strong-testimonials' ),  '<a href="https://wpmtst.helpscoutdocs.com/" target="_blank">','</a>' ); ?></p>
			</div>
		</div>
		<?php
	}
}

