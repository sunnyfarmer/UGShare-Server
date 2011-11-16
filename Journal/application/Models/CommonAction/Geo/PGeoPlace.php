<?php
class Models_CommonAction_Geo_PGeoPlace
{
	public $country_long 			= null;		//国家
	public $country_short 			= null;	
	public $areaLevel1_long			= null;		//一级区域，如省
	public $areaLevel1_short 		= null;		
	public $areaLevel2_long 		= null;		//二级区域
	public $areaLevel2_short 		= null;		
	public $areaLevel3_long 		= null;		//三级区域
	public $areaLevel3_short 		= null;
	public $locality_long 			= null;		//市
	public $locality_short 			= null;
	public $subLocality_long 		= null;		//市下面的区
	public $subLocality_short 		= null;
	public $route_long 				= null; 	//表示一条已命名的路线（如“US 101”）。
	public $route_short 			= null;
	public $street_address_long 	= null;		//街道地址
	public $street_address_short 	= null;
	public $street_number_long		= null;		//街道编号
	public $street_number_short 	= null;
	public $placeName_long			= null;		//地点的具体名字
	public $placeName_short			= null;		
	public $type 					= null;		//地点的类型，国家、一级区域、二级区域等等		
	public $formattedAddress 		= null;		//地址
	
	public $longitude				= null;		//经度
	public $latitude 				= null;		//纬度
	public $lonVPSW					= null;		//viewport southwest longitude
	public $latVPSW					= null;		//viewport southwest latitude
	public $lonVPNE					= null;		//viewport northeast longitude
	public $latVPNE					= null;		//viewport northeast latitude
	public $lonBSSW					= null;		//bounds southwest longitude
	public $latBSSW					= null;		//bounds southwest latitude
	public $lonBSNE					= null;		//bounds northeast longitude
	public $latBSNE					= null;		//bounds northeast latitude
}