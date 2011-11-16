<?php
class Models_CommonAction_Geo_PGeoJiepan extends Models_CommonAction_Geo_PGeographic
{
	public static $API_VERSION = 'v1';
	public static $SEARCH_URL = 'http://api.jiepang.com/v1/locations/search';
	public static $ADD_URL = 'http://api.jiepang.com/v1/locations/add';
	public static $SHOW_URL = 'http://api.jiepang.com/v1/locations/show';
	
	//街旁特色
	
	//search condition define begin
	//<<<...
	/**
	 * (可选)纬度,北纬为正值，南纬为负值
	 * float
	 */
	const PARASTR_LAT = 'lat';
	/**
	 * (可选)经度 ,东经为正值，西经为负值	
	 * float
	 */
	const PARASTR_LON = 'lon';
	/**
	 * (可选)返回结果的页码
	 * int		默认值：1
	 * 
	 */
	const PARASTR_PAGE = 'page';
	/**
	 * (可选)返回的记录条数
	 * int		默认值：10
	 */
	const PARASTR_COUNT = 'count';
	/**
	 * (可选)查询关键字
	 * string
	 */
	const PARASTR_Q = 'q';
	/**
	 * (可选)所在城市
	 * string
	 */
	const PARASTR_CITY = 'city';
	/**
	 * (可选)
	 * 识别码，每个基站的唯一识别码。如果使用基站查询地点列表，请传入以下四个参数，缺一不可。（cell_id，location_area_code，mobile_country_code mobile_network_code）
	 * int
	 */
	const PARASTR_CELLID = 'cell_id';
	/**
	 * (可选)
	 * 地区代码。如果使用基站查询地点列表，请传入以下四个参数，缺一不可。（cell_id，location_area_code，mobile_country_code mobile_network_code）
	 * int
	 */
	const PARASTR_LAT_LOCATION_AREA_CODE = 'location_area_code';
	/**
	 * (可选)
	 * 国家代码。如果使用基站查询地点列表，请传入以下四个参数，缺一不可。（cell_id，location_area_code，mobile_country_code mobile_network_code）
	 * int
	 */
	const PARASTR_LAT_MOBILE_COUNTRY_CODE = 'mobile_country_code';
	/**
	 * (可选)
	 * 网络代码。如果使用基站查询地点列表，请传入以下四个参数，缺一不可。（cell_id，location_area_code，mobile_country_code mobile_network_code）
	 * int
	 */
	const PARASTR_LAT_MOBILE_NETWORK_CODE = 'mobile_network_code';
	//...>>>
	//search condition define end
	
	//show condition define begin
	//<<<...
	/**
	 * 必需
	 * 地点ID
	 * 类型：String
	 */
	const PARASTR_GUID = 'guid';
	//...>>>
	//show condition define end
	
