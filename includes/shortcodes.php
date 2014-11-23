<?php
/**
 * Shortcode functions.
 *
 * @package Strong_Testimonials
 */


/**
 * Normalize empty shortcode attributes.
 *
 * Turns atts into tags - brilliant!
 * Thanks http://wordpress.stackexchange.com/a/123073/32076
 */
if ( ! function_exists( 'normalize_empty_atts' ) ) {
	function normalize_empty_atts( $atts ) {
		if ( ! empty( $atts ) ) {
			foreach ( $atts as $attribute => $value ) {
				if ( is_int( $attribute ) ) {
					$atts[ strtolower( $value ) ] = true;
					unset( $atts[ $attribute ] );
				}
			}
			return $atts;
		}
	}
}


/**
 * Single Testimonial LAYOUT
 */
function wpmtst_single( $post, $args = array() ) {
	extract( array_merge( array( 
			'title'   => 1, 
			'images'  => 1, 
			'content' => '',
			'client'  => 1, 
			'more'    => 0
	), $args ) );
	
	$client_info = do_shortcode( wpmtst_client_info( $post ) );
	
	ob_start();
	include( WPMTST_INC . 'wpmtst-single.php' );
	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}


/**
 * Template functions (partial).
 *
 * @since 1.11.0
 */
function wpmtst_field( $field = null, $args = array() ) {
	if ( ! $field ) return '';
		
	global $post;
	$html = '';
	
	switch( $field ) {
	
		// Apply a character limit to post content.
		case 'truncated' :
			$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
			break;
		
		// Process child shortcodes in [strong] content.
		case 'client' :
			$html = do_child_shortcode( 'strong', $args['content'] );
			break;
		
		// Get the custom field.
		default :
			$html = get_post_meta( $post->ID, $field, true );
			
	}
	echo $html;
}


/**
 * Assemble and display client info.
 */
function wpmtst_client_info( $post ) {
	// ---------------------------------------------------------------------
	// Get the client template, populate it with data from the current post,
	// then render it.
	//
	// Third approach. Took me all day on 6/30/2014.
	// ---------------------------------------------------------------------
	
	$html = '';
	$options  = get_option( 'wpmtst_options' );
	$fields   = get_option( 'wpmtst_fields' );
	$template = $options['client_section'];
	
	$lines = explode( PHP_EOL, $template );
	// [wpmtst-text field="client_name" class="name"]
	// [wpmtst-link url="company_website" text="company_name" new_tab class="company"]
	
	foreach ( $lines as $line ) {
		// to get shortcode:
		$pattern = '/\[([a-z0-9_\-]+)/';
		preg_match( $pattern, $line, $matches );
		if ( $matches ) {
			$shortcode = $matches[1];
			if ( 'wpmtst-text' == $shortcode ) {
				// to get field:
				$pattern = '/field="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content and close shortcode
					$line .= $post_value . '[/' . $shortcode . ']';
					$html .= $line;
				}
			}
			elseif ( 'wpmtst-link' == $shortcode ) {
				// (\w+)="(\w+)"
				// to get url:
				$pattern = '/url="(\w+)"/';
				preg_match( $pattern, $line, $matches2 );
				if ( $matches2 ) {
					$field_name = $matches2[1];
					$post_value = $post->$field_name;
					// add to line as content with separator
					$line .= $post_value . '|';
				}
				// to get text:
				$pattern = '/text="(\w+)"/';
				preg_match( $pattern, $line, $matches3 );
				if ( $matches3 ) {
					$field_name = $matches3[1];
					$post_value = $post->$field_name;
					// add to line as content
					$line .= $post_value;
				}
				// check nofollow:
				$line .= '|';
				if ( get_post_meta( $post->ID, 'nofollow' ) )
					$line .= 'nofollow';
				// close shortcode
				$line .= '[/' . $shortcode . ']';
				$html .= $line;
			}
		}
	}
	// return do_shortcode( $html );
	return $html;
}


/**
 * Client text field shortcode.
 */
