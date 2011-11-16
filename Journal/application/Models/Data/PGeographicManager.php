<?php
class Models_Data_PGeographicManager
{
	const TIMETOWAITFETCHDATA = 2;
	
	/**
	 * 
	 * 获取景点的信息（经度、纬度、地方名、地址、城市、省、国家、评分、点评人数）
	 * @param string $placeId					
	 * @return struct	array('STATUS'=>status , 'DATA'=>array('LONGITUDE'=>longitude , 'LATITUDE'=>latitude , 'PLACENAME'=>placeName , 'ADDRESS'=>address ,'SUBLOCALITY'=>sublocality, 'CITY'=>city , 'PROVINCE'=>province , 'COUNTRY'=>country , 'SCORE'=>score , 'MARKCOUNT'=>markCount))
	 */
	public static function getPlaceInfo($placeId)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$conn = Models_Core::getDoctrineConn();
		
		$result = null;
		
		$query = Doctrine_Query::create()
				->from('TrPlace pl')
				->leftJoin('pl.TrSublocality s')
				->leftJoin('pl.TrCity ct')
				->leftJoin('ct.TrProvince pr')
				->leftJoin('pr.TrCountry co')
				->where('pl.id = ?' , $placeId);
		
		$place = $query->fetchOne();
		
		if ($place)
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA']['LONGITUDE'] = floatval($place->longitude); 
			$result['DATA']['LATITUDE'] = floatval($place->latitude);
			$result['DATA']['PLACENAME'] = $place->name; 
			$result['DATA']['ADDRESS'] = $place->address;
			$result['DATA']['SUBLOCALITY'] = $place->TrSublocality->longname;
			$result['DATA']['CITY'] = $place->TrCity->longname;
			$result['DATA']['PROVINCE'] = $place->TrCity->TrProvince->longname;
			$result['DATA']['COUNTRY'] = $place->TrCity->TrProvince->TrCountry->longname; 
			$result['DATA']['SCORE'] = floatval($place->score);
			$result['DATA']['MARKCOUNT'] = intval($place->markCount);
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETPLACEINFO_PLACEID_INVALID);
		}
		
		return $result;
	}	

	/**
	 * 
	 * 在query对象上添加
	 * @param unknown_type $query
	 * @param unknown_type $latitude
	 * @param unknown_type $longitude
	 * @param unknown_type $radius
	 */
	private static function addBoundToQuery(&$query, $longitudeName , $longitude ,$latitudeName , $latitude  , $radius)
	{
		if (!is_object($query))
		{
			return $query;
		}
		
		$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $radius);
		$leftLon = $box[0];
		$rightLon = $box[1];
		$topLat = $box[2];
		$bottomLat = $box[3];
		//考虑经度超过180度的可能
		if ($leftLon > $rightLon) 
		{
			$query = $query->andWhere("$longitudeName >= ? or $longitudeName <= ?",  array($leftLon, $rightLon));
		} else 
		{
			$query = $query->andWhere("$longitudeName >= ? and $longitudeName <= ?", array($leftLon, $rightLon));
		}
		//不考虑纬度到达90度或者-90度的情况
		$query = $query->andWhere("$latitudeName >= ? and $latitudeName <= ?", array($bottomLat, $topLat));

		return $query;
	}
	
	/**
	 * 
	 * 获得当前地址信息
	 * @param float $longitude
	 * @param float $latitude
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @param string $usrId
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))	 
	 */
	public static function getCurAddress($longitude , $latitude ,  $beginIndex , $rowCount , $usrId = null , $radius = 100.0)
	{
		//TODO 
		$result = null;
		
		//先判断$latitude、$longitude是否为空；为空则获得用户存储过的位置信息
		if (!isset($latitude) && !isset($longitude))
		{
			$conn = Models_Core::getDoctrineConn();

			$query = Doctrine_Query::create()
					->select('u.id , uli.latitude , uli.longitude')
					->from('TrUser u')
					->leftJoin('u.TrUserLocationInfo uli')
					->where('u.id = ?' , $usrId);
					
			$user = $query->fetchOne();
			$latitude = $user->TrUserLocationInfo->latitude;
			$longitude = $user->TrUserLocationInfo->longitude;
		}
		
		if (!isset($latitude) || !isset($longitude) || !isset($beginIndex) || !isset($rowCount) || $beginIndex < 0 || $rowCount <= 0)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETCURADDRESS_MISS_PARAMETER));	
		}
		
		//开启搜索进程
		$paraArr = array($latitude , $longitude);
