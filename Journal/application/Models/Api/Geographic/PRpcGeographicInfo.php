<?php
class Models_Api_Geographic_PRpcGeographicInfo
{
	/**
	 * 
	 * 获取景点的信息（经度、纬度、地方名、地址、区域、城市、省、国家、评分、点评人数）
	 * @param string $placeId					
	 * @return struct	array('STATUS'=>status , 'DATA'=>array('LONGITUDE'=>longitude , 'LATITUDE'=>latitude , 'PLACENAME'=>placeName , 'ADDRESS'=>address ,'SUBLOCALITY'=>sublocality, 'CITY'=>city , 'PROVINCE'=>province , 'COUNTRY'=>country , 'SCORE'=>score , 'MARKCOUNT'=>markCount))
	 */
	public static function getPlaceInfo($placeId)
	{
		$result = Models_Data_PGeographicManager::getPlaceInfo($placeId);
		
		return $result;
	}
	
	/**
	 * 
	 * 获得当前位置的地址信息
	 * @param float $longitude
	 * @param float $latitude
	 * @param int $page
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName , 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))	 
	 */
	public static function getCurAddress($longitude , $latitude , $page , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
	
		return Models_Data_PGeographicManager::getCurAddress($longitude,$latitude,  $page, $rowCount , $usrId);
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
		return Models_Data_PGeographicManager::searchPlaceInSublocality($keyWord, $sublocality, $beginIndex, $rowCount);
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
		return Models_Data_PGeographicManager::searchPlaceInCity($keyWord, $city, $beginIndex, $rowCount);
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
		return Models_Data_PGeographicManager::searchPlaceNearBy($keyWord, $longitude, $latitude, $radius, $beginIndex, $rowCount);
	}
	
	/**
	 * 
	 * 根据用户的使用历史，获取周边热门景点（景点id、景点名、评分、点评人数）
	 * @param float $longitude	当前用户的经度
	 * @param float $latitude	当前用户的纬度
	 * @param float|int $distance	搜索的范围
	 * @param int $beginIndex	
	 * @param int $rowCount						
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score ,'MARKCOUNT'=>markCount) , array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score ,'MARKCOUNT'=>markCount ) , ...))
	 */
	public static function getRimPlaces($longitude = null , $latitude = null , $distance = null , $beginIndex = null , $rowCount = null)
	{//TODO 处理经纬度的参数范围
		//搜索周边热点景点，不用检测登录状态
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PGeographicManager::getRimPlaces($usrId, $longitude, $latitude, $distance, $beginIndex, $rowCount);
		
		return $result;
	}
	/**
	 * 
	 * 通过关键字搜索景点（景点id、景点名、评分、点评人数）
	 * @param string $keyWord	关键字
	 * @param float $longitude	当前用户的经度
	 * @param float $latitude	当前用户的纬度
	 * @param float|int $distance	搜索的范围
	 * @param int $beginIndex
	 * @param int $rowCount		
	 * @return struct	array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score , 'MARKCOUNT'=>markCount),array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'DISTANCE'=>distance, 'SCORE'=>score , 'MARKCOUNT'=>markCount), ...))
	 */
	public static function searchPlace($keyWord , $longitude = null , $latitude = null , $distance = null , $beginIndex = null , $rowCount = null)
	{
		$result = Models_Data_PGeographicManager::searchPlace($keyWord, $longitude, $latitude, $distance, $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 根据用户使用习惯，获得当前时间比较火的城市;如果用户没有登录，那么不考虑用户使用习惯，直接搜索((当前时间最火)的景点)多的城市.
	 * @param float|int|null $longitude
	 * @param float|int|null $latitude
	 * @param float|int $distance	搜索的范围，以公里为单位
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('CITYID'=>cityId , 'CITYNAME'=>cityName), array('CITYID'=>cityId , 'CITYNAME'=>cityName),...))
	 */
	public static function getCurrentHotCitys($longitude ,$latitude  ,$distance , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		$result = Models_Data_PGeographicManager::getCurrentHotCitys($usrId, $longitude , $latitude, $distance, $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 根据标签搜索景点
	 * @param string $tag
	 * @param float $longitude
	 * @param float $latitude
	 * @param float|int $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score), array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score),...))
	 */
	public static function getPlacesByTag($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
		$result = Models_Data_PGeographicManager::getPlacesByTag($tag, $longitude, $latitude, $distance, $beginIndex, $rowCount);
		return $result;
	}
	/**
	 * 
	 * 通过地方最火的特征来搜索景点
	 * @param string $tag
	 * @param float $longitude
	 * @param float $latitude
	 * @param float|int $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct array('STATUS'=>status , 'DATA'=>array(array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score), array('PLACEID'=>placeId , 'PLACENAME'=>placeName ,'SCORE'=>score),...))
	 */
	public static function getPlacesByHotTag($tag , $longitude , $latitude , $distance , $beginIndex , $rowCount)
	{
		$result = Models_Data_PGeographicManager::getPlacesByHotTag($tag, $longitude, $latitude, $distance, $beginIndex, $rowCount);
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
		$result = Models_Data_PGeographicManager::getPlacesByMonth($month, $longitude, $latitude, $distance, $beginIndex, $rowCount);
		return $result;
	}
}