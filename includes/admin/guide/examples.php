<div class="guide-content examples">
	<section>
		<h3><?php _e( 'Examples', 'strong-testimonials' ); ?></h3>
		<table class="reference examples first wide">
			<tr class="sub">
				<th><?php _e( 'shortcode', 'strong-testimonials' ); ?></th>
				<th><?php _e( 'result', 'strong-testimonials' ); ?></th>
			</tr>
			<tr class="example">
				<td>
<pre>
[strong title thumbnail]
    [client]
        [field name="client_name" class="name"]
        [field name="company_name" url="company_website" class="company" new_tab]
    [/client]
[/strong]</pre>
				</td>
				<td><?php _e( 'To show all testimonials<br> with their title and Featured Image,<br> from oldest to newest,<br> with the client\'s name<br> and a link to their website (if provided)', 'strong-testimonials' ); ?></td>
			</tr>
			<tr class="example">
				<td>
<pre>
[strong excerpt slideshow show_for="5" effect_for="1" count="3" random]
    [client]
        [field name="client_name" class="name"]
    [/client]
[/strong]</pre>
				</td>
				<td><?php _e( 'To create a slideshow<br> of 3 random excerpts,<br> with the client\'s name,<br> showing each one for 5 seconds<br> before fading to the next over 1 second', 'strong-testimonials' ); ?></td>
			</tr>
			<tr class="example">
				<td>
<pre>
[strong title length="150" newest category="2"
    more_page="15" more_text="More client testimonials&hellip;"]
    [client]
        [field name="client_name" class="name"]
        [date format="F Y"]
    [/client]
[/strong]</pre>
				</td>
				<td><?php printf( __( 'To show the title and truncated content<br> of testimonials from category 2,<br> from newest to oldest,<br> with the client name<br> and the date formatted like "%s",<br> and a link to a page 15', 'strong-testimonials' ), date( "F Y" ) ); ?></td>
			</tr>
		</table>
	</section>
</div>
