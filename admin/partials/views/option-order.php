<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-order">
		<?php echo esc_html_x( 'Order', 'noun', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
		<div class="inline">
		<select id="view-order" name="view[data][order]">
			<?php foreach ( $view_options['order'] as $order => $order_label ) : ?>
			<option value="<?php echo esc_attr( $order ); ?>" <?php selected( $order, $view['order'] ); ?>><?php echo esc_html( $order_label ); ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	</div>
</td>
<td class="divider">
	<p><?php echo wp_kses_post( _e( '<code>order</code>', 'strong-testimonials' ) ); ?></p>
</td>
<td>
	<p><?php echo wp_kses_post( _e( 'oldest | newest | random | menu_order', 'strong-testimonials' ) ); ?></p>
</td>
<td>
	<p><?php echo wp_kses_post( _e( '<code>order="random"</code>', 'strong-testimonials' ) ); ?></p>
</td>
