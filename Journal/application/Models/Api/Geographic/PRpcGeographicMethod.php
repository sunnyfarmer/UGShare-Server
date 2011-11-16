<?php
class Models_Api_Geographic_PRpcGeographicMethod
{
	/**
	 * 
	 * 添加新的景点
	 * @param string $name
	 * @param float $longitude
	 * @param float $latitude
	 * @return struct	array('STATUS'=>status)
	 */
	public static function addPlace($name , $longitude , $latitude)
	{
		//先判断用户是否登录
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			//用户没有登录，那么返回错误的值
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDPLACE);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_USERID, $usrId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_NAME, $name);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_LONGITUDE, $longitude);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACE_LATITUDE, $latitude);

		$result = $addBh->chase(true);
		return $result;
	}
	/**
	 * 
	 * 删除景点
	 * @param string $placeId
	 * @return struct	array('STATUS'=>status)
	 */
	public static function deletePlace($placeId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEPLACE);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEPLACE_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEPLACE_PLACEID , $placeId);
						
		$result = $deleteBh->chase(true);
		
		return $result;
	}
	
	/**
	 * 
	 * 添加景点标签
	 * @param string $placeId
	 * @param string $tag
	 * @return struct	array('STATUS'=>status)
	 */
	public static function addPlaceTag($placeId , $tag)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDPLACETAG);

		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACETAG_USERID, $usrId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACETAG_PLACEID, $placeId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPLACETAG_TAG, $tag);
		
		$result = $addBh->chase(true);
		
		return $result;
	}
	
	/**
	 * 
	 * 驴友认同景点的标签
	 * @param string $placeTagId
	 * @return struct	array('STATUS'=>status)
	 */
	public static function agreeTag($placeTagId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$agreeBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BAGREETAG);
		
		$agreeBh->setProperty(Models_Behavior_PBehaviorEnum::PAGREETAG_USERID, $usrId);
		$agreeBh->setProperty(Models_Behavior_PBehaviorEnum::PAGREETAG_PLACETAGID, $placeTagId);
	
		$result = $agreeBh->chase(true);
	
		return $result;
	}
}