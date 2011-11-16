<?php
/**
 * 返回的数据结构剖析：
 * types[] 数组指示传回结果的类型。此数组包含一个或多个标签，这些标签标识结果中返回的特征的类型。例如，对“Chicago”的地址解析返回“locality”，表示“Chicago”是一个城市；同时返回“political”，表示它是一个政治实体。
 * formatted_address 是一个字符串，包含此位置的人类可读地址。通常该地址相当于“邮政地址”，有时会因不同国家/地区而存在差异。（请注意，部分国家/地区会有许可限制，禁止发布真实的邮政地址，如英国。）此地址通常由一个或多个地址部分组成。例如，地址“111 8th Avenue, New York, NY”包含四个地址组成部分，即“111”（街道门牌号）、“8th Avenue”（街道地址）、“New York”（城市）和“NY”（美国的一个州）。这些地址组成部分包含附加信息，如下面所述。
 * address_components[] 是一个包含多个地址组成部分（如上文所述）的数组。每个 address_component 通常包含以下几个组成部分：
 * 		types[] 是一个数组，表示地址组成部分的类型。
 * 		long_name 是地址解析器传回的完整文本说明或地址组成部分的名称。
 * 		short_name 是地址组成部分的缩写文本名称（如果有）。例如，阿拉斯加州的地址组成部分可能具有 long_name“Alaska”和 short_name“AK”（使用 2 个字母的邮政缩写）。
 * 		请注意，address_components[] 包含的地址组成部分可能多于 formatted_address 中所注明的地址组成部分。
 * geometry 包含以下信息：
 * 		location 包含地址解析生成的纬度值和经度值。对于常规地址查询，此字段通常是最重要的。
 * 		location_type 存储有关指定位置的附加数据。当前支持以下值：
 * 			"ROOFTOP" 表示传回的结果是一个精确的地址解析值，我们可获得精确到街道地址的位置信息。
 * 			"RANGE_INTERPOLATED" 表示返回的结果是一个近似值（通常表示某条道路上的地址），该地址处于两个精确点（如十字路口）之间。当无法对街道地址进行精确的地址解析时，通常会返回近似结果。
 * 			"GEOMETRIC_CENTER" 表示返回的结果是折线（如街道）或多边形（区域）等内容的几何中心。
 * 			"APPROXIMATE" 表示返回的结果是一个近似值。
 * 		viewport 包含用于显示传回结果的建议可视区域，并被指定为两个纬度/经度值，分别定义可视区域边框的 southwest 和 northeast 角。通常，该可视区域用于在将结果显示给用户时作为结果的框架。
 * 		bounds（可选择传回）存储可完全包含传回结果的边框。请注意，这些边界可能与建议的可视区域不相符。（例如，旧金山包含费拉隆岛。该岛实际上是旧金山市的一部分，但不应该在可视区域内传回。）
 * 
 */

class Models_CommonAction_PGeocoding
{
	const GEOCODING_URL = 'http://maps.google.com/maps/api/geocode/';
		
	const RESPONSE_TYPE_XML		= 'xml';	//返回数据结构的类型：xml
	const RESPONSE_TYPE_JSON	= 'json'; 	//返回数据结构的类型：json
	
	const PARAM_ID_ADDRESS 	= 'address';	//参数id（必需），您要进行地址解析的地址
	const PARAM_ID_LATLNG 	= 'latlng';		//参数id（必需），您希望获取的、距离最近的、可人工读取地址的纬度/经度文本值
	const PARAM_ID_SENSOR 	= 'sensor';		//参数id（必需），指示地址解析请求是否来自装有位置传感器的设备。该值必须为 true 或 false。
	const PARAM_ID_BOUNDS	= 'bounds';		//参数id（可选），要在其中更显著地偏移地址解析结果的可视区域的边框
	const PARAM_ID_REGION	= 'region';		//参数id（可选），区域代码，指定为 ccTLD
	const PARAM_ID_LANGUAGE	= 'language';	//参数id（可选），传回结果时所使用的语言
	
