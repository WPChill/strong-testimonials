<?php

/**
 * Class Strong_Testimonials_Page_Shortcodes
 *
 * @since 2.31.0
 */
class Strong_Testimonials_Page_Shortcodes {

	/**
	 * Strong_Testimonials_Page_Shortcodes constructor.
	 */
	private function __construct() {
	}

	/**
	 * Render the shortcode instructions page.
	 */
	public static function render_page() {
		$star_solid   = '<svg class="star_solid" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z"></path></svg>';
		$star_regular = '<svg class="star_regular" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M528.1 171.5L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6zM388.6 312.3l23.7 138.4L288 385.4l-124.3 65.3 23.7-138.4-100.6-98 139-20.2 62.2-126 62.2 126 139 20.2-100.6 98z"></path></svg>';
		$star_half    = '<svg class="star_half" aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="-8 -8 584 520"><path  d="M508.55 171.51L362.18 150.2 296.77 17.81C290.89 5.98 279.42 0 267.95 0c-11.4 0-22.79 5.9-28.69 17.81l-65.43 132.38-146.38 21.29c-26.25 3.8-36.77 36.09-17.74 54.59l105.89 103-25.06 145.48C86.98 495.33 103.57 512 122.15 512c4.93 0 10-1.17 14.87-3.75l130.95-68.68 130.94 68.7c4.86 2.55 9.92 3.71 14.83 3.71 18.6 0 35.22-16.61 31.66-37.4l-25.03-145.49 105.91-102.98c19.04-18.5 8.52-50.8-17.73-54.6zm-121.74 123.2l-18.12 17.62 4.28 24.88 19.52 113.45-102.13-53.59-22.38-11.74.03-317.19 51.03 103.29 11.18 22.63 25.01 3.64 114.23 16.63-82.65 80.38z"></path></svg>';
	
		$svg_args = array(
			'svg'   => array(
				'class'           => true,
				'aria-hidden'     => true,
				'aria-labelledby' => true,
				'role'            => true,
				'xmlns'           => true,
				'width'           => true,
				'height'          => true,
				'viewbox'         => true, // <= Must be lower case!
				'id'              => true,
			),
			'g'     => array( 'fill' => true ),
			'title' => array( 'title' => true ),
			'path'  => array(
				'd'    => true,
				'fill' => true,
			),
			'style' => array( 'type' => true ),
			'span' => array(
				'style' 		=> array(),
				'class' 		=> array(),
			),
		);

		

		$stars = '<span class="strong-rating"><span class="star0 star"></span><span class="star">'.$star_solid.'</span><span class="star">'.$star_solid.'</span><span class="star">'.$star_solid.'</span><span class="star">'.$star_solid.'</span><span class="star current half">'.$star_half.'</span></span>';
		$stars_rounded = '<span class="strong-rating"><span class="star0 star"></span><span class="star">'.$star_solid.'</span><span class="star">'.$star_solid.'</span><span class="star">'.$star_solid.'</span><span class="star current">'.$star_solid.'</span><span class="star">'.$star_regular.'</span></span>';

		$tags = array(
			'a' => array(
				'href'   => array(),
				'target' => array(),
			),
		);
		?>
		<div class="wrap wpmtst shortcodes has-stars">

			<h1><?php esc_html_e( 'Shortcodes', 'strong-testimonials' ); ?></h1>

			<h2><?php esc_html_e( 'Testimonial Views', 'strong-testimonials' ); ?></h2>

			<p>
				<?php echo wp_kses_post( esc_html_e( 'Each view has a unique shortcode like ', 'strong-testimonials' ) ); ?><code>&#91;testimonial_view id="1"&#93;</code>.
				<?php printf( '<a href="%s">%s</a>', esc_url( admin_url( 'edit.php?post_type=wpm-testimonial&page=testimonial-views' ) ), esc_html__( 'Go to views', 'strong-testimonials' ) ); ?>
			</p>

			<h2><?php esc_html_e( 'Testimonial Count', 'strong-testimonials' ); ?></h2>

			<p><?php printf( esc_html__( 'Use %s to display the number of testimonials.', 'strong-testimonials' ), '<code>&#91;testimonial_count&#93;</code>' ); ?></p>

			<table class="form-table shortcodes" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<p><?php esc_html_e( 'Default', 'strong-testimonials' ); ?></p>
					</td>
					<td class="shortcode">
						<?php /* translators: %s is a shortcode */ ?>
						<p>
							<?php printf( esc_html__( 'Read some of our %s testimonials!', 'strong-testimonials' ), '&#91;testimonial_count&#93;' ); ?>
						</p>
					</td>
				</tr>
				<tr>
					<td>
						<?php /* translators: %s is a shortcode attribute */ ?>
						<p><?php printf( esc_html__( 'To count for a specific category, add the %s attribute with the category slug.', 'strong-testimonials' ), '<code>category</code>' ); ?>
					</td>
					<td class="shortcode">
						<?php /* translators: %s is a shortcode */ ?>
						<p>
							<?php printf( esc_html__( 'Here\'s what %s local clients say', 'strong-testimonials' ), '&#91;testimonial_count category="local"&#93;' ); ?>
						</p>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'Average Rating', 'strong-testimonials' ); ?></h2>

