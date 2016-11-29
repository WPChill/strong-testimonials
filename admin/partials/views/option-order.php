<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-order">
		<?php _ex( 'Order', 'noun', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
		<select id="view-order" name="view[data][order]">
			<?php foreach ( $view_options['order'] as $order => $order_label ) : ?>
			<option value="<?php echo $order; ?>" <?php selected( $order, $view['order'] ); ?>><?php echo $order_label; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
</td>