	//Result Language Begin
	//<<<...
	const LANGUAGE_LIST_ARABIC 					= 'ar';
	const LANGUAGE_LIST_BASQUE 					= 'eu';
	const LANGUAGE_LIST_BULGARIAN 				= 'bg';
	const LANGUAGE_LIST_BENGALI 				= 'bn';
	const LANGUAGE_LIST_CATALAN 				= 'ca';
	const LANGUAGE_LIST_CZECH 					= 'cs';
	const LANGUAGE_LIST_DANISH 					= 'da';
	const LANGUAGE_LIST_GERMAN 					= 'de';
	const LANGUAGE_LIST_GREEK 					= 'el';
	const LANGUAGE_LIST_ENGLISH 				= 'en';
	const LANGUAGE_LIST_ENGLISH_AUSTRALIAN		= 'en-AU';
	const LANGUAGE_LIST_ENGLISH_GREAT_BRITAIN 	= 'en-GB';
	const LANGUAGE_LIST_SPANISH 				= 'es';
	const LANGUAGE_LIST_FARSI 					= 'fa';
	const LANGUAGE_LIST_FINNISH 				= 'fi';
	const LANGUAGE_LIST_FILIPINO 				= 'fil';
	const LANGUAGE_LIST_FRENCH 					= 'fr';
	const LANGUAGE_LIST_GALICIAN 				= 'gl';
	const LANGUAGE_LIST_GUJARATI 				= 'gu';
	const LANGUAGE_LIST_HINDI 					= 'hi';
	const LANGUAGE_LIST_CROATIAN 				= 'hr';
	const LANGUAGE_LIST_HUNGARIAN 				= 'hu';
	const LANGUAGE_LIST_INDONESIAN				= 'id';
	const LANGUAGE_LIST_ITALIAN 				= 'it';
	const LANGUAGE_LIST_HEBREW 					= 'iw';
	const LANGUAGE_LIST_JAPANESE 				= 'ja';
	const LANGUAGE_LIST_KANNADA 				= 'kn';
	const LANGUAGE_LIST_KOREAN 					= 'ko';
	const LANGUAGE_LIST_LITHUANIAN 				= 'lt';
	const LANGUAGE_LIST_LATVIAN 				= 'lv';
	const LANGUAGE_LIST_MALAYALAM 				= 'ml';
	const LANGUAGE_LIST_MARATHI 				= 'mr';
	const LANGUAGE_LIST_DUTCH 					= 'nl';
	const LANGUAGE_LIST_NORWEGIAN_NYNORSK 		= 'nn';
	const LANGUAGE_LIST_NORWEGIAN 				= 'no';
	const LANGUAGE_LIST_ORIYA 					= 'or';
	const LANGUAGE_LIST_POLISH 					= 'pl';
	const LANGUAGE_LIST_PORTUGUESE 				= 'pt';
	const LANGUAGE_LIST_PORTUGUESE_BRAZIL 		= 'pt-BR';
	const LANGUAGE_LIST_PORTUGUESE_PORTUGAL 	= 'pt-PT';
	const LANGUAGE_LIST_ROMANSCH 				= 'rm';
	const LANGUAGE_LIST_ROMANIAN 				= 'ro';
	const LANGUAGE_LIST_RUSSIAN 				= 'ru';
	const LANGUAGE_LIST_SLOVAK 					= 'sk';
	const LANGUAGE_LIST_SLOVENIAN 				= 'sl';
	const LANGUAGE_LIST_SERBIAN 				= 'sr';
	const LANGUAGE_LIST_SWEDISH 				= 'sv';
	const LANGUAGE_LIST_TAGALOG 				= 'tl';
	const LANGUAGE_LIST_TAMIL 					= 'ta';
	const LANGUAGE_LIST_TELUGU 					= 'te';
	const LANGUAGE_LIST_THAI 					= 'th';
	const LANGUAGE_LIST_TURKISH		 			= 'tr';
	const LANGUAGE_LIST_UKRAINIAN 				= 'uk';
	const LANGUAGE_LIST_VIETNAMESE			 	= 'vi';
	const LANGUAGE_LIST_CHINESE_SIMPLIFIED 		= 'zh-CN';
	const LANGUAGE_LIST_CHINESE_TRADITIONAL 	= 'zh-TW';
	
	//...>>>
	//Result Language End
	
