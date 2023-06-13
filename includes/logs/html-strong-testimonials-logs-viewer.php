<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( $logs ) : ?>
<div class="wrap wpmtst">

	<h1><?php esc_html_e( 'Strong Testimonials Debug Logs', 'strong-testimonials' ); ?></h1>
	<div id="log-viewer-select">
		<div class="alignleft">
			<h2>
				<?php echo esc_html( $viewed_log ); ?>
				<?php
				$subfolder = '';
				if ( strpos( $viewed_log, '/' ) ) {
					$subfolder = explode( '/', $viewed_log )[0];
					$log_name  = explode( '/', $viewed_log )[1];
				} else {
					$log_name = $viewed_log;
				}
				?>
				<?php if ( ! empty( $viewed_log ) ) : ?>
					<a class="page-title-action" href="
					<?php
					echo esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'st_log_remove' => sanitize_title( $log_name ),
									'subdir' => sanitize_title( $subfolder ),
								),
								admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-logs' )
							),
							'remove_log'
						)
					);
					?>
														" class="button"><?php esc_html_e( 'Delete log', 'strong-testimonials' ); ?></a>
					<a class="page-title-action" href="
					<?php
					echo esc_url(
						wp_nonce_url(
							add_query_arg(
								array(
									'st_log_download' => sanitize_title( $log_name ),
									'subdir'   => sanitize_title( $subfolder ),
								),
								admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-logs' )
							),
							'download_log'
						)
					);
					?>
														" class="button"><?php esc_html_e( 'Download log', 'strong-testimonials' ); ?></a>
				<?php endif; ?>
			</h2>
		</div>
		<div class="alignright">
			<form action="<?php echo esc_url( admin_url( 'edit.php?post_type=wpm-testimonial&page=strong-testimonials-logs' ) ); ?>" method="post">
				<select name="log_file">
					<?php foreach ( $logs as $log_key => $log_file ) : ?>
						<?php
							$timestamp = filemtime( WPMTST_LOGS . $log_file );
							$date      = sprintf(
								__( '%1$s at %2$s', 'strong-testimonials' ),
								wp_date( 'd-M-y', $timestamp ),
								wp_date( 'h:i:s', $timestamp )
							);
						?>
						<option value="<?php echo esc_attr( $log_key ); ?>" <?php selected( sanitize_title( $viewed_log ), $log_key ); ?>><?php echo esc_html( $log_file ); ?> (<?php echo esc_html( $date ); ?>)</option>
					<?php endforeach; ?>
				</select>
				<button type="submit" class="button" value="<?php esc_attr_e( 'View', 'strong-testimonials' ); ?>"><?php esc_html_e( 'View', 'strong-testimonials' ); ?></button>
			</form>
		</div>
		<div class="clear"></div>
	</div>
	<div id="log-viewer">
		<pre><?php echo esc_html( file_get_contents( WPMTST_LOGS . $viewed_log ) ); ?></pre>
	</div>
<?php else : ?>
	<div class="updated inline"><p><?php esc_html_e( 'There are currently no logs to view.', 'strong-testimonials' ); ?></p></div>
<?php endif; ?>
</div>
