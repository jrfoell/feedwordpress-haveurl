<?php
/**
 * Plugin Name: FeedWordPress Have URL
 * Description: Modify href if article linked to already exists on syndicated site
 * Version: 1.0
 * Author: Justin Foell
 * Author URI: https://github.com/jrfoell/feedwordpress-haveurl
 * License: GPL
 */ 

function fwp_haveurl() {
	require_once 'class.fwp-haveurl.php';
	$fwp_custom = FWP_HaveURL::get_instance();
	$fwp_custom->hook();
}
add_action( 'plugins_loaded', 'fwp_haveurl' );

