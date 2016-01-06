<?php
/**
 * The Guide pages.
 *
 * @since 1.9.1
 * @package Strong_Testimonials
 */

function wpmtst_guide() {
	$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcome';
	$page = admin_url( 'edit.php?post_type=wpm-testimonial&page=guide');
	?>
	<div class="wrap wpmtst guide">
		<h1><?php _e( 'Strong Testimonials Guide', 'strong-testimonials' ); ?></h1>
		<?php /* translators: In the Guide. */ ?>
		<h2 class="nav-tab-wrapper">
			<a href="<?php echo $page; ?>" class="nav-tab <?php echo $tab == 'welcome' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Welcome', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=version2" class="nav-tab <?php echo $tab == 'version2' ? 'nav-tab-active' : ''; ?>" style="color:red;">
				<?php _e( 'Version 2 - Important!', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=start" class="nav-tab <?php echo $tab == 'start' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Getting Started', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=views" class="nav-tab <?php echo $tab == 'views' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Views', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=templates" class="nav-tab <?php echo $tab == 'templates' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Templates', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=shortcodes" class="nav-tab <?php echo $tab == 'shortcodes' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Shortcodes', 'strong-testimonials' ); ?>
			</a>
			<a href="<?php echo $page; ?>&tab=translation" class="nav-tab <?php echo $tab == 'translation' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Translation', 'strong-testimonials' ); ?>
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
			case 'translation':
				include 'translation.php';
				break;
			case 'shortcodes':
				include 'shortcodes.php';
				break;
			case 'templates':
				include 'templates.php';
				break;
			case 'views':
				include 'views.php';
				break;
			case 'start':
				include 'start.php';
				break;
			case 'version2':
				include 'version2.php';
				break;
			default:
				include 'welcome.php';
		}
		?>
	</div>
	<?php
}

function wpmtst_guide_before_content() {
	include 'plugin-sidebar.php';
}
add_action( 'wpmtst_guide_before_content', 'wpmtst_guide_before_content' );

function wpmtst_guide_after_content() {}
//add_action( 'wpmtst_guide_after_content', 'wpmtst_guide_after_content' );
