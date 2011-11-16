<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$conn = Models_Core::getDoctrineConn();
$query = Doctrine_Query::create()
		->select('count(c.id)')
		->from('TrCity c')
		->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);

$sumRs = $query->fetchOne();
$sum = is_numeric($sumRs['count']) ? intval($sumRs['count']) : -1;

$conn = Models_Core::getDoctrineConn();
$query = Doctrine_Query::create()
		->select('c.id , c.longname')
		->from('TrCity c');
$cityArr = $query->execute();

function getNextCity()
{
	global $sum;
	global $cityArr;
	static $id = -2;
	if ($id < $sum)
	{
		$id++;
		return $cityArr[$id]->longname;
	}
	else 
	{
		return false;
	}
}
?>