			<p>
				<?php /* translators: %s is a shortcode */ ?>
				<?php printf( wp_kses_post( __( 'If using a <strong>single</strong> rating field, use %s to display the average rating.', 'strong-testimonials' ) ), '<code>&#91;testimonial_average_rating&#93;</code>' ); ?>
			</p>

			<table class="form-table shortcodes average" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<p><?php esc_html_e( 'Default', 'strong-testimonials' ); ?></p>
						<p class="description"><?php echo wp_kses_post( __( 'You must use the closing slash <code>/</code> if using the shortcode with content elsewhere on your page.', 'strong-testimonials' ) ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)','strong-testimonials'); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<p><?php esc_html_e( 'Customize using content tags.', 'strong-testimonials' ); ?></p>
						<p><?php esc_html_e( 'Default:', 'strong-testimonials' ); ?></p>
						<p><code>{title}</code><br><code>{stars}</code><br><code>{summary}</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{title} {stars} {summary}&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)','strong-testimonials'); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<p><?php esc_html_e( 'Alternate content tags.', 'strong-testimonials' ); ?></p>
						<p><code>{title2}</code><br><code>{summary2}</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{title2} {stars} {summary2}&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<span class="strong-rating-title"><?php esc_html_e( 'Average of 9 Ratings:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							<tr>
						</tr>
					</table>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><?php esc_html_e( 'Insert tags into your custom content.', 'strong-testimonials' ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{stars}<?php esc_html( 'Our average rating is ', 'strong-testimonials' ); ?> &lt;b&gt;{summary2}&lt;/b&gt;&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<?php esc_html_e( 'Our average rating is ', 'strong-testimonials' ); ?><b><span class="strong-rating-summary"><?php esc_html_e( '4.3 stars', 'strong-testimonials' ); ?></span></b>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>{stars}</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{stars}&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<?php echo wp_kses( $stars, $svg_args ); ?>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>{average}</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{average}&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average"><span class="strong-rating-average"><?php esc_html_e( '4.3', 'strong-testimonials' ); ?></span></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>decimals</code></p>
						<p class="description"><?php esc_html_e( 'If you need to display multiple decimals for average rating you have to set a number to decimal.', 'strong-testimonials' ); ?></p>
                                              
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating decimals="number" /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average block"><span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.333 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
                        
			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>{count}</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating&#93;{count}&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average"><span class="strong-rating-count"><?php esc_html_e( '9', 'strong-testimonials' ); ?></span></div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
                        
			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>field</code></p>
						<p class="description"><?php esc_html_e( 'If using multiple rating fields, you can specify the specific field you need.', 'strong-testimonials' ); ?></p>
						<p class="description"><?php esc_html_e( 'If using multiple rating fields, you can use "all" to use all the rating fields.', 'strong-testimonials' ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating field="rating" /&#93;</td>
							</tr>
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating field="all" /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average block"><span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>block</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating block /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average block"><span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>centered</code></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating centered /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average centered"><span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><code>rounded</code></p>
                        <p class="description"><?php esc_html_e( 'Round the rating(ex.: from 4.3 -> 4 or 4.7->5)', 'strong-testimonials' ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating rounded &#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average"><span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars_rounded, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

            <table class="form-table shortcodes average">
                <tr>
                    <td>
                        <p><code>block</code> and <code>centered</code></p>
                    </td>
                    <td class="has-inner">
                        <table class="inner" cellpadding="0" cellspacing="0">
                            <tr>
                                <td class="shortcode">&#91;testimonial_average_rating block centered /&#93;</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="strong-rating-wrapper average block centered"><span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
                                        <?php echo wp_kses( $stars, $svg_args ); ?>
                                        <span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

			<table class="form-table shortcodes average">
				<tr>
					<td>
						<p><?php echo wp_kses_post( __( 'The default container element is <code>div</code>. Select another element using <code>element</code>.', 'strong-testimonials' ) ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating element="span" /&#93;</td>
							</tr>
							<tr>
								<td>
									<span class="strong-rating-wrapper average">
										<span class="strong-rating-title"><?php esc_html_e( 'Average Rating:', 'strong-testimonials' ); ?></span>
										<?php echo wp_kses( $stars, $svg_args ); ?>
										<span class="strong-rating-summary"><?php esc_html_e( '4.3 stars (based on 9 ratings)', 'strong-testimonials' ); ?></span>
									</span>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

		</div>
		<?php
	}

}
