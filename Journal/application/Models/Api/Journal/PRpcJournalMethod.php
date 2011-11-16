<?php
class Models_Api_Journal_PRpcJournalMethod
{
	/**
	 * 添加游记中的景点内容
	 * @param string $journalPlaceId
	 * @param string $infoText
	 * @param float $latitude
	 * @param float $longitude
	 * @return struct	array('STATUS'=>int , 'DATA'=>array('INFOID'=>infoId))
	 */
	public static function addJournalPlaceInfo($journalPlaceId , $infoText , $latitude = null , $longitude = null)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDJOURNALPLACEINFO);
		
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_USERID , $usrId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_JOURNALPLACEID, $journalPlaceId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_INFOTEXT , $infoText);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_LATITUDE, $latitude);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDJOURNALPLACEINFO_LONGITUDE , $longitude);
		
		$result = $addBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 添加照片到游记的景点内容中
	 * @param string $journalInfoId		景点内容的id
	 * @param string $photoTitle
	 * @param string $photoContent
	 * @return struct	array('STATUS'=>int , 'DATA'=>array('PHOTOID'=>photoId))
	 */
	public static function addPhotoToJournalInfo($journalInfoId , $photoTitle , $photoContent)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return  array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDPHOTOTOJOURNALINFO);
		
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_USERID , $usrId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_INFOID , $journalInfoId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_PHOTOTITLE, $photoTitle);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDPHOTOTOJOURNALINFO_PHOTO, $photoContent);
		
		$result = $addBh->chase(true);

		return $result;
	}
	/**
	 * 
	 * 评论游记 
	 * @param string $journalId
	 * @param string $commentText
	 * @return struct	array('STATUS'=>int)
	 */
	public static function commentJournal($journalId , $commentText)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return  array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$commentBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BCOMMENTJOURNAL);

		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_USERID , $usrId);
		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_JOURNALID, $journalId);
		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNAL_COMMENT , $commentText);

		$result = $commentBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 评论游记景点
	 * @param string $journalPlaceId
	 * @param string $commentText
	 * @return struct	array('STATUS'=>int)
	 */
	public static function commentJournalPlace($journalPlaceId , $commentText)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}	
	
		$commentBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BCOMMENTJOURNALPLACE);
		
		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_USERID, $usrId);
		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_JOURLANPLACEID, $journalPlaceId);
		$commentBh->setProperty(Models_Behavior_PBehaviorEnum::PCOMMENTJOURNALPLACE_COMMENT, $commentText);
	
		$result = $commentBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 创建新的游记
	 * @param string $title
	 * @param boolean $isPrivate
	 * @return struct	array('STATUS'=>int , 'DATA'=>array('JOURNALID'=>journalId))
	 */
	public static function createJournal($title , $isPrivate = false)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$createBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BCREATEJOURNAL);
		
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_USERID , $usrId);
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_TITLE , $title);	
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNAL_ISPRIVATE , $isPrivate);
		
		$result = $createBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 创建新的游记景点
	 * @param string $journalId
	 * @param string $place		景点名称或者景点id
	 * @return struct	array('STATUS'=>int , 'DATA'=>array('JOURNALPLACEID'=>journalPlaceId))
	 */
	public static function createJournalPlace($journalId , $place)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$createBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BCREATEJOURNALPLACE);
		
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_USERID, $usrId);
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_JOURNALID, $journalId);
		$createBh->setProperty(Models_Behavior_PBehaviorEnum::PCREATEJOURNALPLACE_PLACE, $place);
		
		$result = $createBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 删除游记
	 * @param string $journalId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteJournal($journalId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEJOURNAL);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNAL_USERID, $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNAL_JOURNALID, $journalId);
		
		$result = $deleteBh->chase(true);

		return $result;
		
	}
	/**
	 * 
	 * 删除游记景点
	 * @param string $journalPlaceId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteJournalPlace($journalPlaceId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEJOURNALPLACE);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACE_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACE_JOURNALPLACEID, $journalPlaceId);
	
		$result = $deleteBh->chase(true);

		return $result;
	}
	/**
	 * 
	 * 删除游记内容
	 * @param string $infoId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteJournalPlaceInfo($infoId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEJOURNALPLACEINFO);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACEINFO_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACEINFO_INFOID , $infoId);
		
		$result = $deleteBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 删除照片
	 * @param string $photoId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteInfoPhoto($photoId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEINFOPHOTO);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEINFOPHOTO_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEINFOPHOTO_PHOTOID, $photoId);
		
		$result = $deleteBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 删除游记评论
	 * @param string $commentId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteJournalComment($commentId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEJOURNALCOMMENT);

		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALCOMMENT_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALCOMMENT_COMMENTID, $commentId);

		$result = $deleteBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 删除游记景点的评论
	 * @param string $commentId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function deleteJournalPlaceComment($commentId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEJOURNALPLACECOMMENT);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACECOMMENT_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEJOURNALPLACECOMMENT_COMMENTID, $commentId);

		$result = $deleteBh->chase(true);
		
		return $result;
	}

	/**
	 * 
	 * 收藏游记
	 * @param string $journalId
	 * @param string $comment	收藏游记时留下的评语
	 * @return struct	array('STATUS'=>int)
	 */
	public static function favouriteJournal($journalId , $comment = null)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$favBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BFAVOURITEJOURNAL);
		
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_USERID , $usrId);
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_JOURNALID , $journalId);
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNAL_COMMENT, $comment);
		
		$result = $favBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 收藏游记景点
	 * @param string $journalPlaceId
	 * @param string $comment	收藏游记景点的时候留下的评语
	 * @return struct	array('STATUS'=>int)
	 */
	public static function favouriteJournalPlace($journalPlaceId , $comment = null)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$favBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BFAVOURITEJOURNALPLACE);
		
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_USERID, $usrId);
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_JOURNALPLACEID, $journalPlaceId);
		$favBh->setProperty(Models_Behavior_PBehaviorEnum::PFAVOURITEJOURNALPLACE_COMMENT, $comment);
		
		$result = $favBh->chase(true);

		return $result;
	}
	
	/**
	 * 
	 * 取消收藏游记
	 * @param string $journalId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function unFavouriteJournal($journalId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$unFavBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BUNFAVOURITEJOURNAL);
		
		$unFavBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNAL_USERID, $usrId);
		$unFavBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNAL_JOURNALID, $journalId);
		
		$result = $unFavBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 取消收藏游记景点
	 * @param string $journalPlaceId
	 * @return struct	array('STATUS'=>int)
	 */
	public static function unFavouriteJornalPlace($journalPlaceId)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$unFavBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BUNFAVOURITEJOURNALPLACE);
		
		$unFavBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNALPLACE_USERID, $usrId);
		$unFavBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFAVOURITEJOURNALPLACE_JOURNALPLACEID, $journalPlaceId);
		
		$result = $unFavBh->chase(true);
		
		return $result;
	}
	
	/**
	 * 
	 * 设置游记的开放属性（公开游记、私人游记）
	 * @param string $journalId
	 * @param boolean $isPrivate
	 * @return struct	array('STATUS'=>int)
	 */
	public static function setJournalPrivacy($journalId , $isPrivate)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$setBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BSETJOURNALPRIVACY);

		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_USERID , $usrId);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_JOURNALID , $journalId);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETJOURNALPRIVACY_ISPRIVATE , $isPrivate);
		
		$result = $setBh->chase(true);
		
		return $result;
	}
}