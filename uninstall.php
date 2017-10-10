<?php
/**
 * Strong Testimonials uninstall procedure
 */
 
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// TODO Leave No Trace

Strong_Testimonials_Updater::remove_caps();