	//GeoCoding Status begin
	//<<<...
	const STATUS_OK						= 'OK';					//表示未发生错误；地址成功进行了解析并且至少传回了一个地址解析结果。
	const STATUS_ZERO_RESULTS				= 'ZERO_RESULTS';		//表示地址解析成功，但未返回结果。如果地址解析过程中传递的偏远位置 address 或 latlng 并不存在，则会出现 种情况。
	const STATUS_OVER_QUERY_LIMIT 		= 'OVER_QUERY_LIMIT';	//表示您超出了配额。
	const STATUS_REQUEST_DENIED 			= 'REQUEST_DENIED';		//表示您的请求被拒绝，通常是由于缺少 sensor 参数。
	const STATUS_INVALID_REQUEST 			= 'INVALID_REQUEST';	//通常表示缺少查询参数（address 或 latlng）。
	//...>>>
	//GeoCoding Status end

	//address component type begin
	//<<<...
	const ADR_COMPONENT_TYPE_POLITICAL 						= 'political'; 						//表示一个政治实体。此类型通常表示代表某个行政管理区的多边形。
		//上层地址
	const ADR_COMPONENT_TYPE_COUNTRY						= 'country';  						//表示国家政治实体。在地址解析器返回的结果中，该部分通常列在最前面。
	const ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_1 	= 'administrative_area_level_1'; 	//表示仅次于国家级别的行政实体。在美国，这类行政实体是指州。并非所有国家都有该行政级别。
	const ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_2 	= 'administrative_area_level_2'; 	//表示国家级别下的二级行政实体。在美国，这类行政实体是指县。并非所有国家都有该行政级别。
	const ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_3 	= 'administrative_area_level_3'; 	//表示国家级别下的三级行政实体。此类型表示较小的行政单位。并非所有国家都有该行政级别。
	const ADR_COMPONENT_TYPE_LOCALITY 						= 'locality'; 						//表示合并的市镇级别政治实体。
	const ADR_COMPONENT_TYPE_SUBLOCALITY 					= 'sublocality'; 					//表示仅次于地区级别的行政实体。
	const ADR_COMPONENT_TYPE_ROUTE 							= 'route'; 							//表示一条已命名的路线（如“US 101”）。
	const ADR_COMPONENT_TYPE_STREET_ADDRESS 				= 'street_address';					//表示一个精确的街道地址。
	const ADD_COMPONENT_TYPE_STREET_NUMBER					= 'street_number';					//表示一个街道编号
		//下层地址
	const ADR_COMPONENT_TYPE_COLLOQUIAL_AREA 				= 'colloquial_area'; 				//表示实体的通用别名。
	const ADR_COMPONENT_TYPE_ESTABLISHMENT					= 'establishment';					//表示用户自定义加入的地点
	const ADR_COMPONENT_TYPE_PREMISE 						= 'premise'; 						//表示已命名的位置，通常是具有常用名称的建筑物或建筑群。
	const ADR_COMPONENT_TYPE_SUBPREMISE 					= 'subpremise'; 					//表示仅次于已命名位置级别的实体，通常是使用常用名称的建筑群中的某座建筑物。
	const ADR_COMPONENT_TYPE_NATURAL_FEATURE 				= 'natural_feature'; 				//表示某个明显的自然特征。
	const ADR_COMPONENT_TYPE_AIRPORT 						= 'airport'; 						//表示机场。
	const ADR_COMPONENT_TYPE_TRAIN_STATION					= 'train_station';					//代表火车站
	const ADR_COMPONENT_TYPE_PARK 							= 'park'; 							//表示已命名的公园。
	const ADR_COMPONENT_TYPE_POINT_OF_INTEREST 				= 'point_of_interest'; 				//表示已命名的兴趣点。通常，这些“POI”是一些不易归入其他类别的比较有名的当地实体，如“帝国大厦”或“自由女神像”。
	const ADR_COMPONENT_TYPE_POST_BOX 						= 'post_box'; 						//表示一个具体的邮筒。
	const ADR_COMPONENT_TYPE_STREET_NUMBER 					= 'street_number'; 					//表示精确的街道编号。
	const ADR_COMPONENT_TYPE_FLOOR 							= 'floor'; 							//表示建筑物的楼层号。
	const ADR_COMPONENT_TYPE_ROOM 							= 'room'; 							//表示建筑物的房间编号。
		//定不了位置
	const ADR_COMPONENT_TYPE_INTERSECTION 					= 'intersection';					//表示一个大十字路口，通常由两条主道交叉形成。
	const ADR_COMPONENT_TYPE_NEIGHBORHOOD 					= 'neighborhood'; 					//表示已命名的邻近地区。
		//地址其他信息
	const ADR_COMPONENT_TYPE_POSTAL_CODE 					= 'postal_code'; 					//表示邮政编码，用于确定相应国家/地区内的信件投递地址。
	//...>>>
	//address component type end
	
