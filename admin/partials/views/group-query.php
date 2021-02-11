<?php
$then_classes = array(
	'then',
	'then_display',
	'then_not_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'select' ),
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_filter( $then_classes ) ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php _e( 'Query', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-select">
        <tr class="subheading">
            <td><?php _e( 'Option', 'strong-testimonials' ); ?></td>
            <td><?php _e( 'Setting', 'strong-testimonials' ); ?></td>
            <td class="divider" colspan="2">
				<?php _e( 'or Shortcode Attribute', 'strong-testimonials' ); ?>
				<span class="help-links">
					<span class="description">
						<a href="#tab-panel-wpmtst-help-shortcode" class="open-help-tab"><?php esc_html_e( 'Help', 'strong-testimonials' ); ?></a>
					</span>
				</span>
			</td>
            <td>Example</td>
        </tr>
		<tr class="then then_display then_not_slideshow then_not_form" style="display: none;">
			<?php include( 'option-select.php' ); ?>
		</tr>
		<tr class="then then_slideshow then_not_single then_multiple" style="display: none;">
			<?php include( 'option-category.php' ); ?>
		</tr>
		<tr class="then then_slideshow then_not_single then_multiple" style="display: none;">
			<?php include( 'option-order.php' ); ?>
		</tr>
		<tr class="then then_slideshow then_not_single then_multiple" style="display: none;">
			<?php include( 'option-limit.php' ); ?>
		</tr>
		<?php do_action( 'wpmtst_views_group_query', $view ); ?>
	</table>
</div>
