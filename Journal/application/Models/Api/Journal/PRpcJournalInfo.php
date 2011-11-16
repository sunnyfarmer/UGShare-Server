<?php
class Models_Api_Journal_PRpcJournalInfo
{
	/**
	 * 
	 * 获取游记的信息(标题、创建时间、更新时间、旅游id、驴友名、评分、点评人数、是否私有)
	 * @param string $journalId
	 * @return struct	array('STATUS'=>status ,'DATA'=>array('TITLE'=>title , 'TIME'=>time , 'UPDATETIME'=>updateTime , 'USERID'=>userId , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount , 'ISPRIVATE'=>boolean))
	 */
	public static function getJournalInfo($journalId)
	{	
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();	
		
		$result = Models_Data_PJournalManager::getJournalInfo($usrId , $journalId);
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记的景点列表（游记景点id、景点id、景点名、评分、点评人数、内容数量）
	 * @param string $journalId
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status ,
	 * 						'DATA'=>array(
	 * 							array(
	 * 								'JOURNALPLACEID'=>journalPlaceId , 
	 * 								'PLACEID'=>placeId , 
	 * 								'PLACENAME'=>placeName ,
	 * 								'CREATETIME'=>createTime ,
	 * 								'UPDATETIME'=>updateTime ,
	 * 								'SCORE'=>score , 
	 * 								'MARKCOUNT'=>markCount , 
	 * 								'INFOCOUNT'=>infoCount
	 * 							) , 
	 * 							array(	
	 * 								'JOURNALPLACEID'=>journalPlaceId , 
	 * 								'PLACEID'=>placeId ,  
	 * 								'PLACENAME'=>placeName , 
	 * 								'CREATETIME'=>createTime ,
	 * 								'UPDATETIME'=>updateTime ,
	 * 								'SCORE'=>score , 
	 * 								'MARKCOUNT'=>markCount , 
	 * 								'INFOCOUNT'=>infoCount
	 * 							)...
	 * 						)
	 * 					)
	 */
	public static function getJournalPlaces($journalId , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();	
		
		$result = Models_Data_PJournalManager::getJournalPlaces($usrId , $journalId, $beginIndex, $rowCount);
		
		return $result;	
	}