function wpmtst_text_shortcode( $atts, $content = null ) {
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;
		
	extract( shortcode_atts(
		array( 
				'field' => '', 
				'class' => ''
		),
		normalize_empty_atts( $atts )
	) );
	return '<div class="' . $class . '">' . $content . '</div>';
}
add_shortcode( 'wpmtst-text', 'wpmtst_text_shortcode' );


/**
 * Client link shortcode.
 */
function wpmtst_link_shortcode( $atts, $content = null ) {
	// content like "company_website|company_name|nofollow"
	// bail if no content
	if ( empty( $content ) || '|' === $content )
		return;

	extract( shortcode_atts(
		array( 
				'url'      => '', 
				'new_tab'  => 0,
				'nofollow' => '',   // client-specific, not global
				'text'     => '', 
				'class'    => ''
		),
		normalize_empty_atts( $atts )
	) );
		
	list( $url, $text, $nofollow ) = explode( '|', $content );
	
	// if no company name, use domain name
	if ( ! $text )
		$text = preg_replace( "(^https?://)", "", $url );
		
	// if no url, return as text shortcode instead
	if ( $url )
		return '<div class="' . $class . '"><a href="' . $url . '"'. link_new_tab( $new_tab, false ) . link_nofollow( $nofollow, false ) . '>' . $text . '</a></div>';
	else
		return '<div class="' . $class . '">' . $text . '</div>';
}
add_shortcode( 'wpmtst-link', 'wpmtst_link_shortcode' );


/**
 * Single testimonial shortcode.
 *
 * @uses wpmtst-single.php
 */
function wpmtst_single_shortcode( $atts ) {
	extract( shortcode_atts( 
		array( 'id' => '' ),
		normalize_empty_atts( $atts )
	) );
	$post = wpmtst_get_post( get_post( $id ) );
	$display = '<div id="wpmtst-container">';
	$display .= wpmtst_single( $post );
	$display .= '</div>';
	return $display;
}
add_shortcode( 'wpmtst-single', 'wpmtst_single_shortcode' );


/**
 * Random testimonial shortcode.
 *
 * @uses wpmtst-single.php
 */
function wpmtst_random_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 
				'category' => '', 
				'limit' => 1
		),
		normalize_empty_atts( $atts )
	) );
	$categories = explode( ',', $category );

	$args = array(
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $limit,
			'orderby'        => 'rand',
			'post_status'    => 'publish'
	);

	if ( $category ) {
		$args['tax_query'] = array(
				array(
						'taxonomy' => 'wpm-testimonial-category',
						'field'    => 'term_id',
						'terms'    => $categories,
						'include_children' => false
				)
		);
	}
	
	$wp_query = new WP_Query();
	$results  = $wp_query->query( $args );

	$display = '<div id="wpmtst-container">';
	foreach ( $results as $post ) {
		$display .= wpmtst_single( wpmtst_get_post( $post ) );
	}
	$display .= '</div>';
	
	return $display;
}
add_shortcode( 'wpmtst-random', 'wpmtst_random_shortcode' );


/**
 * All testimonials shortcode.
 *
 * @uses wpmtst-single.php
 */
function wpmtst_all_shortcode( $atts ) {
	extract( shortcode_atts(
		array( 
				'category' => '', 
				'limit' => -1
		),
		normalize_empty_atts( $atts )
	) );
	$categories = explode( ',', $category );

	$args = array(
			'post_type'      => 'wpm-testimonial',
			'posts_per_page' => $limit,
			'orderby'        => 'post_date',
			'order'          => 'DESC',
			'post_status'    => 'publish'
	);

	if ( $category ) {
		$args['tax_query'] = array(
				array(
						'taxonomy' => 'wpm-testimonial-category',
						'field'    => 'term_id',
						'terms'    => $categories,
						'include_children' => false
				)
		);
	}

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container">';
	foreach ( $results as $post ) {
		$display .= '<div class="result">' . wpmtst_single( wpmtst_get_post( $post ) ) . '</div>';
	}
	$display .= '</div><!-- wpmtst-container -->';

	return $display;
}
add_shortcode( 'wpmtst-all', 'wpmtst_all_shortcode' );


