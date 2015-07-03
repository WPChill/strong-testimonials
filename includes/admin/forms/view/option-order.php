<?php /* translators: On the Views admin screen. */ ?>
	<th><label for="view-order"><?php _ex( 'Order', 'noun', 'strong-testimonials' ); ?></label>
</th>
<td colspan="2">
	<select id="view-order" name="view[data][order]" autocomplete="off">
		<?php foreach ( $order_list as $order => $order_label ) : ?>
		<option value="<?php echo $order; ?>" <?php selected( $order, $view['order'] ); ?>><?php echo $order_label; ?></option>
		<?php endforeach; ?>
	</select>
</td>
