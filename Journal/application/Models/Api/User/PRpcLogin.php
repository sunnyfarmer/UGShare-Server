<?php
class Models_Api_User_PRpcLogin
{
	/**
	 * 
	 * login by email or telephone
	 * @param string $loginInfo
	 * @param string $password
	 * @return struct	array('STATUS'=>status)
	 */
	public static function login($loginInfo , $password)
	{
		$result = null;
		if (Models_CommonAction_PDataJudge::isAddress($loginInfo))
		{
			//执行邮箱登陆
			$loginBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BLOGINEMAIL);
			$loginBh->setProperty(Models_Behavior_PBehaviorEnum::PLOGINEMAIL_EMAIL , $loginInfo);
			$loginBh->setProperty(Models_Behavior_PBehaviorEnum::PLOGINEMAIL_PASSWORD, $password);
			$result = $loginBh->chase(true);		
		}
		elseif(Models_CommonAction_PDataJudge::isTelephone($loginInfo))
		{
			//执行手机登陆
			$loginBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BLOGINTELEPHONE);
			$loginBh->setProperty(Models_Behavior_PBehaviorEnum::PLOGINTELEPHONE_TELEPHONE, $loginInfo);
			$loginBh->setProperty(Models_Behavior_PBehaviorEnum::PLOGINTELEPHONE_PASSWORD, $password);
			$result = $loginBh->chase(true);
		}
		else 
		{
			$result['STATUS'] = intval(Models_Core::STATE_BEHAVIOR_LOGIN_LOGININFO_INVALID); 
		}
		return $result;
	}
	
	/**
	 * 
	 * 通过第三方账号登陆
	 * @param string $accessToken
	 * @return struct	array('STATUS'=>status)
	 */
	public static function loginByOthers($accessToken)
	{
		throw new Zend_XmlRpc_Server_Exception('I am empty now ');
	}
}