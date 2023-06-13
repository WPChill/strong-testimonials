<?php
/**
 * Challenge main class
 *
 * @since 2.6.8
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class ST_Challenge_Modal{


    public function __construct(){

        if( is_admin() && $this->show_challenge() && 1 == get_option( 'wpmtst-challenge', 1 ) ){
            add_action( 'admin_footer', array( $this, 'challenge_render' ), 99 );
            add_action( 'admin_enqueue_scripts', array( $this, 'challenge_scripts' ) );
            add_action( 'wp_ajax_wpmtst_challenge_hide', array( $this, 'wpmtst_challenge_hide' ) );
        }

    }

    public function challenge_render(){

        $new_gallery_url = add_query_arg(
            array(
                'post_type'        => 'wpm-testimonial',
            ),
            admin_url( 'post-new.php' )
        );

        ?>
        <div class="wpmtst-challenge-wrap">
            <div class="wpmtst-challenge-header">
                <span id="wpmtst-challenge-close" class="dashicons dashicons-no-alt"></span>
                <p><?php echo wp_kses_post( __( 'Start enjoying <strong>Strong Testimonials</strong> by adding your first testimonial.', 'strong-testimonials' ) ); ?></p>
            </div>
            <div class="wpmtst-challenge-list">
                <p><span class="wpmtst-challenge-marker"></span> <?php esc_html_e( 'Primul lucru din lista', 'strong-testimonials' ); ?></p>
                <p><span class="wpmtst-challenge-marker"></span> <?php esc_html_e( 'Al 2-lea lucru din lista', 'strong-testimonials' ); ?></p>
                <p><span class="wpmtst-challenge-marker"></span> <?php esc_html_e( 'Al 3-lea lucru din lista', 'strong-testimonials' ); ?></p>
                <p><span class="wpmtst-challenge-marker"></span> <?php esc_html_e( 'Al 4-lea lucru din lista', 'strong-testimonials' ); ?></p>
                <p><span class="wpmtst-challenge-marker"></span> <?php esc_html_e( 'Al 5-lea lucru din lista', 'strong-testimonials' ); ?></p>
            </div>
            <div class="wpmtst-challenge-footer">
                <img src="<?php echo esc_url( WPMTST_URL . 'admin/img/mascot.png' ); ?>" class="wpmtst-challenge-logo"/>
                <div>
                    <h3>Strong Testimonials</h3>
                    <p><span class="wpmtst-challenge-time">5:00</span> remaining.</p>
                </div>
            </div>
            <div class="wpmtst-challenge-footer-button">
                <a id="wpmtst-challenge-button" href="<?php echo esc_url( $new_gallery_url ); ?>" class="wpmtst-btn wpmtst-challenge-btn"><?php esc_html_e( 'Create First Gallery', 'strong-testimonials' ); ?></a>
            </div>

        </div>
        <?php
    }

	public function challenge_scripts( $hook ) {

		wp_enqueue_style( 'wpmtst-challenge-style', WPMTST_URL . 'assets/css/challenge.css', array(), true );
        wp_enqueue_script( 'wpmtst-challenge-script', WPMTST_URL . 'assets/js/challenge.js', array( 'jquery' ), '1.0.0', true );
        $args = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'wpmtst-challenge' ),
        );
        wp_localize_script( 'wpmtst-challenge-script', 'wpmtstChallenge', $args );

	}

    public function show_challenge(){
        $args = array(
            'numberposts' => 1,
            'post_status' => 'any, trash',
            'post_type'   => 'wpm-testimonial'
          );
        
        if( !isset( $_GET['post_type'] ) || 'wpm-testimonial' !== $_GET['post_type'] ){
            return false;
        }

        if( empty( get_posts( $args ) ) ){
            return true;
        }

        return false;
        
    }
    public function wpmtst_challenge_hide(){

		if ( !isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'wpmtst-challenge' ) ) {
			wp_send_json_error();
			die();
		}

		update_option( 'wpmtst-challenge', 0 );
        wp_send_json_success();
		wp_die();
    }

}
new ST_Challenge_Modal();
?>
