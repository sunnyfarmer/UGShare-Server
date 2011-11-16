<?php
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath.'/../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init();				//初始化设置

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$application->bootstrap()
            ->run();
            