<?php
/**
 * The Guide pages.
 *
 * @since 1.9.1
 * @package Strong_Testimonials
 */ 

function wpmtst_guide() {
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'getting-started';
	$page = '?post_type=wpm-testimonial&page=guide';
	?>
	<div class="wrap wpmtst guide">
		<h1><?php _e( 'Strong Testimonials Guide', 'strong-testimonials' ); ?></h1>
		<?php /* translators: In the Guide. */ ?>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo $page; ?>" class="nav-tab <?php echo $tab == 'getting-started' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Getting Started', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=views" class="nav-tab <?php echo $tab == 'views' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Views', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=templates" class="nav-tab <?php echo $tab == 'templates' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Templates', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=features" class="nav-tab <?php echo $tab == 'features' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'New Features', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=shortcodes" class="nav-tab <?php echo $tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Shortcodes', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=page-builders" class="nav-tab <?php echo $tab == 'page-builders' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Page Builders', 'strong-testimonials' ); ?>
			</a>
		</h2>
		<?php
		switch ( $tab ) {
			case 'page-builders':
				include 'page-builders.php';
				break;
			case 'templates':
				include 'templates.php';
				break;
			case 'views':
				include 'views.php';
				break;
			case 'features':
				include 'features.php';
				break;
			case 'shortcodes':
				include 'shortcodes.php';
				break;
			default:
				include 'start.php';
		}
		?>
	</div>
	<?php
}

function wpmtst_guide_before_content() {
	?>
	<div id="plugin-sidebar">
		<p><?php _e( 'Need help? Have an idea? Found a bug?', 'strong-testimonials' ); ?></p>
		<p><?php printf( __( 'Please use the <a href="%s" target="_blank">support forum</a> or <a href="%s" target="_blank">contact me</a>.', 'strong-testimonials' ), 'http://wordpress.org/support/plugin/strong-testimonials', 'https://www.wpmission.com/contact/' ); ?></p>
	</div>
	<?php
}
add_action( 'wpmtst_guide_before_content', 'wpmtst_guide_before_content' );

function wpmtst_guide_after_content() {
	?>
	<h3><?php _e( 'Need help? Have an idea? Found a bug?', 'strong-testimonials' ); ?></h3>
	<p><?php printf( __( 'Please use the <a href="%s" target="_blank">support forum</a> or <a href="%s" target="_blank">contact me</a>.', 'strong-testimonials' ), 'http://wordpress.org/support/plugin/strong-testimonials', 'https://www.wpmission.com/contact/' ); ?></p>
	<?php
}
//add_action( 'wpmtst_guide_after_content', 'wpmtst_guide_after_content' );
