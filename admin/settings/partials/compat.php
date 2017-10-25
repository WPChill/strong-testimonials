<?php
/**
 * Compatibility settings
 *
 * @since 2.28.0
 */

$options = get_option('wpmtst_compat_options');
?>
<h2><?php _e('Prerender'); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <th scope="row">
			<?php _e('Prerender', 'strong-testimonials'); ?>
    </th>
    <td>
      <fieldset>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[prerender]" value="current"
								<?php checked($options['prerender'], 'current'); ?> />
						<?php _e('Current page', 'strong-testimonials'); ?> <?php _e('(default)', 'strong-testimonials'); ?>
          </label>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[prerender]" value="all"
								<?php checked($options['prerender'], 'all'); ?> />
						<?php _e('All views', 'strong-testimonials'); ?>
          </label>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[prerender]" value="none"
								<?php checked($options['prerender'], 'none'); ?> />
						<?php _e('None', 'strong-testimonials'); ?>
          </label>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
      </fieldset>
    </td>
  </tr>
</table>

<hr/>
<h2><?php _e('Themes'); ?></h2>

<table class="form-table" cellpadding="0" cellspacing="0">
  <tr valign="top">
    <th scope="row">
			<?php _e('Ajax Page Loading', 'strong-testimonials'); ?>
    </th>
    <td>
      <div class="tab-option-header">
        <p><?php _e('about page loading', 'strong-testimonials'); ?></p>
        <p><?php printf(__('<a href="%s" target="_blank">article</a>', 'strong-testimonials'), esc_url('')); ?></p>
      </div>
			<?php /* (blank) | universal | nodes_added | event | script */ ?>
      <fieldset>
				<?php
				/*
				 * ------------------------------
				 * None
				 * ------------------------------
				 */
				?>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[ajax][method]" value=""
								<?php checked($options['ajax']['method'], ''); ?> />
						<?php _e('None', 'strong-testimonials'); ?> <?php _e('(default)', 'strong-testimonials'); ?>
          </label>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
				<?php
				/*
				 * ------------------------------
				 * Universal (timer)
				 * ------------------------------
				 */
				?>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[ajax][method]" value="universal"
								<?php checked($options['ajax']['method'], 'universal'); ?> />
						<?php _e('Universal', 'strong-testimonials'); ?>
          </label>
          <div class="inline inline-middle">
            <label for="universal-timer">
							<?php _ex('Check every', 'timer setting', 'strong-testimonials'); ?>
            </label>
            <input type="number" id="universal-timer" class="input-incremental"
                   name="wpmtst_compat_options[ajax][universal_timer]"
                   min=".1" max="5" step=".1"
                   value="<?php echo $options['ajax']['universal_timer']; ?>" size="3">
						<?php _ex('seconds', 'timer setting', 'strong-testimonials'); ?>
          </div>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
				<?php
				/*
				 * ------------------------------
				 * Observer
				 * ------------------------------
				 */
				?>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[ajax][method]" value="nodes_added"
								<?php checked($options['ajax']['method'], 'nodes_added'); ?> />
						<?php _e('Observer', 'strong-testimonials'); ?>
          </label>
          <div class="inline inline-middle">
            <label for="observer-timer">
							<?php _ex('Check once after', 'timer setting', 'strong-testimonials'); ?>
            </label>
            <input type="number" id="observer-timer" class="input-incremental"
                   name="wpmtst_compat_options[ajax][observer_timer]"
                   min=".1" max="5" step=".1"
                   value="<?php echo $options['ajax']['observer_timer']; ?>" size="3">
						<?php _ex('seconds', 'timer setting', 'strong-testimonials'); ?>
          </div>
          <p class="description"><?php _e('about DOM changes', 'strong-testimonials'); ?></p>
        </div>
        <?php
        /*
        * ------------------------------
        * Container element ID
        * ------------------------------
        */
        ?>
        <div>
          <div>
            <label for="container-id">
              <?php _e( 'Container element ID', 'strong-testimonials' ); ?>
            </label>
            <input type="text" id="container-id" class="code"
                   name="wpmtst_compat_options[ajax][container_id]"
                   value="<?php echo $options['ajax']['container_id']; ?>" size="30"/>
          </div>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
        <?php
        /*
        * ------------------------------
        * Added node ID
        * ------------------------------
        */
        ?>
        <div>
          <div>
            <label for="addednode-id">
              <?php _e( 'Added node ID', 'strong-testimonials' ); ?>
            </label>
            <input type="text" id="addednode-id" class="code"
                   name="wpmtst_compat_options[ajax][addednode_id]"
                   value="<?php echo $options['ajax']['addednode_id']; ?>" size="30"/>
          </div>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>

        <?php
				/*
				 * ------------------------------
				 * Custom event
				 * ------------------------------
				 */
				?>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[ajax][method]" value="event"
								<?php checked($options['ajax']['method'], 'event'); ?> />
						<?php _e('Custom event', 'strong-testimonials'); ?>
          </label>

          <div class="inline inline-middle">
            <label for="event">
              <input type="text" id="event" class="code"
                     name="wpmtst_compat_options[ajax][event]"
                     value="<?php echo $options['ajax']['event']; ?>" size="30"/>
            </label>
          </div>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
				<?php
				/*
				 * ------------------------------
				 * Specific script
				 * ------------------------------
				 */
				?>
        <div class="has-radio">
          <label>
            <input type="radio" name="wpmtst_compat_options[ajax][method]" value="script"
								<?php checked($options['ajax']['method'], 'script'); ?> />
						<?php _e('Specific script', 'strong-testimonials'); ?>
          </label>
          <div class="inline inline-middle">
            <label>
              <select id="ajax-script" name="wpmtst_compat_options[ajax][script]">
                <option value="" <?php selected( $options['ajax']['script'], '' ); ?>>
				          <?php _e( '&mdash; Select &mdash;' ); ?>
                </option>
                <option value="barba" <?php selected( $options['ajax']['script'], 'barba' ); ?>>
		              <?php _e( 'Barba.js' ); ?>
                </option>
              </select>
            </label>
          </div>
          <p class="description"><?php _e('about this option', 'strong-testimonials'); ?></p>
        </div>
      </fieldset>
    </td>
  </tr>
</table>
