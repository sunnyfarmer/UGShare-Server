<?php
class Models_CommonAction_Geo_PGeographicManager
{
	const ONCE_SEARCH_COUNT = 10;
	
	const GEOGRAPHIC_TYPE_BAIDU = 1;
	const GEOGRAPHIC_TYPE_GOOGLE = 2;
	const GEOGRAPHIC_TYPE_JIEPAN = 3;
	
	public static $GeographicNameArr = array(
		self::GEOGRAPHIC_TYPE_BAIDU 	=> 'PGeoBaidu', 
		self::GEOGRAPHIC_TYPE_GOOGLE	=> 'PGeoGoogle',
		self::GEOGRAPHIC_TYPE_JIEPAN	=> 'PGeoJiepan'
	);
	
	public static $GeoAvailableArr = array(
		self::GEOGRAPHIC_TYPE_BAIDU		=> false, 
		self::GEOGRAPHIC_TYPE_GOOGLE	=> false, 
		self::GEOGRAPHIC_TYPE_JIEPAN	=> true,
	);
	
	const CLASSNAME_PREDIX = 'Models_CommonAction_Geo_';
	
	/**
	 * 
	 * 获得PGeographic的实例
	 * @param int $type
	 * @return Models_CommonAction_Geo_PGeographic
	 */
	public static function getGeoObj($type)
	{
		$geoObj = null;
		if (array_key_exists($type, self::$GeographicNameArr))
		{
			$className = self::CLASSNAME_PREDIX.self::$GeographicNameArr[$type];
			
			$geoObj = new $className();
		}
		
		return $geoObj;
	}
	
