<?php /* translators: On the Views admin screen. */ ?>
<th>
	<label for="view-all">
		<?php _e( 'Quantity', 'strong-testimonials' ); ?>
	</label>
</th>
<td>
	<div class="row">
        <div class="inline">
            <select id="view-all" name="view[data][all]" class="if select">
                <option value="1" <?php selected( $view['all'] ); ?>>
                    <?php _e( 'all', 'strong-testimonials' ); ?>
                </option>
                <option value="0" class="trip" <?php selected( $view['all'], 0 ); ?>>
                    <?php _ex( 'count', 'noun', 'strong-testimonials' ); ?>
                </option>
            </select>
            &nbsp;
            <label>
                <input type="number" id="view-count" class="input-incremental then_all"
                       name="view[data][count]" value="<?php echo $view['count']; ?>"
                       min="1" size="5" style="display: none;">
            </label>
        </div>
        <div class="inline">
            <p class="description">How many testimonials to select for this view.</p>
        </div>
        <div class="inline then then_slideshow then_not_display then_not_form" style="display: none;">
            <p class="description">Slideshows will display one a time.</p>
        </div>
	</div>
</td>
