<?php

/**
 * Class Strong_Testimonials_Settings_Compat
 *
 * @since 2.28.0
 */
class Strong_Testimonials_Settings_Compat {

	const TAB_NAME = 'compat';

	const OPTION_NAME = 'wpmtst_compat_options';

	const GROUP_NAME = 'wpmtst-compat-group';

	var $options;

	/**
	 * Strong_Testimonials_Settings_Compat constructor.
	 */
	public function __construct() {
		$this->options = get_option( self::OPTION_NAME );
		$this->add_actions();
	}

	/**
	 * Add actions and filters.
	 */
	public function add_actions() {
		add_action( 'wpmtst_register_settings', array( $this, 'register_settings' ) );
		add_action( 'wpmtst_settings_tabs', array( $this, 'register_tab' ), 3, 2 );
		add_filter( 'wpmtst_settings_callbacks', array( $this, 'register_settings_page' ) );
		add_action( 'wp_ajax_wpmtst_add_lazyload_pair', array( $this, 'add_lazyload_pair' ) );
	}

	/**
	 * Register settings tab.
	 *
	 * @param $active_tab
	 * @param $url
	 */
	public function register_tab( $active_tab, $url ) {
		printf( '<a href="%s" class="nav-tab %s">%s</a>',
		        esc_url( add_query_arg( 'tab', self::TAB_NAME, $url ) ),
		        esc_attr( $active_tab == self::TAB_NAME ? 'nav-tab-active' : '' ),
		        esc_html__( 'Compatibility', 'strong-testimonials' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting( self::GROUP_NAME, self::OPTION_NAME, array( $this, 'sanitize_options' ) );
	}

	/**
	 * Register settings page.
	 *
	 * @param $pages
	 *
	 * @return mixed
	 */
	public function register_settings_page( $pages ) {
		$pages[ self::TAB_NAME ] = array( $this, 'settings_page' );

		return $pages;
	}

	/**
	 * Sanitize settings.
	 *
	 * @param $input
	 * @since 2.28.0
	 * @since 2.31.0 controller
	 * @since 2.31.0 lazyload
	 * @return array
	 */
	public function sanitize_options( $input ) {
		$input['page_loading'] = sanitize_text_field( $input['page_loading'] );

		if ( 'general' == $input['page_loading'] ) {
			$input['prerender']      = 'all';
			$input['ajax']['method'] = 'universal';
		} else {
			$input['prerender']      = sanitize_text_field( $input['prerender'] );
			$input['ajax']['method'] = sanitize_text_field( $input['ajax']['method'] );
		}

		$input['ajax']['universal_timer'] = floatval( sanitize_text_field( $input['ajax']['universal_timer'] ) );
		$input['ajax']['observer_timer']  = floatval( sanitize_text_field( $input['ajax']['observer_timer'] ) );
		$input['ajax']['container_id']    = sanitize_text_field( $input['ajax']['container_id'] );
		$input['ajax']['addednode_id']    = sanitize_text_field( $input['ajax']['addednode_id'] );
		$input['ajax']['event']           = sanitize_text_field( $input['ajax']['event'] );
		$input['ajax']['script']          = sanitize_text_field( $input['ajax']['script'] );

		$input['controller']['initialize_on'] = sanitize_text_field( $input['controller']['initialize_on'] );

		// FIXME: Special handling until proper use of default values in v3.0
        $default = array(
	        'enabled' => false,
	        'classes' => array(
		        array(
			        'start'  => '',
			        'finish' => '',
		        ),
	        ),
        );

		if ( ! isset( $input['lazyload'] ) ) {

			$input['lazyload'] = $default;

		} else {

			$input['lazyload']['enabled'] = wpmtst_sanitize_checkbox( $input['lazyload'], 'enabled' );

			if ( isset( $input['lazyload']['classes'] ) && $input['lazyload']['classes'] ) {

				// May be multiple pairs.
				foreach ( $input['lazyload']['classes'] as $key => $classes ) {

				    // Sanitize classes or remove empty pairs.
					// Reduce multiple empty pairs down to default value of single empty pair.
					if ( $classes['start'] || $classes['finish'] ) {
						$input['lazyload']['classes'][ $key ]['start']  = str_replace( '.', '', sanitize_text_field( $classes['start'] ) );
						$input['lazyload']['classes'][ $key ]['finish'] = str_replace( '.', '', sanitize_text_field( $classes['finish'] ) );
					} else {
						unset( $input['lazyload']['classes'][$key] );
					}

					if ( ! count( $input['lazyload']['classes'] ) ) {
						$input['lazyload'] = $default;
					}

				}

			} else {

				$input['lazyload'] = $default['classes'];

			}

		}
                $input['random_js'] = wpmtst_sanitize_checkbox( $input, 'random_js' );
		return $input;
	}

	/**
	 * Print settings page.
	 */
	public function settings_page() {
		settings_fields( self::GROUP_NAME );
		$this->settings_top();
	}

	/**
	 * Compatibility settings
	 *
	 * @since 2.31.0 controller
	 * @since 2.31.0 lazyload
	 */
	public function settings_top() {
		$this->settings_intro();
		$this->settings_random_js();
		$this->settings_page_loading();
		$this->settings_prerender();
		$this->settings_monitor();
		$this->settings_controller();
	}

	/**
	 * Settings intro
	 */
	public function settings_intro() {
		?>
		<h2><?php esc_html_e( 'Common Scenarios', 'strong-testimonials' ); ?></h2>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<td>
					<div class="scenarios">

						<div class="row header">
							<div>
								<?php esc_html_e( 'Symptom', 'strong-testimonials' ); ?>
							</div>
							<div>
								<?php esc_html_e( 'Possible Cause', 'strong-testimonials' ); ?>
							</div>
							<div>
								<?php esc_html_e( 'Try', 'strong-testimonials' ); ?>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php esc_html_e( 'Views not working', 'strong-testimonials' ); ?></strong></p>
								<p><?php echo wp_kses_post( __( 'A testimonial view does not appear correctly the <strong>first time</strong> you view the page but it does when you <strong>refresh</strong> the page.', 'strong-testimonials' ) ); ?></p>
								<p><?php esc_html_e( 'For example, it has no style, no pagination, or the slider has not started.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php echo wp_kses_post( __( 'Your site uses <strong>Ajax page loading</strong> &ndash; also known as page animations, transition effects or Pjax (pushState Ajax) &ndash; provided by your theme or another plugin.', 'strong-testimonials' ) ); ?></p>
								<p><?php esc_html_e( 'Instead of loading the entire page, this technique fetches only the new content.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php esc_html_e( 'Ajax Page Loading', 'strong-testimonials' ); ?>:</strong> <?php esc_html_e( 'General', 'strong-testimonials' ); ?></p>
								<p>
									<a href="#" id="set-scenario-1">
										<?php /* translators: link text on Settings > Compatibility tab */ esc_html_e( 'Set this now', 'strong-testimonials' ); ?>
									</a>
								</p>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php esc_html_e( 'Slider never starts', 'strong-testimonials' ); ?></strong></p>
								<p><?php esc_html_e( 'A testimonial slider does not start or is missing navigation controls.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php esc_html_e( 'The page is very busy loading image galleries, other sliders or third-party resources like social media posts.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php esc_html_e( 'Load Event', 'strong-testimonials' ); ?>:</strong> <?php esc_html_e( 'window load', 'strong-testimonials' ); ?></p>
							</div>
						</div>

						<div class="row">
							<div>
								<p><strong><?php esc_html_e( 'Masonry layout not working', 'strong-testimonials' ); ?></strong></p>
								<p><?php esc_html_e( 'A testimonial view with the Masonry layout has only one column or works inconsistently in different browsers or devices.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><?php esc_html_e( 'The page is very busy loading image galleries, other sliders or third-party resources like social media posts.', 'strong-testimonials' ); ?></p>
							</div>
							<div>
								<p><strong><?php esc_html_e( 'Load Event', 'strong-testimonials' ); ?>:</strong> <?php esc_html_e( 'window load', 'strong-testimonials' ); ?></p>
							</div>
						</div>

					</div><!-- .scenarios -->
				</td>
			</tr>
		</table>

		<h2><?php esc_html_e( 'Compatibility Settings', 'strong-testimonials' ); ?></h2>

		<?php
	}

	public function settings_random_js() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Random JS', 'strong-testimonials' ); ?>
				</th>
				<td>
			<fieldset>
				<label>
					<input type="checkbox" name="wpmtst_compat_options[random_js]" <?php checked( $this->options['random_js'] ); ?> />
					<?php esc_html_e( 'Randomize testimonials via javascript to ensure proper behaviour. Check this if using page caching plugins (WP Rocket, Super Cache, W3 Total Cache etc.)', 'strong-testimonials' ); ?>
					<?php esc_html_e( 'Off by default.', 'strong-testimonials' ); ?>
				</label>
			</fieldset>
				</td>
			</tr>
		</table> <?php
        }

	/**
	 * Page Loading
	 */
	public function settings_page_loading() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Ajax Page Loading', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p>
							<?php esc_html_e( 'This does not perform Ajax page loading.', 'strong-testimonials' ); ?>
							<?php esc_html_e( 'It provides compatibility with themes and plugins that use Ajax to load pages, also known as page animation or transition effects.', 'strong-testimonials' ); ?>
						</p>
					</div>
					<fieldset data-radio-group="prerender">
						<?php $this->settings_page_loading_none(); ?>
						<?php $this->settings_page_loading_general(); ?>
						<?php $this->settings_page_loading_advanced(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * None (default)
	 */
	public function settings_page_loading_none() {
		$checked = checked( $this->options['page_loading'], '', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="page-loading-none">
					<input id="page-loading-none"
						name="wpmtst_compat_options[page_loading]"
						type="radio"
						value=""
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'None', 'strong-testimonials' ); ?>
					<em><?php esc_html_e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'No compatibility needed.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php esc_html_e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * General
	 */
	public function settings_page_loading_general() {
		$checked = checked( $this->options['page_loading'], 'general', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="page-loading-general">
					<input id="page-loading-general"
						name="wpmtst_compat_options[page_loading]"
						type="radio"
						value="general"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'General', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'Be ready to render any view at any time.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php esc_html_e( 'This works well with common Ajax methods.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Advanced
	 */
	public function settings_page_loading_advanced() {
		$checked = checked( $this->options['page_loading'], 'advanced', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="page-loading-advanced">
					<input id="page-loading-advanced"
						name="wpmtst_compat_options[page_loading]"
						data-group="advanced"
						type="radio"
						value="advanced"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Advanced', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'For specific configurations.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Prerender
	 */
	public function settings_prerender() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Prerender', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php esc_html_e( 'Load stylesheets and populate script variables up front.', 'strong-testimonials' ); ?>
							<a class="open-help-tab" href="#tab-panel-wpmtst-help-prerender"><?php esc_html_e( 'Help', 'strong-testimonials' ); ?></a>
						</p>
					</div>
					<fieldset data-radio-group="prerender">
						<?php $this->settings_prerender_current(); ?>
						<?php $this->settings_prerender_all(); ?>
						<?php $this->settings_prerender_none(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Current (default)
	 */
	public function settings_prerender_current() {
		$checked = checked( $this->options['prerender'], 'current', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="prerender-current">
					<input id="prerender-current"
						name="wpmtst_compat_options[prerender]"
						type="radio"
						value="current"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Current page', 'strong-testimonials' ); ?>
					<em><?php esc_html_e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'For the current page only.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php esc_html_e( 'This works well for most themes.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * All
	 */
	public function settings_prerender_all() {
		$checked = checked( $this->options['prerender'], 'all', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="prerender-all">
					<input id="prerender-all"
						type="radio"
						name="wpmtst_compat_options[prerender]"
						value="all"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'All views', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'For all views. Required for Ajax page loading.', 'strong-testimonials' ); ?></p>
				<p class="about"><?php echo wp_kses_post( __( 'Then select an option for <strong>Monitor</strong> below.', 'strong-testimonials' ) ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * None
	 */
	public function settings_prerender_none() {
		$checked = checked( $this->options['prerender'], 'none', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="prerender-none">
					<input id="prerender-none"
						type="radio"
						name="wpmtst_compat_options[prerender]"
						value="none"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'None', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'When the shortcode is rendered. May result in a flash of unstyled content.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Monitor
	 */
	public function settings_monitor() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0" data-sub="advanced">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Monitor', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php esc_html_e( 'Initialize sliders, pagination and form validation as pages change.', 'strong-testimonials' ); ?></p>
					</div>
					<fieldset data-radio-group="method">
						<?php $this->settings_monitor_none(); ?>
						<?php $this->settings_monitor_universal(); ?>
						<?php $this->settings_monitor_observer(); ?>
						<?php $this->settings_monitor_event(); ?>
						<?php $this->settings_monitor_script(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * None
	 */
	public function settings_monitor_none() {
		$checked = checked( $this->options['ajax']['method'], '', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="method-none">
					<input id="method-none"
						type="radio"
						name="wpmtst_compat_options[ajax][method]"
						value=""
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'None', 'strong-testimonials' ); ?>
					<em><?php esc_html_e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'No compatibility needed.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Universal (timer)
	 */
	public function settings_monitor_universal() {
		$checked = checked( $this->options['ajax']['method'], 'universal', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="method-universal">
					<input id="method-universal"
						name="wpmtst_compat_options[ajax][method]"
						type="radio"
						value="universal"
						data-group="universal"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Universal', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'Watch for page changes on a timer.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="universal">
			<div class="radio-sub">
				<label for="universal-timer">
					<?php echo esc_html_x( 'Check every', 'timer setting', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input id="universal-timer"
					name="wpmtst_compat_options[ajax][universal_timer]"
					type="number"
					min=".1" max="5" step=".1"
					value="<?php echo esc_attr( $this->options['ajax']['universal_timer'] ); ?>"
					size="3" />
				<?php echo esc_html_x( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Observer
	 */
	public function settings_monitor_observer() {
		$checked = checked( $this->options['ajax']['method'], 'observer', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="method-observer">
					<input id="method-observer"
						name="wpmtst_compat_options[ajax][method]"
						data-group="observer"
						type="radio"
						value="observer"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Observer', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'React to changes in specific page elements.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php esc_html_e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<?php
		/*
		 * Timer
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="observer-timer">
					<?php echo esc_html_x( 'Check once after', 'timer setting', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input id="observer-timer"
					name="wpmtst_compat_options[ajax][observer_timer]"
					type="number"
					min=".1" max="5" step=".1"
					value="<?php echo esc_attr( $this->options['ajax']['observer_timer'] ); ?>"
					size="3" />
				<?php echo esc_html_x( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
			</div>
		</div>

		<?php
		/*
		 * Container element ID
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="container-id">
					<?php esc_html_e( 'Container ID', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<span class="code input-before">#</span>
				<input class="code element"
					id="container-id"
					name="wpmtst_compat_options[ajax][container_id]"
					type="text"
					value="<?php echo esc_attr( $this->options['ajax']['container_id'] ); ?>" />
				<p class="about adjacent"><?php esc_html_e( 'the element to observe', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<?php
		/*
		 * Added node ID
		 */
		?>
		<div class="row" data-sub="observer">
			<div class="radio-sub">
				<label for="addednode-id">
					<?php esc_html_e( 'Added node ID', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<span class="code input-before">#</span>
				<input class="code element"
					id="addednode-id"
					name="wpmtst_compat_options[ajax][addednode_id]"
					type="text"
					value="<?php echo esc_attr( $this->options['ajax']['addednode_id'] ); ?>" />
				<p class="about adjacent"><?php esc_html_e( 'the element being added', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Custom event
	 */
	public function settings_monitor_event() {
		$checked = checked( $this->options['ajax']['method'], 'event', false );
		$class   = $checked ? 'current' : ''; ?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="method-event">
					<input id="method-event"
						name="wpmtst_compat_options[ajax][method]"
						data-group="event"
						type="radio"
						value="event"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Custom event', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'Listen for specific events.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php esc_html_e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="event">
			<div class="radio-sub">
				<label for="event-name">
					<?php esc_html_e( 'Event name', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<input class="code"
					id="event-name"
					name="wpmtst_compat_options[ajax][event]"
					type="text"
					value="<?php echo esc_attr( $this->options['ajax']['event'] ); ?>"
					size="30" />
			</div>
		</div>
		<?php
	}

	/**
	 * Specific script
	 */
	public function settings_monitor_script() {
		$checked = checked( $this->options['ajax']['method'], 'script', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="method-script">
					<input id="method-script"
						name="wpmtst_compat_options[ajax][method]"
						data-group="script"
						type="radio"
						value="script"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'Specific script', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'Register a callback for a specific Ajax script.', 'strong-testimonials' ); ?></p>
				<p class="description"><?php esc_html_e( 'For advanced users.', 'strong-testimonials' ); ?></p>
			</div>
		</div>

		<div class="row" data-sub="script">
			<div class="radio-sub">
				<label for="script-name">
					<?php esc_html_e( 'Script name', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<select id="script-name" name="wpmtst_compat_options[ajax][script]">
					<option value="" <?php selected( $this->options['ajax']['script'], '' ); ?>>
						<?php esc_html_e( '&mdash; Select &mdash;', 'strong-testimonials' ); ?>
					</option>
					<option value="barba" <?php selected( $this->options['ajax']['script'], 'barba' ); ?>>
						Barba.js
					</option>
				</select>
			</div>
		</div>
		<?php
	}

	/**
	 * Controller
	 *
	 * @since 2.31.0
	 */
	public function settings_controller() {
		?>
		<table class="form-table" cellpadding="0" cellspacing="0">
			<tr valign="top">
				<th scope="row">
					<?php esc_html_e( 'Load Event', 'strong-testimonials' ); ?>
				</th>
				<td>
					<div class="row header">
						<p><?php esc_html_e( 'When to start sliders, Masonry, pagination and form validation.', 'strong-testimonials' ); ?></p>
					</div>
					<fieldset>
						<?php $this->settings_page_controller_documentready(); ?>
						<?php $this->settings_page_controller_windowload(); ?>
					</fieldset>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Document ready (default)
	 */
	public function settings_page_controller_documentready() {
		$checked = checked( $this->options['controller']['initialize_on'], 'documentReady', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="controller-documentready">
					<input id="controller-documentready"
						name="wpmtst_compat_options[controller][initialize_on]"
						type="radio"
						value="documentReady"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'document ready', 'strong-testimonials' ); ?>
					<em><?php esc_html_e( '(default)', 'strong-testimonials' ); ?></em>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'This works well if your page load time is less than a few seconds.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Document ready (default)
	 */
	public function settings_page_controller_windowload() {
		$checked = checked( $this->options['controller']['initialize_on'], 'windowLoad', false );
		$class   = $checked ? 'current' : '';
		?>
		<div class="row">
			<div>
				<label class="<?php echo esc_attr( $class ); ?>" for="controller-windowload">
					<input id="controller-windowload"
						name="wpmtst_compat_options[controller][initialize_on]"
						type="radio"
						value="windowLoad"
						<?php echo esc_attr( $checked ); ?> />
					<?php esc_html_e( 'window load', 'strong-testimonials' ); ?>
				</label>
			</div>
			<div>
				<p class="about"><?php esc_html_e( 'Try this if your page load time is more than a few seconds.', 'strong-testimonials' ); ?></p>
			</div>
		</div>
		<?php
	}
}

new Strong_Testimonials_Settings_Compat();
