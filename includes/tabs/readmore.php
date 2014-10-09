<!-- READ MORE -->
<div id="tabs-readmore">
	<table class="shortcode-table wide">
		<tr>
			<th><h3><?php _e( '"Read More" link', 'strong-testimonials' ); ?></h3></th>
			<th><em><?php _e( 'must not be inside another [ strong ] shortcode', 'strong-testimonials' ); ?></em></th>
		</tr>
		<tr>
			<td>
				<?php _e( 'appears after the loop', 'strong-testimonials' ); ?><br />
				<em><?php _e( 'default text is "Read more" (translatable)', 'strong-testimonials' ); ?></em>
			</td>
			<td>
				read_more
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'to another page (required)', 'strong-testimonials' ); ?><br />
				<em><?php _e( 'ID or slug', 'strong-testimonials' ); ?></em>
			</td>
			<td>
				page
			</td>
		</tr>
		<tr>
			<td>
				<?php _e( 'add classes', 'strong-testimonials' ); ?><br />
				<!--<em><?php _e( '', 'strong-testimonials' ); ?></em>-->
			</td>
			<td>
				class
			</td>
		</tr>

		<tr>
			<th><h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3></th>
		</tr>
		<tr class="example">
			<td>
				<?php _e( 'minimal', 'strong-testimonials' ); ?><br />
			</td>
			<td>
				[strong read_more page="27" /]
			</td>
		</tr>
		<tr class="example">
			<td>
			<?php _e( 'full', 'strong-testimonials' ); ?><br />
			</td>
			<td>
				[strong read_more page="all-testimonials" class="strong-more"]Read more testimonials[/strong]
			</td>
		</tr>
	</table>
</div>