	//GEOMETRY LOCATION TYPE begin
	//<<<...
	const LOCATION_TYPE_ROOFTOP 				= "ROOFTOP"; 			//表示传回的结果是一个精确的地址解析值，我们可获得精确到街道地址的位置信息。
	const LOCATION_TYPE_RENGE_INTERPOLATED		= "RANGE_INTERPOLATED"; //表示返回的结果是一个近似值（通常表示某条道路上的地址），该地址处于两个精确点（如十字路口）之间。当无法对街道地址进行精确的地址解析时，通常会返回近似结果。
	const LOCATION_TYPE_RENGE_GEOMETRIC_CENTER	= "GEOMETRIC_CENTER"; 	//表示返回的结果是折线（如街道）或多边形（区域）等内容的几何中心。
	const LOCATION_TYPE_RENGE_APPROXIMATE		= "APPROXIMATE"; 		//表示返回的结果是一个近似值。
	//...>>>
	//GEOMETRY LOCATION TYPE end
	
	private $xml 					= null;
	
	private $country_long 			= null;		//国家
	private $country_short 			= null;	
	private $areaLevel1_long		= null;		//一级区域，如省
	private $areaLevel1_short 		= null;		
	private $areaLevel2_long 		= null;		//二级区域
	private $areaLevel2_short 		= null;		
	private $areaLevel3_long 		= null;		//三级区域
	private $areaLevel3_short 		= null;
	private $locality_long 			= null;		//市
	private $locality_short 		= null;
	private $subLocality_long 		= null;		//市下面的区
	private $subLocality_short 		= null;
	private $route_long 			= null; 	//表示一条已命名的路线（如“US 101”）。
	private $route_short 			= null;
	private $street_address_long 	= null;		//街道地址
	private $street_address_short 	= null;
	private $street_number_long		= null;		//街道编号
	private $street_number_short 	= null;
	private $placeName_long			= null;		//地点的具体名字
	private $placeName_short		= null;		
	private $type 					= null;		//地点的类型，国家、一级区域、二级区域等等		
	private $formattedAddress 		= null;		//地址
	
	private $longitude				= null;		//经度
	private $latitude 				= null;		//纬度
	private $lonVPSW				= null;		//viewport southwest longitude
	private $latVPSW				= null;		//viewport southwest latitude
	private $lonVPNE				= null;		//viewport northeast longitude
	private $latVPNE				= null;		//viewport northeast latitude
	private $lonBSSW				= null;		//bounds southwest longitude
	private $latBSSW				= null;		//bounds southwest latitude
	private $lonBSNE				= null;		//bounds northeast longitude
	private $latBSNE				= null;		//bounds northeast latitude
	/**
	 * 
	 * @param string $address		地址
	 * @param string $longitude		经度
	 * @param string $latitude		纬度
	 * @param string $bSensor		指示地址解析请求是否来自装有位置传感器的设备
	 * @param string $type			返回结果的类型，xml或者json
	 * @param string $language		返回结果的语言
	 */
	public function __construct($address = null , $longitude = null, $latitude = null ,$bSensor = 'false' , $type = self::RESPONSE_TYPE_XML , $language = self::LANGUAGE_LIST_CHINESE_SIMPLIFIED)
	{
		$xmlStr = self::getDataStruct($address , $longitude , $latitude ,$bSensor, $type , $language);
		$num = strpos($xmlStr, '<?xml');
		$xmlStr = substr($xmlStr, $num);
		$this->xml = simplexml_load_string($xmlStr);
		
		$this->parse();
	}
	
