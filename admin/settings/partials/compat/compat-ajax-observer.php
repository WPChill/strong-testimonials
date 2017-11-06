<?php
/**
 * Observer
 */
?>
<div class="row">
    <div>
        <label for="method-observer">
            <input type="radio" id="method-observer" name="wpmtst_compat_options[ajax][method]" value="observer"
				<?php checked( $options['ajax']['method'], 'observer' ); ?>
                   data-group="observer"/>
			<?php _e( 'Observer', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <p class="about"><?php _e( 'React to changes in specific page elements.', 'strong-testimonials' ); ?></p>
        <p class="description"><?php _e( 'For advanced users.', 'strong-testimonials' ); ?></p>
    </div>
</div>

<?php
/*
 * Timer
 */
?>
<div class="row" data-sub="observer">
    <div class="radio-sub">
        <label for="observer-timer">
			<?php _ex( 'Check once after', 'timer setting', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <input type="number" id="observer-timer"
               name="wpmtst_compat_options[ajax][observer_timer]"
               min=".1" max="5" step=".1" size="3"
               value="<?php echo $options['ajax']['observer_timer']; ?>" autocomplete="off">
		<?php _ex( 'seconds', 'timer setting', 'strong-testimonials' ); ?>
    </div>
</div>

<?php
/*
 * Container element ID
 */
?>
<div class="row" data-sub="observer">
    <div class="radio-sub">
        <label for="container-id">
			<?php _e( 'Container ID', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <span class="code input-before">#</span>
        <input type="text" id="container-id" class="code element"
               name="wpmtst_compat_options[ajax][container_id]"
               value="<?php echo $options['ajax']['container_id']; ?>"/>
        <p class="about adjacent"><?php _e( 'the element to observe', 'strong-testimonials' ); ?></p>
    </div>
</div>

<?php
/*
 * Added node ID
 */
?>
<div class="row" data-sub="observer">
    <div class="radio-sub">
        <label for="addednode-id">
			<?php _e( 'Added node ID', 'strong-testimonials' ); ?>
        </label>
    </div>
    <div>
        <span class="code input-before">#</span>
        <input type="text" id="addednode-id" class="code element"
               name="wpmtst_compat_options[ajax][addednode_id]"
               value="<?php echo $options['ajax']['addednode_id']; ?>"/>
        <p class="about adjacent"><?php _e( 'the element being added', 'strong-testimonials' ); ?></p>
    </div>
</div>
