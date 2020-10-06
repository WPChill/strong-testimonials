<?php 

/**
 * Class that handles all the assets and includes for every block 
 * 
 * @since 2.40.5
 */

class Strong_Gutemberg {

    public function __construct() {
        add_action( 'init', array( $this,'register_block_type') );
        add_action( 'init', array( $this,'generate_js_vars') );
    }

    public function register_block_type() {

        wp_register_script( 'st-block-js', WPMTST_URL . 'assets/js/blocks-js.js', array( 'wp-i18n', 'wp-element', 'wp-editor', 'wp-blocks', 'wp-components', 'wp-api', 'wp-data'), WPMTST_VERSION );

        wp_register_style( 'st-block-css', WPMTST_URL . 'assets/css/blocks.css', array(), WPMTST_VERSION );

        register_block_type( 'strongtestimonials/view', array(
            'render_callback' => array( $this, 'render_view' ),
            'editor_script'   => 'st-block-js',
			'editor_style'    => 'st-block-css',
        ));

    }

    public function generate_js_vars() {

        wp_localize_script(
            'st-block-js', 'st_views', array(
                'adminURL'     => admin_url(),
                'ajaxURL'      => admin_url( 'admin-ajax.php' ),
                'views'        => wpmtst_unserialize_views( wpmtst_get_views() ),
            )
        );
    }

    public function render_view( $attributes ) {

        if( 0 == count( $attributes ) ) {
            return;
        }

        if( '0' == $attributes['id'] ) {
            return;
        }

        return "[testimonial_view id={$attributes['id']}]";
    }

}

new Strong_Gutemberg();