<?php
/**
 * Strong Testimonials uninstall procedure
 */
 
if( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) 
	exit();

delete_option( 'wpmtst_options' );
delete_option( 'wpmtst_fields' );
