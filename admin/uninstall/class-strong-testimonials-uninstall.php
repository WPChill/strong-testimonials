<?php


class Strong_Testimonials_Uninstall {

    /**
     * @since 2.40.2
     * Strong_Testimonials_Uninstall constructor.
     *
     */
    function __construct() {

        // Deactivation
        add_filter( 'plugin_action_links_' . WPMTST_PLUGIN, array(
            $this,
            'filter_action_links'
        ) );
        if (!is_network_admin()) {
            add_action( 'admin_footer-plugins.php', array( $this, 'add_uninstall_form' ), 16 );
        }
        add_action( 'wp_ajax_st_uninstall_plugin', array( $this, 'st_uninstall_plugin' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'uninstall_scripts' ) );
    }

    /**
     * Enqueue uninstall scripts
     *
     * @since 2.40.2
     */
    public function uninstall_scripts() {

        $current_screen = get_current_screen();
        if ( 'plugins' == $current_screen->base ) {
            wp_enqueue_style( 'st-uninstall', WPMTST_URL . 'admin/css/uninstall.css' );
            wp_enqueue_script( 'st-uninstall', WPMTST_URL . 'admin/js/st-uninstall.js', array( 'jquery' ), WPMTST_VERSION, true );
            wp_localize_script( 'st-uninstall', 'wpStUninstall', array(
                'redirect_url' => admin_url( '/plugins.php' ),
                'nonce'        => wp_create_nonce( 'st_uninstall_plugin' )
            ) );
        }
    }

    /**
     * @param $links
     *
     * @return array
     *
     * @since 2.40.2
     * Set uninstall link
     */
    public function filter_action_links( $links ) {

        $links = array_merge( $links, array(
            '<a onclick="javascript:event.preventDefault();" id="st-uninstall-link"  class="uninstall-st st-red-text" href="#">' . esc_html__( 'Uninstall', 'strong-testimonials' ) . '</a>'
        ) );

        return $links;
    }

    /**
     * Form text strings
     * These can be filtered
     * @since 2.40.2
     */
    public function add_uninstall_form() {

        // Get our strings for the form
        $form = $this->get_form_info();

        ?>
        <div class="st-uninstall-form-bg">
        </div>
        <div class="st-uninstall-form-wrapper">
            <span class="st-uninstall-form" id="st-uninstall-form">
                <div class="st-uninstall-form-head">
                    <h3><strong><?php echo esc_html( $form['heading'] ); ?></strong></h3>
                    <i class="close-uninstall-form">X</i>
                </div>
        <div class="st-uninstall-form-body"><p><?php echo wp_kses_post( $form['body'] ); ?></p>

        <?php
        if ( is_array( $form['options'] ) ) {
            ?>
            <div class="st-uninstall-options">
                <?php
                foreach ( $form['options'] as $key => $option ) {

                    $before_input = '';
                    $after_input  = '';
                    if ( 'delete_all' == $key ) {
                        $before_input = '<strong class="st-red-text">';
                        $after_input  = '</strong>';
                    }

                    echo ' <p class="st-uninstall-options-checkbox" ><input type="checkbox" name="' . esc_attr( $key ) . ' " id="' . esc_attr( $key ) . '" value="' . esc_attr( $key ) . '"> <label for="' . esc_attr( $key ) . '">' . wp_kses_post( $before_input ) . esc_attr( $option['label'] ) . wp_kses_post( $after_input ) . '</label></p><p class="description">' . esc_html( $option['description'] ) . '</p>'; // phpcs:ignore $before_input, $after_input OK

                }
                ?>
            </div><!-- .st-uninstall-options -->
        <?php } ?>
        </div><!-- .st-uninstall-form-body -->
        <p class="deactivating-spinner"><span
                    class="spinner"></span><?php echo esc_html__( 'Cleaning...', 'strong-testimonials' ); ?></p>
        <div class="uninstall">
            <p>
                <a id="st-uninstall-submit-form" class="button button-primary"
                   href="#"><?php echo esc_html__( 'Uninstall', 'strong-testimonials' ); ?></a>
            </p>
        </div>
            </span>
        </div>
    <?php }

    /*
     * Form text strings
     * These are non-filterable and used as fallback in case filtered strings aren't set correctly
     * @since 2.40.2
     */
    public function get_form_info() {
        $form            = array();
        $form['heading'] = esc_html__( 'Sorry to see you go', 'strong-testimonials' );
        $form['body']    = '<strong class="st-red-text">' . esc_html__( ' Caution!! This action CANNOT be undone', 'strong-testimonials' ) . '</strong>';
        $form['options'] = apply_filters( 'st_uninstall_options', array(
            'delete_all'        => array(
                'label'       => esc_html__( 'Delete all data', 'strong-testimonials' ),
                'description' => esc_html__( 'Select this to delete all data Strong Testimonials plugin and it\'s add-ons have set in your database.', 'strong-testimonials' )
            ),
            'delete_options'    => array(
                'label'       => esc_html__( 'Delete Strong Testimonials options', 'strong-testimonials' ),
                'description' => esc_html__( 'Delete options set by Strong Testimonials plugin and it\'s add-ons  to options table in the database.', 'strong-testimonials' )
            ),
            'delete_transients' => array(
                'label'       => esc_html__( 'Delete Strong Testimonials set transients', 'strong-testimonials' ),
                'description' => esc_html__( 'Delete transients set by Strong Testimonials plugin and it\'s add-ons  to options table in the database.', 'strong-testimonials' )
            ),
            'delete_cpt'        => array(
                'label'       => esc_html__( 'Delete testimonials post type', 'strong-testimonials' ),
                'description' => esc_html__( 'Delete post types made by Strong Testimonials and it\'s add-ons.', 'strong-testimonials' )
            ),
            'delete_terms'        => array(
                'label'       => esc_html__( 'Delete categories', 'strong-testimonials' ),
                'description' => esc_html__( 'Delete Delete categories made by Strong Testimonials and it\'s add-ons.', 'strong-testimonials' )
            ),
            'delete_st_tables'        => array(
                'label'       => esc_html__( 'Delete tables', 'strong-testimonials' ),
                'description' => esc_html__( 'Delete tables made by Strong Testimonials and it\'s add-ons.', 'strong-testimonials' )
            ),
        ) );

        return $form;
    }

