<?php
/**
 * Class Strong_Testimonials_Settings
 */
class Strong_Testimonials_Forms {

	const DEFAULT_TAB = 'fields';

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
	 * Register settings
	 */
	public static function register_settings() {
		self::$callbacks = apply_filters( 'wpmtst_form_callbacks', array() );
	}

	/**
	 * Settings page
	 */
	public static function settings_page() {
		if ( ! current_user_can( 'strong_testimonials_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'strong-testimonials' ) );
		}
		$tab = self::get_tab();
		$url = admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-fields' );
		?>
		<div class="wrap wpmtst">

			<h1><?php echo wp_kses_post( apply_filters( 'wpmtst_cpt_singular_name', esc_html__( 'Form', 'strong-testimonials' ) ) ); ?></h1>
						
						<?php do_action( 'wpmtst_testimonials_settings' ); ?>
						
			<?php if ( isset( $_GET['settings-updated'] ) ) : ?>
				<div id="message" class="updated notice is-dismissible">
					<p><?php esc_html_e( 'Settings saved.', 'strong-testimonials' ); ?></p>
				</div>
			<?php endif; ?>

			<h2 class="nav-tab-wrapper">
				<?php do_action( 'wpmtst_form_tabs', $tab, $url ); ?>
			</h2>
		<?php if ( 'fields' !== $tab ) : ?>
			<div class="wpmts-settings-columns">
				<form id="<?php echo esc_attr( $tab ); ?>-form" method="post" action="options.php" enctype="multipart/form-data">
					<?php
					if ( isset( self::$callbacks[ $tab ] ) && wpmtst_callback_exists( self::$callbacks[ $tab ] ) ) {
						call_user_func( self::$callbacks[ $tab ] );
					} else {
						call_user_func( self::$callbacks[ self::DEFAULT_TAB ] );
					}

					if ( has_action( 'wpmtst_settings_submit_row' ) ) {
						echo '<p class="submit-buttons">';
						do_action( 'wpmtst_settings_submit_row' );
						echo '</p>';
					}
					?>
				</form>
				<?php do_action( 'wpmtst_admin_after_settings_form' ); ?>
			</div>
						<?php
						else :
							if ( isset( self::$callbacks[ $tab ] ) && wpmtst_callback_exists( self::$callbacks[ $tab ] ) ) {
									call_user_func( self::$callbacks[ $tab ] );
							} else {
									call_user_func( self::$callbacks[ self::DEFAULT_TAB ] );
							}
						endif;
						?>

		</div><!-- .wrap -->
		<?php
	}

	private static function get_tab() {
		return ( isset( $_GET['tab'] ) && sanitize_text_field( wp_unslash( $_GET['tab'] ) ) ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : self::DEFAULT_TAB;
	}
}

Strong_Testimonials_Forms::init();
