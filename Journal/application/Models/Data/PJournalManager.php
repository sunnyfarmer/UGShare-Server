<?php
class Models_Data_PJournalManager
{
	/**
	 * 
	 * 获取游记的信息(标题、创建时间、更新时间、旅游id、驴友名、评分、点评人数、是否私有)
	 * @param string $usrId
	 * @param string $journalId
	 * @return struct	array('STATUS'=>status ,'DATA'=>array('TITLE'=>title , 'TIME'=>time , 'UPDATETIME'=>updateTime , 'USERID'=>userId , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount , 'ISPRIVATE'=>boolean))
	 */
	public static function getJournalInfo($usrId , $journalId)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		
		$result = array();
		$result['STATUS'] = null;
		
		$conn = Models_Core::getDoctrineConn();
		//不能得到其他驴友的私人游记
		$query = Doctrine_Query::create()
				->select('j.id , j.title ,j.time , j.updateTime , j.score , j.markCount , j.isPrivate , u.id , u.username')
				->from('TrJournal j')
				->leftJoin('j.TrUser u')
				->where('j.id = ?' , $journalId)
				->andWhere('j.usr_id_ref = ? or j.isPrivate = 0' , $usrId);
		$journal = $query->fetchOne();
        
		if ($journal)
		{
			$result['DATA']['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA']['TITLE'] = $journal->title;
			$result['DATA']['TIME'] = new DateTime($journal->time);
			$result['DATA']['UPDATETIME'] = new DateTime($journal->updateTime);
			$result['DATA']['USERID'] = $journal->TrUser-id;
			$result['DATA']['USERNAME'] = $journal->TrUser->username;
			$result['DATA']['SCORE'] = floatval($journal->score);
			$result['DATA']['MARKCOUNT'] = intval($journal->markCount);
			$result['DATA']['ISPRIATE'] = intval($journal->isPrivate);
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALINFO_NO_PERMISSIONS);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取用户收藏的游记列表 （收藏游记时的评论、游记id、游记标题、用户id、用户名、收藏游记的时间、游记图片数量、游记评分、游记点评人数）
	 * @param string $usrId			发出请求的用户的id
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
	public static function getFavouriteJournal($usrId ,$requestUsrId, $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (!$requestUsrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETFAVOURITEJOURNAL_REQUESTUSRID_NULL));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('jfl.id, j.id, j.title, j.photoCount, u.id, u.username, jfl.time, j.time, j.updateTime, j.score, j.markCount')
				->from('TrJournalFavouriteList jfl')
				->leftJoin('jfl.TrJournal j')
				->leftJoin('j.TrUser u')
				->where('jfl.usr_id_ref = ?' , $requestUsrId)
				->andWhere('j.isPrivate = 0 or j.usr_id_ref = ?' , $usrId)
				->offset($beginIndex)
				->limit($rowCount)
				->orderBy('jfl.time desc')
				;
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $jfl)
			{
				$singleJFL['COMMENT'] = $jfl->comment;
				$singleJFL['JOURNALID'] = $jfl->TrJournal->id;
				$singleJFL['TITLE'] = $jfl->TrJournal->title;
				$singleJFL['USERID'] = $jfl->TrJournal->TrUser->id;
				$singleJFL['USERNAME'] = $jfl->TrJournal->TrUser->username;
				$singleJFL['TIME'] = new DateTime($jfl->time);
				$singleJFL['CREATETIME'] = new DateTime($jfl->TrJournal->time);
				$singleJFL['UPDATETIME'] = new DateTime($jfl->TrJournal->time);
				$singleJFL['PHOTOCOUNT'] = intval($jfl->TrJournal->photoCount);
				$singleJFL['SCore'] = floatval($jfl->TrJournal->score);
				$singleJFL['MARKCOUNT'] = intval($jfl->TrJournal->markCount);
				
				array_push($result['DATA'], $singleJFL);
			}
			
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETFAVOURITEJOURNAL_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取用户收藏的游记景点的列表（收藏游记景点时的评论、游记景点的id、收藏的时间、景点的id、景点的名称、用户的id、用户名、游记景点的照片数量、游记景点的评分、游记景点的评分人数）
	 * @param string $usrId
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
	public static function getFavouriteJournalPlace($usrId ,$requestUsrId ,  $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		if (!$requestUsrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETFAVOURITEJOURNALPLACE_REQUESTUSRID_NULL));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('jpfl.id, jpfl.comment, j.id, jpfl.time, p.id, p.name, u.id, u.username, jp.photoCount, jp.time, jp.updateTime, jp.score, jp.markCount')
				->from('TrJournalPlaceFavouriteList jpfl')
				->leftJoin('jpfl.TrJournalPlace jp')
				->leftJoin('jp.TrPlace p')
				->leftJoin('jp.TrJournal j')
				->leftJoin('j.TrUser u')
				->where('jpfl.usr_id_ref = ?' , $requestUsrId)
				->andWhere('j.isPrivate = 0 or j.usr_id_ref = ?' , $usrId)
				->offset($beginIndex)
				->limit($rowCount)
				->orderBy('jpfl.time desc')
				;
				
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = Models_Core::STATE_REQUEST_SUCCESS;
			$result['DATA'] = array();
			foreach ($rs as $jpfl)
			{
				$singleJpfl['COMMENT'] = $jpfl->comment;
				$singleJpfl['JOURNALPLACEID'] = $jpfl->TrJournalPlace->id;
				$singleJpfl['TIME'] = new DateTime($jpfl->time);
				$singleJpfl['PLACEID']= $jpfl->TrJournalPlace->TrPlace->id;
				$singleJpfl['PLACENAME']= $jpfl->TrJournalPlace->TrPlace->name;
				$singleJpfl['USERID']= $jpfl->TrJournalPlace->TrJournal->usr_id_ref;
				$singleJpfl['USERNAME']= $jpfl->TrJournalPlace->TrJournal->TrUser->username; 
				$singleJpfl['PHOTOCOUNT'] = intval($jpfl->TrJournalPlace->photoCount);
				$singleJpfl['CREATETIME'] = new DateTime($jpfl->TrJournalPlace->time);
				$singleJpfl['UPDATETIME'] = new DateTime($jpfl->TrJournalPlace->updateTime);
				$singleJpfl['SCORE']= floatval($jpfl->TrJournalPlace->score); 
				$singleJpfl['MARKCOUNT']= intval($jpfl->TrJournalPlace->markCount);
				
				array_push($result['DATA'], $singleJpfl);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETFAVOURITEJOURNALPLACE_ZERO_RESULT);
		}
		
		return $result;		
	}
	
	/**
	 * 
	 * 获取游记列表（游记标题、游记更新时间、游记图片数量、游记评分、游记点评人数）
	 * @param string $usrId				请求列表的用户id
	 * @param string $requestUsrId		如果为空，那么返回当前登录用户的游记列表
	 * @param int $beginIndex			列表的开始index
	 * @param int $rowCount				列表的参数
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
	public static function getJournal($usrId ,$requestUsrId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_BEHAVIOR_ADDJOURNALPLACEINFO_MISS_PARAMETER));
		}
		else 
		{
			if (!$requestUsrId)
			{
				$requestUsrId = $usrId;
			}
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('j.id , j.title , j.updateTime , j.photoCount,j.placeCount,j.cityCount , j.score , j.markCount')
				->from('TrJournal j')
				->where('j.usr_id_ref = ?' , $requestUsrId)
				->andWhere('j.isPrivate = 0  or j.usr_id_ref = ?' , $usrId)
				->orderBy('j.updateTime desc')
				->offset($beginIndex)
				->limit($rowCount);
				
//		$query = $query
//				->leftJoin('j.TrJournalPlace jp')
//				->leftJoin('jp.TrJournalPlaceInfo jpi')
//				->leftJoin('jpi.TrPhoto photo')
//				->groupBy('j.id');
		
		//$query = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		
		$rs = $query->execute();
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $j)
			{
				$singleJournal['JOURNALID'] = $j->id; 
				$singleJournal['TITLE'] = $j->title; 
				$singleJournal['TIME'] = new DateTime($j->time);
				$singleJournal['UPDATETIME'] = new DateTime($j->updateTime);  
				$singleJournal['PHOTOCOUNT'] = intval($j->photoCount); 
				$singleJournal['PLACECOUNT'] = intval($j->placeCount);
	 			$singleJournal['CITYCOUNT'] = intval($j->cityCount); 
				$singleJournal['SCORE'] = floatval($j->score); 
				$singleJournal['MARKCOUNT'] = intval($j->markCount);
				
				array_push($result['DATA'], $singleJournal);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNAL_ZERO_RESULT);
		}

		return $result;
	}	
	
	/**
	 * 
	 * 以城市为目的地搜索游记（游记标题、游记更新时间、用户名、评分、点评人数）
	 * @param string $usrId
	 * @param string|int $city		
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return struct 	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('JOURNALID'=>journalId , 'JOURNALTITLE'=>title , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime , 'USERID'=>userid , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount) , 
	 * 							array('JOURNALID'=>journalId , 'JOURNALTITLE'=>title , 'CREATETIME'=>createTime, 'UPDATETIME'=>updateTime , 'USERID'=>userid , 'USERNAME'=>username , 'SCORE'=>score , 'MARKCOUNT'=>markCount) , 
	 * 						...
	 * 						)	
	 * 					)	 
	 */
	public static function getJournalByCity($usrId , $city , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (!$city)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETJOURNALBYCITY_CITYID_NULL));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('j.id, j.title, j.time, j.updateTime, u.id, u.username, j.score, j.markCount')
				->from('TrJournal j')
				->leftJoin('j.TrUser u')
				->leftJoin('j.TrJournalPlace jp')
				->leftJoin('jp.TrPlace p')
				->leftJoin('p.TrCity c')
				->where('c.id = ? or c.longname = ? or c.shortname =?' , array($city , $city , $city))
				->andWhere('j.isPrivate = 0 or j.usr_id_ref = ?' , $usrId)
				->offset($beginIndex)
				->limit($rowCount);
				
		$rs  = $query->execute();
		
		if (count($rs))
		{
			$result['STATUS'] = Models_Core::STATE_REQUEST_SUCCESS;
			$result['DATA'] = array();
			foreach ($rs as $j)
			{
				$singleJournal['JOURNALID'] = $j->id; 
				$singleJournal['JOURNALTITLE'] = $j->title; 
				$singleJournal['CREATETIME'] = new DateTime($j->time);
				$singleJournal['UPDATETIME'] = new DateTime($j->updateTime); 
				$singleJournal['USERID'] = $j->TrUser->id; 
				$singleJournal['USERNAME'] = $j->TrUser->username; 
				$singleJournal['SCORE'] = floatval($j->score); 
				$singleJournal['MARKCOUNT'] = intval($j->markCount);
				
				array_push($result['DATA'], $singleJournal);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALBYCITY_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 通过景点搜索游记景点内容（游记id、游记标题、驴友id、用户名、更新时间、评分、点评人数）
	 * @param string $usrId
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
	public static function getJournalByPlace($usrId , $place , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$conn= Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('j.id, j.title, jp.id, u.id, u.username, jp.time, jp.updateTime, jp.score, jp.markCount')
				->from('TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->leftJoin('j.TrUser u')
				->leftJoin('jp.TrPlace p')
				->where('j.isPrivate = 0 or j.usr_id_ref = ? ' , $usrId)
				->andWhere('p.id = ? or p.name = ?' , array($place , $place))
				->offset($beginIndex)
				->limit($rowCount)
				;
		$rs = $query->execute();
		
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($rs as $jp)
			{
				$singleJournalPlace['JOURNALID'] = $jp->TrJournal->id;
				$singleJournalPlace['JOURNALTITLE'] = $jp->TrJournal->title;
				$singleJournalPlace['JOURNALPLACEID'] = $jp->id; 
				$singleJournalPlace['USEID'] = $jp->TrJournal->TrUser->id; 
				$singleJournalPlace['USERNAME'] = $jp->TrJournal->TrUser->username;
				$singleJournalPlace['CREATETIME'] = new DateTime($jp->time);
				$singleJournalPlace['UPDATETIME'] = new DateTime($jp->updateTime);
				$singleJournalPlace['SCORE'] = floatval($jp->score); 
				$singleJournalPlace['MARKCOUNT'] = intval($jp->markCount);
				
				array_push($result['DATA'], $singleJournalPlace);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALBYPLACE_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记的评论，返回结构体（状态，array（用户id、用户名、评论id、评论、评论时间））
	 * @param string $usrId
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
	public static function getJournalComment($usrId , $journalId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (!$usrId || !$journalId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETJOURNALCOMMENT_MISS_PARAMETER));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('jc.id , jc.comment_text ,jc.time ,  u.id , u.username')
				->from('TrJournalComment jc')
				->leftJoin('jc.TrUser u')
				->orderBy('jc.time')
				->offset($beginIndex)
				->limit($rowCount);
				
		$jcArr = $query->execute();
		
		if (count($jcArr))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			foreach ($jcArr as $jc)
			{
				$singleJc['USERID'] = $jc->TrUser->id;
				$singleJc['USERNAME'] = $jc->TrUser->username; 
				$singleJc['COMMENTID'] = $jc->id; 
				$singleJc['COMMENT'] = $jc->comment_text;
				$singleJc['TIME'] = new DateTime($jc->time);
		
				array_push($result['DATA'], $singleJc);
			}
			
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALCOMMENT_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记景点的评论， 返回结构体（状态，array（用户id、用户名、评论id、评论、评论时间））
	 * @param string $usrId
	 * @param string $journalPlaceId
	 * @param string $beginIndex
	 * @param string $rowCount
	 * @return struct 	array('STATUS'=>status , 
	 * 						'DATA'=>array(	
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 							array('USERID'=>userid , 'USERNAME'=>username , 'COMMENTID'=>commentId , 'COMMENT'=>COMMENT , 'TIME'=>time),
	 * 						...
	 * 						)
	 * 					)	 
	 */
	public static function getJournalPlaceComment($usrId , $journalPlaceId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (! $usrId || ! $journalPlaceId) {
		    return array('STATUS' => intval(Models_Core::STATE_DATA_GETJOURNALPLACECOMMENT_MISS_PARAMETER));
		}
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->from('TrJournalPlaceComment jpc')
				->leftJoin('jpc.TrUser u')
				->leftJoin('jpc.TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->where('j.isPrivate = 0 or j.usr_id_ref = ?' , $usrId)
				->andWhere('jpc.jpc_id_ref = ?' , $journalPlaceId)
				->orderBy('jpc.time')
				->offset($beginIndex)
				->limit($rowCount);
		
		$jpcArr = $query->execute();
		if (count($jpcArr)) {
		    $result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
		    $result['DATA'] = array();
		    foreach ($jpcArr as $jpc) {
		        $singleJpc['USERID'] = $jpc->TrUser->id;
		        $singleJpc['USERNAME'] = $jpc->TrUser->username;
		        $singleJpc['COMMENTID'] = $jpc->id;
		        $singleJpc['COMMENT'] = $jpc->comment_text;
		        $singleJpc['TIME'] = new DateTime($jpc->time);
		        array_push($result['DATA'], $singleJpc);
		    }
		} else {
		    $result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALPLACECOMMENT_ZERO_RESULT);
		}
		return $result;
		
	}
	
	/**
	 * 
	 * 获取游记景点的内容（内容id、内容文字、内容创建时间、、纬度、经度、图片数量、图片url数组）
	 * @param string $usrId		
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
	public static function getJournalPlaceInfo($usrId , $journalPlaceId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('jpi.*')
				->from('TrJournalPlaceInfo jpi')
				->leftJoin('jpi.TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->where('jp.id = ?' , $journalPlaceId)
				->andWhere('j.usr_id_ref = ? or j.isPrivate = 0' , $usrId)
				->offset($beginIndex)
				->limit($rowCount)
				->orderBy('jpi.time desc');
		//		->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		$jpis = $query->execute();
		
		if (count($jpis))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			$jpiStr = '';
			$jpiCot = 0;
			foreach ($jpis as $jpi)
			{
				$jpiId = $jpi->id;
				$jpiOrder[$jpiId] = $jpiCot;
				
				$singleResult['INFOID'] = $jpiId;
				$singleResult['CONTENT'] = $jpi->info;
				$singleResult['TIME'] = new DateTime($jpi->time);
				$singleResult['LATITUDE'] = floatval($jpi->latitude);
				$singleResult['LONGITUDE'] = floatval($jpi->longitude);
				$singleResult['PHOTOCOUNT'] = intval($jpi->photoCount);
				
				$result['DATA'][$jpiCot] = $singleResult;
				$jpiCot++;		
			}
		
			foreach ($jpiOrder as $jpi_id_ref =>$order)
			{
				$count = $result['DATA'][$order]['PHOTOCOUNT'];
				if ($count > 0)
					{	
						$query = Doctrine_Query::create()
							->select('p.id , p.title , p.smallVersion')
							->from('TrPhoto p')
							->where('p.jpi_id_ref = ?' , $jpi_id_ref)
							->offset(0)
							->limit(3)
							->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
			
						$photoArr = $query->execute();
						foreach ($photoArr as $photo)
						{
							$photoUrl = Models_CommonAction_PPhoto::saveToTemp($photo['id'], $photo['smallVersion'], Models_CommonAction_PPhoto::SMALL_VERSION);
							array_push($result['DATA'][$order], array('PHOTOID'=>$photo['id'] , 'PHOTOTITLE'=>$photo['title'] , 'PHOTOURL'=>$photoUrl));
						}
					}
			}
			
			
		}		
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALPLACEINFO_NO_INFO);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获取游记景点内容的图片
	 * @param string $usrId
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
	public static function getJournalInfoPhoto($usrId , $journalPlaceInfoId , $beginIndex, $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		
		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('p.id , p.title , p.smallVersion')
				->from('TrPhoto p')
				->where('p.jpi_id_ref = ?' , $journalPlaceInfoId)
				->offset($beginIndex)
				->limit($rowCount)
				->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
				
		$photoArr = $query->execute();
		
		if(count($photoArr))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
		
			$photoArr = $query->execute();
			foreach ($photoArr as $photo)
			{
				$photoUrl = Models_CommonAction_PPhoto::saveToTemp($photo['id'], $photo['smallVersion'], Models_CommonAction_PPhoto::SMALL_VERSION);
				array_push(
					$result['DATA'] , 
					array(
						'PHOTOID'=>$photo['id'] , 
						'PHOTOTITLE'=>$photo['title'] , 
						'PHOTOURL'=>$photoUrl
					)
				);
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
		}
		
		return $result;
	}

	/**
	 * 
	 * 获取游记的景点列表（游记景点id、景点id、景点名、评分、点评人数、内容数量）
	 * @param string $usrId
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
	public static function getJournalPlaces($usrId , $journalId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
			->select('jp.id, p.id, p.name, jp.time, jp.updateTime, jp.score, jp.markCount, jp.infoCount')
			->from('TrJournalPlace jp')	
			->leftJoin('jp.TrJournal j')
			->leftJoin('jp.TrPlace p')
			->where('jp.jnl_id_ref = ?' , $journalId)
			->andWhere('j.usr_id_ref = ? or j.isPrivate = 0' , $usrId)
			->offset($beginIndex)
			->limit($rowCount)
			->orderBy('jp.time desc');
		$jps = $query->execute();
	
		if (count($jps))
		{	
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			$jpCot = 0;
			$jpOrder = array();
			foreach ($jps as $jp)
			{
				//得到journalplaceId，并组成jpIdStr
				$jpId = $jp->id;
				$jpOrder[$jpId] = $jpCot;
				
				$singleResult['JOURNALPLACEID'] = $jp->id;
				$singleResult['PLACEID'] = $jp->TrPlace->id;
				$singleResult['PLACENAME'] = $jp->TrPlace->name;
				$singleResult['CREATETIME'] = new DateTime($jp->time);
				$singleResult['UPDATETIME'] = new DateTime($jp->updateTime);
				$singleResult['SCORE'] = floatval($jp->score);
				$singleResult['MARKCOUNT'] = intval($jp->markCount);
				$singleResult['INFOCOUNT'] = intval($jp->infoCount);
				$result['DATA'][$jpCot] = $singleResult;
				$jpCot++;
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALPLACES_NO_JOURNALPLACE);
		}
		
		return $result;
	}

	/**
	 * 
	 * 通过用户的历史，返回特色游记景点内容（用户id、用户名、内容id、内容文字、内容创建时间、图片数量、图片数组）
	 * @param string $usrId
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
	public static function getSpecialJournal($usrId , $beginIndex , $rowCount , $loopRange)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$result = null;
		
		$conn = Models_Core::getDoctrineConn();
		
		$query = Doctrine_Query::create()
				->select('avg(markCount)')
				->from('TrJournalPlace jp');
		$query = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		$rs= $query->execute();
		$avgMarkCount = $rs[0]['avg'];
				
		$query = Doctrine_Query::create()
				->select('jp.id , j.id , u.id , u.username')
				->from('TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->leftJoin('j.TrUser u')
				->where('jp.markCount > ?' , $avgMarkCount)
				->andWhere('j.isPrivate = 0 or j.usr_id_ref = ?' , $usrId)
				->orderBy('jp.score desc')
				->offset(0)
				->limit($loopRange)
				;
		$query = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		$rs = $query->execute();
		
		$rsCount = count($rs);	
		$randHistory = array();
		while ($rsCount)
		{
			while (true)
			{
				$randNum = rand(0, $rsCount-1);
				if (!in_array($randNum, $randHistory))
				{
					array_push($randHistory, $randNum);
					break;
				}
			}
			$jpId = $rs[$randNum]['id'];
			$uId = $rs[$randNum]['TrJournal']['TrUser']['id'];
			$uName = $rs[$randNum]['TrJournal']['TrUser']['username'];
			
			$query = Doctrine_Query::create()
					->select('jpi.id , jpi.info , jpi.time , jpi.photoCount')
					->from('TrJournalPlaceInfo jpi')
					->where('jpi.jpc_id_ref = ?' , $jpId)
					->orderBy('jpi.time desc')
					->offset($beginIndex)
					->limit($rowCount);
			$jpiArr = $query->execute();
		
			if (count($jpiArr))
			{
				$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
				$result['DATA'] = array();	
				$result['DATA']['USERID'] =  $uId;
				$result['DATA']['USERNAME'] = $uName;
				
				foreach ($jpiArr as $jpi)
				{
					$singleJpi = null;
					$singleJpi['INFOID'] = $jpi->id;
					$singleJpi['CONTENT'] = $jpi->info; 
					$singleJpi['TIME'] =  new DateTime($jpi->time);
					$singleJpi['PHOTOCOUNT'] = intval($jpi->photoCount);
					
					if (0 != $jpi->photoCount)
					{
						$query = Doctrine_Query::create()
								->select('p.id , p.title , p.smallVersion')
								->from('TrPhoto p')
								->where('p.jpi_id_ref = ?' , $jpi->id)
								->offset(0)
								->limit(3);
						$photoArr = $query->execute();		
						foreach ($photoArr as $photo)
						{
							$singlePhoto['PHOTOID'] = $photo->id;
							$singlePhoto['PHOTOTITLE'] = $photo->title;
							$singlePhoto['PHOTO'] = Models_CommonAction_PPhoto::saveToTemp($photo->id, $photo->smallVersion, Models_CommonAction_PPhoto::SMALL_VERSION);
							array_push($singleJpi, $singlePhoto);
						}
					}
					array_push($result['DATA'], $singleJpi);
				}
				break;
			}
		}
		
		if (!$result)
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETSPECIALJOURNAL_ZERO_RESULT);
		}
		
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
	 * @param string  $usrId
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
	public static function getFriendMovement($usrId , $bObliged , $rBeginIndex , $rRowCount, $bRefresh = false ,  $beginIndex = null , $rowCount = null)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$idArr = self::getFriendMovementIdRequest($usrId, $bObliged, $rBeginIndex, $rRowCount , $bRefresh , $beginIndex , $rowCount);
	
		$dataType = $idArr[0];
		$ids = $idArr[1];
		return self::getFriendMovementDetailRequest($ids, $dataType);
	}

	/**
	 * 
	 * 处理用户权限，内容id的获取，存取到缓冲文件去
	 * @param string $usrId
	 * @param boolean $bObliged
	 * @param int $rBeginIndex
	 * @param int $rRowCount
	 * @param boolean $bRefresh
	 * @param int $beginIndex
	 * @param int $rowCount
	 * @return array		array(dattype, array(id1 , id2 ,id3 , ...))动态的id数组
	 */
	private static function getFriendMovementIdRequest($usrId , $bObliged , $rBeginIndex , $rRowCount, $bRefresh = false ,  $beginIndex = null , $rowCount = null)
	{
		$conn = Models_Core::getDoctrineConn();

		$returnIds = null;
		$dataType = null;
		
		//刷新了，就重新从数据库中搜索id
		if ($bRefresh)
		{
			$query = Doctrine_Query::create()
				->select('jm.id')	
				->from('TrJournalMovement jm')
  	  			->where('jm.usr_id_ref in (select usr_id_other_ref from tr_user_to_user where usr_id_self_ref = ?) or usr_id_ref = ?', array($usrId , $usrId))
    			->offset($beginIndex)
    			->limit($rowCount)
    			->orderBy('jm.time DESC')
    			->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
	    	$movements = $query->execute();		

	    	$returnIds = array();
	    	$rEndIndex = $rBeginIndex + $rRowCount;
	    	//定义缓存的数据
	    	$cacheName = strval($usrId);
	    	$requestMethod = Models_Data_Core::REQUEST_METHOD_GETFRIENDMOVEMENT;
	    	$dataType = null;
	    	$dataCot = null;
	    	$dataArr = array();
	    	if (count($movements))
	    	{
	    		//将id存放到缓冲文件中，返回这次请求的id数组
	    		$cot = 0;
	    		foreach ($movements as $movement)
	    		{
	    			array_push($dataArr, $movement['id']);
	    			
	    			//将此次请求的id放到returnIds数组中
	    			if ($cot >= $rBeginIndex && $cot < $rEndIndex)
	    			{
	    				array_push($returnIds, $movement['id']);
	    			}
	    			$cot++;
	    		}
	    		$dataType = Models_Data_Core::DATATYPE_MOVEMENT;
	    	}
	    	else if ($bObliged)
	    	{
	    		//搜索热门的游记，将这些游记的id，放在缓冲文件中，返回这次请求的id数组
		    	$query = Doctrine_Query::create()
					->select('j.id')
					->from('TrJournal j')
					->leftJoin('j.TrUser u')
					->where('j.markCount > '.Models_Core::SCORE_JOURNAL_LEAST_MARKCOUNT)
					->offset($rBeginIndex)
					->limit($rRowCount)
					->orderBy('j.score DESC')
					->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
				$journals = $query->execute();
	
				$cot = 0;
				foreach ($journals as $j)
				{	
					$jId = $j['id'];
					array_push($dataArr, $jId);
	    			
	    			//将此次请求的id放到returnIds数组中
	    			if ($cot >= $rBeginIndex && $cot < $rEndIndex)
	    			{
	    				array_push($returnIds, $jId);
	    			}
	    			$cot++;
				}
				$dataType = Models_Data_Core::DATATYPE_JOURNAL;
	    	}
	    	else
	    	{
	    		return null;
	    	}
	    	
	    	//将请求数据存储到缓存中去
	    	Models_CommonAction_PDataCache::modify($usrId, $cacheName , $requestMethod , $dataType , $dataCot , $dataArr);
	    	Models_CommonAction_PDataCache::closeFile();
		}
		//否则，从缓冲文件中读取id
		else
		{
			//如果缓冲文件中的记录超越了，就返回overIndex的状态
			$cacheName = strval($usrId);
			$dataCache = Models_CommonAction_PDataCache::getCache($usrId, $cacheName, $rBeginIndex, $rRowCount);
			$returnIds = $dataCache->dataArr;
			$dataType = $dataCache->dataType;
		}
		
		return array($dataType , $returnIds);
	}
	/**
	 * 
	 * 获取详细返回信息
	 * @param array $ids
	 * @param int $dataType
	 */	
	private static function getFriendMovementDetailRequest($ids , $dataType)
	{
		if (is_null($ids) || !is_array($ids) || !count($ids))
		{
			return array('STATUS'=>Models_Core::STATE_DATA_GETFRIENDMOVEMENT_NO_JOURNAL_EXIST);
		}
		
		//用ids数组，写成idStr字符串
		$idStr = '';
		foreach ($ids as $id)
		{
			$idStr .= "$id,";
		}
		$idStr = rtrim(rtrim($idStr , ' ') , ',');
		$idStr = "($idStr)";
		//确定返回的结构体的结构---1
		$result['STATUS'] = null;
		
		switch ($dataType)
		{
			case Models_Data_Core::DATATYPE_MOVEMENT:
				$conn = Models_Core::getDoctrineConn();
		
				$query = Doctrine_Query::create()
						->select('jm.id , jm.type , jm.movement_id')
						->from('TrJournalMovement jm')
						->where('jm.id in '. $idStr)
						->orderBy('jm.time DESC')
						->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
				$movements = $query->execute();
				
				if (count($movements))
				{
					//按动态类型，将动态的id分组保存到数组中
					$movementGroup = array();
					$moveCot = 0;
					foreach($movements as $movement)
					{
						$id = $movement['id'];
						$move_id = $movement['movement_id'];
						$type = $movement['type'];
						
						$movementGroup[$type][$moveCot] = array($id , $move_id);
						
						//先确定返回的结果结构----2
						$result['DATA'][$moveCot] = null;
						$moveCot++;
					}
					foreach ($movementGroup as $type=>$movement)
					{
						if (0 == count($movement))
						{
							continue;
						}
						
						$order = null;
						$uniqueId = null;
						
						//用动态id的数组构造id字符串
						$idStr = '';
						foreach ($movement as $cot=>$movementIds)
						{
							$id = $movementIds[0];
							$movementId = $movementIds[1];
							//记录动态的index
							$order[$movementId] = $cot;
							//记录动态的唯一标识id
							$uniqueId[$movementId] = $id;
							$idStr .= "$movementId,";
						}
						$idStr = rtrim(rtrim($idStr , ' ') , ',');
						$idStr = "( $idStr )";
						
						//定义query
						$query = null;
						switch ($type)
						{
							case Models_Core::MOVEMENT_TYPE_CREATE_JOURNAL:
								$query = Doctrine_Query::create()
										->select('j.id , j.title , j.time , u.id , u.username')
										->from('TrJournal j')
										->leftJoin('j.TrUser u')
										->where('j.id in '.$idStr);
								$journals = $query->execute();
								
								foreach ($journals as $j)
								{	
									$jId = $j->id;
									
									$uId = $j->TrUser->id;
									$mType = intval($type);
									$uName = $j->TrUser->username;
									$jTitle = $j->title;
									$time = new DateTime($j->time);
									$keyWord = '';
									$cjMovement = array('_ID'=>$uniqueId[$jId] ,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);
									
									//按顺序放动态内容
									$result['DATA'][$order[$jId]] = $cjMovement;
								}
								break;
					        case Models_Core::MOVEMENT_TYPE_CREATE_JOURNAL_PLACE:
								$query = Doctrine_Query::create()
										->select('jp.id ,j.id , u.id , u.username , j.title ,jp.time ,p.id,p.name')
										->from('TrJournalPlace jp')
										->leftJoin('jp.TrPlace p')
										->leftJoin('jp.TrJournal j')
										->leftJoin('j.TrUser u')
										->where('jp.id in '.$idStr);
								$jPlaces = $query->execute();
					
								foreach ($jPlaces as $jp)
								{
									$jpId = $jp->id;
									
									$uId = $jp->TrJournal->TrUser->id;
									$uName = $jp->TrJournal->TrUser->username;
									$mType = intval($type);
									$jId = $jp->TrJournal->id;
									$jTitle = $jp->TrJournal->title;
									$time = new DateTime($jp->time);
									$keyWord = $jp->TrPlace->name;
									$cjpMovement = array('_ID'=>$uniqueId[$jpId] ,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);	
								
									//按顺序放动态内容
									$result['DATA'][$order[$jpId]] = $cjpMovement;
								}
								break;
					        case Models_Core::MOVEMENT_TYPE_CREATE_JOURNAL_INFO:
								$query = Doctrine_Query::create()
										->select('jpi.id ,jpi.time, jp.id , j.id,j.title , p.id ,p.name, u.id , u.username ')
										->from('TrJournalPlaceInfo jpi')
										->leftJoin('jpi.TrJournalPlace jp')
										->leftJoin('jp.TrPlace p')
										->leftJoin('jp.TrJournal j')
										->leftJoin('j.TrUser u')
										->where('jpi.id in '.$idStr);
								$jpis = $query->execute();
					        	
								foreach ($jpis as $jpi)
								{
									$jpiId = $jpi->id;
									
									$uId = $jpi->TrJournalPlace->TrJournal->TrUser->id;
									$uName = $jpi->TrJournalPlace->TrJournal->TrUser->username;
									$mType = intval($type);
									$jpId = $jpi->TrJournalPlace->id;
									$jId = $jpi->TrJournalPlace->TrJournal->id;
									$jTitle = $jpi->TrJournalPlace->TrJournal->title;
									$time = new DateTime($jpi->time);
									$keyWord = $jpi->TrJournalPlace->TrPlace->name;
									
									$cjpiMovement = array('_ID'=>$uniqueId[$jpiId] ,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jpId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);					
									//按顺序放动态内容
									$result['DATA'][$order[$jpiId]] = $cjpiMovement;
								}
								break;
					        case Models_Core::MOVEMENT_TYPE_FAVOURITE_JOURNAL:
								$query = Doctrine_Query::create()
										->select('jfl.id ,jfl.time , j.id , j.title,j.score, u.id , u.username')
										->from('TrJournalFavouriteList jfl')
										->leftJoin('jfl.TrJournal j')
										->leftJoin('j.TrUser u')
										->where('jfl.id in '.$idStr);
								$jfls = $query->execute();
					        	
								foreach ($jfls as $jfl)
								{
									$jflId = $jfl->id;
									
									$uId = $jfl->TrJournal->TrUser->id;
									$uName = $jfl->TrJournal->TrUser->username;
									$mType = intval($type);
									$jId = $jfl->TrJournal->id;
									$jTitle = $jfl->TrJournal->title;
									$time = new DateTime($jfl->time);
									$keyWord = $jfl->TrJournal->score;
									
									$jflMovement = array('_ID'=>$uniqueId[$jflId] ,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);					
									//按顺序放动态内容
									$result['DATA'][$order[$jflId]] = $jflMovement;
								}
								break;
					        case Models_Core::MOVEMENT_TYPE_FAVOURITE_JOURNAL_PLACE:
								$query = Doctrine_Query::create()
										->select('jpfl.id,jpfl.time , jp.id , p.id , p.name, j.id , j.title, u.id , u.username')
										->from('TrJournalPlaceFavouriteList jpfl')
										->leftJoin('jpfl.TrJournalPlace jp')
										->leftJoin('jp.TrPlace p')
										->leftJoin('jp.TrJournal j')
										->leftJoin('j.TrUser u')
										->where('jpfl.id in '.$idStr);
								$jpfls = $query->execute();
					        	
								foreach ($jpfls as $jpfl)
								{
									$jpflId = $jpfl->id;
									
									$uId = $jpfl->TrJournalPlace->TrJournal->TrUser->id;
									$uName = $jpfl->TrJournalPlace->TrJournal->TrUser->username;
									$mType = intval($type);
									$jpId = $jpfl->TrJournalPlace->id;
									$jId = $jpfl->TrJournalPlace->TrJournal->id;
									$jTitle = $jpfl->TrJournalPlace->TrJournal->title;
									$time = new DateTime($jpfl->time);
									$keyWord = $jpfl->TrJournalPlace->TrPlace->name;
									
									$jpflMovement = array('_ID'=>$uniqueId[$jpflId] ,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jpId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);					
									//按顺序放动态内容
									$result['DATA'][$order[$jpflId]] = $jpflMovement;
								}
								break;
						}
					}
				}
				break;
			case Models_Data_Core::DATATYPE_JOURNAL:
				$query = Doctrine_Query::create()
					->select('j.id,j.score , j.title , j.time , u.id , u.username')
					->from('TrJournal j')
					->leftJoin('j.TrUser u')
					->where('j.id in '.$idStr)
					->orderBy('j.score DESC');
				$journals = $query->execute();
				
				$result['DATA'] = array();
				foreach ($journals as $j)
				{	
					$jId = $j->id;
					
					$uId = $j->TrUser->id;
					$mType = intval(Models_Core::MOVEMENT_TYPE_HOT_JOURNAL);
					$uName = $j->TrUser->username;
					$jTitle = $j->title;
					$time = new DateTime($j->time);
					$keyWord = '';
					$cjMovement = array('_ID'=>null,'USERID'=>$uId , 'MOVEMENTTYPE'=>$mType , 'USERNAME'=>$uName , 'MOVEMENTID'=>$jId ,'JOURNALID'=>$jId , 'JOURNALTITLE'=>$jTitle ,'TIME'=>$time, 'KEYWORD'=>$keyWord);
					
					//按顺序放动态内容
					array_push($result['DATA'], $cjMovement);
				}
				break;
		}
		$result['STATUS'] = Models_Core::STATE_REQUEST_SUCCESS;
		return  $result;
	}
	
	/**
	 * 
	 * 获取游记中城市（按照时间顺序） ， 返回结果（状态码，城市id、城市名、在该城市进入的景点数量，景点的id数组）
	 * @param string $usrId				用户id
	 * @param string|int|long $journalId			游记的id
	 * @param int $beginIndex
	 * @param int $rowCount				如果rowCount为-1，那么返回所有城市
	 * @throws Zend_XmlRpc_Server_Exception
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
	public static function getJournalCitys($usrId , $journalId , $beginIndex , $rowCount)
	{
//		throw new Zend_XmlRpc_Server_Exception('I am empty now');

		$conn= Models_Core::getDoctrineConn();
		
		$result = null;
		
		$query = Doctrine_Query::create()
				->select('jp.id ,p.id, c.id , c.longname')
				->from('TrJournalPlace jp')
				->leftJoin('jp.TrJournal j')
				->leftJoin('jp.TrPlace p')
				->leftJoin('p.TrCity c')
				->where('jp.jnl_id_ref = ?' , $journalId)
				->andWhere('j.usr_id_ref = ? or j.isPrivate = 0' , $usrId);
		//$query = $query->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		$rs = $query->execute();
		
		if (count($rs))
		{
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array();
			$cityArr = array();
			foreach ($rs as $jp)
			{
				$cityId = $jp->TrPlace->TrCity->id;
				$cityName = $jp->TrPlace->TrCity->longname;
				$jpId = $jp->id;
				
				if (!isset($cityArr[$cityId]))
				{
					$cityArr[$cityId]['name'] = $cityName;
					$cityArr[$cityId]['count'] = 1;
					$cityArr[$cityId]['jpArr'] = array($jpId);
				}
				else
				{
					$cityArr[$cityId]['count']++;
					array_push($cityArr[$cityId]['jpArr'], $jpId);
				}
			}
			
			$cot = 0;
			if ($rowCount == -1)
				$rowCount = count($rs);
			$endIndex = $beginIndex+$rowCount;
			foreach ($cityArr as $cityId=>$cityData)
			{
				if ($cot >= $beginIndex)
				{
					array_push($result['DATA'], array('CITYID'=>$cityId , 'CITYNAME'=>$cityData['name'] , 'PLACECOUNT'=>intval($cityData['count']) , 'PLACEARR'=>$cityData['jpArr']));
				}
				$cot++;
				if ($cot >= $endIndex)
					break;
			}
		}
		else
		{
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETJOURNALCITYS_ZERO_RESULT);
		}
		
		return $result;
	}
	
	/**
	 * 
	 * 获得用户最新修改的游记
	 * @param string $usrId
	 * @return struct	array(
	 * 						'STATUS'=>status,
	 * 						'DATA'=>array(
	 * 							'JOURNALID'=>journalId
	 * 						)
	 * 					)
	 */
	public static function getLatestJournal($usrId) {
		if (!$usrId) {
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETLATESTJOURNAL_USERID_NULL));
		}
		
		$conn = Models_Core::getDoctrineConn();
		$result = null;
		$query = Doctrine_Query::create()
				->select('j.id')
				->from('TrJournal j')
				->where('j.usr_id_ref = ?' , $usrId)
				->orderBy('j.updateTime desc')
				->limit(1)
				->setHydrationMode(Doctrine_Core::HYDRATE_ARRAY);
		
		$rs = $query->fetchOne();
		
		if ($rs) {
			$result['STATUS'] = intval(Models_Core::STATE_REQUEST_SUCCESS);
			$result['DATA'] = array('JOURNALID'=>$rs['id']);
		} else {
			$result['STATUS'] = intval(Models_Core::STATE_DATA_GETLATESTJOURNAL_ZERO_RESULT);
		}
		
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
		$result = null;
		
		if (!$photoId) {
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETPHOTO_PHOTOID_UNKNOWN));
		}
		
		$versionStr = null;
		switch ($photoVersion) {
			case Models_CommonAction_PPhoto::ORIGINAL_VERSION:
				$versionStr = 'originVersion';
				break;	
			case Models_CommonAction_PPhoto::PC_VERSION:
				$versionStr = 'pcVersion';
				break;
			case Models_CommonAction_PPhoto::MOBILE_VERSION:
				$versionStr = 'mobileVersion';
				break;
			case Models_CommonAction_PPhoto::SMALL_VERSION:
				$versionStr = 'smallVersion';
				break;
			default:
				return array('STATUS'=>intval(Models_Core::STATE_DATA_GETPHOTO_VERSION_UNKNOWN));
				break;
		}
		
		$conn = Models_Core::getDoctrineConn();
		$query = Doctrine_Query::create()
				->select("p.id, p.title, p.$versionStr")
				->from('TrPhoto p')
				->where('p.id = ?', $photoId);
		
		$rs = $query->fetchOne();
		
		if (!$rs) {
			return array('STATUS'=>intval(Models_Core::STATE_DATA_GETPHOTO_PHOTO_UNEXIST));
		}
		
		$photoTitle = $rs->title;
		$photoUrl = Models_CommonAction_PPhoto::saveToTemp($photoId, $rs->$versionStr, $photoVersion);
		
		$result = array(
			'STATUS' => intval(Models_Core::STATE_REQUEST_SUCCESS),
			'DATA' => array(
				'PHOTOID' => $photoId,
				'PHOTOTITLE' => $photoTitle,
				'PHOTOURL' => $photoUrl,
				'PHOTOVERSION' => $photoVersion
			)
		);
		return $result;
	}
}