<?php
class Models_Api_Im_PRpcImMethod
{
	/**
	 * 
	 * 添加黑名单
	 * @param string|array $blackList	单个用户id 或者 一个用户id数组
	 * @return struct array('STATUS'=>status)
	 */
	public static function addBlackList($blackList)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$addBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BADDBLACKLIST);
		
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDBLACKLIST_USERID , $usrId);
		$addBh->setProperty(Models_Behavior_PBehaviorEnum::PADDBLACKLIST_BLACKLIST , $blackList);
	
		$result = $addBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 清空黑名单
	 * @return struct array('STATUS'=>status)
	 */
	public static function clearBlackList()
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$clearBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BCLEARBLACKLIST);
		
		$result = $clearBh->setProperty(Models_Behavior_PBehaviorEnum::PCLEARBLACKLIST_USERID);
		
		return $result;
	}
	/**
	 * 
	 * 从黑名单中删除一些用户
	 * @param string|array $whiteList
	 * @return struct array('STATUS'=>status)
	 */
	public static function deleteBlackList($whiteList)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$deleteBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BDELETEBLACKLIST);
		
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEBLACKLIST_USERID , $usrId);
		$deleteBh->setProperty(Models_Behavior_PBehaviorEnum::PDELETEBLACKLIST_BLACKLIST , $whiteList);
		
		$result = $deleteBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 开启IM，或者关闭IM；开启了，可被人发现，否则不被发现
	 * @param unknown_type $isEmable
	 * @return struct array('STATUS'=>status)
	 */
	public static function enableIM($isEnable)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$enableBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BENABLEIM);
		
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PENABLEIM_USERID , $usrId);
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PENABLEIM_ISENABLE , $isEnable);
	
		$result = $enableBh->chase(true);
		
		return $result;
	}
	
	/**
	 * 
	 * 发送IM信息
	 * @param string $toUserId
	 * @param string $msg
	 * @return struct array('STATUS'=>status)
	 */
	public static function sendMsg($toUserId , $msg)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$enableBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BSENDMSG);
		
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PSENDMSG_USERID , $usrId);
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PSENDMSG_RECEIVEUSERID , $toUserId);
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PSENDMSG_MSG , $msg);

		$result = $enableBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 接受IM信息							
	 * @return struct array('STATUS'=>status , array('USERID'=>userid , 'MSG'=>msg , 'TIME'=>time) , array('USERID'=>userid , 'MSG'=>msg , 'TIME'=>time) , ...)
	 */
	public static function receiveMsg()
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return Models_Core::STATE_NOT_LOGIN;
		}
		
		$enableBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BRECEIVEMSG);
		
		$enableBh->setProperty(Models_Behavior_PBehaviorEnum::PRECEIVEMSG_USERID , $usrId);
				
		$result = $enableBh->chase(true);
		
		return $result;
	}
}