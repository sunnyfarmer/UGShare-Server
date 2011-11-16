<?php
class Models_Data_PUserManager
{
	/**
	 * 
	 * 获取周边用户列表（暂时是所有旅游的列表）
	 * @param string $usrId
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
	public static function getCloseUser($usrId , $distance = null , $longitude = null , $latitude = null , $beginIndex = null , $rowCount = null)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$result = null;

		if (is_null($longitude) || is_null($latitude) || is_null($distance) || is_null($beginIndex) || is_null($rowCount))
		{
			return array('STATUS' => intval(Models_Core::STATE_DATA_GETCLOSEUSER_MISS_PARAMETER));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		//算出该距离的bounding
		$box = Models_CommonAction_PMath::boundOfLatLng($latitude, $longitude, $distance);
		$leftLon = $box[0];
		$rightLon = $box[1];
		$topLat = $box[2];
		$bottomLat = $box[3];
		
		$query = Doctrine_Query::create()
				->select('u.id , u.username')
				->from('TrUser u')
				->leftJoin('u.TrUserLocationInfo uli')
				->where('uli.isOpen = 1')
				->andWhere('uli.usr_id_ref != ?' , $usrId)
				->offset($beginIndex)
				->limit($rowCount);
				
		//考虑经度超过180度的可能
		if ($leftLon > $rightLon) 
		{
		    $query = $query->andWhere('uli.longitude >= ? or uli.longitude <= ?', array($leftLon, $rightLon));
		} 
		else 
		{
		    $query = $query->andWhere('uli.longitude >= ? and uli.longitude <= ?', array($leftLon, $rightLon));
		}
		//不考虑纬度到达90度或者-90度的情况
		$query = $query->andWhere('uli.latitude >= ? and uli.latitude <= ?', array($bottomLat, $topLat));
					
		$users = $query->execute();
		
		if (count($users))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($users as $user)
			{
				$targetUserId = $user->id;
				$usrName = $user->username;
				
				$singleUser['USERID'] = $targetUserId;
				$singleUser['USERNAME'] = $usrName;
				
				array_push($result['DATA'], $singleUser);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETCLOSEUSER_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取用户大头像，不存在用户则返回获取失败的状态码
	 * @param string $curUserId
	 * @param string $requestUsrId
	 * @return struct	array('STATUS'=>status , 'DATA'=>array('AVATARURL'=>avatarUrl))
	 */
	public static function getUserBigAvatar($curUserId, $requestUsrId)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('u.id , a.id , a.origin')
				->from('TrAvatar a')
				->leftJoin('a.TrUser u')
				->where('u.id = ?' , $requestUsrId);
		
		$userAvatar = $query->fetchOne();
		
		if ($userAvatar)
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			
			$avatarId = $userAvatar->id;
			$avatar = $userAvatar->origin;
			
			$avatarUrl = Models_CommonAction_PAvatar::saveToTemp($avatarId, $avatar, Models_CommonAction_PAvatar::BIG_VERSION);
			