/**
 * Cycle testimonials shortcode.
 *
 * @uses wpmtst-single.php
 */
function wpmtst_cycle_shortcode( $atts ) {
	extract( shortcode_atts(
		array(),
		normalize_empty_atts( $atts )
	) );
	$cycle = get_option( 'wpmtst_cycle' );

	do_action( 
		'wpmtst_cycle_hook', 
		$cycle['effect'], 
		$cycle['speed'], 
		$cycle['timeout'], 
		$cycle['pause'],
		'tcycle_cycle_shortcode'
	);

	if ( 'rand' == $cycle['order'] ) {
		$orderby = 'rand';
		$order   = '';
	}
	elseif ( 'oldest' == $cycle['order'] ) {
		$orderby = 'post_date';
		$order   = 'ASC';
	}
	else {
		$orderby = 'post_date';
		$order   = 'DESC';
	}
	$limit = ( $cycle['all'] ? -1 : $cycle['limit'] );
	
	$args = array(
			'post_type'      => 'wpm-testimonial',
			'post_status'    => 'publish',
			'posts_per_page' => $limit,
			'orderby'        => $orderby,
			'order'          => $order,
	);

	if ( $cycle['category'] && 'all' != $cycle['category'] ) {
		$args['tax_query'] = array(
				array(
						'taxonomy' => 'wpm-testimonial-category',
						'field'    => 'term_id',
						'terms'    => $cycle['category'],
						'include_children' => false,
				)
		);
	}

	$wp_query = new WP_Query();
	$results = $wp_query->query( $args );

	$display = '<div id="wpmtst-container" class="tcycle tcycle_cycle_shortcode">';
	foreach ( $results as $post ) {
		$display .= '<div class="result t-slide">' . wpmtst_single( wpmtst_get_post( $post ), $cycle ) . '</div>';
	}
	$display .= '</div><!-- #wpmtst-container -->';

	return $display;
}
add_shortcode( 'wpmtst-cycle', 'wpmtst_cycle_shortcode' );


/**
 * Pagination on "All Testimonials" shortcode.
 */
function wpmtst_pagination_function() {
	// $per_page = get_option( 'wpmtst_options' )['per_page']; // only PHP 5.3+
	$options  = get_option( 'wpmtst_options' );
	$per_page = $options['per_page'] ? $options['per_page'] : 5;
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$("#wpmtst-container").quickPager({ pageSize: <?php echo $per_page; ?>, currentPage: 1, pagerLocation: "after" });
			$(".strong-container").quickPager({ pageSize: <?php echo $per_page; ?>, currentPage: 1, pagerLocation: "after" });
		});
	</script>
	<?php
}


/**
 * Notify admin upon testimonial submission.
 */
function wpmtst_notify_admin() {
// function wpmtst_notify_admin( $email = '' ) {
	$options = get_option( 'wpmtst_options' );
	$admin_notify = $options['admin_notify'];
	$admin_email  = $options['admin_email'];

	if ( $admin_notify && $admin_email ) {
		/* translators: Subject line for new testimonial notification email. */
		$subject = __( 'New testimonial for', 'strong-testimonials' ) . ' ' . get_option( 'blogname' );
		$headers = 'From: noreply@' . preg_replace( '/^www\./', '', $_SERVER['HTTP_HOST'] );
		/* translators: Message for new testimonial notification email. */
		$message = sprintf( __( 'New testimonial submission for %s. This is awaiting action from the website administrator.', 'strong-testimonials' ), get_option( 'blogname' ) );
		// $message = sprintf( __( 'New testimonial submission from %s for %s. This is awaiting action from the website administrator.', 'strong-testimonials' ), $email, get_option( 'blogname' ) );
		// More info here? A copy of testimonial? A link to admin page? A link to approve directly from email?
		wp_mail( $admin_email, $subject, $message, $headers );
	}
}
