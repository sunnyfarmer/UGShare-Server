<?php
class Models_CommonAction_PAuthorization
{
	/**
	 * 
	 * get the current user's id
	 */
	public static function getCurrentUsrId()
	{
		Models_Core::initSession();
		$usrId = null;
		if ( isset($_SESSION['usrId']) )
		{
			$usrId = $_SESSION['usrId'];
		}
		return $usrId;
	}
	/**
	 * 
	 * check if the user is authorizated
	 * @param unknown_type $sessionId
	 * @return boolean
	 */
	public static function isAuthorizated()
	{
		Models_Core::initSession();
		$usrId = null;
		//get the user id
		if ( isset($_SESSION['usrId']) )
		{
			$usrId = $_SESSION['usrId'];
		}
		if (!$usrId)
		{//session['usrId'] is not exist , means user is not logined
			return false;
		}
		else
		{
			return true;
		}
	}
	/**
	 * 
	 * 授权
	 * @param unknown_type $usrId
	 */
	public static function authorization($usrId)
	{
		self::setSession($usrId);
		self::writeOnlineRecord($usrId);
	}
	
	/**
	 * 
	 * 设置session
	 * @param unknown_type $usrId
	 */
	private static function setSession($usrId)
	{
		Models_Core::initSession();
		$_SESSION['usrId'] = $usrId;
	}
	
	/**
	 * 
	 * 记录用户的登陆记录
	 * @param unknown_type $usrId
	 */
	private static function writeOnlineRecord($usrId)
	{
	
	}
	
	
}