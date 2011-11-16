<?php
$curDir = dirname(__FILE__);
$commonBootDir = realpath($curDir . '/../PCommonBoot.php');
require_once $commonBootDir;
//先初始化类的autoload方法
PCommonBoot::init();

//先设置数据库连接
$conn = Models_Core::getDoctrineConn();
//生成Models
$resultBool = Doctrine_Core::generateModelsFromDb('Doctrine', array('doctrineConn'), array('generateTableClasses' => true));



