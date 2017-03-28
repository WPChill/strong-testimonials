<?php
/**
 * Contextual help.
 */

add_action( 'load-wpm-testimonial_page_testimonial-fields', 'wpmtst_help_fields_editor' );
function wpmtst_help_fields_editor() {

	$content = '<p>' . __( 'These fields let you customize your testimonials to gather the information you need.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>' . __( 'This editor serves two purposes: (1) to modify the form as it appears on your site, and (2) to modify the custom fields added to each testimonial.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>' . __( 'The default fields are designed to fit most situations. You can quickly add or remove fields and change several display properties.', 'strong-testimonials' ) . '</p>';

	$content .= '<p>';
	$content .= __( 'Fields will appear in this order on the form.', 'strong-testimonials' ) . '&nbsp;';
	$content .= sprintf( __( 'Reorder by grabbing the %s icon.', 'strong-testimonials' ), '<span class="dashicons dashicons-menu"></span>' );
	$content .= '</p>';

	$content .= '<p>' . __( 'To display this form, create a view and select Form mode.', 'strong-testimonials' ) . '</p>';

	// Links

	$links = array(
		'<a href="https://support.strongplugins.com/article/complete-example-customizing-form/" target="_blank">' . __( 'Tutorial', 'strong-testimonials' ) . '</a>',
		'<a href="' . admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-settings&tab=form' ) . '">' . __( 'Form settings', 'strong-testimonials' ) . '</a>'
	);

	// WPML
	if ( wpmtst_is_plugin_active( 'wpml' ) ) {
		$links[] = sprintf( __( 'Translate these fields in <a href="%s">WPML String Translations</a>', 'strong-testimonials' ),
			admin_url( 'admin.php?page=wpml-string-translation%2Fmenu%2Fstring-translation.php&context=strong-testimonials-form-fields' ) );
	}

	// Polylang
	if ( wpmtst_is_plugin_active( 'polylang' ) ) {
		$links[] = sprintf( __( 'Translate these fields in <a href="%s">Polylang String Translations</a>', 'strong-testimonials' ),
			admin_url( 'options-general.php?page=mlang&tab=strings&s&group=strong-testimonials-form-fields&paged=1' ) );
	}

	$content .= '<p>' . implode( ' | ', $links ) . '</p>';

	get_current_screen()->add_help_tab( array(
		'id'      => 'wpmtst-help',
		'title'   => __( 'Form Fields', 'strong-testimonials' ),
		'content' => $content,
	) );
}


add_action( 'load-wpm-testimonial_page_testimonial-views', 'wpmtst_help_view_editor_pagination' );
function wpmtst_help_view_editor_pagination() {
	ob_start();
	?>
	<p><?php _e( 'Some of the features and drawbacks for each method.', 'strong-testimonials' ); ?></p>

	<table class="wpmtst-help-tab" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th></th>
			<th><?php _e( 'Simple', 'strong-testimonials' ); ?></th>
			<th><?php _e( 'Standard', 'strong-testimonials' ); ?></th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td><?php _e( 'best use', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'ten pages or less', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'more than ten pages', 'strong-testimonials' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'URLs', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'does not change the URL', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'uses paged URLs just like standard WordPress posts', 'strong-testimonials' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'the Back button', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'It does not remember which page of testimonials you are on. If you click away &ndash; for example, on a "Read more" link &ndash; then click back, you will return to page one.', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'You will return the last page you were on so this works well with "Read more" links.', 'strong-testimonials' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'works with random order option', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'yes' ); ?></td>
			<td><?php _e( 'no' ); ?></td>
		</tr>
		<tr>
			<td><?php _e( 'works in a widget', 'strong-testimonials' ); ?></td>
			<td><?php _e( 'yes' ); ?></td>
			<td><?php _e( 'no' ); ?></td>
		</tr>
		</tbody>
	</table>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	get_current_screen()->add_help_tab( array(
		'id'      => 'wpmtst-help-pagination',
		'title'   => __( 'Pagination', 'strong-testimonials' ),
		'content' => $content,
	) );
}


add_action( 'load-wpm-testimonial_page_testimonial-views', 'wpmtst_help_view_editor_stretch' );
function wpmtst_help_view_editor_stretch() {
	ob_start();
	?>
	<p><?php _e( 'This setting will set the height of the <b>slideshow container</b> to match the tallest slide in order to keep elements below it from bouncing up and down during slide transitions. With testimonials of uneven length, the result is whitespace underneath the shorter testimonials.', 'strong-testimonials' ); ?></p>
    <p><?php _e( 'Select the <b>Stretch</b> option to stretch the borders and background vertically to compensate.', 'strong-testimonials' ); ?></p>
    <p><?php _e( 'Use the excerpt or abbreviated content if you want to minimize the whitespace.', 'strong-testimonials' ); ?></p>
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	get_current_screen()->add_help_tab( array(
		'id'      => 'wpmtst-help-stretch',
		'title'   => __( 'Stretch', 'strong-testimonials' ),
		'content' => $content,
	) );
}
