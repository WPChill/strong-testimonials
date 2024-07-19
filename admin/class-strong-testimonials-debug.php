<?php


class Strong_Testimonials_Debug {

	/**
	 * Holds the class object.
	 *
	 * @since 3.1.15
	 *
	 * @var object
	 */
	public static $instance;

	/**
	 * Strong_Testimonials_Debug constructor.
	 *
	 * @since 3.1.15
	 */
	function __construct() {

		add_action( 'admin_init', array( $this, 'wpmtst_export_testimonial' ) );

		/* Fire our meta box setup function on the post editor screen. */
		add_action( 'load-post.php', array( $this, 'debug_meta_box_setup' ) );
		add_action( 'load-post-new.php', array( $this, 'debug_meta_box_setup' ) );

		// Hide debug testimonial by default
		add_filter( 'hidden_meta_boxes' , array( $this, 'hide_meta_box' ), 10, 2 );
	}



	/**
	 * Returns the singleton instance of the class.
	 *
	 * @return object The Strong_Testimonials_Debug object.
	 * @since 3.1.15
	 */
	public static function get_instance() {

		if ( !isset( self::$instance ) && !( self::$instance instanceof Strong_Testimonials_Debug ) ) {
			self::$instance = new Strong_Testimonials_Debug();
		}

		return self::$instance;

	}