	private function parse()
	{
		if (!$this->xml)
		{
			return false;
		}
		
		$status = $this->xml->status;
		$result = $this->xml->result;
		switch($status)
		{
			case self::STATUS_OK:
				//获取地点的类型	
				$this->type = array();
				foreach($result->type as $tp)
				{
					array_push($this->type, strval($tp));
				}				
				//获取详细地址
				$this->formattedAddress = strval($result->formatted_address);
				
				//解析地址构造
				foreach($result->address_component as $component)
				{
					foreach($component->type as $comTp)
					{
						switch ($comTp)
						{
							case self::ADR_COMPONENT_TYPE_COUNTRY:
								$this->country_long = strval($component->long_name);
								$this->country_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_1:
								$this->areaLevel1_long = strval($component->long_name);
								$this->areaLevel1_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_2:
								$this->areaLevel2_long = strval($component->long_name);
								$this->areaLevel2_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_ADMINISTRATIVE_AREA_LEVEL_3:
								$this->areaLevel3_long = strval($component->long_name);
								$this->areaLevel3_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_LOCALITY:
								$this->locality_long = strval($component->long_name);	
								$this->locality_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_SUBLOCALITY:
								$this->subLocality_long = strval($component->long_name);
								$this->subLocality_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_ROUTE:
								$this->route_long = strval($component->long_name);
								$this->route_short = strval($component->short_name);
								break;
							case self::ADR_COMPONENT_TYPE_STREET_ADDRESS:
								$this->street_address_long = strval($component->long_name);
								$this->street_address_short = strval($component->short_name);
								break;
							case self::ADD_COMPONENT_TYPE_STREET_NUMBER:
								$this->street_number_long = strval($component->long_name);
								$this->street_number_short = strval($component->short_name);
								break;
                            case self::ADR_COMPONENT_TYPE_COLLOQUIAL_AREA:
                            case self::ADR_COMPONENT_TYPE_ESTABLISHMENT:
                            case self::ADR_COMPONENT_TYPE_PREMISE:
                            case self::ADR_COMPONENT_TYPE_SUBPREMISE:
                            case self::ADR_COMPONENT_TYPE_NATURAL_FEATURE:
                            case self::ADR_COMPONENT_TYPE_AIRPORT:
                            case self::ADR_COMPONENT_TYPE_TRAIN_STATION:
                            case self::ADR_COMPONENT_TYPE_PARK:
                            case self::ADR_COMPONENT_TYPE_POINT_OF_INTEREST:
                            case self::ADR_COMPONENT_TYPE_POST_BOX:
                            case self::ADR_COMPONENT_TYPE_STREET_NUMBER:
                            case self::ADR_COMPONENT_TYPE_FLOOR:
                            case self::ADR_COMPONENT_TYPE_ROOM:
                            	$this->placeName_long = strval($component->long_name);
                            	$this->placeName_short = strval($component->short_name);
                                break;
						}
					}
				}
				foreach($result->geometry as $geometry)
				{
					$location = $geometry->location;
					$this->longitude = floatval($location->lng);
					$this->latitude = floatval($location->lat);

					$viewport = $geometry->viewport;
					$this->lonVPSW = floatval($viewport->southwest->lng);
					$this->latVPSW = floatval($viewport->southwest->lat);
					$this->lonVPNE = floatval($viewport->northeast->lng);
					$this->latVPNE = floatval($viewport->northeast->lat);
					
					$bounds = $geometry->bounds;
					$this->lonBSSW = floatval($bounds->southwest->lng);
					$this->latBSSW = floatval($bounds->southwest->lat);
					$this->lonBSNE = floatval($bounds->northeast->lng);
					$this->latBSNE = floatval($bounds->northeast->lat);
					
					break;
				}
				return true;
				break;
			case self::STATUS_ZERO_RESULTS:
				return false;
				break;
			case self::STATUS_OVER_QUERY_LIMIT:
				return false;
				break;
			case self::STATUS_INVALID_REQUEST:
				return false;
				break;
			case self::STATUS_REQUEST_DENIED:
				return false;
				break;
		}
	}
	


	/**
	 * @return float $longitude
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * @return float $latitude
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * @return the $lonVPSW
	 */
	public function getLonVPSW() {
		return $this->lonVPSW;
	}

	/**
	 * @return the $latVPSW
	 */
	public function getLatVPSW() {
		return $this->latVPSW;
	}

	/**
	 * @return the $lonVPNE
	 */
	public function getLonVPNE() {
		return $this->lonVPNE;
	}

	/**
	 * @return the $latVPNE
	 */
	public function getLatVPNE() {
		return $this->latVPNE;
	}

	/**
	 * @return the $lonBSSW
	 */
	public function getLonBSSW() {
		return $this->lonBSSW;
	}

	/**
	 * @return the $latBSSW
	 */
	public function getLatBSSW() {
		return $this->latBSSW;
	}

	/**
	 * @return the $lonBSNE
	 */
	public function getLonBSNE() {
		return $this->lonBSNE;
	}

