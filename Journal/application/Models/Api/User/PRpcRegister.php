<?php
class Models_Api_User_PRpcRegister
{
	/**
	 * 
	 * register by email or telehpone number
	 * @param string $registerInfo		邮箱地址，或者手机号码
	 * @param string $username			用户名
	 * @param string $password			密码
	 * @return struct	array('STATUS'=>status)
	 */
	public 	static function register($registerInfo , $username , $password)
	{
		if (Models_CommonAction_PDataJudge::isAddress($registerInfo))
		{
			//执行邮箱注册
			$registerBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BREGISTEREMAIL);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_EMAIL , $registerInfo);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_USERNAME, $username);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTEREMAIL_PASSWORD, $password);
			$result = $registerBh->chase(true);
			
			return $result;
		}
		else if(Models_CommonAction_PDataJudge::isTelephone($registerInfo))
		{
			//执行手机注册
			$registerBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BREGISTERTELEPHONE);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_TELEPHONE, $registerInfo);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_USERNAME, $username);
			$registerBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERTELEPHONE_PASSWORD, $password);
			$result = $registerBh->chase(true);
			
			return $result;
		}
		else 
		{
			return array('STATUS'=>Models_Core::STATE_BEHAVIOR_REGISTER_REGISTERINFO_INVALID); 
		}
	}
	/**
	 * 
	 * confirm register by msg
	 * @param string $telephone
	 * @param string $content
	 * @return struct	array('STATUS'=>status)
	 */
	public static function confirmTelephoneByMsg($telephone , $content)
	{
		//执行手机注册确认
		$confirmBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BREGISTERCONFIRMTELEPHONEBYMSG);
		$confirmBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMTELEPHONEBYMSG_TELEPHONE , $telephone);
		$confirmBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMTELEPHONEBYMSG_CONTENT, $content);
		$result = $confirmBh->chase(true);

		return $result;
	}
	/**
	 * 
	 * confirm register by msg
	 * @param string $verifycode
	 * @return struct	array('STATUS'=>status)
	 */
	public static function confirmTelephoneByCode($verifycode)
	{
		//执行手机注册确认
		$confirmBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BREGISTERCONFIRMTELEPHONEBYCODE);
		$confirmBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMTELEPHONEBYCODE_VERIFYCODE , $verifycode);
		$result = $confirmBh->chase(true);

		return $result;
	}
	/**
	 * 
	 * confirm register by Email
	 * @param string $verifyCode
	 * @return struct	array('STATUS'=>status)
	 */
	public static function confirmEmail($verifyCode)
	{
		//执行邮箱注册
		$confirmBh = Models_Behavior_PBehaviorManager::getInstance(Models_Behavior_PBehaviorEnum::BREGISTERCONFIRMEMAIL);
    	$confirmBh->setProperty(Models_Behavior_PBehaviorEnum::PREGISTERCONFIRMEMAIL_VERIFYCODE , $verifyCode);
    	$result = $confirmBh->chase(true);
    	
    	return $result;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getStruct()
	{
		Models_CommonAction_PAuthorization::authorization(1);
		$result = array($_SESSION , $_COOKIE , session_id());
		
		return $result;
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getInteger()
	{
		return 123;
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getLong()
	{
		return 2147483649;
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getDouble()
	{
		return 2147483649.234234;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param boolean $bool
	 */
	public static function getBoolean($bool = false)
	{
		if ($bool)
			return 'true';
		else 
			return 'false';
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getBase64()
	{
		return base64_encode('12312');
	}
	/**
	 * 
	 * Enter description here ...
	 */
	public static function getTime()
	{
		
		return new DateTime();
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param array $titles
	 * @param array $images
	 */
	public static function receiveImages($titles , $images)
	{
		$root = 'D:\\avatar\\rec\\';
		if (count($titles) != count($images))
		{
			return '数组不对啊！';
		}
		
		$cot = count($titles);
		
		while ($cot-->0)
		{
			$fileName = $root.time().'-'.$titles[$cot].'.jpg';
			$fp = fopen($fileName , 'wb');
			fwrite($fp, base64_decode($images[$cot]));
			fclose($fp);
		}
		
		return '应该可以了';
	}
}