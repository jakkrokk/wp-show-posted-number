<?php
/*
Plugin Name: Show Posted Numbers
Description: Visualze your wordpress post schedule with graphs
Version: 1.0.0
Author: jakkrokk
*/
date_default_timezone_set('Asia/Tokyo');
define('MY_PLUGIN_DIR',WP_PLUGIN_DIR.'/show-post-numbers/');

add_action('admin_menu','spn_create_menu');
function spn_create_menu(){
	add_menu_page(
		'Your Posted Numbers',
		'Posted Numbers',
		'read',
		WP_PLUGIN_DIR.'/show-post-numbers/menu.php',
		$function = '',
		$icon_url = '',
		$position = null);
}
function getClassPath(){
	return MY_PLUGIN_DIR.'spn.class.php';
}
