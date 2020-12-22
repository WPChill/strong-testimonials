<?php
$then_classes = array(
	'then',
	'then_display',
	'then_not_form',
	'then_slideshow',
	'then_single_template',
	apply_filters( 'wpmtst_view_section', '', 'fields' ),
);
?>
<div class="<?php echo esc_attr( implode( ' ', array_filter( $then_classes ) ) ); ?>" style="display: none;">
	<h3>
		<?php /* translators: On the Views admin screen. */ ?>
		<?php _e( 'Fields', 'strong-testimonials' ); ?>
	</h3>
	<table class="form-table multiple group-show">

		<?php
		$then_classes = array(
			'then',
			'then_display',
			'then_not_form',
			'then_slideshow',
			'then_not_single_template',
			apply_filters( 'wpmtst_view_section', '', 'title' ),
		);
		?>
		<tr class="<?php echo esc_attr( implode( ' ', array_filter( $then_classes ) ) ); ?>" style="display: none;">
			<?php include( 'option-title.php' ); ?>
		</tr>

		<?php
		$then_classes = array(
			'then',
			'then_display',
			'then_not_form',
			'then_slideshow',
			'then_not_single_template',
			apply_filters( 'wpmtst_view_section', '', 'thumbnail' ),
		);
		?>
		<tr class="<?php echo esc_attr( implode( ' ' ,array_filter( $then_classes ) ) ); ?>" style="display: none;">
			<?php include( 'option-thumbnail.php' ); ?>
		</tr>

		<?php
		$then_classes = array(
			'then',
			'then_display',
			'then_not_form',
			'then_slideshow',
			'then_not_single_template',
			apply_filters( 'wpmtst_view_section', '', 'content' ),
		);
		?>
		<tr class="<?php echo esc_attr( implode( ' ', array_filter( $then_classes )  ) ); ?>" style="display: none;">
			<?php include( 'option-content.php' ); ?>
		</tr>

		<?php
		$then_classes = array(
			'then',
			'then_display',
			'then_not_form',
			'then_slideshow',
			'then_single_template',
			apply_filters( 'wpmtst_view_section', '', 'client-section' ),
		);
		?>
		<tr class="<?php echo esc_attr( implode( ' ', array_filter( $then_classes )  ) ); ?>" style="display: none;">
			<?php include( 'option-client-section.php' ); ?>
		</tr>

	</table>
</div>
