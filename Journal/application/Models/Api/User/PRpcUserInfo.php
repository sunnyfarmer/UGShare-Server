<?php
class Models_Api_User_PRpcUserInfo
{
	/**
	 * 
	 * 获取用户的基础信息，如 用户名username、电话号telephone、邮箱地址email、用户签名saying、关注人数followNumber、追随者人数followedNumber、位置position、
	 * @param string $requestUsrId	如果为null，那么返回当前登录用户的用户基础信息
	 * @return struct	array('STATUS'=>status , 'DATA'=>array('USERNAME'=>username , 'TELEPHONE'=>telephone , 'EMAIL'=>email , 'SAYING'=>saying ,'FOLLOWNUMBER'=>followNumber , 'FOLLOWEDNUMBER'=>followedNumber ,  'LONGITUDE'=>longitude , 'LATITUDE'=>latitude))
	 */
	public static function getUserInfo($requestUsrId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
				
		$result = Models_Data_PUserManager::getUserInfo($usrId, $requestUsrId);
	
		return $result;
	}

	/**
	 * 
	 * 获取用户大头像，不存在用户则返回获取失败的状态码
	 * @param string $requestUsrId
	 * @return struct	array('STATUS'=>status , 'DATA'=>array('AVATARURL'=>avatarUrl))
	 */
	public static function getUserBigAvatar($requestUsrId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();

		$result = Models_Data_PUserManager::getUserBigAvatar($usrId, $requestUsrId);
	
		return $result;
	}
	/**
	 * 
	 * 获取用户的位置信息（用户id ， 经度，纬度），如果没有该用户的位置信息，或者用户不公开位置信息，那么返回获取失败的状态码
	 * @param string $requestUsrId
	 * @return array	array('STATUS'=>status ,
	 * 						'DATA'=>array(
	 * 							'USERID'=>usrId , 
	 * 							'LONGITUDE'=>longitude , 
	 * 							'LATITUDE'=>latitude
	 * 						)
	 * 					)
	 */
	public static function getUserPosition($requestUsrId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$result = Models_Data_PUserManager::getUserPosition($usrId, $requestUsrId);
	
		return $result;
	}
	
	/**
	 * 
	 * 获取用户头像的缩小版，不存在用户则返回获取失败的状态码
	 * @param string $requestUsrId	头像所属用户的id
	 * @return struct	array('STATUS'=>status ,'DATA'=>array('AVATARURL'=>avatarUrl))
	 */
	public static function getUserSmallAvatar($requestUsrId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();

		$result = Models_Data_PUserManager::getUserSmallAvatar($usrId, $requestUsrId);
	
		return $result;
	}
	/**
	 * 
	 * 获取周边用户列表（暂时是所有旅游的列表）
	 * @param float $longitude
	 * @param float $latitude
	 * @param float $distance
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('USERID'=>usrid , 'USERNAME'=>username) , 
	 * 							array('USERID'=>usrid , 'USERNAME'=>username) , 
	 * 							....
	 * 						)
	 * 					)	
	 */
	public static function getCloseUser($longitude = null, $latitude = null, $distance = null , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$result = Models_Data_PUserManager::getCloseUser($usrId , $distance , $longitude , $latitude , $beginIndex , $rowCount);
	
		return $result;
	}
}