//		Models_CommonAction_Geo_PGeographicManager::beginProcess('getCurAddress', $paraArr);

		//获取数据库连接
		$conn = Models_Core::getDoctrineConn();

		//如果搜索本地服务器有结果则直接返回，否则等待若干秒后重新搜索
		$hasWaited = false;
		$rs = null;
		while (!$hasWaited)
		{
			//搜索本地服务器
			$query = Doctrine_Query::create()
					->select('p.id , p.name , p.score , p.markCount')
					->from('TrPlace p');
			self::addBoundToQuery($query, 'p.longitude', $longitude, 'p.latitude', $latitude, $radius);
			
			$rs = $query->execute();
			if (count($rs))
			{
				break;
			}
			else
			{
				//等待若干秒TIMETOWAITFETCHDATA
				$hasWaited = true;
				sleep(self::TIMETOWAITFETCHDATA);
			}
		}
			
		if (isset($rs) && count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);	
			$result['DATA'] = array();
			
			foreach ($rs as $place)
			{
				$pId = $place->id;
				$pName = $place->name;
				$pScore = $place->score;
				$pMarkCount = $place->markCount;
				
				$singlePlace = array(
							'PLACEID'=>$pId , 
							'PLACENAME'=>$pName , 
							'SCORE'=>$pScore , 
							'MARKCOUNT'=>$pMarkCount
				);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETCURADDRESS_ZERO_RESULT);	
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 通过关键字搜索区域内（区）的景点
	 * @param string $keyWord
	 * @param string $area
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))	 
	 */
	public static function searchPlaceInSublocality($keyWord , $sublocality , $beginIndex , $rowCount)
	{
		//TODO
		//首先搜索本地数据库，（暂时以数据库sql语句查询，以后要用Lucene作索引检索），同时开启搜索进程
		if (!isset($keyWord) || !isset($sublocality) || !isset($beginIndex) || !isset($rowCount) || $beginIndex < 0 || $rowCount <= 0)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_SEARCHPLACEINSUBLOCALITY_MISS_PARAMETER));	
		}
		
		//开启搜索进程
		$paraArr = array($keyWord , $sublocality);
//		Models_CommonAction_Geo_PGeographicManager::beginProcess('searchInArea', $paraArr);

		//获取数据库连接
		$conn = Models_Core::getDoctrineConn();
		//如果有结果则直接返回，否则等待若干秒后重新搜索
		$hasWaited = false;
		$rs = null;
		while(!$hasWaited)
		{
			$query = Doctrine_Query::create()
					->select('p.id , p.name , p.score , p.markCount')
					->from('TrPlace p')
					->leftJoin('p.TrSublocality s')
					->where("s.longname like '%$sublocality%' or s.shortname like '%$sublocality%'");
			
			$rs = $query->execute();		
			if (count($rs))
			{
				break;
			}
			else
			{
				//等待若干秒TIMETOWAITFETCHDATA
				$hasWaited = true;
				sleep(self::TIMETOWAITFETCHDATA);
			}
		}

		if (isset($rs) && count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);	
			$result['DATA'] = array();
			
			foreach ($rs as $place)
			{
				$pId = $place->id;
				$pName = $place->name;
				$pScore = $place->score;
				$pMarkCount = $place->markCount;
				
				$singlePlace = array(
							'PLACEID'=>$pId , 
							'PLACENAME'=>$pName , 
							'SCORE'=>$pScore , 
							'MARKCOUNT'=>$pMarkCount
				);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_SEARCHPLACEINSUBLOCALITY_ZERO_RESULT);	
		}
		
		return $result;
	}


	
	/**
	 * 
	 * 通过关键字搜索区域内（市）的景点
	 * @param string $keyWord
	 * @param string $area
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))	 
	 */
	public static function searchPlaceInCity($keyWord , $city , $beginIndex , $rowCount)
	{
		//TODO
		//首先搜索本地数据库，（暂时以数据库sql语句查询，以后要用Lucene作索引检索），同时开启搜索进程
		if (!isset($keyWord) || !isset($city) || !isset($beginIndex) || !isset($rowCount) || $beginIndex < 0 || $rowCount <= 0)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_SEARCHPLACEINCITY_MISS_PARAMETER));	
		}
		
		//开启搜索进程
		$paraArr = array($keyWord , $city);