	/**
	 * 
	 * 合并结果集
	 * @param array $rsArr	array(Models_CommonAction_Geo_PGeoResult , Models_CommonAction_Geo_PGeoResult ,....)
	 * @return array(Models_CommonAction_Geo_PGeoResult , Models_CommonAction_Geo_PGeoResult , ...)
	 */
	public static function mergeResult($rsArr)
	{		
		/**
		 * 
		 * @var Models_CommonAction_Geo_PGeoResult
		 */
		$result = null;
		$size = count($rsArr);
		
		if (isset($rsArr[0]) && is_object($rsArr[0]))
		{
			$result = $rsArr[0];
			for ($cot = 1 ; $cot < $size ; $cot++)
			{
				$result->merge($rsArr[$cot]);
			}			
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 搜索地址（综合利用多个地理信息数据库）
	 * @param float $latitude
	 * @param float $longitude
	 * @param int $page
	 * @param int $rowCount
	 * @param int $geoLib		地理信息库参数为null时，将所有库都搜索一遍，并返回总得结果集
	 */	
	public static  function getCurAddress($latitude , $longitude , $page , $rowCount , $geoLib = null)
	{
		$result = null;
		if ($geoLib === null)
		{
			$rsArr = array();
			foreach (self::$GeoAvailableArr as $key=>$boolValue)
			{
				if ($boolValue)
				{
					$obj = self::getGeoObj($key);
					$obj->getCurAddressEx($latitude, $longitude, $page, $rowCount);
					array_push($rsArr, $obj->getResult());
				}
			}
			$result = self::mergeResult($rsArr);
		}		
		else
		{
			if (self::$GeoAvailableArr[$geoLib])
			{
				$obj = self::getGeoObj($geoLib);
				$obj->getCurAddressEx($latitude, $longitude, $page, $rowCount);
				$result = $obj->getResult();
			}
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 搜索级别区域内（国家、省、市）的地方（综合利用多个地理信息数据库）
	 * @param string $keyword
	 * @param string $area
	 * @param int $page
	 * @param int $rowCount
	 * @param int $geoLib	地理信息库参数为null时，将所有库都搜索一遍，并返回总得结果集
	 */
	public static function searchInArea($keyword , $area , $page , $rowCount , $geoLib = null)
	{
		$result =null;
		if ($geoLib === null)
		{
			$rsArr = array();
			foreach (self::$GeoAvailableArr as $key=>$boolValue)
			{
				if ($boolValue)
				{
					$obj = self::getGeoObj($key);
					$obj->searchInAreaEx($keyword, $area, $page, $rowCount);
					array_push($rsArr, $obj->getResult());
				}
			}
			$result = self::mergeResult($rsArr);
		}
		else
		{
			if (self::$GeoAvailableArr[$geoLib])
			{
				$obj = self::getGeoObj($geoLib);
				$obj->searchInAreaEx($keyword, $area, $page, $rowCount);
				$result = $obj->getResult();
			}
		}
		return $result;	
	}
	
	/**
	 * 
	 * 搜索周边的地方（综合利用多个地理信息数据库）
	 * @param string $keyword
	 * @param float $latitude
	 * @param float $longitude
	 * @param float $radius
	 * @param int $page
	 * @param int $rowCount
	 * @param int $geoLib	地理信息库参数为null时，将所有库都搜索一遍，并返回总得结果集
	 */	
	public static function searchNearBy($keyword , $latitude , $longitude , $radius , $page , $rowCount , $geoLib = null)
	{
		$result =null;

		if ($geoLib === null)
		{
			$rsArr = array();
			foreach (self::$GeoAvailableArr as $key=>$boolValue)
			{
				if ($boolValue)
				{
					$obj = self::getGeoObj($key);
					$obj->searchNearByEx($keyword, $latitude, $longitude, $radius, $page, $rowCount);
					array_push($rsArr, $obj->getResult());
				}
			}
			$result = self::mergeResult($rsArr);
		}
		else 
		{
			if (self::$GeoAvailableArr[$geoLib])
			{
				$obj = self::getGeoObj($geoLib);
				$obj->searchNearByEx($keyword, $latitude, $longitude, $radius, $page, $rowCount);
				$result = $obj->getResult();
			}		
		}
		return $result;	
	}
	
	/**
	 * 
	 * 开启搜索进程
	 * @param string $method
	 * @param array $paraArr
	 */
	public static function beginProcess($method , $paraArr)
	{		
		//判断该查询是否已经执行过，是则直接跳出方法；否则进入下一步
		{
			//TODO
		}

		if(':' === PATH_SEPARATOR)
		{
			//添加新进程，然后进入循环，把搜出来的结果存储到数据库中
			$pid = pcntl_fork();
			if ($pid)
			{
				
			}
			else 
			{
				/****************************
				****以下这段代码放到搜索结果进程里面去			
				*************************/
				$hasMoreArray = self::$GeoAvailableArr;
		
				$falseCount = 0;
				$page = 1;
				$count = self::ONCE_SEARCH_COUNT;
				array_push($paraArr, $page);
				array_push($paraArr, $count);
				array_push($paraArr, null);
		
				$paraSize = count($paraArr);
		
				$obj = new Models_CommonAction_Geo_PGeographicManager();
	
				while (true)
				{

					foreach (self::$GeoAvailableArr as $key=>$boolValue)
					{
						if (!$hasMoreArray[$key])
						{
							continue;
						}
						else
						{
							$paraArr[$paraSize-1] = $key;
							$rs = call_user_method_array($method, $obj, $paraArr);
							if ($rs === null || !$rs->hasMore)
							{//结果集为空，或者，结果集中的hasMore为false时，赋值该地理信息库的hasMore为false；那么下次就不再执行该地理信息库的查询
								$hasMoreArray[$key] = false;
							}
							if ($rs)
							{//存储到数据库中去
								//TODO
								$conn = Models_Core::getDoctrineConn();
								try 
								{
									$conn->beginTransaction();
									$placeArr = $rs->getPlaceArr();
									foreach ($placeArr as $place)
									{
										$cityName = $place->areaLevel2_long;
										$placeName = $place->placeName_long;
										$address = $place->formattedAddress;
										$longitude = $place->longitude;
										$latitude = $place->latitude;
								
										$query = Doctrine_Query::create()
												->select('c.id')
												->from('TrCity c')
												->where("c.longname like '%$cityName%' or c.shortname like '%$cityName%'");
										$city = $query->fetchOne();
										//TODO 需要做城市不存在的处理
										$cityId = $city->id;
								
										$placeDb = new TrPlace();
										$placeDb->cty_id_ref = $cityId;
										$placeDb->name = $placeName;
										$placeDb->address = $address;
										$placeDb->longitude = $longitude;
										$placeDb->latitude = $latitude;

										$placeDb->save();
									}
									$conn->commit();
								}
								catch (Exception $e)
								{
									// do nothing now
									$conn->rollback();
								}
							}
						}
					}
					//设置搜索的页面和页面返回记录数
					$page++;
					$paraArr[$paraSize-3] = $page;
					$paraArr[$paraSize-2] = $count;
			
					//对所有搜索判断符做或运算，如果还存在true，则继续；否则，break终止循环
					$boolValue = false;
					foreach ($hasMoreArray as $key=>$bool)
					{
						$boolValue |= $bool;
					}
					if (!$boolValue)
					{
						break;
					}
				}
				/****************************
				 ***以上这段代码放到搜索结果进程里面去		
				 ****************************/
			}
		}
		elseif (';' === PATH_SEPARATOR)
		{
			throw new Exception('Windows无法开启多进程');
			return false;
		}
	}
}
