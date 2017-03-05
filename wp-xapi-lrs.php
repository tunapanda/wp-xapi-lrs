<?php

/*
Plugin Name: xAPI LRS
Plugin URI: http://github.com/tunapanda/wp-xapi-lrs
GitHub Plugin URI: http://github.com/tunapanda/wp-xapi-lrs
Description: Enables your WordPress site to act as an xAPI Learning Record Store.
Version: 0.0.3
*/

if (!defined("PHP_VERSION_ID") || PHP_VERSION_ID<50500) {
	trigger_error('Your PHP version is too old, you need at least 5.5.0, you have '.phpversion(),E_USER_ERROR);
	return;
}

require_once __DIR__."/ext/minixapi/MiniXapi.php";
require_once __DIR__."/include/WpUtil.php";
require_once __DIR__."/include/Template.php";

use xapilrs\WpUtil;
use xapilrs\Template;

function xapilrs_activate() {
	global $wpdb;

	$miniXapi=new MiniXapi();
	$miniXapi->setPdo(WpUtil::getCompatiblePdo());
	$miniXapi->setTablePrefix($wpdb->prefix);

	if (!$miniXapi->isInstalled())
		$miniXapi->install();

	if (!get_option("xapilrs_username"));
		update_option("xapilrs_username",
			bin2hex(openssl_random_pseudo_bytes(16)));

	if (!get_option("xapilrs_password"));
		update_option("xapilrs_password",
			bin2hex(openssl_random_pseudo_bytes(16)));
}

register_activation_hook(__FILE__,"xapilrs_activate");

function xapilrs_uninstall() {
	global $wpdb;

	$miniXapi=new MiniXapi();
	$miniXapi->setPdo(WpUtil::getCompatiblePdo());
	$miniXapi->setTablePrefix($wpdb->prefix);
	$miniXapi->uninstall();

	delete_option("xapilrs_username");
	delete_option("xapilrs_password");
}

register_uninstall_hook(__FILE__,"xapilrs_uninstall");

function xapilrs_info_page() {
	$params=array();

	$params["url"]=plugins_url("endpoint.php",__FILE__);
	$params["username"]=get_option("xapilrs_username");
	$params["password"]=get_option("xapilrs_password");

	Template::display(__DIR__."/tpl/info.php",$params);
}

function xapilrs_admin_menu() {
	add_options_page(
		'xAPI LRS',
		'xAPI LRS',
		'manage_options',
		'xapilrs_info_page',
		'xapilrs_info_page'
	);	
}

add_action('admin_menu','xapilrs_admin_menu');