	//parameter value array
	private $paraArray = array(
		self::PARASTR_LAT => null,
		self::PARASTR_LON => null,
		self::PARASTR_PAGE => null,
		self::PARASTR_COUNT => null,
		self::PARASTR_Q		=> null,
		self::PARASTR_CITY	=> null,
		self::PARASTR_CELLID=> null,
		self::PARASTR_LAT_LOCATION_AREA_CODE=>null ,
		self::PARASTR_LAT_MOBILE_COUNTRY_CODE=>null,
		self::PARASTR_LAT_MOBILE_NETWORK_CODE=>null,
		self::PARASTR_GUID => null,
	);
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::getCurAddress()
	 */
	public function getCurAddress($latitude, $longitude, $page, $rowCount) {

		$this->clearParameter();
		
		$this->setParameter(self::PARASTR_LAT, $latitude);
		$this->setParameter(self::PARASTR_LON, $longitude);
		$this->setParameter(self::PARASTR_PAGE, $page);
		$this->setParameter(self::PARASTR_COUNT, $rowCount);
		
		$json = Models_CommonAction_PHttpRequest::requestByGet(self::$SEARCH_URL, $this->getSettedParameter());

		$jsonResult = $this->parseJSon($json);
		
		$hasMore = $jsonResult->has_more;
		$placeArr = $jsonResult->items;

		//清空之前的搜索记录
		if (count($placeArr))
		{
			$this->clearResult();
		}
		$this->result->setHasMore($hasMore);
		
		foreach ($placeArr as $place)
		{
			$pName = $place->name;
			$pLon = $place->lon;
			$pLat = $place->lat;
			$pGuid = $place->guid;
			$pAddr = $place->addr;

			//地点的类型（暂时搁置）
//			$pCategories = $place->categories;
//			$tagArr = array();
//			foreach ($pCategories as $category)
//			{
//				$tagName = $category->name;
//				array_push($tagArr, $tagName);
//			}
			
			$pCityName = self::getCityByGuid($pGuid);
		
			$placeObj = new Models_CommonAction_Geo_PGeoPlace();
			$placeObj->placeName_long = $pName;
			$placeObj->placeName_short = $pName;
			
			$placeObj->formattedAddress = $pAddr;
			
			$placeObj->latitude = $pLat;
			$placeObj->longitude = $pLon;
			
			$placeObj->areaLevel2_long = $pCityName;
			$placeObj->areaLevel2_short = $pCityName;
			$this->result->addPlace($placeObj);
		}
		$this->finishSearch();
		return true;
	}
	
	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::searchInArea()
	 */
	public function searchInArea($keyword, $area, $page, $rowCount) {

		$this->clearParameter();
		$this->setParameter(self::PARASTR_Q, $keyword);
		$this->setParameter(self::PARASTR_CITY, $area);
		$this->setParameter(self::PARASTR_PAGE, $page);
		$this->setParameter(self::PARASTR_COUNT, $rowCount);
				
		$json = Models_CommonAction_PHttpRequest::requestByGet(self::$SEARCH_URL, $this->getSettedParameter());

		$jsonResult = $this->parseJSon($json);
		
		$hasMore = $jsonResult->has_more;
		$placeArr = $jsonResult->items;

		//清空之前的搜索记录
		if (count($placeArr))
		{
			$this->clearResult();
		}
		$this->result->setHasMore($hasMore);
		foreach ($placeArr as $place)
		{
			$pName = $place->name;
			$pLon = $place->lon;
			$pLat = $place->lat;
			$pGuid = $place->guid;
			$pAddr = $place->addr;

			//地点的类型（暂时搁置）
//			$pCategories = $place->categories;
//			$tagArr = array();
//			foreach ($pCategories as $category)
//			{
//				$tagName = $category->name;
//				array_push($tagArr, $tagName);
//			}
			
			$pCityName = self::getCityByGuid($pGuid);
		
			$placeObj = new Models_CommonAction_Geo_PGeoPlace();
			$placeObj->placeName_long = $pName;
			$placeObj->placeName_short = $pName;
			
			$placeObj->formattedAddress = $pAddr;
			
			$placeObj->latitude = $pLat;
			$placeObj->longitude = $pLon;
			
			$placeObj->areaLevel2_long = $pCityName;
			$placeObj->areaLevel2_short = $pCityName;
			$this->result->addPlace($placeObj);
		}
		$this->finishSearch();
		return true;
		
	}