//		Models_CommonAction_Geo_PGeographicManager::beginProcess('searchInArea', $paraArr);

		//获取数据库连接
		$conn = Models_Core::getDoctrineConn();
		//如果有结果则直接返回，否则等待若干秒后重新搜索
		$hasWaited = false;
		$rs = null;
		while(!$hasWaited)
		{
			$query = Doctrine_Query::create()
					->select('p.id , p.name , p.score , p.markCount')
					->from('TrPlace p')
					->leftJoin('p.TrCity c')
					->where("c.longname like '$city' or c.shortname like '$city'");
			
			$rs = $query->execute();		
			if (count($rs))
			{
				break;
			}
			else
			{
				//等待若干秒TIMETOWAITFETCHDATA
				$hasWaited = true;
				sleep(self::TIMETOWAITFETCHDATA);
			}
		}

		if (isset($rs) && count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);	
			$result['DATA'] = array();
			
			foreach ($rs as $place)
			{
				$pId = $place->id;
				$pName = $place->name;
				$pScore = $place->score;
				$pMarkCount = $place->markCount;
				
				$singlePlace = array(
							'PLACEID'=>$pId , 
							'PLACENAME'=>$pName , 
							'SCORE'=>$pScore , 
							'MARKCOUNT'=>$pMarkCount
				);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_SEARCHPLACEINCITY_ZERO_RESULT);	
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 通过关键字搜索周边范围的景点
	 * @param string $keyWord
	 * @param float $longitude
	 * @param float $latitude
	 * @param float $radius
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))	 
	 */
	public static function searchPlaceNearBy($keyWord , $longitude , $latitude , $radius , $beginIndex , $rowCount)
	{
		//TODO
		//首先搜索本地数据库，（暂时以数据库sql语句查询，以后要用Lucene作索引检索），同时开启搜索进程
		if (!isset($keyWord) || !isset($longitude) || !isset($latitude) || !isset($radius) || !isset($beginIndex) || !isset($rowCount) || $beginIndex < 0 || $rowCount <= 0)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_SEARCHPLACENEARBY_MISS_PARAMETER));	
		}
		
		//开启搜索进程
		$paraArr = array($keyWord , $longitude , $latitude , $radius);