	/**
	 * Export single testimonial
	 *
	 * @since 3.1.15
	 */
	public function wpmtst_export_testimonial(){

		if ( isset( $_GET['wpmtst_single_download'] ) ){

			// WXR_VERSION is declared here
			require_once ABSPATH . 'wp-admin/includes/export.php';

			$post = get_post( absint( $_GET['wpmtst_single_download'] ) );

			if ( !$post || 'wpm-testimonial' != $post->post_type ){
				return;
			}

			global $wpdb;

			$testimonial_name = sanitize_key( $post->post_name );
			if ( !empty( $testimonial_name ) ){
				$testimonial_name .= '.';
			}
			$date        = gmdate( 'Y-m-d' );
			$wp_filename = $testimonial_name . $date . '.xml';

			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $wp_filename );
			header( 'Content-Type: text/xml; charset=' . get_option( 'blog_charset' ), true );

			echo '<?xml version="1.0" encoding="' . esc_html( get_bloginfo( 'charset' ) ) . "\" ?>\n";

			?>
			<!-- This is a WordPress eXtended RSS file generated by WordPress as an export of your site. -->
			<!-- It contains information about your site's posts, pages, comments, categories, and other content. -->
			<!-- You may use this file to transfer that content from one site to another. -->
			<!-- This file is not intended to serve as a complete backup of your site. -->

			<!-- To import this information into a WordPress site follow these steps: -->
			<!-- 1. Log in to that site as an administrator. -->
			<!-- 2. Go to Tools: Import in the WordPress admin panel. -->
			<!-- 3. Install the "WordPress" importer from the list. -->
			<!-- 4. Activate & Run Importer. -->
			<!-- 5. Upload this file using the form provided on that page. -->
			<!-- 6. You will first be asked to map the authors in this export file to users -->
			<!--    on the site. For each author, you may choose to map to an -->
			<!--    existing user on the site or to create a new user. -->
			<!-- 7. WordPress will then import each of the posts, pages, comments, categories, etc. -->
			<!--    contained in this file into your site. -->

			<rss version="2.0"
				 xmlns:excerpt="http://wordpress.org/export/<?php echo esc_html( WXR_VERSION ); ?>/excerpt/"
				 xmlns:content="http://purl.org/rss/1.0/modules/content/"
				 xmlns:wfw="http://wellformedweb.org/CommentAPI/"
				 xmlns:dc="http://purl.org/dc/elements/1.1/"
				 xmlns:wp="http://wordpress.org/export/<?php echo esc_html( WXR_VERSION ); ?>/"
			>

				<channel>
					<title><?php bloginfo_rss( 'name' ); ?></title>
					<link><?php bloginfo_rss( 'url' ); ?></link>
					<description><?php bloginfo_rss( 'description' ); ?></description>
					<pubDate><?php echo esc_html( gmdate( 'D, d M Y H:i:s +0000' ) ); ?></pubDate>
					<language><?php bloginfo_rss( 'language' ); ?></language>
					<wp:wxr_version><?php echo esc_html( WXR_VERSION ); ?></wp:wxr_version>

					<?php

					$title = $post->post_title;

					?>
					<item>
						<title><?php echo esc_html( $title ); ?></title>
						<link><?php the_permalink_rss(); ?></link>
						<pubDate><?php echo mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ); ?></pubDate>
						<dc:creator><?php echo $this->wxr_cdata( get_the_author_meta( 'login' ) ); ?></dc:creator>
						<guid isPermaLink="false"><?php the_guid(); ?></guid>
						<description></description>
						<wp:post_id><?php echo (int)$post->ID; ?></wp:post_id>
						<wp:post_date><?php echo $this->wxr_cdata( $post->post_date ); ?></wp:post_date>
						<wp:post_date_gmt><?php echo $this->wxr_cdata( $post->post_date_gmt ); ?></wp:post_date_gmt>
						<wp:comment_status><?php echo $this->wxr_cdata( $post->comment_status ); ?></wp:comment_status>
						<wp:ping_status><?php echo $this->wxr_cdata( $post->ping_status ); ?></wp:ping_status>
						<wp:post_name><?php echo $this->wxr_cdata( $post->post_name ); ?></wp:post_name>
						<wp:status><?php echo $this->wxr_cdata( $post->post_status ); ?></wp:status>
						<wp:post_parent><?php echo (int)$post->post_parent; ?></wp:post_parent>
						<wp:menu_order><?php echo (int)$post->menu_order; ?></wp:menu_order>
						<wp:post_type><?php echo $this->wxr_cdata( $post->post_type ); ?></wp:post_type>
						<wp:post_password><?php echo $this->wxr_cdata( $post->post_password ); ?></wp:post_password>
						<?php
						$postmeta = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID ) );
						foreach ( $postmeta as $meta ) :
							/**
							 * Filters whether to selectively skip post meta used for WXR exports.
							 *
							 * Returning a truthy value from the filter will skip the current meta
							 * object from being exported.
							 *
							 * @param bool   $skip     Whether to skip the current post meta. Default false.
							 * @param string $meta_key Current meta key.
							 * @param object $meta     Current meta object.
							 *
							 * @since 3.3.0
							 *
							 */
							if ( apply_filters( 'wxr_export_skip_postmeta', false, $meta->meta_key, $meta ) ){
								continue;
							}
							?>
							<wp:postmeta>
								<wp:meta_key><?php echo $this->wxr_cdata( $meta->meta_key ); ?></wp:meta_key>
								<wp:meta_value><?php echo $this->wxr_cdata( $meta->meta_value ); ?></wp:meta_value>
							</wp:postmeta>
						<?php
						endforeach;
						?>
					</item>
				</channel>
			</rss>
			<?php
			die();
		}
	}

	/**
	 * Wrap given string in XML CDATA tag.
	 *
	 * @param string $str String to wrap in XML CDATA tag.
	 *
	 * @return string
	 * @since 3.1.15
	 *
	 */
	private	function wxr_cdata( $str ){
		if ( !seems_utf8( $str ) ){
			$str = utf8_encode( $str );
		}
		// $str = ent2ncr(esc_html($str));
		$str = '<![CDATA[' . str_replace( ']]>', ']]]]><![CDATA[>', wp_kses_post( $str ) ) . ']]>';

		return $str;
	}

	/**
	 * Add Debug metabox
	 *
	 * @since 3.1.15
	 */
	public function debug_meta_box_setup() {

		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array( $this, 'add_debug_meta_box' ),10 );

	}

	/**
	 * Add Debug metabox
	 *
	 * @since 3.1.15
	 */
	public function add_debug_meta_box() {
		add_meta_box(
				'wpmtst-debug',      // Unique ID
				esc_html__('Debug testimonial', 'strong-testimonials'),    // Title
				array( $this, 'output_debug_meta' ),   // Callback function
				'wpm-testimonial',         // Admin page (or post type)
				'side',         // Context
				'low'         // Priority
		);

	}

	/**
	 * Default hidden debug metabox
	 *
	 * @since 3.1.15
	 */
	public function hide_meta_box( $hidden, $screen ) {
		$user_id = get_current_user_id();
		if ($user_id === 0) {
			return $hidden;
		}
	
		$user_meta = get_user_meta( $user_id, 'metaboxhidden_wpm-testimonial', true );

		//make sure we are dealing with the correct screen
		if ( ( 'post' === $screen->base ) && ( 'wpm-testimonial' === $screen->id ) && in_array( 'wpmtst-debug', $user_meta ) ) {
			$hidden[] = 'wpmtst-debug';
		}

		return $hidden;
	}

	/**
	 * Output the Debug testimonial metabox
	 *
	 * @since 3.1.15
	 */
	public function output_debug_meta(){
		?>
		<div class="wpmtst-upsells-carousel-wrapper">
			<div class="wpmtst-upsells-carousel">
				<div class="wpmtst-upsell wpmtst-upsell-item">
					<p class="wpmtst-upsell-description"><?php echo esc_html__( 'Export the testimonial and send it to Strong Testimonial\'s support team so that we can debug your problem much easier.', 'strong-testimonials' ); ?></p>
					<p>
						<a href="<?php echo esc_url( add_query_arg( array(
								'wpmtst_single_download' => absint( get_the_ID() ),
						) ) ); ?>"
						   class="button"><?php esc_html_e( 'Export testimonial', 'strong-testimonials' ) ?></a>

					</p>
					<?php do_action('wpmtst_debug_metabox_content'); ?>
				</div>
			</div>
		</div>
		<?php
	}

}

$st_debug = Strong_Testimonials_Debug::get_instance();