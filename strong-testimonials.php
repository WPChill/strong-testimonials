<?php
/**
 * Plugin Name: Strong Testimonials
 * Plugin URI: https://strongtestimonials.com
 * Description: Collect and display your testimonials or reviews.
 * Author: WPChill
 * Author URI: https://wpchill.com/
 * Version: 3.2.3
 * Text Domain: strong-testimonials
 * Domain Path: /languages
 * Requires: 4.6 or higher
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Requires PHP: 5.6
 *
 * Copyright 2014-2019 Chris Dillon chris@strongwp.com
 * Copyright 2019-2020 MachoThemes office@machothemes.com
 * Copyright 2020       WPChill     heyyy@wpchill.com
 *
 * Original Plugin URI:         https://strongplugins.com/plugins/strong-testimonials
 * Original Author URI:         https://strongplugins.com
 * Original Author:             https://profiles.wordpress.org/cdillon27/
 *
 * NOTE:
 * Chris Dillon ownership rights were ceased on: 01/20/2019 06:52:23 PM when ownership was turned over to MachoThemes
 * MachoThemes ownership started on: 01/20/2019 06:52:24 PM
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'WPMTST_VERSION', '3.2.3' );

define( 'WPMTST_PLUGIN', plugin_basename( __FILE__ ) ); // strong-testimonials/strong-testimonials.php
define( 'WPMTST', dirname( WPMTST_PLUGIN ) );           // strong-testimonials
define( 'WPMTST_LOGS', wp_upload_dir()['basedir'] . '/st-logs/' );
defined( 'WPMTST_STORE_URL' ) || define( 'WPMTST_STORE_URL', 'https://strongtestimonials.com/' );
defined( 'WPMTST_ALT_STORE_URL' ) || define( 'WPMTST_ALT_STORE_URL', 'https://license.wpchill.com/strongtestimonials/' );
defined( 'WPMTST_STORE_UPGRADE_URL' ) || define( 'WPMTST_STORE_UPGRADE_URL', 'https://strongtestimonials.com/pricing' );

if ( ! class_exists( 'Strong_Testimonials' ) ) {
	require_once plugin_dir_path( __FILE__ ) . 'class-strong-testimonials.php';
}

register_activation_hook( __FILE__, array( 'Strong_Testimonials', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Strong_Testimonials', 'plugin_deactivation' ) );

add_action( 'wp_initialize_site', array( 'Strong_Testimonials', 'mu_new_blog' ), 10 );

// phpcs:disable WordPress.NamingConventions.ValidFunctionName
function WPMST() {
	return Strong_Testimonials::instance();
}
// phpcs:enable WordPress.NamingConventions.ValidFunctionName

// Get plugin running.
WPMST();