	/**
	 * @return the $latBSNE
	 */
	public function getLatBSNE() {
		return $this->latBSNE;
	}

	/**
	 * 
	 * 根据地点或者经纬度，获取数据
	 * @param string $address		地址
	 * @param string $longitude		经度
	 * @param string $latitude		纬度
	 * @param string $bSensor		指示地址解析请求是否来自装有位置传感器的设备
	 * @param string $type			返回结果的类型，xml或者json
	 * @param string $language		返回结果的语言
	 * @return string
	 */
	public static function getDataStruct($address = null , $longitude = null, $latitude = null , $bSensor = 'false' ,  $type = self::RESPONSE_TYPE_XML , $language = self::LANGUAGE_LIST_CHINESE_SIMPLIFIED)
	{
		$url = self::GEOCODING_URL . $type;
		$params = null;
		//如果地址不为空，那么根据地点名搜索地理信息
		if ($address)
		{
			$params = array(self::PARAM_ID_ADDRESS=>$address , self::PARAM_ID_SENSOR=>$bSensor , self::PARAM_ID_LANGUAGE=>$language);
		}
		//否则，根据经纬度搜索地理信息
		else if ($longitude && $latitude)
		{
			$latlngStr = "$latitude,$longitude";
			$params = array(self::PARAM_ID_LATLNG=>$latlngStr , self::PARAM_ID_SENSOR=>$bSensor , self::PARAM_ID_LANGUAGE=>$language);			
		}
		else
		{
			return null;
		}
		
		$response = Models_CommonAction_PHttpRequest::requestByGet($url, $params);
	
		return $response;
	}
	/**
	 * @return the $xml
	 */
	public function getXml() {
		return $this->xml;
	}

	/**
	 * @return the $country_long
	 */
	public function getCountry_long() {
		return $this->country_long;
	}

	/**
	 * @return the $country_short
	 */
	public function getCountry_short() {
		return $this->country_short;
	}

	/**
	 * @return the $areaLevel1_long
	 */
	public function getAreaLevel1_long() {
		return $this->areaLevel1_long;
	}

	/**
	 * @return the $areaLevel1_short
	 */
	public function getAreaLevel1_short() {
		return $this->areaLevel1_short;
	}

	/**
	 * @return the $areaLevel2_long
	 */
	public function getAreaLevel2_long() {
		return $this->areaLevel2_long;
	}

	/**
	 * @return the $areaLevel2_short
	 */
	public function getAreaLevel2_short() {
		return $this->areaLevel2_short;
	}

	/**
	 * @return the $areaLevel3_long
	 */
	public function getAreaLevel3_long() {
		return $this->areaLevel3_long;
	}

	/**
	 * @return the $areaLevel3_short
	 */
	public function getAreaLevel3_short() {
		return $this->areaLevel3_short;
	}

	/**
	 * @return the $locality_long
	 */
	public function getLocality_long() {
		return $this->locality_long;
	}

	/**
	 * @return the $locality_short
	 */
	public function getLocality_short() {
		return $this->locality_short;
	}

	/**
	 * @return the $subLocality_long
	 */
	public function getSubLocality_long() {
		return $this->subLocality_long;
	}

	/**
	 * @return the $subLocality_short
	 */
	public function getSubLocality_short() {
		return $this->subLocality_short;
	}

	/**
	 * @return the $route_long
	 */
	public function getRoute_long() {
		return $this->route_long;
	}

	/**
	 * @return the $route_short
	 */
	public function getRoute_short() {
		return $this->route_short;
	}

	/**
	 * @return the $street_address_long
	 */
	public function getStreet_address_long() {
		return $this->street_address_long;
	}

	/**
	 * @return the $street_address_short
	 */
	public function getStreet_address_short() {
		return $this->street_address_short;
	}
	/**
	 * @return the $street_number_long
	 */
	public function getStreet_number_long() {
		return $this->street_number_long;
	}

	/**
	 * @return the $street_number_short
	 */
	public function getStreet_number_short() {
		return $this->street_number_short;
	}

	
	/**
	 * @return the $placeName_long
	 */
	public function getPlaceName_long() {
		return $this->placeName_long;
	}

	/**
	 * @return the $placeName_short
	 */
	public function getPlaceName_short() {
		return $this->placeName_short;
	}

	/**
	 * @return the $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return the $formattedAddress
	 */
	public function getFormattedAddress() {
		return $this->formattedAddress;
	}


}