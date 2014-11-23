<?php
/*
 * The Guide Pages.
 *
 * @since 1.9.1
 * @package Strong_Testimonials
 */ 
function wpmtst_guide() {
	?>
	<div class="wrap wpmtst guide">
		<h2><?php _e( 'Strong Testimonials Guide', 'strong-testimonials' ); ?></h2>
		<?php 
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'getting-started';
		$page = '?post_type=wpm-testimonial&page=guide';
		?>
		<h2 class="nav-tab-wrapper">
			<?php /* translators: This appears in the Guide. */ ?>
			<a href="<?php echo $page; ?>" class="nav-tab <?php echo $tab == 'getting-started' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Getting Started', 'strong-testimonials' ); ?>
			</a>
			<?php /* translators: This appears in the Guide. */ ?>
			<a href="<?php echo $page; ?>&tab=simple" class="nav-tab <?php echo $tab == 'simple' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Simple', 'strong-testimonials' ); ?>
			</a>
			<?php /* translators: This appears in the Guide. */ ?>
			<a href="<?php echo $page; ?>&tab=advanced" class="nav-tab <?php echo $tab == 'advanced' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Advanced', 'strong-testimonials' ); ?>
			</a>
			<?php /* translators: This appears in the Guide. */ ?>
			<a href="<?php echo $page; ?>&tab=notes" class="nav-tab <?php echo $tab == 'notes' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Notes', 'strong-testimonials' ); ?>
			</a>
			<?php /* translators: This appears in the Guide. */ ?>
			<a href="<?php echo $page; ?>&tab=future" class="nav-tab <?php echo $tab == 'future' ? 'nav-tab-active' : ''; ?>">
				<?php _e( 'Future', 'strong-testimonials' ); ?>
			</a>
		</h2>
		<?php
		if( 'future' == $tab )
			include( 'guide-future.php' );
		elseif( 'notes' == $tab )
			include( 'guide-notes.php' );
		elseif( 'advanced' == $tab )
			include( 'guide-advanced.php' );
		elseif( 'simple' == $tab )
			include( 'guide-simple.php' );
		else // first tab
			include( 'guide-start.php' );
		?>
	</div>
	<?php
}
