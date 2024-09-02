<?php
/**
 * Contextual help.
 */

class Strong_Testimonials_Help {

	public function __construct() {}

	public static function init() {
		add_action( 'load-wpm-testimonial_page_testimonial-fields', array( __CLASS__, 'fields_editor' ) );
		add_action( 'load-wpm-testimonial_page_testimonial-views', array( __CLASS__, 'views_list' ) );
		add_action( 'load-wpm-testimonial_page_testimonial-views', array( __CLASS__, 'shortcode_attributes' ) );
		add_action( 'load-wpm-testimonial_page_testimonial-views', array( __CLASS__, 'view_editor_pagination' ) );
		add_action( 'load-wpm-testimonial_page_testimonial-views', array( __CLASS__, 'view_editor_stretch' ) );

		add_action( 'load-wpm-testimonial_page_testimonial-settings', array( __CLASS__, 'settings_compat' ) );
	}

	/**
	 * Compatibility settings.
	 */
	public static function settings_compat() {
		if ( ! isset( $_GET['tab'] ) || 'compat' !== sanitize_key( $_GET['tab'] ) ) {
			return;
		}

		ob_start();
		?>
	<p><?php esc_html_e( 'Normally, a web page will load its stylesheets (font, color, size, etc.) before the content. When the content is displayed, the style is ready and the page appears as it was designed.', 'strong-testimonials' ); ?></p>
		<p><?php esc_html_e( 'When a browser displays the content before all the stylesheets have been loaded, a flash of unstyled content can occur.', 'strong-testimonials' ); ?></p>
	<p>
			<?php
			printf(
				wp_kses(
					// translators: %s is the URL to an external explanation about Flash of Unstyled Content (FOUC).
					__( '<a href="%s" target="_blank">Explained further here</a>', 'strong-testimonials' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				esc_url( 'https://en.wikipedia.org/wiki/Flash_of_unstyled_content' )
			);
			?>
		|
			<?php
			printf(
				wp_kses(
					// translators: %s is the URL to a demonstration on CodePen.
					__( '<a href="%s" target="_blank">Demonstrated here</a>', 'strong-testimonials' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				esc_url( 'https://codepen.io/micikato/full/JroPNm/' )
			);
			?>
		|
			<?php
			printf(
				wp_kses(
					// translators: %s is the URL to an expert's observations on CSS Tricks.
					__( '<a href="%s" target="_blank">An expert\'s observations here</a>', 'strong-testimonials' ),
					array(
						'a' => array(
							'href'   => array(),
							'target' => array(),
						),
					)
				),
				esc_url( 'https://css-tricks.com/fout-foit-foft/' )
			);
			?>
	</p>
	<p><?php esc_html_e( 'When this occurs with plugins that use shortcodes, it means the plugin\'s stylesheet was enqueued when the shortcode was rendered so it gets loaded after the content instead of in the normal sequence.', 'strong-testimonials' ); ?></p>
	<p><?php esc_html_e( 'The prerender option ensures this plugin\'s stylesheets are loaded before the content.', 'strong-testimonials' ); ?></p>
		<?php
		$content = ob_get_clean();

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help-prerender',
				'title'   => esc_html__( 'Prerender', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}

	/**
	 * Custom fields editor.
	 */
	public static function fields_editor() {
		ob_start();
		?>
		<p><?php esc_html_e( 'These fields let you customize your testimonials to gather the information you need.', 'strong-testimonials' ); ?></p>
		<p><?php esc_html_e( 'This editor serves two purposes: (1) to modify the form as it appears on your site, and (2) to modify the custom fields added to each testimonial.', 'strong-testimonials' ); ?></p>
		<p><?php esc_html_e( 'The default fields are designed to fit most situations. You can quickly add or remove fields and change several display properties.', 'strong-testimonials' ); ?></p>
		<p>
			<?php esc_html_e( 'Fields will appear in this order on the form.', 'strong-testimonials' ); ?> 
			<?php
			// translators: %s is the icon for reordering fields.
			printf( esc_html__( 'Reorder by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' );
			?>
		</p>
		<p><?php esc_html_e( 'To display this form, create a view and select Form mode.', 'strong-testimonials' ); ?></p>
		<?php
		$content = ob_get_clean();

		// Links

		$links = array(
			'<a href="' . admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form' ) . '">' . __( 'Form settings', 'strong-testimonials' ) . '</a>',
		);

		$content .= '<p>' . implode( ' | ', $links ) . '</p>';

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help',
				'title'   => esc_html__( 'Form Fields', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}

	/**
	 * About views.
	 */
	public static function views_list() {
		ob_start();
		?>
		<div>
			<p><?php esc_html_e( 'A view is simply a group of settings with an easy-to-use editor.', 'strong-testimonials' ); ?>
			<p><?php echo wp_kses_post( __( 'You can create an <strong>unlimited</strong> number of views.', 'strong-testimonials' ) ); ?></p>
			<p><?php esc_html_e( 'For example:', 'strong-testimonials' ); ?></p>
			<ul class="standard">
				<li><?php esc_html_e( 'Create a view to display your testimonials in a list, grid, or slideshow.', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'Create a view to show a testimonial submission form', 'strong-testimonials' ); ?></li>
				<li><?php esc_html_e( 'Create a view to append your custom fields to the individual testimonial using your theme single post template.', 'strong-testimonials' ); ?></li>
				<?php do_action( 'wpmtst_views_intro_list' ); ?>
			</ul>
			<p><?php esc_html_e( 'Add a view to a page with its unique shortcode or add it to a sidebar with the Strong Testimonials widget.', 'strong-testimonials' ); ?></p>
		</div>
		<?php
		$content = ob_get_clean();

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help-views',
				'title'   => esc_html__( 'About Views', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}

	/**
	 * Shortcode attributes.
	 */
	public static function shortcode_attributes() {
		if ( ! isset( $_GET['action'] ) ) {
			return;
		}

		ob_start();
		?>
		<div>
			<p><?php echo wp_kses_post( __( 'Optional shortcode attributes will override the view settings. Use this to create reusable view <strong>patterns</strong>.', 'strong-testimonials' ) ); ?>
			<p><?php echo wp_kses_post( __( 'Overridable settings: <code>post_ids</code>, <code>category</code>, <code>order</code>, <code>count</code>.', 'strong-testimonials' ) ); ?>
			<p><?php echo wp_kses_post( esc_html__( 'For example, imagine you have five services, a sales page for each service, and a testimonial category for each service. To display the testimonials on each service page, you can create five duplicate views, one for each category.', 'strong-testimonials' ) ); ?>
			<p><?php echo wp_kses_post( __( 'Or you can configure one view as a pattern and add it to each service page with the <code>category</code> attribute.', 'strong-testimonials' ) ); ?>
			<p>
				<?php echo wp_kses_post( '<code>[testimonial_view id="1" category="service-1"]</code>' ); ?>,
				<?php echo wp_kses_post( '<code>[testimonial_view id="1" category="service-2"]</code>' ); ?>, etc.
			</p>
			<p>
				<?php echo wp_kses_post( esc_html__( 'Attributes may be used in combination. For example:', 'strong-testimonials' ) ); ?>
				<?php echo wp_kses_post( '<code>[testimonial_view id="1" category="service-3" order="random" count="5"]</code>' ); ?>
			</p>
			<p><?php echo wp_kses_post( __( 'Using <code>post_ids</code> is the most specific method and it will override category and count (whether settings or attributes).', 'strong-testimonials' ) ); ?></p>
		</div>
		<?php
		$content = ob_get_clean();

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help-shortcode',
				'title'   => esc_html__( 'Shortcode Attributes', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}

	/**
	 * Pagination comparison.
	 */
	public static function view_editor_pagination() {
		if ( ! isset( $_GET['action'] ) ) {
			return;
		}

		ob_start();
		?>
		<p><?php esc_html_e( 'Some of the features and drawbacks for each method.', 'strong-testimonials' ); ?></p>

		<table class="wpmtst-help-tab" cellpadding="0" cellspacing="0">
			<thead>
			<tr>
				<th></th>
				<th><?php esc_html_e( 'Simple', 'strong-testimonials' ); ?></th>
				<th><?php esc_html_e( 'Standard', 'strong-testimonials' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td><?php esc_html_e( 'best use', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'ten pages or less', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'more than ten pages', 'strong-testimonials' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'URLs', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'does not change the URL', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'uses paged URLs just like standard WordPress posts', 'strong-testimonials' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'the Back button', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'It does not remember which page of testimonials you are on. If you click away &ndash; for example, on a "Read more" link &ndash; then click back, you will return to page one.', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'You will return the last page you were on so this works well with "Read more" links.', 'strong-testimonials' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'works with random order option', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'yes', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'no', 'strong-testimonials' ); ?></td>
			</tr>
			<tr>
				<td><?php esc_html_e( 'works in a widget', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'yes', 'strong-testimonials' ); ?></td>
				<td><?php esc_html_e( 'no', 'strong-testimonials' ); ?></td>
			</tr>
			</tbody>
		</table>
		<?php
		$content = ob_get_clean();

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help-pagination',
				'title'   => esc_html__( 'Pagination', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}

	/**
	 * Slideshow stretch explanation.
	 */
	public static function view_editor_stretch() {
		if ( ! isset( $_GET['action'] ) ) {
			return;
		}

		ob_start();
		?>
		<p><?php echo wp_kses_post( __( 'This will set the height of the <b>slideshow container</b> to match the tallest slide in order to keep elements below it from bouncing up and down during slide transitions. With testimonials of uneven length, the result is whitespace underneath the shorter testimonials.', 'strong-testimonials' ) ); ?></p>
		<p><?php echo wp_kses_post( __( 'Select the <b>Stretch</b> option to stretch the borders and background vertically to compensate.', 'strong-testimonials' ) ); ?></p>
		<p><?php esc_html_e( 'Use the excerpt or abbreviated content if you want to minimize the whitespace.', 'strong-testimonials' ); ?></p>
		<?php
		$content = ob_get_clean();

		get_current_screen()->add_help_tab(
			array(
				'id'      => 'wpmtst-help-stretch',
				'title'   => esc_html__( 'Stretch', 'strong-testimonials' ),
				'content' => $content,
			)
		);
	}
}

Strong_Testimonials_Help::init();
