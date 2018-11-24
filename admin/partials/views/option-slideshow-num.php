<?php /* translators: In the view editor. */ ?>
<th>
	<?php _e( 'Show', 'strong-testimonials' ); ?>
</th>
<td>
	<?php include( 'option-slideshow-type.php' ); ?>

    <div class="inline then then_slider_type then_not_single then_multiple" style="display: none;">
        <div class="row" style="background: lightpink;">
            <p>breakpoints</p>
            <?php include( 'option-slideshow-breakpoints.php' ); ?>
        </div>
    </div>
</td>
