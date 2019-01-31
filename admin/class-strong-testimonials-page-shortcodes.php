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

		$stars = '<span class="strong-rating"><span class="star0 star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star"></span><span class="star current half"></span></span>';

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
				<?php echo wp_kses_post( _e( 'Each view has a unique shortcode like <code>&#91;testimonial_view id="1"&#93;</code>.', 'strong-testimonials' ) ); ?>
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
				<?php echo wp_kses_post( printf( __( 'If using a <strong>single</strong> rating field, use %s to display the average rating.', 'strong-testimonials' ), '<code>&#91;testimonial_average_rating&#93;</code>' ) ); ?>
			</p>

			<table class="form-table shortcodes average" cellpadding="0" cellspacing="0">
				<tr>
					<td>
						<p><?php esc_html_e( 'Default', 'strong-testimonials' ); ?></p>
						<p class="description"><?php echo wp_kses_post( _e( 'You must use the closing slash <code>/</code> if using the shortcode with content elsewhere on your page.', 'strong-testimonials' ) ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating /&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
										<span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
										<span class="strong-rating-title">Average of 9 Ratings:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars</span>
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
								<td class="shortcode">&#91;testimonial_average_rating&#93;{stars} Our average rating is &lt;b&gt;{summary2}&lt;/b&gt;&#91;/testimonial_average_rating&#93;</td>
							</tr>
							<tr>
								<td>
									<div class="strong-rating-wrapper average">
										<?php echo wp_kses_post( $stars ); ?>
										Our average rating is <b><span class="strong-rating-summary">4.3 stars</span></b>
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
										<?php echo wp_kses_post( $stars ); ?>
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
									<div class="strong-rating-wrapper average"><span class="strong-rating-average">4.3</span></div>
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
									<div class="strong-rating-wrapper average"><span class="strong-rating-count">9</span></div>
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
									<div class="strong-rating-wrapper average block"><span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
									<div class="strong-rating-wrapper average centered"><span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
									<div class="strong-rating-wrapper average block centered"><span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
						<p><?php echo wp_kses_post( _e( 'The default container element is <code>div</code>. Select another element using <code>element</code>.', 'strong-testimonials' ) ); ?></p>
					</td>
					<td class="has-inner">
						<table class="inner" cellpadding="0" cellspacing="0">
							<tr>
								<td class="shortcode">&#91;testimonial_average_rating element="span" /&#93;</td>
							</tr>
							<tr>
								<td>
									<span class="strong-rating-wrapper average">
										<span class="strong-rating-title">Average Rating:</span>
										<?php echo wp_kses_post( $stars ); ?>
										<span class="strong-rating-summary">4.3 stars (based on 9 ratings)</span>
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
