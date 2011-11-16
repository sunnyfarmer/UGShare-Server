<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //initial setting
//open service
$server = Models_Api_PXmlrpc::getSingleton();//get the object of the server
$server->work(); //service begin to work

