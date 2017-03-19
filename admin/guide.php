<?php
/**
 * The Guide page.
 *
 * @since 1.9.1
 * @package Strong_Testimonials
 */

function wpmtst_guide() {
	$tags = array(
		'a'      => array(
			'href'   => array(),
			'target' => array(),
			'class'  => array() ),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
	);

	$plugin_data    = WPMST()->get_plugin_data();
	$plugin_version = $plugin_data['Version'];
	$major_minor    = strtok( $plugin_version, '.' ) . '.' . strtok( '.' );
	?>
	<div class="wrap wpmtst-guide">

		<h1 class="large"><?php printf( __( 'Welcome to Strong Testimonials %s', 'strong-testimonials' ), $major_minor ); ?></h1>

		<div class="guide-wrapper">
			<?php include 'partials/guide/guide.php'; ?>
			<?php include 'partials/guide/sidebar.php'; ?>
		</div>

	</div>
	<?php
}
