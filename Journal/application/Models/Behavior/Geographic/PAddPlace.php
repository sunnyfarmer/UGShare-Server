<?php
class Models_Behavior_Geographic_PAddPlace extends Models_Behavior_PIBehavior
{
	public function __construct()
	{
		parent::__construct(Models_Behavior_PBehaviorEnum::BADDPLACE);
	}
	
	/* (non-PHPdoc)
	 * @see Models_Behavior_PIBehavior::todo()
	 */
	protected function todo() {
//		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
		if (!$this->isPropertySet())
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPLACE_MISS_PARAMETER));
		}
		
		$usrId = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACE_USERID];
		$placeName = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACE_NAME];
		$longitude = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACE_LONGITUDE];
		$latitude = $this->propertys[Models_Behavior_PBehaviorEnum::PADDPLACE_LATITUDE];
		
		$geographicMsg = new Models_CommonAction_PGeocoding(null , $longitude , $latitude);
		
		$country_long = $geographicMsg->getCountry_long();				//国家
		$country_short = $geographicMsg->getCountry_short();
		$province_long = $geographicMsg->getAreaLevel1_long();			//省
		$province_short = $geographicMsg->getAreaLevel1_short();		
		$city_long = $geographicMsg->getLocality_long();				//市
		$city_short = $geographicMsg->getLocality_short();
		$sublocality_long = $geographicMsg->getSublocality_long();		//区
		$sublocality_short = $geographicMsg->getSublocality_short();
		
		if (!$country_long || !$province_long || !$city_long)
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDPLACE_LATLNG_INVALID));
		}
		
		$conn = Models_Core::getDoctrineConn();
		try 
		{
			$conn->beginTransaction();
		
			$geoIds = self::getGeographicIds($country_long, $country_short, $province_long, $province_short, $city_long, $city_short, $sublocality_long, $sublocality_short);
			$ctId 	= $geoIds[2];
			$subId 	= $geoIds[3];

			$place = new TrPlace();
			$place->name = $placeName;
			$place->longitude = $longitude;
			$place->latitude = $latitude;
			$place->cty_id_ref = $ctId;
			$place->slc_id_ref = $subId;
			
			$place->save();
			$conn->commit();
			
			return array('STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS));
		}
		catch (Exception $e)
		{
			$conn->rollback();
			return array('STATUS'=>intval(Models_Core::STATE_DB_ERROR));
		}
			
	}	
	
	/**
	 * 
	 * 获取地理信息的id，包括（国家id、省id、城市id、区id）
	 * @param string $country_long
	 * @param string $country_short
	 * @param string $province_long
	 * @param string $province_short
	 * @param string $city_long
	 * @param string $city_short
	 * @param string $sublocality_long
	 * @param string $sublocality_short
	 */
	private static function getGeographicIds($country_long , $country_short , $province_long , $province_short , $city_long , $city_short , $sublocality_long , $sublocality_short)
	{
		//地理信息的id
		$coId = null;
		$pId = null;
		$ctId = null;
		$subId = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$conn->beginTransaction();
		$query = Doctrine_Query::create()
				->select('co.id')
				->from('TrCountry co')
				->where('co.longname = ?' , $country_long);
		$country = $query->fetchOne();
		//有国家
		if ($country)
		{
			$coId = $country->id;
			$query = Doctrine_Query::create()
					->select('p.id')
					->from('TrProvince p')
					->where('p.longname = ? and p.ctr_id_ref = ?' , array($province_long , $coId));
			$province = $query->fetchOne();	
			//有省
			if ($province)
			{
				$pId = $province->id;
				$query = Doctrine_Query::create()
						->select('ct.id')
						->from('TrCity ct')
						->where('ct.longname = ? and ct.pvc_id_ref = ?' , array($city_long , $pId));
				$city = $query->fetchOne();
				//有市
				if ($city)
				{
					$ctId = $city->id;
					$query = Doctrine_Query::create()
							->select('sub.id')
							->from('TrSublocality sub')
							->where('sub.longname = ? and sub.cty_id_ref = ?' , array($sublocality_long , $ctId));
					$sublocality = $query->fetchOne();
					//有区
					if ($sublocality)
					{
						$subId = $sublocality->id;
					}
				}
			}
		}
		//有国家名，没有国家id
		if (!$coId && $country_long)
		{	
			$country = new TrCountry();
			$geo = new Models_CommonAction_PGeocoding($country_long);
			$country->longitude = $geo->getLongitude();
			$country->latitude = $geo->getLatitude();
			$country->longname = $country_long;
			$country->shortname = $country_short;
			$country->save();
			$coId = $country->id;
		}	
		//有省名，没有省id，有国家id
		if (!$pId && $province_long && $coId)
		{
			$province = new TrProvince();
			$geo = new Models_CommonAction_PGeocoding($province_long);
			$province->longitude = $geo->getLongitude();
			$province->latitude = $geo->getLatitude();
			$province->ctr_id_ref = $coId;
			$province->longname = $province_long;
			$province->shortname = $province_short;
			$province->save();
			$pId = $province->id;
		}
		//有市名，没有市id，有省id
		if (!$ctId && $city_long && $pId)
		{	
			$city = new TrCity();
			$geo = new Models_CommonAction_PGeocoding($city_long);
			$city->longitude = $geo->getLongitude();
			$city->latitude = $geo->getLatitude();
			$city->pvc_id_ref = $pId;
			$city->longname = $city_long;
			$city->shortname = $city_short;
			$city->save();
			$ctId = $city->id;
		}
		//有区名，没有区id，有市id
		if (!$subId && $sublocality_long && $ctId)
		{	
			$sublocality = new TrSublocality();
			$geo = new Models_CommonAction_PGeocoding($sublocality_long);
			$sublocality->longitude = $geo->getLongitude();
			$sublocality->latitude = $geo->getLatitude();
			$sublocality->cty_id_ref = $ctId;
			$sublocality->longname = $sublocality_long;
			$sublocality->shortname = $sublocality_short;
			$sublocality->save();
			$subId = $sublocality->id;
		}
		$conn->commit();
		
		return array($coId , $pId , $ctId , $subId);
	}
}