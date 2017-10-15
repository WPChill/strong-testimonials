<?php
/**
 * Class Strong_Testimonials_Settings
 */
class Strong_Testimonials_Settings {

    public static $callbacks;

	/**
	 * Strong_Testimonials_Settings constructor.
	 */
	public function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init() {
		self::add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public static function add_actions() {
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );
	}

	/**
	 * Check for active add-ons.
	 *
	 * @since 2.1
	 */
	public static function has_active_addons() {
		return has_action( 'wpmtst_licenses' );
	}

	/**
	 * Register settings
	 */
	public static function register_settings() {
		self::$callbacks = apply_filters( 'wpmtst_settings_callbacks', array() );
        do_action( 'wpmtst_register_settings' );
	}

	/**
	 * Settings page
	 */
	public static function settings_page() {
		if ( ! current_user_can( 'strong_testimonials_options' ) )
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		?>
		<div class="wrap wpmtst">

			<h1><?php _e( 'Testimonial Settings', 'strong-testimonials' ); ?></h1>

			<?php if( isset( $_GET['settings-updated'] ) ) : ?>
				<div id="message" class="updated notice is-dismissible">
					<p><?php _e( 'Settings saved.' ) ?></p>
				</div>
			<?php endif; ?>

			<?php
			$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
			$url        = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings' );
			?>
			<h2 class="nav-tab-wrapper">

				<a href="<?php echo add_query_arg( 'tab', 'general', $url ); ?>" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _ex( 'General', 'adjective', 'strong-testimonials' ); ?></a>

				<a href="<?php echo add_query_arg( 'tab', 'form', $url ); ?>" class="nav-tab <?php echo $active_tab == 'form' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Form', 'strong-testimonials' ); ?></a>

				<?php do_action( 'wpmtst_settings_tabs', $active_tab, $url ); ?>

				<?php if ( self::has_active_addons() ): ?>
					<a href="<?php echo add_query_arg( 'tab', 'licenses', $url ); ?>" class="nav-tab <?php echo $active_tab == 'licenses' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Licenses', 'strong-testimonials' ); ?></a>
				<?php endif; ?>

			</h2>

			<form id="<?php echo $active_tab; ?>-form" method="post" action="options.php">
				<?php
				if ( isset( self::$callbacks[ $active_tab ] ) && wpmtst_callback_exists( self::$callbacks[ $active_tab ] ) ) {
					call_user_func( self::$callbacks[ $active_tab ] );
				} else {
					call_user_func( self::$callbacks['general'] );
				}
				?>
				<p class="submit-row">
					<?php submit_button( '', 'primary', 'submit-form', false ); ?>
					<?php do_action( 'wpmtst_settings_submit_row'); ?>
				</p>
			</form>

		</div><!-- .wrap -->
		<?php
	}

}

Strong_Testimonials_Settings::init();
