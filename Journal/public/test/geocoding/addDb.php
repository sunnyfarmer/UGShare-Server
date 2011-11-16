<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$province = $_POST['province'];//'广东省';
$city = $_POST['city'];//'北京市';
$lat = $_POST['lat'];//'12323';
$lng = $_POST['lng'];//'213';
$placeName = $_POST['placeName'];//'不夜城';
$address = $_POST['address'];//'你家';

//$f = fopen('db.txt', 'at');
//if ($f)
//{
//	fwrite($f, "\nprovince:$province,city:$city,lat:$lat,lng:$lng,placeName:$placeName,address:$address\n");
//	fclose($f);
//
//	echo "1";
//}
//else 
//{
//	echo "0";
//}

$conn = Models_Core::getDoctrineConn();

$query = Doctrine_Query::create()
		->select('c.id')
		->from('TrCity c')
		->where('c.longname like ? or c.shortname like ? ' , array("%$city%" , "%$city%"));
$city = $query->fetchOne();

$cId = $city->id;

try {
	$conn->beginTransaction();
	
	$placeDb = new TrPlace();
	$placeDb->name = $placeName;
	$placeDb->cty_id_ref = $cId;
	$placeDb->latitude = $lat;
	$placeDb->longitude = $lng;
	$placeDb->address = $address;

	$placeDb->save();
	
	$conn->commit();
	
	echo "1";
}
catch (Exception $e)
{
	echo "0";
	
	$conn->rollback();
	
	$f = fopen("log.txt", "at");
	fwrite($f, $e->getMessage()."\n".date('r')."\n");
	fclose($f);
}





