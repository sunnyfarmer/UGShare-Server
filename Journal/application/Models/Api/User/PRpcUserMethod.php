<?php
class Models_Api_User_PRpcUserMethod
{
	/**
	 * 
	 * 用户账号绑定邮箱
	 * @param string $email
	 * @return	struct array('STATUS'=>status )
	 */
	public static function bindEmail($email)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$bindBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BBINDEMAIL);
	
		$bindBh->setProperty(Models_Behavior_PBehaviorEnum::PBINDEMAIL_USERID, $usrId);
		$bindBh->setProperty(Models_Behavior_PBehaviorEnum::PBINDEMAIL_EMAIL , $email);

		$result = $bindBh->chase(true);
	
		return $result;
	}
	/**
	 * 
	 * 用户账号绑定手机号
	 * @param string $telephone
	 * @return struct array('STATUS'=>status )
	 */
	public static function bindMobile($telephone)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$bindBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BBINDMOBILE);
	
		$bindBh->setProperty(Models_Behavior_PBehaviorEnum::PBINDMOBILE_USERID, $usrId);
		$bindBh->setProperty(Models_Behavior_PBehaviorEnum::PBINDMOBILE_MOBILE , $telephone);

		$result = $bindBh->chase(true);
	
		return $result;
	}
	/**
	 * 
	 * 关注驴友
	 * @param string|array $followUserInfo	userId列表、单个userId
	 * @return struct array('STATUS'=>status )
	 */
	public static function followUserById($followUserInfo)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$followBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BFOLLOWUSERBYID);
		
		$followBh->setProperty(Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYID_USERID, $usrId);
		$followBh->setProperty(Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYID_IDS , $followUserInfo);
		
		$result = $followBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 关注驴友
	 * @param string|array $followUserInfo	电话号列表、单个电话号
	 * @return struct array('STATUS'=>status )
	 */
	public static function followUserByTelephone($followUserInfo)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$followBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BFOLLOWUSERBYTELEPHONE);
		
		$followBh->setProperty(Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYTELEPHONE_USERID, $usrId);
		$followBh->setProperty(Models_Behavior_PBehaviorEnum::PFOLLOWUSERBYTELEPHONE_TELEPHONES , $followUserInfo);
		
		$result = $followBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 取消关注驴友
	 * @param string|array $userInfo	userId列表、单个userId
	 * @return struct array('STATUS'=>status )
	 */
	public static function unFollowUserById($userInfo)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}	
		
		$unFollowBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BUNFOLLOWUSERBYID);

		$unFollowBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFOLLOWUSERBYID_USERID, $usrId);
		$unFollowBh->setProperty(Models_Behavior_PBehaviorEnum::PUNFOLLOWUSERBYID_IDS , $userInfo);
		
		$result = $unFollowBh->chase(true);
		
		return $result;
	}
	
	/**
	 * 
	 * 设置新头像
	 * @param string $avatar
	 * @return struct array('STATUS'=>status )
	 */
	public static function setAvatar($avatar)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$setBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BSETAVATAR);
		
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETAVATAR_USERID, $usrId);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETAVATAR_AVATAR, $avatar);
		
		$result = $setBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 设置新密码
	 * @param string $oldPwd
	 * @param string $newPwd
	 * @return struct array('STATUS'=>status)
	 */
	public static function setNewPassword($oldPwd , $newPwd)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}

		$setBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BSETNEWPASSWORD);
		
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_USERID , $usrId);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_OLDPWD , $oldPwd);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETNEWPASSWORD_NEWPWD , $newPwd);

		$result = $setBh->chase(true);
		
		return $result;
	}
	/**
	 * 
	 * 设置签名（座右铭）
	 * @param string $saying
	 * @return struct array('STATUS'=>status)
	 */
	public static function setSaying($saying)
	{
		$usrId = Models_CommonAction_PAuthorization::getCurrentUsrId();
		if (!$usrId)
		{
			return array('STATUS'=>intval(Models_Core::STATE_NOT_LOGIN));
		}
		
		$setBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BSETSAYING);

		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETSAYING_USERID , $usrId);
		$setBh->setProperty(Models_Behavior_PBehaviorEnum::PSETSAYING_SAYING , $saying);
	
		$result = $setBh->chase(true);
		
		return $result;
	}
}