			$result['DATA']['AVATARURL'] = $avatarUrl;
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETUSERBIGAVATAR_USERID_INVALID);
		}
		return $result;
	}
	/**
	 * 
	 * @param string $curUserId	发出请求的用户的id
	 * 获取用户头像的缩小版，不存在用户则返回获取失败的状态码
	 * @param string $requestUsrId	头像所属用户的id
	 * @return struct	array('STATUS'=>status ,'DATA'=>array('AVATARURL'=>avatarUrl))
	 */
	public static function getUserSmallAvatar($curUserId , $requestUsrId)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$result = null;
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('u.id , a.id , a.small')
				->from('TrAvatar a')
				->leftJoin('a.TrUser u')
				->where('u.id = ?' , $requestUsrId);
		
		$userAvatar = $query->fetchOne();
		
		if ($userAvatar)
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			
			$avatarId = $userAvatar->id;
			$avatar = $userAvatar->small;
			
			$avatarUrl = Models_CommonAction_PAvatar::saveToTemp($avatarId, $avatar , Models_CommonAction_PAvatar::SMALL_VERSION);
			
			$result['DATA']['AVATAR'] = $avatarUrl;
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETUSERSMALLAVATAR_USERID_INVALID);
		}
		
		return $result;
	}
	/**
	 * 
	 * 获取用户的基础信息，如 用户名username、电话号telephone、邮箱地址email、用户签名saying、关注人数followNumber、追随者人数followedNumber、位置position
	 * @param string $usrId
	 * @param string $requestUsrId	
	 * @return struct	array('STATUS'=>status ,'USERNAME'=>username , 'TELEPHONE'=>telephone , 'EMAIL'=>email , 'SAYING'=>saying 'FOLLOWNUMBER'=>followNumber , 'FOLLOWEDNUMBER'=>followedNumber ,  'LONGITUDE'=>longitude , 'LATITUDE'=>latitude)
	 */
	public static function getUserInfo($usrId , $requestUsrId)
	{
		if (!$usrId)
		{//如果当前用户没有登录，直接返回
			return array('STATUS' => intval(Models_Core::STATE_DATA_GETUSERINFO_REQUESTUSERID_NULL));
		}
		if (! $requestUsrId) 
		{//如果当前用户登录了，但是没有提供请求用户的id，那么默认返回当前登录用户的基础信息
			$requestUsrId = $usrId;	
		}
		
		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('u.id , u.username , u.telephone , u.followNumber, u.followedNumber, uli.longitude , uli.latitude, e.id , e.address')
				->from('TrUser u')
				->leftJoin('u.TrEmail e')
				->leftJoin('u.TrUserLocationInfo uli')
				->where('u.id = ?' , $requestUsrId);
		
		$user = $query->fetchOne();
		
		if ($user)
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
		
			$result['USERNAME'] = $user->username; 
			$result['TELEPHONE'] = $user->telephone; 
			$result['EMAIL'] = $user->TrEmail[0]->address; 
			$result['FOLLOWNUMBER'] = intval($user->followNumber);
			$result['FOLLOWEDNUMBER'] = intval($user->followedNumber);
			$result['LONGITUDE'] = floatval($user->TrUserLocationInfo[0]->longitude);
			$result['LATITUDE'] = floatval($user->TrUserLocationInfo[0]->latitude);
		
			$query = Doctrine_Query::create()
					->select('s.id , s.saying')
					->from('TrSaying s')
					->where('s.usr_id_ref = ?' , $requestUsrId)
					->orderBy('s.time desc');
		
			$saying = $query->fetchOne();
			
			$result['SAYING'] = $saying->saying;
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETUSERINFO_USERID_INVALID);
		}
		
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
	public static function getUserPosition($curUserId , $usrId)
	{
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETUSERPOSITION_REQUESTUSERID_NULL));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$user = Doctrine_Query::create()
				->select('u.id , l.longitude , l.latitude')
				->from('TrUser u')
				->leftJoin('u.TrUserLocationInfo l')
				->where('u.id = ?' , $usrId)
				->andWhere('u.id = l.usr_id_ref');
		if ($curUserId != $usrId)
		{
			$user = $user->andWhere('l.isOpen = 1');
		}
		$user->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		
		if ($user->count())
		{
			$usrPos = $user->fetchOne();
			$longitude = floatval($usrPos['TrUserLocationInfo'][0]['longitude']);
			$latitude = floatval($usrPos['TrUserLocationInfo'][0]['latitude']);
			
			$pos = array(
					'STATUS'=>intval(Models_Core::STATE_REQUEST_SUCCESS) ,
					'DATA'=>array(
						'USERID'=>$usrId , 
						'LONGITUDE'=>$longitude , 
						'LATITUDE'=>$latitude
					)
					);

			return $pos;
		}
		else
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETUSERPOSITION_USER_POSITION_SECRET));
		}
	}
}