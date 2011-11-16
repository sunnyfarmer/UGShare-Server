<?php
date_default_timezone_set('Asia/Shanghai');
$curPath = dirname(__FILE__);
$bootDir = realpath($curPath . '/../../../application/PCommonBoot.php');
require_once $bootDir;
PCommonBoot::init(); //初始化设置

$conn = Models_Core::getDoctrineConn();

//先获取城市列表（注意offset 和  limit）
$query = Doctrine_Query::create()
		->select('c.id')
		->from('TrCity c')
		->offset(300)
		->limit(100)
		;
		
$rs = $query->execute();
$cityIdArr = array();
foreach ($rs as $city)
{
	array_push($cityIdArr, $city->id);
}

$deleteIdArr = array();
//逐个城市，找出重复的地里信息
foreach ($cityIdArr as $cId)
{
	echo "begin>>>$cId\n";
	$query = Doctrine_Query::create()
			->select('p.name , count(p.id)')
			->from('TrPlace p')
			->where('p.cty_id_ref = ?' , $cId)
			->groupBy('p.name');
			
	$rs = $query->execute();

	foreach ($rs as $place)
	{
		$pName = $place->name;
		$pCount = $place->count;
		
		if ($pCount > 1)
		{
			echo "$pName\n";
			$placeSameNameArr = array();

			//记录要删除的id
			$queryInner = Doctrine_Query::create()
						->select('p.id, p.latitude, p.longitude')
						->from('TrPlace p')
						->where('p.name = ?' , $pName);

			$rsInner = $queryInner->execute();
			foreach ($rsInner as $placeSameName)
			{
//				echo "name:$pName  id:".$placeSameName->id."  lat:".$placeSameName->latitude."  lng:".$placeSameName->longitude."\n";
				if (!hasPlaceSameLocation($placeSameNameArr, $placeSameName))
				{//没有重复的position，那么放到重名position数组
					array_push(
						$placeSameNameArr, 
						array(
							'ID'=>$placeSameName->id,
							'LATITUDE'=>$placeSameName->latitude,
							'LONGITUDE'=>$placeSameName->longitude
						)
					);
				}
				else
				{//重复，则将id放入需要删除的id中去
echo "*******************************************************************************************\n";
					array_push($deleteIdArr, $placeSameName->id);
				}
			}			
						
		}
	}
		
	echo "end>>>$cId\n";
}

print_r($deleteIdArr);

$deleteIdStr = '';
foreach($deleteIdArr as $id)
{
	$deleteIdStr .= "$id,";
}
$deleteIdStr = rtrim($deleteIdStr , ',');
if($deleteIdStr)
{
	$query = Doctrine_Query::create()
		->delete('TrPlace p')
		->where("p.id in ($deleteIdStr)")
	;
	$query->execute();
}

function hasPlaceSameLocation($placeArr , $place)
{
	foreach ($placeArr as $placeInArr)
	{
		if (
			$placeInArr['LATITUDE'] == $place->latitude &&
			$placeInArr['LONGITUDE'] == $place->longitude
		)
		{
			return true;
		}
	}
	
	return false;
}




