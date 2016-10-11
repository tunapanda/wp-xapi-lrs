<?php

require_once __DIR__."/include/WpUtil.php";
require_once xapilrs\WpUtil::getWpLoadPath();
require_once __DIR__."/ext/minixapi/MiniXapi.php";

use xapilrs\WpUtil;
global $wpdb;

// Remote magic quotes, WordPress always adds these. Refernce:
// http://stackoverflow.com/questions/8949768/with-magic-quotes-disabled-why-does-php-wordpress-continue-to-auto-escape-my
// https://core.trac.wordpress.org/ticket/18322
foreach ($_REQUEST as $k=>$v)
	$_REQUEST[$k]=stripslashes($v);

$miniXapi=new MiniXapi();
$miniXapi->setPdo(WpUtil::getCompatiblePdo());
$miniXapi->setTablePrefix($wpdb->prefix);
$miniXapi->setBasicAuth(
	get_option("xapilrs_username").":".
	get_option("xapilrs_password")
);

$miniXapi->serve();