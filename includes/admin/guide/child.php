<!-- CHILD SHORTCODES -->
<?php /* translators: In the [strong] shortcode instructions. */ ?>
<tr class="heading">
	<th colspan="4"><?php _e( 'Custom Fields', 'strong-testimonials' ); ?></th>
</tr>
<tr class="section">
	<th colspan="4">
		<!--<h4>--><?php //_e( 'Custom Fields', 'strong-testimonials' ); ?><!--</h4>-->
		<div class="description"><?php _e( 'These child shortcodes must be inside a <code>[strong][/strong]</code> pair', 'strong-testimonials' ); ?></div>
	</th>
</tr>
<tr class="sub">
	<th>shortcode</th>
	<th>attribute</th>
	<th>purpose</th>
	<th>accepted values</th>
</tr>
<tr class="att">
	<td>[client]</td>
	<td></td>
	<td>
		<?php _e( 'to start the client section', 'strong-testimonials' ); ?>
	</td>
	<td></td>
</tr>
<tr class="att">
	<td>[field]</td>
	<td></td>
	<td>
		<?php _e( 'to show a custom field', 'strong-testimonials' ); ?>
	</td>
	<td></td>
</tr>
<tr class="att">
	<td></td>
	<td>name <em>(required)</em></td>
	<td><?php _e( 'the field name', 'strong-testimonials' ); ?></td>
	<td>a custom field:<br><span class="code"><?php echo implode( ', ', wpmtst_get_custom_field_list() ); ?></span></td>
</tr>
<tr class="att">
	<td></td>
	<td>class</td>
	<td><?php _e( 'to add a CSS class', 'strong-testimonials' ); ?></td>
	<td>a list of class names, separated by commas</td>
</tr>
<tr class="att">
	<td></td>
	<td>url</td>
	<td>
		<?php _e( 'to construct a link with another custom field', 'strong-testimonials' ); ?>
	</td>
	<td>a custom field:<br><span class="code"><?php echo implode( ', ', wpmtst_get_custom_field_list() ); ?></span></td>
</tr>
<tr class="att">
	<td></td>
	<td>new_tab</td>
	<td>
		<?php _e( 'to open that link in a new tab', 'strong-testimonials' ); ?>
	</td>
	<td></td>
</tr>
<tr class="att">
	<td>[date]</td>
	<td></td>
	<td>
		<?php _e( 'to show the post date', 'strong-testimonials' ); ?>
	</td>
	<td></td>
</tr>
<tr class="att">
	<td></td>
	<td>format</td>
	<td><?php _e( 'to change the date format', 'strong-testimonials' ); ?></td>
	<td><a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank" rel="nofollow">more about date formats</a></td>
</tr>
<tr class="att">
	<td></td>
	<td>class</td>
	<td><?php _e( 'to add a CSS class', 'strong-testimonials' ); ?></td>
	<td>a list of class names, separated by commas</td>
</tr>

<tr class="example">
	<td colspan="4" class="has-inner">
		<table>
			<tr class="sub">
				<th><?php _e( 'example', 'strong-testimonials' ); ?></th>
			</tr>
			<tr class="left">
				<td>
<pre>
[strong &hellip;]
    [client]
        [field name="client_name" class="name"]
        [field name="company_name" url="company_website" class="company" new_tab]
        [date class="date" format="F Y"]
    [/client]
[/strong]</pre>
				</td>
			</tr>
		</table>
	</td>
</tr>
