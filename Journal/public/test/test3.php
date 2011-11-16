<?php
//date_default_timezone_set('Asia/Shanghai');
//$curPath = dirname(__FILE__);
//$bootDir = realpath($curPath . '/../../application/PCommonBoot.php');
//require_once $bootDir;
//PCommonBoot::init(); //初始化设置

$fp = fopen('D:\aaa', 'a+');

if (flock($fp, LOCK_SH)) {
	fwrite($fp, "begin\n");
//	sleep(10);
	fwrite($fp, "end\n");
	flock($fp, LOCK_UN);
} else {
	echo "Could not get the lock!";
}

fclose($fp);

////省
//$query = Doctrine_Query::create()
//		->from('TProvince p');
//
//$result = $query->execute();
//
//$conn->beginTransaction();
//foreach ($result as $pro)
//{
//	$name = $pro->ProName;
//	
//	$proDb = new TrProvince();
//	$proDb->longname = $name;
//	$proDb->shortname = $name;
//	$proDb->ctr_id_ref = 1;
//	
//	$proDb->save();
//}
//$conn->commit();


////市
//$query = Doctrine_Query::create()
//		->from('TCity c')
//		;
//$result = $query->execute();
//$conn->beginTransaction();
//
//foreach ($result as $city)
//{
//	$cName = $city->CityName;
//	$proSort = $city->ProID;
//	$query = Doctrine_Query::create()
//			->from('TProvince p')
//			->where('p.id = ?' , $proSort);
//	$pro = $query->fetchOne();
//	$proName = $pro->ProName;
//	
//	$query = Doctrine_Query::create()
//			->select('p.id')	
//			->from('TrProvince p')
//			->where('p.longname = ?' , $proName);
//	$province = $query->fetchOne();
//	
//	$priId = $province->id;
//	
//	$cityDb = new TrCity();
//	$cityDb->longname = $cName;
//	$cityDb->shortname = $cName;
//	$cityDb->pvc_id_ref = $priId;
//	
//	$cityDb->save();
//}
//
//$conn->commit();


////区
//$cot = 0;
//$size = 1000;
//while (true)
//{
//	$query = Doctrine_Query::create()
//			->from('TDistrict d')
//			->offset($cot)
//			->limit($size)
//			;
//echo $query->getDql()."\n";
//	$cot += $size;
//	$disArr = $query->execute();
//	if (count($disArr) == 0 || false == $disArr)
//	{
//		break;
//	}
//	$conn->beginTransaction();
//	foreach ($disArr as $dis)
//	{
//		$dName = $dis->DisName;
//		$citySort = $dis->CityID;
//		
//		$query = Doctrine_Query::create()
//				->from('TCity c')
//				->where('c.CitySort = ?' , $citySort)
//				;
//		$city = $query->fetchOne();
//		$cName = $city->CityName;
//		
//		$query = Doctrine_Query::create()
//				->from('TrCity c')
//				->where('c.longname = ?' , $cName)
//				;
//		$cty = $query->fetchOne();
//		$ctyId = $cty->id;
//		
//		$sub = new TrSublocality();
//		$sub->longname = $dName;
//		$sub->shortname = $dName;
//		$sub->cty_id_ref = $ctyId;
//		
//		$sub->save();
//	}
//	$conn->commit();		
//}