<?php
//date_default_timezone_set('Asia/Shanghai');
//$curPath = dirname(__FILE__);
//$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
//require_once $bootDir;
//PCommonBoot::init(); //初始化设置
//
//header('Content-type:image/jpeg');
//
//$conn = Models_Core::getDoctrineConn();
//$query = Doctrine_Query::create()
//		->delete();

$fp = fopen('D:\aaa', 'a+');

$count = 0;
while (true) {
	if(flock($fp, LOCK_EX)) {
		fwrite($fp, "I get you\n");
		sleep(10);
		flock($fp, LOCK_UN);
		break;
	}
	else {
		echo "$count\n";
		$count++;
		sleep(1);
	}
}

fclose($fp);