	/**
	 * 
	 * 获取游记景点的内容（内容id、内容文字、内容创建时间、、纬度、经度、图片数量、图片url数组）
	 * @param string $journalPlaceId
	 * @param int $beginIndex
	 * @param int $rowCount 
	 * @return struct	array(
	 * 						'STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array(
	 * 								'INFOID'=>infoId , 
	 * 								'CONTENT'=>content , 
	 * 								'TIME'=>time , 
	 * 								'LATITUDE'=>latitude , 
	 * 								'LONGITUDE'=>longitude, 
	 * 								'PHOTOCOUNT'=>photoCount , 
	 * 								array(
	 * 									'PHOTOID'=>photoId , 
	 * 									'PHOTOTITLE'=>photoTitle , 
	 * 									'PHOTOURL'=>smallPhotoUrl
	 * 								), 
	 * 								array(
	 * 									'PHOTOID'=>photoId , 
	 * 									'PHOTOTITLE'=>photoTitle , 
	 * 									'PHOTOURL'=>smallPhotoUrl
	 * 								),
	 * 								...
	 * 							) , 
	 * 							... 
	 * 					)
	 */
	public static function getJournalPlaceInfo($journalPlaceId , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();	
		
		$result = Models_Data_PJournalManager::getJournalPlaceInfo($usrId , $journalPlaceId, $beginIndex, $rowCount);
		
		return $result;
	}
	/**
	 * 
	 * 获取游记景点内容的图片
	 * @param string $journalPlaceInfoId
	 * @param int $beginIndex
	 * @param int $rowCount 
	 * @return struct	array('STATUS'=>status ,
	 * 						'DATA'=>array(
	 * 							array('PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl) , 
	 * 							array('PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl) , 
	 * 							...
	 *						)
	 */
	public static function getJournalInfoPhoto($journalPlaceInfoId , $beginIndex, $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournalInfoPhoto($usrId, $journalPlaceInfoId, $beginIndex, $rowCount);

		return $result;
	}
	/**
	 * 
	 * 获取游记列表（游记标题、游记更新时间、游记图片数量、游记评分、游记点评人数）
	 * @param string $requestUserId		如果为空，那么返回当前登录用户的游记列表
	 * @param int $beginIndex
	 * @param int $rowCount 	
	 * @return	struct	array(
	 * 						'STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array(
	 * 								'JOURNALID'=>journald , 
	 * 								'TITLE'=>title ,
	 * 								'TIME'=>time, 
	 * 								'UPDATETIME'=>updateTime , 
	 * 								'PHOTOCOUNT'=>photoCount ,
	 * 								'PLACECOUNT'=>placeCount ,
	 * 								'CITYCOUNT'=>cityCount , 
	 *  							'SCORE'=>score , 
	 *  							'MARKCOUNT'=>markCount
	 *  						),
	 *  						array(
	 *  							'JOURNALID'=>journald , 
	 *  							'TITLE'=>title ,
	 *  							'TIME'=>time, 
	 * 	 							'UPDATETIME'=>updateTime , 
	 *  							'PHOTOCOUNT'=>photoCount , 
	 * 								'PLACECOUNT'=>placeCount ,
	 * 								'CITYCOUNT'=>cityCount , 
	 *  							'SCORE'=>score , 
	 *  							'MARKCOUNT'=>markCount
	 *  						), 
	 *  						...
	 *  					)
	 *  				)
	 */
	public static function getJournal($requestUserId,$beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournal($usrId, $requestUserId , $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 获取用户收藏的游记列表 （收藏游记时的评论、游记id、游记标题、用户id、用户名、收藏游记的时间、游记图片数量、游记评分、游记点评人数）
	 * @param string $requestUsrId	收藏游记列表所属的用户id，如果为空，那么获得发出请求用户的收藏列表
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct	array('STATUS'=>status ,
	 * 						'DATA'=>array(
	 * 							array('COMMENT'=>comment,'JOURNALID'=>journalId , 'TITLE'=>title , 'USERID'=>userId, 'USERNAME'=>username , 'TIME'=>time , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTIME, 'PHOTOCOUNT'=>photoCount , 'SCore'=>score , 'MARKCOUNT'=>markCount),
	 * 							array('COMMENT'=>comment,'JOURNALID'=>journalId , 'TITLE'=>title , 'USERID'=>userId, 'USERNAME'=>username , 'TIME'=>time , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTIME, 'PHOTOCOUNT'=>photoCount , 'SCore'=>score , 'MARKCOUNT'=>markCount), 
	 * 							...
	 * 						)
	 * 					)
	 */
	public static function getFavouriteJournal($requestUsrId,$beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getFavouriteJournal($usrId,$requestUsrId, $beginIndex, $rowCount);

		return $result;
	}
	
	/**
	 * 
	 * 获取用户收藏的游记景点的列表（收藏游记景点时的评论、游记景点的id、收藏的时间、景点的id、景点的名称、用户的id、用户名、游记景点的照片数量、游记景点的评分、游记景点的评分人数）
	 * @param string $requestUsrId	收藏游记景点列表所属的用户id
	 * @param int $beginIndex	
	 * @param int $rowCount						
	 * @return struct	array('STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array('COMMENT'=>comment,'JOURNALPLACEID'=>journalPlaceId , 'TIME'=>time , 'PLACEID'=>placeId , 'PLACENAME'=placeName , 'USERID'=>userId,'USERNAME'=>username , 'PHOTOCOUNT'=>photoCount, 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime, 'SCORE'=>score, 'MARKCOUNT'=>markCount),
	 * 							array('COMMENT'=>comment,'JOURNALPLACEID'=>journalPlaceId , 'TIME'=>time , 'PLACEID'=>placeId , 'PLACENAME'=placeName , 'USERID'=>userId,'USERNAME'=>username , 'PHOTOCOUNT'=>photoCount, 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime, 'SCORE'=>score, 'MARKCOUNT'=>markCount), 
	 * 							...
	 * 						)
	 * 					)
	 */
	public static  function getFavouriteJournalPlace($requestUsrId , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getFavouriteJournalPlace($usrId,$requestUsrId, $beginIndex, $rowCount);

		return $result;
	}
	
	/**
	 * 
	 * 获取驴友的动态（包括最新发布的游记、最新收藏的游记和游记景点）（唯一id、用户id、Movement类型、用户名、Movement_id、游记id、游记标题、动态时间、关键字）
	 * 动态类型：1 创建游记、2 添加游记景点、3 添加游记内容，4 收藏游记、5 收藏游记景点
	 * 当动态类型为：1 创建游记，那么关键字为null（暂时保留）；动态的id为游记的id
	 * 				2 添加游记景点，那么关键字为景点名字；动态的id为游记的id
	 * 				3 添加游记内容，那么关键字为景点名字；动态的id为游记景点的id
	 * 				4 收藏游记，那么关键字为 游记评分；动态的id为游记的id
	 * 				5 收藏游记景点，那么关键字为景点名字；动态的id为游记景点的id
	 * @param boolean $bObliged		如果为true，那么当用户的所有驴友都没有动态的时候，返回其他用户的热门动态；如果为false，那么当用户的所有驴友都没有动态的时候，返回空
	 * @param int $rBeginIndex		这次请求的记录在预请求中的offset
	 * @param int $rRowCount		这次请求的记录行数
	 * @param boolean $bRefresh		是否要刷新
	 * @param int $beginIndex		这次预请求的记录offset
	 * @param int $rowCount			这次预请求的记录行数
	 * @return struct	array('STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array('_ID'=>id , 'USERID'=>userid , 'MOVEMENTTYPE'=>movementType , 'USERNAME'=>username , 'MOVEMENTID'=>movementId ,'JOURNALID'=>journalId , 'JOURNALTITLE'=>journalTitle ,'TIME'=>time, 'KEYWORD'=>keyWord) ,
	 * 	 						array('_ID'=>id , 'USERID'=>userid , 'MOVEMENTTYPE'=>movementType , 'USERNAME'=>username , 'MOVEMENTID'=>movementId ,'JOURNALID'=>journalId , 'JOURNALTITLE'=>journalTitle ,'TIME'=>time, 'KEYWORD'=>keyWord) ,
	 * 							...
	 * 						)
	 * 					)
	 */
	public static function getFriendMovement($bObliged , $rBeginIndex , $rRowCount, $bRefresh = false ,  $beginIndex = null , $rowCount = null)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getFriendMovement($usrId, $bObliged, $rBeginIndex, $rRowCount , $bRefresh , $beginIndex , $rowCount);
		
		return $result;
	}
		
	/**
	 * 
	 * 以城市为目的地搜索游记（游记id、游记标题、游记更新时间、用户id、用户名、评分、点评人数）
	 * @param string $city|int		
	 * @param int $beginIndex
	 * @param int $rowCount						
	 * @return struct 	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('JOURNALID'=>journalId , 'JOURNALTITLE'=>title , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime , 'USERID'=>userid , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount) , 
	 * 							array('JOURNALID'=>journalId , 'JOURNALTITLE'=>title , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime, 'USERID'=>userid , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount) , 
	 * 						...
	 * 						)	
	 * 					)
	 */
	public static function getJournalByCity($city , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournalByCity($usrId, $city , $beginIndex , $rowCount);

		return $result;
	}

	/**
	 * 
	 * 通过景点搜索游记景点内容（游记id、游记标题、驴友id、用户名、更新时间、评分、点评人数）
	 * @param string $place	景点id 或者 景点名称
	 * @param int $beginIndex
	 * @param int $rowCount							
	 * @return struct	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('JOURNALID'=>journalId,'JOURNALTITLE'=>journalTitle,'JOURNALPLACEID'=>journalPlaceId , 'USEID'=>userid , 'USERNAME'=>usernme , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime, 'SCORE'=>score' , 'MARKCOUNT'=>markCount) , 
	 * 							array('JOURNALID'=>journalId,'JOURNALTITLE'=>journalTitle,'JOURNALPLACEID'=>journalPlaceId , 'USEID'=>userid , 'USERNAME'=>usernme , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime, 'SCORE'=>score' , 'MARKCOUNT'=>markCount) , 
	 * 							...
	 * 						)
	 * 					)
	 */
	public static function getJournalByPlace($place , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournalByPlace($usrId, $place, $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 通过用户的历史，返回特色游记景点内容（用户id、用户名、内容id、内容文字、内容创建时间、图片数量、图片数组）
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @param int $loopRange	在排名为loopRange以内的游记景点中，随即抽取
	 * @return struct	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							'USERID'=>userid , 
	 * 							'USERNAME'=>username , 
	 * 							array('INFOID'=infoId ,'CONTENT'=>content , 'TIME'=>time , 'PHOTOCOUNT'=>photoCount , array('PHOTOID'=>photiId , 'PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl) , array('PHOTOID'=>photiId , 'PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl),... ),
	 * 							array('INFOID'=infoId ,'CONTENT'=>content , 'TIME'=>time , 'PHOTOCOUNT'=>photoCount , array('PHOTOID'=>photiId , 'PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl) , array('PHOTOID'=>photiId , 'PHOTOTITLE'=>photoTitle , 'PHOTOURL'=>smallPhotoUrl),... ), 
	 * 							... 
	 * 						)
	 * 					)
	 */
	public static function getSpecialJournal($beginIndex , $rowCount , $loopRange= 100)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getSpecialJournal($usrId , $beginIndex , $rowCount,$loopRange);
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记的评论，返回结构体（状态，array（用户id、用户名、评论id、评论、评论时间））
	 * @param string $journalId	
	 * @param int $beginIndex
	 * @param int $rowCount						
	 * @return struct 	array('STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 							...
	 *							)
	 *				 	 )
	 */
	public static function getJournalComment($journalId , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournalComment($usrId, $journalId, $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记景点的评论， 返回结构体（状态，array（用户id、用户名、评论id、评论、评论时间））
	 * @param string $journalPlaceId
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct 	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 						...
	 * 						)
	 * 					)
	 */
	public static function getJournalPlaceComment($journalPlaceId , $beginIndex , $rowCount)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		
		$result = Models_Data_PJournalManager::getJournalPlaceComment($usrId, $journalPlaceId, $beginIndex, $rowCount);

		return $result;
	}
	
	/**
	 * 
	 * 获取游记中城市（按照时间顺序） ， 返回结果（状态码，城市id、城市名、在该城市进入的景点数量，景点的id数组）
	 * @param string|int|long $journalId			游记的id
	 * @param int $beginIndex
	 * @param int $rowCount			如果rowCount为-1，那么返回所有城市
	 * @return struct	array(
	 * 						'STATUS'=>status , 
	 * 						'DATA'=>array(
	 * 							array(
	 * 								'CITYID'=>cityId ,
	 * 	 							'CITYNAME'=>cityName , 
	 * 								'PLACECOUNT'=>placeCount , 
	 * 								'PLACEARR'=>array(
	 * 									'JOURNALPLACEID'=>placeId , 
	 * 									'JOURNALPLACEID'=>placeId , 
	 * 									...
	 *								)
	 *							),
	 *							...
	 *						)
	 *					)
	 */
	public static function getJournalCitys($journalId , $beginIndex , $rowCount) {
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$result = Models_Data_PJournalManager::getJournalCitys($usrId, $journalId, $beginIndex, $rowCount);
		
		return $result;
	}
	
	/**
	 * 
	 * 获得用户最新修改的游记
	 * @return struct	array(
	 * 						'STATUS'=>status,
	 * 						'DATA'=>array(
	 * 							'JOURNALID'=>journalId
	 * 						)
	 * 					)
	 */
	public static function getLatestJournal() {
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId) {
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$result = Models_Data_PJournalManager::getLatestJournal($usrId);
		
		return $result;
	}
	
	/**
	 * 
	 * 获得图片的链接地址
	 * @param string $photoId
	 * @param int $photoVersion	
	 * 			ORIGINAL_VERSION	0
	 * 			PC_VERSION 			1
	 * 			MOBILE_VERSION		2
	 * 			SMALL_VERSION		3
	 * @return struct	array(
	 * 						'STATUS'=>status,
	 * 						'DATA'=>array(
	 * 							'PHOTOID'=>photoId,
	 * 							'PHOTOTITLE'=>photoTitle,
	 * 							'PHOTOURL'=>photoUrl,
	 * 							'PHOTOVERSION'=>photoversion
	 * 						)
	 * 					)
	 */
	public static function getPhoto($photoId, $photoVersion) {
		return Models_Data_PJournalManager::getPhoto($photoId, $photoVersion);
	}
}