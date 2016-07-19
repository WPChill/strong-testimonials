<?php
/**
 * View Controls class.
 *
 * @since 2.11.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'Strong_View_Controls' ) ) :

class Strong_View_Controls {

	public static function cycle_controls() {
		$plugin_version = get_option( 'wpmtst_plugin_version' );
		$controls_url = plugin_dir_url( __FILE__ ) . 'controls/slideshow/';

		switch ( WPMST()->atts( 'slideshow_nav' ) ) {

			case 'simple':
				?>
				<div class="strong-cycle-controls simple">
					<div class="cycle-pager"></div>
				</div>
				<?php
				wp_enqueue_style( 'wpmtst-controls-simple', $controls_url . 'simple/controls.css', array( 'wpmtst-font-awesome' ), get_option( 'wpmtst_plugin_version' ) );
				break;

			case 'buttons1':
				?>
				<div class="strong-cycle-controls buttons1 left">
					<span class="cycle-prev"></span>
				</div>
				<div class="strong-cycle-controls buttons1 right">
					<span class="cycle-next"></span>
				</div>
				<?php
				wp_enqueue_style( 'wpmtst-controls-buttons1', $controls_url . 'buttons1/controls.css', array( 'wpmtst-font-awesome' ), $plugin_version );
				break;

			case 'buttons2':
				?>
				<div class="strong-cycle-controls buttons2">
					<span class="cycle-prev"></span><span class="cycle-next"></span>
				</div>
				<?php
				wp_enqueue_style( 'wpmtst-controls-buttons2', $controls_url . 'buttons2/controls.css', array( 'wpmtst-font-awesome' ), $plugin_version );
				break;

			case 'indexed':
				?>
				<div class="strong-cycle-controls indexed">
					<div class="cycle-pager"></div>
				</div>
				<?php
				wp_enqueue_style( 'wpmtst-controls-indexed', $controls_url . 'indexed/controls.css', array(), $plugin_version );
				break;

			default:
		}
	}
}

endif;