    /**
     * @since 2.40.2
     * Strong Testimonials Uninstall procedure
     */
    public function st_uninstall_plugin() {

        global $wpdb;
        check_ajax_referer( 'st_uninstall_plugin', 'security' );

        $uninstall_option = isset( $_POST['options'] ) ? array_map( 'absint', $_POST['options'] ) : false;

        // Delete options
        if ( '1' == $uninstall_option['delete_options'] ) {
            // filter for options to be added by Strong Testimonial's add-ons
            $options_array = apply_filters( 'st_uninstall_db_options', array( 'wpmtst_options', 'wpmtst_admin_notices','wpmtst_auto_dismiss_notices', 'wpmtst_plugin_version', 'wpmtst_compat_options', 'wpmtst_do_activation_redirect', 'wpmtst_config_errors', 'wpmtst_history', 'wpmtst_addons', 'wpmtst_update_log', 'wpmtst_db_version', 'wpmtst_custom_forms', 'wpmtst_fields', 'wpmtst_form_options', 'strong_testimonials_license_key', 'wpmtst_sticky_views', 'wpmtst_view_options', 'wpmtst_importer_options', 'wpmtst_view_default', 'wpmtst_base_forms', 'widget_strong-testimonials-view-widget', 'strong_testimonials_wisdom_notification_times', 'strong_testimonials_wisdom_block_notice', 'strong_testimonials_license_status', 'strong_testimonials_advanced_settings', 'strong_testimonials_wisdom_last_track_time', 'strong_testimonials_wisdom_admin_emails'  ) );

            foreach ( $options_array as $db_option ) {
                delete_option( $db_option );
            }

        }

        // Delete transients
        if ( '1' == $uninstall_option['delete_transients'] ) {
            // filter for transients to be added by Strong Testimonial's add-ons
            $transients_array = apply_filters( 'st_uninstall_transients', array( 'wpmtst_mail_queue', 'strong_testimonials_all_extensions', 'wpmtst_update_in_progress', 'wpmtst_order_query' ) );

            foreach ( $transients_array as $db_transient ) {
                delete_transient( $db_transient );
            }

        }

        // Delete custom post type
        if ( '1' == $uninstall_option['delete_cpt'] ) {

            $post_types   = apply_filters( 'st_uninstall_post_types', array( 'wpm-testimonial' ) );
            $testimonials = get_posts( array( 'post_type' => $post_types, 'posts_per_page' => -1, 'fields' => 'ids' ) );

            if ( is_array( $testimonials ) && !empty( $testimonials ) ) {

                $id_in = implode( ',', $testimonials );

                $sql      = $wpdb->prepare( "DELETE FROM  $wpdb->posts WHERE ID IN ( $id_in )" );
                $sql_meta = $wpdb->prepare( "DELETE FROM  $wpdb->postmeta WHERE post_id IN ( $id_in )" );
                $wpdb->query( $sql );
                $wpdb->query( $sql_meta );
            }
        }

        if ( '1' == $uninstall_option['delete_st_tables'] ) {
            $st_views_table = $wpdb->prefix . "strong_views";

            $sql_st_views_table = $wpdb->prepare( "DROP TABLE IF EXISTS $st_views_table" );
            $wpdb->query( $sql_st_views_table );

        }

        if ( '1' == $uninstall_option['delete_terms'] ) {
            $term_type    = apply_filters( 'st_uninstall_terms', array( 'wpm-testimonial-category
' ) );
            $testimonials  = get_terms( array( 'taxonomy' => 'wpm-testimonial-category', 'hide_empty' => false, 'fields' => 'tt_ids' ) );

            if ( is_array( $testimonials ) && !empty( $testimonials ) ) {

                $id_in = implode( ',', $testimonials );

                $sql      = $wpdb->prepare( "DELETE FROM  $wpdb->terms WHERE term_id IN ( $id_in )" );
                $sql_meta = $wpdb->prepare( "DELETE FROM  $wpdb->term_taxonomy WHERE term_id IN ( $id_in )" );
                $wpdb->query( $sql );
                $wpdb->query( $sql_meta );
            }

        }

        do_action( 'st_uninstall' );

        deactivate_plugins( WPMTST_PLUGIN );
        wp_die();
    }

}

$st_uninstall = new Strong_Testimonials_Uninstall();