//		Models_CommonAction_Geo_PGeographicManager::beginProcess('searchPlaceNearBy', $paraArr);

		//获取数据库连接
		$conn = Models_Core::getDoctrineConn();
		//如果有结果则直接返回，否则等待若干秒后重新搜索
		$hasWaited = false;
		$rs = null;
		while(!$hasWaited)
		{
			$query = Doctrine_Query::create()
					->select('p.id , p.name , p.score , p.markCount')
					->from('TrPlace p')
					->where("p.name like '%$keyWord%'");
			self::addBoundToQuery($query, 'p.longitude', $longitude, 'p.latitude', $latitude, $radius);
			$rs = $query->execute();		
			if (count($rs))
			{
				break;
			}
			else
			{
				//等待若干秒TIMETOWAITFETCHDATA
				$hasWaited = true;
				sleep(self::TIMETOWAITFETCHDATA);
			}
		}

		if (isset($rs) && count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);	
			$result['DATA'] = array();
			
			foreach ($rs as $place)
			{
				$pId = $place->id;
				$pName = $place->name;
				$pScore = $place->score;
				$pMarkCount = $place->markCount;
				
				$singlePlace = array(
							'PLACEID'=>$pId , 
							'PLACENAME'=>$pName , 
							'SCORE'=>$pScore , 
							'MARKCOUNT'=>$pMarkCount
				);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_SEARCHPLACENEARBY_ZERO_RESULT);	
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 通过关键字搜索景点（景点id、景点名、评分、点评人数）
	 * @param string $keyWord	关键字
	 * @param float $longitude	当前用户的经度
	 * @param float $latitude	当前用户的纬度
	 * @param float $distance	搜索的范围
	 * @param int $beginIndex
	 * @param int $rowCount		
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))
	 */
	public static  function searchPlace($keyWord , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$result = null;

		$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		$leftLon = $box[0];
		$rightLon = $box[1];
		$topLat = $box[2];
		$bottomLat = $box[3];
		
		$placeSum = 0;				//已经搜索出来的景点数量
		$isLast = false;			//标记：数据库中在bound范围内的所有景点是否已经全部搜索出来
		$bSearchOnline = false;		//标记：是否已经在其他服务器搜索过该关键字
		
		$conn = Models_Core::getDoctrineConn();

		//如果一直查询所有在bound范围内的地点，直至获取到所有在圆周范围内的地点位置
		while (true)
		{
			$query = Doctrine_Query::create()
					->from('TrPlace p');
			//考虑经度超过180度的可能
			if ($leftLon > $rightLon)
			{
				$query = $query->where('p.longitude >= ? or p.longitude <= ?', array($leftLon , $rightLon));
			}
			else 
			{
				$query = $query->where('p.longitude >= ? and p.longitude <= ?', array($leftLon , $rightLon));
			}
			//不考虑纬度到达90度或者-90度的情况
			$query = $query->andWhere('p.latitude >= ? and p.latitude <= ?' , array($bottomLat , $topLat));
			
			$query = $query
					->andWhere('p.name like ?' , "%$keyWord%")
					->offset($beginIndex)
					->limit($rowCount);
			$placeArr = $query->execute();
			$placeArrCot = count($placeArr);
			if ($placeArrCot < $rowCount)		
			{
				$isLast = true;
			}
			if (count($placeArr))
			{
				$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
				$result['DATA'] = array();
				foreach ($placeArr as $place)
				{
					//计算地点与当前位置的距离，如果小于请求的范围，那么插入到返回结果中
					$ppDistance = Models_CommonAction_PMath::distanceBtwLatLng($latitude, $longitude, $place->latitude, $place->longitude);
					if ($distance >= $ppDistance)
					{
						$singlePlace['PLACEID'] = $place->id;
						$singlePlace['PLACENAME'] = $place->name;
						$singlePlace['SCORE'] = floatval($place->score);
						$singlePlace['MARKCOUNT'] = intval($place->markCount);
						
						$pLat = $place->latitude;
						$pLon = $place->longitude;
						$pDistance = floatval(Models_CommonAction_PMath::distanceBtwLatLng($latitude, $longitude, $pLat, $pLon));
						$singlePlace['DISTANCE'] = $pDistance;
						
						array_push($result['DATA'], $singlePlace);
						$placeSum++;
						if ($placeSum >= $rowCount)
							break;
					}
				}
			}
			else 
			{
				if (!$placeSum)
				{
					if(!$bSearchOnline)
					{
						$geo = new Models_CommonAction_PGeocoding($keyWord);
						$placeName_long = $geo->getPlaceName_long();
						if ($placeName_long)
						{
							$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDPLACE);
							$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_USERID, null);
							$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_NAME, $placeName_long);
							$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_LONGITUDE, $geo->getLongitude());
							$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_LATITUDE, $geo->getLatitude());
	
							$chaseResult = $addBh->chase(true);
							if ($chaseResult['STATUS'] == intval(Models_Core::STATE_REQUEST_SUCCESS))
							{
								$isLast = false;
								$bSearchOnline = true;
							}
						}
					}
				}
			}
			$beginIndex += $rowCount;
			if ($isLast || $placeSum >= $rowCount)
				break;
		}
		
		if (!$result)
		{
			//数据库里面没有任何周边的景点信息
			$result['STATUS'] = intval(Models_Core::STATE_DATA_SEARCHPLACE_NO_PLACE);
		}
		return $result;		
	}

	/**
	 * 
	 * 获取周边的景点，（根据热度排序，即游记较多的景点，评分较高的景点）
	 * @param string $usrId
	 * @param float $longitude
	 * @param float $latitude
	 * @param float $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score ,'MARKCOUNT'=>markCount) , array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score ,'MARKCOUNT'=>markCount ) , ...))
	 */
	public static function getRimPlaces($usrId , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('avg(p.markCount)')
				->from('TrPlace p');
		
		$rs = $query->fetchOne();
		$avgMarkCount = $rs->avg;
		
		$query = Doctrine_Query::create()
				->select('p.id , p.name , p.score , p.markCount')
				->from('TrPlace p');
		
		if ($avgMarkCount != 0)
		{
			$query = $query->where('p.markCount > ?' , $avgMarkCount);
		}
				
		//算出范围
		if ($longitude && $latitude && $distance) 
		{
			$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		    $leftLon = $box[0];
		    $rightLon = $box[1];
		    $topLat = $box[2];
		    $bottomLat = $box[3];
		    //考虑经度超过180度的可能
		    if ($leftLon > $rightLon) {
		        $query = $query->andWhere('p.longitude >= ? or p.longitude <= ?',  array($leftLon, $rightLon));
		    } else {
		        $query = $query->andWhere('p.longitude >= ? and p.longitude <= ?', array($leftLon, $rightLon));
		    }
		    //不考虑纬度到达90度或者-90度的情况
		    $query = $query->andWhere('p.latitude >= ? and p.latitude <= ?', array($bottomLat, $topLat));
		}		
		
		$query = $query
				->orderBy('p.score desc')
				->offset($beginIndex)
				->limit($rowCount);
		
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $place)
			{
				$singlePlace['PLACEID'] = $place->id;
				$singlePlace['PLACENAME'] = $place->name;
				$singlePlace['SCORE'] = floatval($place->score);
				$singlePlace['MARKCOUNT'] = intval($place->markCount);
		
				$pLon = floatval($place->longitude);
				$pLat = floatval($place->latitude);
				$pDistance = floatval(Models_CommonAction_PMath::distanceBtwLatLng($latitude, $longitude, $pLat, $pLon));
				$singlePlace['DISTANCE'] = $pDistance;				
				
				array_push($result['DATA'] , $singlePlace);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETRIMPLACES_ZERO_PLACES);
		}
		
		return $result;	
	}
	
	/**
	 * 
	 * 根据用户使用习惯，获得当前时间比较火的城市;如果用户没有登录，那么不考虑用户使用习惯，直接搜索当前时间最火的景点多的城市.
	 * @param string|int $usrId
	 * @param float|int|null $longitude
	 * @param float|int|null $latitude
	 * @param float $distance	搜索的范围，以公里为单位
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('CITYID'=>cityId , 'CITYNAME'=>cityName), array('CITYID'=>cityId , 'CITYNAME'=>cityName),...))
	 */
	public static function getCurrentHotCitys($usrId , $longitude , $latitude ,$distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$conn = Models_Core::getDoctrineConn();
		
		$result = null;

		//当参数中没有位置参数，并且有用户id时，获得用户上次更新的位置信息
		if( (!$longitude || !$latitude ) && $usrId)
		{
			$query = Doctrine_Query::create()
					->select('uli.longitude , uli.latitude')
					->from('TrUserLocationInfo uli')
					->where('uli.usr_id_ref = ?' , $usrId)
					->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
			$rs = $query->fetchOne();
		
			$longitude = $rs['longitude'];
			$latitude = $rs['latitude'];
		}
		$cityIdStr = '';
		$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		if ($box)
		{
			$leftLon = $box[0];
			$rightLon = $box[1];
			$topLat = $box[2];
			$bottomLat = $box[3];
			
			$query = Doctrine_Query::create()
					->select('c.id')
					->from('TrCity c');
			//考虑经度超过180度的可能
			if ($leftLon > $rightLon)
			{
				$query = $query->where('c.longitude >= ? or c.longitude <= ?', array($leftLon , $rightLon));
			}
			else
			{
				$query = $query->where('c.longitude >= ? and c.longitude <= ?', array($leftLon , $rightLon));
			}
			//不考虑纬度到达90度或者-90度的情况
			$query = $query->andWhere('c.latitude >= ? and c.latitude <= ?' , array($bottomLat , $topLat));
		
			$rs = $query->execute();
			
			foreach ($rs as $city)
			{
				$cityIdStr .= $city->id . ',';
			}
		}		
		$cityIdStr = rtrim(rtrim($cityIdStr , ' ') , ',');
		
		$curMonth = date('n');
		
		$query = Doctrine_Query::create()
				->select('c.id , c.longname , count(c.id)')
				->from('TrCity c')
				->leftJoin('c.TrPlace p')
				->where("p.id in (select plc_id_ref from tr_place_hot_month where month = ?)" , $curMonth)
				->groupBy('c.id')
				->orderBy('count(c.id) desc')
				->offset($beginIndex)
				->limit($rowCount);
		if ($cityIdStr != '' && $cityIdStr != null)
		{
			$cityIdStr = "($cityIdStr)";
			$query = $query->andWhere('c.id in ' . $cityIdStr);
		}		
				
		$rs = $query->execute();

		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $city)
			{
				$cityRs['CITYID'] = $city->id;
				$cityRs['CITYNAME'] = $city->longname;
				
				array_push($result['DATA'], $cityRs);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETCURRENTHOTCITYS_ZERO_RESULT);
		}
		
		return  $result;
	}
	
	/**
	 * 
	 * 根据标签搜索景点
	 * @param string $tag
	 * @param float $longitude
	 * @param float $latitude
	 * @param float $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score), array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score),...))
	 */
	public static function getPlacesByTag($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$result = null;			//返回结果

		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('p.id , p.name , p.score')
			    ->from('TrPlace p')
			    ->leftJoin('p.TrPlaceTag pt')

			    ->leftJoin('pt.TrTag tag')
			    ->where('tag.tag_text = ?', $tag);
		//算出范围
		if ($longitude && $latitude && $distance) 
		{
		    $box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		    $leftLon = $box[0];
		    $rightLon = $box[1];
		    $topLat = $box[2];
		    $bottomLat = $box[3];
		
		    //考虑经度超过180度的可能
		    if ($leftLon > $rightLon) 
		    {
		        $query = $query->andWhere('p.longitude >= ? or p.longitude <= ?', array($leftLon, $rightLon));
		    } 
		    else {
		        $query = $query->andWhere('p.longitude >= ? and p.longitude <= ?', array($leftLon, $rightLon));
		    }
		    //不考虑纬度到达90度或者-90度的情况
		    $query = $query->andWhere('p.latitude >= ? and p.latitude <= ?', array($bottomLat, $topLat));
		}
		$query = $query
				->orderBy('pt.agreeCount desc')
		    	->offset($beginIndex)
		    	->limit($rowCount);
		
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $place)
			{
				$singlePlace['PLACEID'] = $place->id;
				$singlePlace['PLACENAME'] = $place->name;
				$singlePlace['SCORE'] = floatval($place->score);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETPLACESBYTAG_ZERO_RESULT);
		}		
		
		return $result;
	}
	/**
	 * 
	 * 通过地方最火的特征来搜索景点
	 * @param string $tag
	 * @param float $longitude
	 * @param float $latitude
	 * @param float $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score), array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score),...))	 
	 */
	public static function getPlacesByHotTag($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$conn = Models_Core::getDoctrineConn();
		
		$result = null;			//返回结果

		$query = Doctrine_Query::create()
				->select('p.id , p.name , p.score')
			    ->from('TrPlace p')
			    ->leftJoin('p.TrPlaceHotTag pt')
			    ->leftJoin('pt.TrTag tag')
			    ->where('tag.tag_text = ?', $tag);
		////算出范围
		if ($longitude && $latitude && $distance) 
		{
		    $box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		    $leftLon = $box[0];
		    $rightLon = $box[1];
		    $topLat = $box[2];
		    $bottomLat = $box[3];
		
		    //考虑经度超过180度的可能
		    if ($leftLon > $rightLon) 
		    {
		        $query = $query->andWhere('p.longitude >= ? or p.longitude <= ?', array($leftLon, $rightLon));
		    } 
		    else {
		        $query = $query->andWhere('p.longitude >= ? and p.longitude <= ?', array($leftLon, $rightLon));
		    }
		    //不考虑纬度到达90度或者-90度的情况
		    $query = $query->andWhere('p.latitude >= ? and p.latitude <= ?', array($bottomLat, $topLat));
		}
		$query = $query
		    	->offset($beginIndex)
		    	->limit($rowCount);
		
		//$query = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $place)
			{
				$singlePlace['PLACEID'] = $place->id;
				$singlePlace['PLACENAME'] = $place->name;
				$singlePlace['SCORE'] = floatval($place->score);
				array_push($result['DATA'], $singlePlace);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETPLACESBYHOTTAG_ZERO_RESULT);
		}		
		
		return $result;
	}
	
	/**
	 * 
	 * 通过月份所搜那月份最火的景点
	 * @param int $month	月份：1-12
	 * @param float|int $longitude
	 * @param float|int $latitude
	 * @param float|int $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score), array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score),...))	 
	 */
	public static function getPlacesByMonth($month , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$result = null;

		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('p.id , p.name , p.score')
				->from('TrPlace p')
				->offset($beginIndex)
				->limit($rowCount)
				->where('p.id in ( select plc_id_ref from tr_place_hot_month where month = ?)' , $month);
		
		if ($longitude && $latitude && $distance)
		{
			$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
			$leftLon = $box[0];
			$rightLon = $box[1];
			$topLat = $box[2];
			$bottomLat = $box[3];
		
			//考虑经度超过180度的可能
			if ($leftLon > $rightLon) {
		        $query = $query->where('p.longitude >= ? or p.longitude <= ?', array($leftLon, $rightLon));
		    } else {
		        $query = $query->where('p.longitude >= ? and p.longitude <= ?', array($leftLon, $rightLon));
		    }
		    //不考虑纬度到达90度或者-90度的情况
		    $query = $query->andWhere('p.latitude >= ? and p.latitude <= ?', array($bottomLat, $topLat));
		}
		
		$rs = $query->execute();
		
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $place)
			{
				$singlePlace['PLACEID'] = $place->id; 
				$singlePlace['PLACENAME'] = $place->name;
				$singlePlace['SCORE'] = floatval($place->score);
				
				array_push($result['DATA'], $singlePlace);
			}
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETPLACESBYMONTH_ZERO_RESULT);
		}
		
		return $result;
	}
}
