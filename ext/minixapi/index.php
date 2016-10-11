<?php

require_once __DIR__."/MiniXapi.php";	

$miniXapi=new MiniXapi();
$miniXapi->setDsn("sqlite:".__DIR__."/data/minixapi.sqlite");
$miniXapi->setBasicAuth("hello:world");

if (!$miniXapi->isInstalled())
	$miniXapi->install();

$miniXapi->serve();