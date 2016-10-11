<?php

require_once __DIR__."/include/WpUtil.php";
require_once xapilrs\WpUtil::getWpLoadPath();
require_once __DIR__."/ext/minixapi/MiniXapi.php";

use xapilrs\WpUtil;
global $wpdb;

$miniXapi=new MiniXapi();
$miniXapi->setPdo(WpUtil::getCompatiblePdo());
$miniXapi->setTablePrefix($wpdb->prefix);
$miniXapi->setBasicAuth(
	get_option("xapilrs_username").":".
	get_option("xapilrs_password")
);

$miniXapi->serve();