<?php
$then_classes = array(
	'then',
	'then_display',
	'then_form',
	'then_slideshow',
	'then_not_single_template',
	apply_filters( 'wpmtst_view_section', '', 'style' ),
);
?>
<div class="<?php echo esc_attr( join( array_filter( $then_classes ), ' ' ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php _e( 'Style', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-style">
        <?php do_action( 'wpmtst_view_editor_before_template_list' ); ?>
		<tr class="then then_display then_not_form then_slideshow" style="display: none;">
			<?php
            $current_mode = 'template';
            $current_type = 'display';
            include( 'option-template-list.php' );
            ?>
		</tr>
		<tr class="then then_not_display then_form then_not_slideshow" style="display: none;">
			<?php
			$current_mode = 'form-template';
			$current_type = 'form';
            include( 'option-template-list.php' );
            ?>
		</tr>
		<?php do_action( 'wpmtst_view_editor_before_layout' ); ?>
		<tr class="then then_display then_not_form then_not_slideshow" style="display: none;">
			<?php include( 'option-layout.php' ); ?>
		</tr>
		<?php do_action( 'wpmtst_view_editor_before_background' ); ?>

        <tr id="group-style-option-background" class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-background.php' ); ?>
		</tr>
        <tr id="group-style-option-color" class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-color.php' ); ?>
		</tr>

		<?php do_action( 'wpmtst_view_editor_before_classes' ); ?>
		<tr class="then then_display then_form then_slideshow" style="display: none;">
			<?php include( 'option-classes.php' ); ?>
		</tr>
		<?php do_action( 'wpmtst_view_editor_after_style_section' ); ?>
	</table>
</div>
