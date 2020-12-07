<?php
$custom_fields     = wpmtst_get_custom_fields();
$options = get_option( 'wpmtst_options' );
$url_fields = array();
foreach($custom_fields as $field){
    if('url' == $field['input_type']){
        $url_fields[] = $field;
    }
}

// For older versions where title_link was checkbox
if ( '1' == $view['title_link'] ) {
    $view['title_link'] = 'wpmtst_testimonial';
}

if ( '0' == $view['title_link'] ) {
    $view['title_link'] = 'none';
}
?>

<th>
    <input type="checkbox" id="view-title" name="view[data][title]" value="1" <?php checked( $view['title'] ); ?>
           class="checkbox if toggle">
    <label for="view-title">
		<?php _e( 'Title', 'strong-testimonials' ); ?>
    </label>
</th>
<td colspan="2">
    <div class="row">
        <div class="row-inner">
            <div class="then then_title" style="display: none;">
                <label for="view-title_link">
                    <?php printf( _x( 'Link to %s', 'The name of this post type. "Testimonial" by default.', 'strong-testimonials' ), strtolower( apply_filters( 'wpmtst_cpt_singular_name', __( 'Testimonial', 'strong-testimonials' ) ) ) ); ?>
                </label>
                <div class="wpmtst-tooltip"><span>[?]</span>
                    <div class="wpmtst-tooltip-content"><?php echo esc_html__('"Full testimonial" option doesn\'s work if "Disable permalinks for testimonials" from "Settings" page is enabled.','strong-testimonials'); ?></div>
                </div>

                <select name="view[data][title_link]">
                    <option value="none" <?php selected( 'none', $view['title_link'], true ); ?>><?php echo esc_html__( 'None', 'strong-testimonials' ); ?></option>
                    <?php if ( !isset( $options['disable_rewrite'] ) || '1' != $options['disable_rewrite'] ) { ?>
                        <option value="wpmtst_testimonial" <?php selected( 'wpmtst_testimonial', $view['title_link'], true ); ?>><?php echo esc_html__( 'Full testimonial', 'strong-testimonials' ); ?></option>
                    <?php } ?>

                    <?php foreach ( $url_fields as $url ) { ?>
                        <option value="<?php echo $url['name']; ?>" <?php selected( $url['name'], $view['title_link'] ); ?>><?php echo $url['label']; ?></option>
                    <?php } ?>
                </select>
                
                <?php do_action('wpmtst_view_editor_after_group_fields_title') ?>
            </div>
        </div>
    </div>
</td>