	/* (non-PHPdoc)
	 * @see Models_CommonAction_Geo_PGeographic::searchNearBy()
	 * 半径(radius)不起作用
	 */
	public function searchNearBy($keyword, $latitude, $longitude, $radius, $page, $rowCount) {

		$this->clearParameter();
		
		$this->setParameter(self::PARASTR_Q, $keyword);
		$this->setParameter(self::PARASTR_LON, $longitude);
		$this->setParameter(self::PARASTR_LAT, $latitude);
		$this->setParameter(self::PARASTR_PAGE, $page);
		$this->setParameter(self::PARASTR_COUNT, $rowCount);
		
		$json = Models_CommonAction_PHttpRequest::requestByGet(self::$SEARCH_URL, $this->getSettedParameter());

		$jsonResult = $this->parseJSon($json);
		
		$hasMore = $jsonResult->has_more;
		$placeArr = $jsonResult->items;

		//清空之前的搜索记录
		if (count($placeArr))
		{
			$this->clearResult();
		}
		$this->result->setHasMore($hasMore);
		foreach ($placeArr as $place)
		{
			$pName = $place->name;
			$pLon = $place->lon;
			$pLat = $place->lat;
			$pGuid = $place->guid;
			$pAddr = $place->addr;

			//地点的类型（暂时搁置）
//			$pCategories = $place->categories;
//			$tagArr = array();
//			foreach ($pCategories as $category)
//			{
//				$tagName = $category->name;
//				array_push($tagArr, $tagName);
//			}
			
			$pCityName = self::getCityByGuid($pGuid);
		
			$placeObj = new Models_CommonAction_Geo_PGeoPlace();
			$placeObj->placeName_long = $pName;
			$placeObj->placeName_short = $pName;
			
			$placeObj->formattedAddress = $pAddr;
			
			$placeObj->latitude = $pLat;
			$placeObj->longitude = $pLon;
			
			$placeObj->areaLevel2_long = $pCityName;
			$placeObj->areaLevel2_short = $pCityName;
			$this->result->addPlace($placeObj);
		}
		$this->finishSearch();
		return true;
		
	}

		/**
	 * 
	 * 通过GUID获得地点的城市名
	 * @param unknown_type $guid
	 */
	private static function getCityByGuid($guid)
	{
		$param = array(self::PARASTR_GUID=>$guid);
		$json = Models_CommonAction_PHttpRequest::requestByGet(self::$SHOW_URL, $param);
		
		$jsonResult = self::parseJSon($json);
		
		$cityName = $jsonResult->city;

		return $cityName;
	}

	/**
	 * 
	 * 将json编码的字符串转换为php的结构体
	 * @param string $jsonStr
	 */
	public static function parseJSon($jsonStr)
	{
		$start =strpos($jsonStr, '{');
		
		$jsonStr = substr($jsonStr, $start);
		return json_decode($jsonStr);
	}
	
	/**
	 * 
	 * 清空搜索条件
	 */
	public function clearParameter()
	{
		foreach ($this->paraArray as $key=>$value)
		{
			$this->paraArray[$key] = null;
		}
	}
	
	/**
	 * 
	 * 返回已经设置的参数数组
	 * @return struct	array(key=>value)	
	 */
	public function getSettedParameter()
	{
		$returnArr = array();
		foreach ($this->paraArray as $key=>$value)
		{
			if (isset($value))
			{
				$returnArr[$key] = $value;
			}
		}
		return $returnArr;
	}
	
	/**
	 * 
	 * 设置搜索的条件参数
	 * @param int $key
	 * @param string|int|float $value
	 */
	public function setParameter($key , $value)
	{
		$bSet = false;
		switch ($key)
		{
			case self::PARASTR_LAT:
            case self::PARASTR_LON:
            	if (is_float($value))
            	{
            		$this->paraArray[$key] = $value;
            		$bSet = true;
            	}
            case self::PARASTR_PAGE:
            case self::PARASTR_COUNT:
            case self::PARASTR_CELLID:
            case self::PARASTR_LAT_LOCATION_AREA_CODE:
            case self::PARASTR_LAT_MOBILE_COUNTRY_CODE:
            case self::PARASTR_LAT_MOBILE_NETWORK_CODE:      
               	if (is_int($value))
            	{
            		$this->paraArray[$key] = $value;
            		$bSet = true;
            	}
            case self::PARASTR_Q:
            case self::PARASTR_CITY:
            case self::PARASTR_GUID:
            	if (is_string($value))
            	{
            		$this->paraArray[$key] = $value;
            		$bSet = true;
            	}
        }
		
        return $bSet;
	}
}


